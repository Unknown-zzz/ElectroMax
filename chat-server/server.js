'use strict';

const { WebSocketServer } = require('ws');
const mysql = require('mysql2/promise');

// ── Config ────────────────────────────────────────────────────────────────────
const WS_PORT  = parseInt(process.env.WS_PORT  || '8181');
const DB_HOST  = process.env.DB_HOST  || 'localhost';
const DB_PORT  = parseInt(process.env.DB_PORT  || '3306');
const DB_NAME  = process.env.DB_NAME  || 'electromax';
const DB_USER  = process.env.DB_USER  || 'root';
const DB_PASS  = process.env.DB_PASS  || '';

// ── State ─────────────────────────────────────────────────────────────────────
/** @type {Map<import('ws'), {usuario: null|object}>} */
const clients = new Map();

// ── DB pool ───────────────────────────────────────────────────────────────────
let pool;

async function dbInit() {
  pool = mysql.createPool({
    host: DB_HOST, port: DB_PORT,
    database: DB_NAME, user: DB_USER, password: DB_PASS,
    waitForConnections: true, connectionLimit: 10,
  });
  // Verify connectivity
  const conn = await pool.getConnection();
  conn.release();
}

// ── Auth helpers ──────────────────────────────────────────────────────────────
async function validarToken(token) {
  const now = Date.now();
  const [rows] = await pool.execute(
    'SELECT ct.usuario_id, u.nombre, u.rol FROM chat_tokens ct ' +
    'JOIN usuarios u ON u.id = ct.usuario_id ' +
    'WHERE ct.token = ? AND ct.expires_at >= ? AND u.activo = 1',
    [token, now]
  );
  if (!rows.length) return null;
  // Consume token (one-time use)
  await pool.execute('DELETE FROM chat_tokens WHERE token = ?', [token]);
  const r = rows[0];
  return { id: r.usuario_id, nombre: r.nombre, rol: r.rol };
}

// ── Channel helpers ───────────────────────────────────────────────────────────
async function canalesDeRol(rol) {
  const [rows] = await pool.execute(
    'SELECT c.id, c.slug, c.nombre, c.descripcion, c.icono, c.tipo, cr.puede_escribir ' +
    'FROM chat_canales c ' +
    'JOIN chat_canal_roles cr ON cr.canal_id = c.id AND cr.rol = ? ' +
    'ORDER BY c.orden',
    [rol]
  );
  return rows;
}

async function todosStaff() {
  const [rows] = await pool.execute(
    "SELECT id, nombre, rol FROM usuarios WHERE rol != 'cliente' AND activo = 1 ORDER BY nombre"
  );
  return rows;
}

// ── Message storage ───────────────────────────────────────────────────────────
async function guardarMsgCanal(canalId, userId, nombre, rol, contenido) {
  const ts = Date.now();
  await pool.execute(
    'INSERT INTO chat_mensajes (canal_id, usuario_id, nombre_usuario, rol_usuario, contenido, ts) VALUES (?,?,?,?,?,?)',
    [canalId, userId, nombre, rol, contenido, ts]
  );
  return ts;
}

async function guardarMsgDm(idRem, nombreRem, idDest, contenido) {
  const ts = Date.now();
  await pool.execute(
    'INSERT INTO chat_privados (id_remitente, nombre_remitente, id_destinatario, contenido, ts) VALUES (?,?,?,?,?)',
    [idRem, nombreRem, idDest, contenido, ts]
  );
  return ts;
}

async function historialCanal(canalId, limite = 80) {
  const [rows] = await pool.execute(
    'SELECT * FROM (SELECT * FROM chat_mensajes WHERE canal_id = ? ORDER BY ts DESC LIMIT ?) t ORDER BY ts ASC',
    [canalId, limite]
  );
  return rows.map(r => ({
    canal_id: r.canal_id, id_rem: r.usuario_id,
    nombre: r.nombre_usuario, rol: r.rol_usuario,
    texto: r.contenido, ts: Number(r.ts),
  }));
}

async function historialDm(a, b) {
  const [rows] = await pool.execute(
    'SELECT * FROM chat_privados WHERE (id_remitente=? AND id_destinatario=?) OR (id_remitente=? AND id_destinatario=?) ORDER BY ts ASC',
    [a, b, b, a]
  );
  return rows.map(r => ({
    id_rem: r.id_remitente, nombre: r.nombre_remitente,
    id_dest: r.id_destinatario, texto: r.contenido, ts: Number(r.ts),
  }));
}

// ── Broadcast helpers ─────────────────────────────────────────────────────────
function send(ws, obj) {
  if (ws.readyState === ws.OPEN) ws.send(JSON.stringify(obj));
}

function clientePorId(userId) {
  for (const [ws, st] of clients) {
    if (st.usuario?.id === userId) return ws;
  }
  return null;
}

function onlineIds() {
  return [...clients.values()]
    .filter(st => st.usuario)
    .map(st => st.usuario.id);
}

async function broadcastPresencia() {
  const staff  = await todosStaff();
  const online = onlineIds();
  const pkg    = { type: 'presencia', staff, online };
  for (const [ws, st] of clients) {
    if (st.usuario) send(ws, pkg);
  }
}

// ── Verify channel access ─────────────────────────────────────────────────────
async function tieneAcceso(userId, canalId) {
  const [rows] = await pool.execute(
    'SELECT cr.puede_escribir FROM chat_canal_roles cr ' +
    'JOIN usuarios u ON u.id = ? AND u.rol = cr.rol ' +
    'WHERE cr.canal_id = ?',
    [userId, canalId]
  );
  return rows.length ? rows[0] : null;
}

// ── Handler: auth ─────────────────────────────────────────────────────────────
async function handleAuth(ws, msg) {
  const u = await validarToken(msg.token || '');
  if (!u) {
    send(ws, { type: 'auth_err', texto: 'Token inválido o expirado.' });
    ws.close();
    return;
  }
  clients.get(ws).usuario = u;

  const canales = await canalesDeRol(u.rol);
  const staff   = await todosStaff();
  const online  = onlineIds();

  send(ws, { type: 'auth_ok', usuario: u, canales, staff, online });
  console.log(`[auth] ${u.nombre} (${u.rol})`);
  await broadcastPresencia();
}

// ── Handler: channel message ──────────────────────────────────────────────────
async function handleMsgCanal(ws, msg) {
  const u = clients.get(ws)?.usuario;
  if (!u) return;
  const texto   = (msg.texto || '').trim();
  const canalId = parseInt(msg.canal_id) || 0;
  if (!texto || !canalId) return;

  const acceso = await tieneAcceso(u.id, canalId);
  if (!acceso || !acceso.puede_escribir) return;

  const ts  = await guardarMsgCanal(canalId, u.id, u.nombre, u.rol, texto);
  const pkg = { type: 'msg_canal', canal_id: canalId, id_rem: u.id, nombre: u.nombre, rol: u.rol, texto, ts };

  // Broadcast only to users who have access to this channel
  const [perms] = await pool.execute(
    'SELECT rol FROM chat_canal_roles WHERE canal_id = ?', [canalId]
  );
  const rolesConAcceso = new Set(perms.map(r => r.rol));

  for (const [cws, st] of clients) {
    if (st.usuario && rolesConAcceso.has(st.usuario.rol)) send(cws, pkg);
  }
  console.log(`[canal#${canalId}] ${u.nombre}: ${texto}`);
}

// ── Handler: DM ───────────────────────────────────────────────────────────────
async function handleMsgDm(ws, msg) {
  const u = clients.get(ws)?.usuario;
  if (!u) return;
  const texto  = (msg.texto    || '').trim();
  const idDest = parseInt(msg.id_dest) || 0;
  if (!texto || !idDest || idDest === u.id) return;

  const ts  = await guardarMsgDm(u.id, u.nombre, idDest, texto);
  const pkg = { type: 'msg_dm', id_rem: u.id, nombre: u.nombre, rol: u.rol, id_dest: idDest, texto, ts };

  const destWs = clientePorId(idDest);
  if (destWs) send(destWs, pkg);
  send(ws, pkg); // echo to sender
  console.log(`[dm] ${u.nombre} -> #${idDest}`);
}

// ── Handler: channel history ──────────────────────────────────────────────────
async function handleHistCanal(ws, msg) {
  const u = clients.get(ws)?.usuario;
  if (!u) return;
  const canalId = parseInt(msg.canal_id) || 0;

  const acceso = await tieneAcceso(u.id, canalId);
  if (!acceso) return;

  const mensajes = await historialCanal(canalId);
  send(ws, { type: 'hist_canal', canal_id: canalId, mensajes });
}

// ── Handler: DM history ───────────────────────────────────────────────────────
async function handleHistDm(ws, msg) {
  const u = clients.get(ws)?.usuario;
  if (!u) return;
  const idDest = parseInt(msg.id_dest) || 0;
  if (!idDest) return;

  const mensajes = await historialDm(u.id, idDest);
  send(ws, { type: 'hist_dm', id_dest: idDest, mensajes });
}

// ── Disconnect ────────────────────────────────────────────────────────────────
function desconectar(ws) {
  const st = clients.get(ws);
  clients.delete(ws);
  if (st?.usuario) {
    console.log(`[bye] ${st.usuario.nombre}`);
    broadcastPresencia().catch(() => {});
  }
}

// ── Dispatch ──────────────────────────────────────────────────────────────────
async function dispatch(ws, data) {
  switch (data.type) {
    case 'auth':       await handleAuth(ws, data);      break;
    case 'msg_canal':  await handleMsgCanal(ws, data);  break;
    case 'msg_dm':     await handleMsgDm(ws, data);     break;
    case 'hist_canal': await handleHistCanal(ws, data); break;
    case 'hist_dm':    await handleHistDm(ws, data);    break;
    case 'bye':        ws.close();                      break;
  }
}

// ── Boot ──────────────────────────────────────────────────────────────────────
(async () => {
  try {
    await dbInit();
    console.log('[DB] Conexión establecida.');
  } catch (err) {
    console.error('[DB] Error:', err.message);
    process.exit(1);
  }

  const wss = new WebSocketServer({ port: WS_PORT });
  console.log(`=== ElectroMax Chat Server  ws://0.0.0.0:${WS_PORT} ===`);

  wss.on('connection', (ws, req) => {
    const ip = req.socket.remoteAddress;
    clients.set(ws, { usuario: null });
    console.log(`[+] Conexión nueva  IP:${ip}  total:${clients.size}`);

    ws.on('message', raw => {
      let data;
      try { data = JSON.parse(raw.toString()); } catch { return; }
      if (typeof data !== 'object' || !data || !data.type) return;
      dispatch(ws, data).catch(err => console.error('[dispatch error]', err.message));
    });

    ws.on('close', () => desconectar(ws));
    ws.on('error', err => { console.error('[ws error]', err.message); desconectar(ws); });
  });

  wss.on('error', err => { console.error('[server error]', err.message); process.exit(1); });
})();
