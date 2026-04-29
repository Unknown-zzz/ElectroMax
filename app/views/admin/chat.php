<style>
/* ── Chat Layout — Mobile-first ───────────────────────────────────────── */
#chat-root {
  display: flex;
  flex-direction: column;          /* apilado en móvil */
  height: calc(100svh - 130px);   /* svh para móvil real */
  min-height: 420px;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 4px 24px rgba(0,0,0,.18);
  font-family: 'Segoe UI', system-ui, sans-serif;
}
@media (min-width: 768px) {
  #chat-root {
    flex-direction: row;
    height: calc(100vh - 90px);
    border-radius: 12px;
  }
}

/* ── Server strip — oculto en móvil ──────────────────────────────────── */
#chat-server-bar {
  display: none;                   /* oculto en móvil */
}
.srv-icon {
  width: 44px; height: 44px;
  border-radius: 50%;
  background: #5865f2;
  display: flex; align-items: center; justify-content: center;
  font-size: 18px; cursor: pointer;
  transition: border-radius .2s;
  flex-shrink: 0; color:#fff; font-weight:700;
}
.srv-icon:hover,.srv-icon.active { border-radius: 16px; }

@media (min-width: 768px) {
  #chat-server-bar {
    display: flex;
    width: 60px;
    background: #1a1b1e;
    flex-direction: column;
    align-items: center;
    padding: 12px 0;
    gap: 8px;
    flex-shrink: 0;
  }
}

/* ── Channel sidebar — barra horizontal en móvil ─────────────────────── */
#chat-channels {
  width: 100%;
  background: #2b2d31;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  max-height: 48px;              /* colapsado en móvil */
  overflow: hidden;
  transition: max-height .25s ease;
  border-bottom: 1px solid #1a1b1e;
}
#chat-channels.open { max-height: 260px; }

#chat-channels header {
  padding: 8px 12px;
  font-weight: 700;
  font-size: 13px;
  color: #f2f3f5;
  display: flex;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  user-select: none;
  flex-shrink: 0;
}
#chat-channels header .ch-toggle {
  margin-left: auto;
  font-size: 11px;
  color: #949ba4;
}

/* En móvil, el contenido del sidebar se hace scrolleable */
#chat-channels > div {
  overflow-y: auto;
  max-height: 210px;
}

.ch-section-label {
  color: #949ba4;
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  padding: 10px 10px 4px;
}

@media (min-width: 768px) {
  #chat-channels {
    width: 220px;
    max-height: none;
    overflow: visible;
    border-bottom: none;
    border-right: 1px solid #1a1b1e;
  }
  #chat-channels.open { max-height: none; }
  #chat-channels header {
    padding: 14px 12px 10px;
    font-size: 14px;
    cursor: default;
    border-bottom: 1px solid #1a1b1e;
  }
  #chat-channels header .ch-toggle { display: none; }
  #chat-channels > div { max-height: none; overflow-y: auto; flex: 1; }
  .ch-section-label { padding: 14px 10px 4px; }
}
.ch-item {
  display: flex;
  align-items: center;
  gap: 7px;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
  color: #949ba4;
  font-size: 14px;
  margin: 0 6px;
  transition: background .1s, color .1s;
  position: relative;
}
.ch-item:hover  { background: rgba(255,255,255,.06); color: #dbdee1; }
.ch-item.active { background: rgba(255,255,255,.12); color: #f2f3f5; }
.ch-item .ch-hash { font-size: 15px; flex-shrink: 0; }
.ch-item .ch-name { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ch-item .ch-lock { font-size: 10px; color: #f0b232; }
.ch-unread {
  min-width: 16px; height: 16px;
  background: #ed4245;
  border-radius: 8px;
  font-size: 10px;
  font-weight: 700;
  color: #fff;
  display: flex; align-items: center; justify-content: center;
  padding: 0 4px;
}

/* DM section */
.dm-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 5px 10px;
  border-radius: 4px;
  cursor: pointer;
  margin: 0 6px;
  color: #949ba4;
  font-size: 13px;
  transition: background .1s, color .1s;
}
.dm-item:hover  { background: rgba(255,255,255,.06); color: #dbdee1; }
.dm-item.active { background: rgba(255,255,255,.12); color: #f2f3f5; }
.dm-avatar {
  width: 26px; height: 26px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 700; color: #fff;
  flex-shrink: 0;
  position: relative;
}
.dm-avatar::after {
  content: '';
  position: absolute;
  bottom: -1px; right: -1px;
  width: 9px; height: 9px;
  border-radius: 50%;
  border: 2px solid #2b2d31;
  background: #949ba4;
}
.dm-avatar.online::after { background: #23a55a; }

/* User bar at bottom */
#chat-user-bar {
  margin-top: auto;
  background: #232428;
  padding: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}
#chat-user-bar .ub-avatar {
  width: 30px; height: 30px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; color: #fff;
  flex-shrink: 0;
  position: relative;
}
#chat-user-bar .ub-avatar::after {
  content: '';
  position: absolute;
  bottom: -2px; right: -2px;
  width: 11px; height: 11px;
  border-radius: 50%;
  background: #23a55a;
  border: 2px solid #232428;
}
#chat-user-bar .ub-info { flex: 1; min-width: 0; }
#chat-user-bar .ub-name { font-size: 12px; font-weight: 700; color: #f2f3f5; white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
#chat-user-bar .ub-role { font-size: 10px; color: #949ba4; }

/* ── Main chat area ───────────────────────────────────────────────────── */
#chat-main {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: #313338;
  min-width: 0;
}
#chat-header {
  height: 46px;
  min-height: 46px;
  background: #313338;
  border-bottom: 1px solid #1a1b1e;
  display: flex;
  align-items: center;
  padding: 0 14px;
  gap: 10px;
}
#chat-header .hdr-icon { font-size: 18px; color: #949ba4; }
#chat-header .hdr-title { font-weight: 700; font-size: 14px; color: #f2f3f5; }
#chat-header .hdr-desc  { font-size: 12px; color: #949ba4; margin-left: 8px; }
.hdr-sep { width: 1px; height: 20px; background: #3f4147; margin: 0 4px; }

#messages-area {
  flex: 1;
  overflow-y: auto;
  padding: 8px 0 4px;
  display: flex;
  flex-direction: column;
}
#messages-area::-webkit-scrollbar { width: 6px; }
#messages-area::-webkit-scrollbar-thumb { background: #1a1b1e; border-radius: 3px; }

.msg-row {
  display: flex;
  gap: 14px;
  padding: 2px 14px;
}
.msg-row:hover { background: rgba(4,4,5,.07); }
.msg-row:not(.grouped) { margin-top: 14px; }
.msg-avatar {
  width: 36px; height: 36px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px; font-weight: 700; color: #fff;
  flex-shrink: 0;
  margin-top: 2px;
  cursor: pointer;
}
.msg-row.grouped .msg-avatar { visibility: hidden; width: 36px; }
.msg-row.grouped:hover .grp-ts { display: block; }
.grp-ts {
  display: none;
  position: absolute;
  font-size: 9px;
  color: #949ba4;
  left: 0; top: 50%;
  transform: translateY(-50%);
  width: 36px;
  text-align: center;
}
.msg-body { flex: 1; min-width: 0; }
.msg-header-row {
  display: flex;
  align-items: baseline;
  gap: 7px;
  margin-bottom: 1px;
}
.msg-author {
  font-size: 14px; font-weight: 600;
  cursor: pointer;
}
.msg-author:hover { text-decoration: underline; }
.msg-ts    { font-size: 10px; color: #949ba4; }
.msg-text  { font-size: 14px; color: #dbdee1; line-height: 1.4; word-break: break-word; }
.msg-row.grouped .msg-header-row { display: none; }
.role-pill {
  font-size: 9px;
  font-weight: 700;
  border-radius: 3px;
  padding: 0 4px;
  text-transform: uppercase;
  letter-spacing: .3px;
  vertical-align: middle;
}
.role-admin     { background: #ed4245; color: #fff; }
.role-vendedor  { background: #5865f2; color: #fff; }
.role-inventario{ background: #23a55a; color: #fff; }

/* Welcome splash */
.ch-welcome {
  padding: 14px 14px 20px;
  margin-top: auto;
}
.ch-welcome .wlc-icon  { font-size: 54px; line-height: 1; }
.ch-welcome .wlc-title { font-size: 24px; font-weight: 800; color: #f2f3f5; margin: 6px 0 4px; }
.ch-welcome .wlc-desc  { font-size: 14px; color: #949ba4; }

/* Input area */
#chat-input-area { padding: 0 14px 18px; }
#chat-input-wrap {
  background: #383a40;
  border-radius: 8px;
  display: flex;
  align-items: flex-end;
  padding: 10px 14px;
  gap: 10px;
}
#chat-input-wrap.readonly { opacity: .6; }
#msg-textarea {
  flex: 1;
  background: transparent;
  border: none;
  outline: none;
  color: #dbdee1;
  font-size: 14px;
  resize: none;
  max-height: 180px;
  line-height: 1.4;
  font-family: inherit;
}
#msg-textarea::placeholder { color: #949ba4; }
#btn-send {
  background: none;
  border: none;
  color: #949ba4;
  cursor: pointer;
  font-size: 18px;
  padding: 2px 5px;
  border-radius: 4px;
  transition: color .1s;
  flex-shrink: 0;
}
#btn-send:hover:not(:disabled) { color: #dbdee1; }
#btn-send:disabled { opacity: .4; cursor: not-allowed; }

/* ── Right users panel — oculto en móvil ─────────────────────────────── */
#chat-users-panel {
  display: none;
}

@media (min-width: 768px) {
  #chat-users-panel {
    display: block;
    width: 210px;
    background: #2b2d31;
    overflow-y: auto;
    padding: 14px 8px;
    flex-shrink: 0;
  }
  #chat-users-panel::-webkit-scrollbar { width: 4px; }
  #chat-users-panel::-webkit-scrollbar-thumb { background: #1a1b1e; border-radius: 2px; }
}
.up-section-label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .5px;
  color: #949ba4;
  padding: 4px 8px 6px;
}
.up-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 5px 8px;
  border-radius: 4px;
  cursor: pointer;
}
.up-item:hover { background: rgba(255,255,255,.06); }
.up-avatar {
  width: 30px; height: 30px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 700; color: #fff;
  flex-shrink: 0;
  position: relative;
}
.up-avatar::after {
  content: '';
  position: absolute;
  bottom: -2px; right: -2px;
  width: 10px; height: 10px;
  border-radius: 50%;
  border: 2px solid #2b2d31;
}
.up-item.online  .up-avatar::after { background: #23a55a; }
.up-item.offline .up-avatar::after { background: #747f8d; }
.up-name { font-size: 13px; color: #949ba4; white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.up-item.online .up-name { color: #dbdee1; }

/* Status bar */
#chat-status {
  padding: 3px 14px;
  font-size: 11px;
  color: #949ba4;
  border-top: 1px solid #1a1b1e;
  min-height: 22px;
}
#chat-status.err { color: #ed4245; }

/* Toast */
#chat-toast {
  position: fixed;
  bottom: 24px; right: 24px;
  background: #2b2d31;
  color: #dbdee1;
  padding: 10px 14px;
  border-radius: 8px;
  font-size: 13px;
  box-shadow: 0 4px 16px rgba(0,0,0,.4);
  opacity: 0;
  transform: translateY(8px);
  transition: opacity .2s, transform .2s;
  pointer-events: none;
  z-index: 1000;
  max-width: 280px;
  border-left: 4px solid #5865f2;
}
#chat-toast.show { opacity: 1; transform: translateY(0); }
</style>

<div class="container-fluid py-3">
  <div class="row">
    <div class="col-md-2"><?php include __DIR__ . '/sidebar.php'; ?></div>
    <div class="col-md-10">

      <div id="chat-root">

        <!-- Server strip -->
        <div id="chat-server-bar">
          <div class="srv-icon active" title="ElectroMax">⚡</div>
        </div>

        <!-- Channel sidebar -->
        <div id="chat-channels">
          <header id="ch-header-toggle">
            <span>⚡</span><span>ElectroMax Staff</span>
            <span class="ch-toggle" id="ch-toggle-icon">▼ canales</span>
          </header>
          <div style="flex:1;overflow-y:auto;">
            <div class="ch-section-label">Canales</div>
            <div id="canal-list"></div>
            <div class="ch-section-label" style="margin-top:10px">Mensajes directos</div>
            <div id="dm-list"></div>
          </div>
          <div id="chat-user-bar">
            <div class="ub-avatar" id="ub-av">?</div>
            <div class="ub-info">
              <div class="ub-name" id="ub-nombre">—</div>
              <div class="ub-role" id="ub-role">—</div>
            </div>
          </div>
        </div>

        <!-- Main area -->
        <div id="chat-main">
          <div id="chat-header">
            <span class="hdr-icon" id="hdr-icon">💬</span>
            <span class="hdr-title" id="hdr-title">general</span>
            <span class="hdr-sep"></span>
            <span class="hdr-desc" id="hdr-desc">Canal general del equipo</span>
          </div>
          <div id="messages-area">
            <div class="ch-welcome" id="ch-welcome">
              <div class="wlc-icon">💬</div>
              <div class="wlc-title"># general</div>
              <div class="wlc-desc">Bienvenido al chat del equipo ElectroMax.</div>
            </div>
          </div>
          <div id="chat-status"></div>
          <div id="chat-input-area">
            <div id="chat-input-wrap">
              <textarea id="msg-textarea" rows="1" placeholder="Escribe un mensaje…"></textarea>
              <button id="btn-send" title="Enviar">➤</button>
            </div>
          </div>
        </div>

        <!-- Users panel -->
        <div id="chat-users-panel">
          <div class="up-section-label" id="up-online-lbl">En línea — 0</div>
          <div id="up-online-list"></div>
          <div class="up-section-label" style="margin-top:12px" id="up-offline-lbl">Desconectados — 0</div>
          <div id="up-offline-list"></div>
        </div>

      </div><!-- /chat-root -->

    </div>
  </div>
</div>

<div id="chat-toast"></div>

<script>
// ── Config ──────────────────────────────────────────────────────────────
const WS_URL   = `ws://${location.hostname}:8181`;
const WS_TOKEN = <?= json_encode($token) ?>;

// ── State ────────────────────────────────────────────────────────────────
let ws          = null;
let yo          = null;    // {id, nombre, rol}
let canales     = [];      // [{id, slug, nombre, icono, tipo, puede_escribir}]
let staff       = [];      // [{id, nombre, rol}]
let onlineSet   = new Set();
let unreadDM    = {};      // userId -> count
let mode        = 'canal'; // 'canal' | 'dm'
let canalActivo = null;    // {id, ...}
let dmPartner   = null;    // {id, nombre, rol}
let lastAuthor  = null;

// ── Colors ───────────────────────────────────────────────────────────────
const PALETTE = ['#5865f2','#eb459e','#ed4245','#f0b232','#57f287','#00b0f4','#9c27b0','#ff9800'];
const colorFor  = id => PALETTE[id % PALETTE.length];
const initials  = n  => (n||'?').split(' ').map(w=>w[0]).join('').toUpperCase().slice(0,2);
const rolPill   = r  => {
  const cls = {admin:'role-admin',vendedor:'role-vendedor',inventario:'role-inventario'}[r]||'';
  const lbl = {admin:'Admin',vendedor:'Vendedor',inventario:'Inv.'}[r]||r;
  return `<span class="role-pill ${cls}">${lbl}</span>`;
};

// ── WebSocket ────────────────────────────────────────────────────────────
function conectar() {
  setStatus('Conectando…');
  ws = new WebSocket(WS_URL);
  ws.onopen    = () => { setStatus(''); ws.send(JSON.stringify({type:'auth', token: WS_TOKEN})); };
  ws.onclose   = () => { setStatus('Desconectado. Recargando…', true); setTimeout(()=>location.reload(), 4000); };
  ws.onmessage = e  => dispatch(JSON.parse(e.data));
  ws.onerror   = () => setStatus('Error de conexión', true);
}

function send(obj) {
  if (ws && ws.readyState === WebSocket.OPEN) ws.send(JSON.stringify(obj));
}

// ── Message dispatch ─────────────────────────────────────────────────────
function dispatch(msg) {
  switch (msg.type) {
    case 'auth_ok':     onAuthOk(msg);    break;
    case 'auth_err':    onAuthErr(msg);   break;
    case 'presencia':   onPresencia(msg); break;
    case 'msg_canal':   onMsgCanal(msg);  break;
    case 'msg_dm':      onMsgDm(msg);     break;
    case 'hist_canal':  onHistCanal(msg); break;
    case 'hist_dm':     onHistDm(msg);    break;
  }
}

function onAuthOk(msg) {
  yo      = msg.usuario;
  canales = msg.canales;
  staff   = msg.staff;
  onlineSet = new Set(msg.online || []);

  // Populate user bar
  const av = document.getElementById('ub-av');
  av.textContent       = initials(yo.nombre);
  av.style.background  = colorFor(yo.id);
  document.getElementById('ub-nombre').textContent = yo.nombre;
  document.getElementById('ub-role').textContent   = {admin:'Administrador',vendedor:'Vendedor',inventario:'Inventario'}[yo.rol] || yo.rol;

  renderCanales();
  renderUsers();
  renderDmList();

  // Open first channel
  if (canales.length) abrirCanal(canales[0]);
}

function onAuthErr(msg) {
  setStatus('Error de autenticación: ' + msg.texto, true);
}

function onPresencia(msg) {
  staff     = msg.staff || staff;
  onlineSet = new Set(msg.online || []);
  renderUsers();
  renderDmList();
}

function onMsgCanal(msg) {
  if (mode === 'canal' && canalActivo?.id === msg.canal_id) {
    appendMsg(msg, false);
  } else if (msg.id_rem !== yo?.id) {
    const ch = canales.find(c=>c.id===msg.canal_id);
    toast(`#${ch?.nombre||'?'} · ${msg.nombre}: ${msg.texto.slice(0,60)}`);
  }
}

function onMsgDm(msg) {
  const otherId = msg.id_rem === yo?.id ? msg.id_dest : msg.id_rem;
  const esActivo = mode === 'dm' && dmPartner?.id === otherId;
  if (esActivo) {
    appendMsg(msg, true);
  } else if (msg.id_rem !== yo?.id) {
    unreadDM[msg.id_rem] = (unreadDM[msg.id_rem] || 0) + 1;
    const u = staff.find(s=>s.id===msg.id_rem);
    toast(`${u?.nombre||'Alguien'}: ${msg.texto.slice(0,60)}`);
    renderDmList();
  }
}

function onHistCanal(msg) {
  clearMessages();
  (msg.mensajes || []).forEach(m => appendMsg(m, false));
}

function onHistDm(msg) {
  clearMessages();
  (msg.mensajes || []).forEach(m => appendMsg(m, true));
}

// ── Render channels ──────────────────────────────────────────────────────
function renderCanales() {
  const el = document.getElementById('canal-list');
  el.innerHTML = '';
  for (const ch of canales) {
    const item = document.createElement('div');
    item.className = 'ch-item' + (canalActivo?.id === ch.id ? ' active' : '');
    item.dataset.id = ch.id;

    const hash = document.createElement('span');
    hash.className   = 'ch-hash';
    hash.textContent = ch.icono || '#';

    const name = document.createElement('span');
    name.className   = 'ch-name';
    name.textContent = ch.nombre;

    item.append(hash, name);

    if (!ch.puede_escribir) {
      const lock = document.createElement('span');
      lock.className   = 'ch-lock';
      lock.textContent = '🔒';
      item.appendChild(lock);
    }

    item.onclick = () => abrirCanal(ch);
    el.appendChild(item);
  }
}

function renderDmList() {
  const el = document.getElementById('dm-list');
  el.innerHTML = '';
  for (const u of staff) {
    if (u.id === yo?.id) continue;
    const online  = onlineSet.has(u.id);
    const active  = mode === 'dm' && dmPartner?.id === u.id;
    const unread  = unreadDM[u.id] || 0;

    const item = document.createElement('div');
    item.className = 'dm-item' + (active ? ' active' : '');

    const av = document.createElement('div');
    av.className = 'dm-avatar' + (online ? ' online' : '');
    av.textContent      = initials(u.nombre);
    av.style.background = colorFor(u.id);

    const name = document.createElement('span');
    name.style.cssText = 'flex:1;font-size:12px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
    name.textContent   = u.nombre;

    item.append(av, name);

    if (unread > 0) {
      const b = document.createElement('span');
      b.className   = 'ch-unread';
      b.textContent = unread > 9 ? '9+' : unread;
      item.appendChild(b);
    }

    item.onclick = () => abrirDm(u);
    el.appendChild(item);
  }
}

function renderUsers() {
  const onList  = document.getElementById('up-online-list');
  const offList = document.getElementById('up-offline-list');
  onList.innerHTML = offList.innerHTML = '';
  let cOn = 0, cOff = 0;
  for (const u of staff) {
    if (u.id === yo?.id) continue;
    const online = onlineSet.has(u.id);
    const item   = document.createElement('div');
    item.className = 'up-item ' + (online ? 'online' : 'offline');

    const av = document.createElement('div');
    av.className       = 'up-avatar';
    av.textContent     = initials(u.nombre);
    av.style.background = colorFor(u.id);

    const info = document.createElement('div');
    info.style.minWidth = '0';
    info.innerHTML = `<div class="up-name">${u.nombre}</div>
                      <div style="font-size:9px;color:#949ba4">${{admin:'Administrador',vendedor:'Vendedor',inventario:'Inventario'}[u.rol]||u.rol}</div>`;

    item.append(av, info);
    item.onclick = () => abrirDm(u);

    if (online) { onList.appendChild(item);  cOn++;  }
    else        { offList.appendChild(item); cOff++; }
  }
  document.getElementById('up-online-lbl').textContent  = `En línea — ${cOn}`;
  document.getElementById('up-offline-lbl').textContent = `Desconectados — ${cOff}`;
}

// ── Navigation ───────────────────────────────────────────────────────────
function abrirCanal(ch) {
  mode = 'canal'; canalActivo = ch; dmPartner = null; lastAuthor = null;
  document.getElementById('hdr-icon').textContent  = ch.icono || '#';
  document.getElementById('hdr-title').textContent = ch.nombre;
  document.getElementById('hdr-desc').textContent  = ch.descripcion || '';
  const ta = document.getElementById('msg-textarea');
  ta.placeholder = ch.puede_escribir ? `Escribe en #${ch.nombre}…` : `Solo lectura en #${ch.nombre}`;
  ta.disabled    = !ch.puede_escribir;
  document.getElementById('btn-send').disabled = !ch.puede_escribir;
  document.getElementById('chat-input-wrap').classList.toggle('readonly', !ch.puede_escribir);
  markActiveCanal(ch.id, null);
  clearMessages();
  send({ type: 'hist_canal', canal_id: ch.id });
}

function abrirDm(u) {
  mode = 'dm'; dmPartner = u; canalActivo = null; lastAuthor = null;
  delete unreadDM[u.id];
  document.getElementById('hdr-icon').textContent  = '@';
  document.getElementById('hdr-title').textContent = u.nombre;
  document.getElementById('hdr-desc').textContent  = {admin:'Administrador',vendedor:'Vendedor',inventario:'Inventario'}[u.rol]||u.rol;
  const ta = document.getElementById('msg-textarea');
  ta.placeholder = `Escribe a ${u.nombre}…`;
  ta.disabled    = false;
  document.getElementById('btn-send').disabled = false;
  document.getElementById('chat-input-wrap').classList.remove('readonly');
  markActiveCanal(null, u.id);
  clearMessages();
  send({ type: 'hist_dm', id_dest: u.id });
}

function markActiveCanal(canalId, userId) {
  document.querySelectorAll('.ch-item').forEach(el => {
    el.classList.toggle('active', parseInt(el.dataset.id) === canalId);
  });
  renderDmList(); // re-render to update active state + unread badges
}

// ── Sending ──────────────────────────────────────────────────────────────
function enviar() {
  const ta    = document.getElementById('msg-textarea');
  const texto = ta.value.trim();
  if (!texto || !yo) return;
  ta.value = '';
  ta.style.height = 'auto';

  if (mode === 'canal' && canalActivo?.puede_escribir) {
    send({ type: 'msg_canal', canal_id: canalActivo.id, texto });
  } else if (mode === 'dm' && dmPartner) {
    send({ type: 'msg_dm', id_dest: dmPartner.id, texto });
  }
}

document.getElementById('btn-send').addEventListener('click', enviar);
document.getElementById('msg-textarea').addEventListener('keydown', e => {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); enviar(); }
});
document.getElementById('msg-textarea').addEventListener('input', function() {
  this.style.height = 'auto';
  this.style.height = Math.min(this.scrollHeight, 180) + 'px';
});

// ── Message rendering ─────────────────────────────────────────────────────
function clearMessages() {
  lastAuthor = null;
  document.getElementById('messages-area').innerHTML = '';
}

function appendMsg(msg, isDm) {
  const area    = document.getElementById('messages-area');
  const esYo    = msg.id_rem === yo?.id;
  const grouped = lastAuthor === msg.id_rem;
  lastAuthor    = msg.id_rem;

  const ts   = new Date(msg.ts);
  const hora = ts.toLocaleTimeString('es', { hour:'2-digit', minute:'2-digit' });

  const row = document.createElement('div');
  row.className = 'msg-row' + (grouped ? ' grouped' : '');
  row.style.position = 'relative';

  // Avatar col
  const avWrap = document.createElement('div');
  avWrap.style.position = 'relative';

  const av = document.createElement('div');
  av.className       = 'msg-avatar';
  av.textContent     = initials(msg.nombre);
  av.style.background = colorFor(msg.id_rem);
  av.onclick = () => { const u = staff.find(s=>s.id===msg.id_rem); if(u && u.id !== yo.id) abrirDm(u); };

  const grpTs = document.createElement('div');
  grpTs.className   = 'grp-ts';
  grpTs.textContent = hora;

  avWrap.append(av, grpTs);

  // Body
  const body = document.createElement('div');
  body.className = 'msg-body';

  if (!grouped) {
    const hdr = document.createElement('div');
    hdr.className = 'msg-header-row';

    const author = document.createElement('span');
    author.className   = 'msg-author';
    author.textContent = msg.nombre + (esYo ? ' (tú)' : '');
    author.style.color = colorFor(msg.id_rem);
    author.onclick = () => { const u = staff.find(s=>s.id===msg.id_rem); if(u && u.id !== yo.id) abrirDm(u); };

    const pill = document.createElement('span');
    pill.innerHTML = rolPill(msg.rol);

    const time = document.createElement('span');
    time.className   = 'msg-ts';
    time.textContent = ts.toLocaleDateString('es') + ' ' + hora;

    hdr.append(author, pill, time);
    body.appendChild(hdr);
  }

  const text = document.createElement('div');
  text.className   = 'msg-text';
  text.textContent = msg.texto;
  body.appendChild(text);

  row.append(avWrap, body);
  area.appendChild(row);
  area.scrollTop = area.scrollHeight;
}

// ── Helpers ──────────────────────────────────────────────────────────────
function setStatus(msg, err = false) {
  const el = document.getElementById('chat-status');
  el.textContent = msg;
  el.className   = err ? 'err' : '';
}

let toastTimer = null;
function toast(txt, ms = 4000) {
  const el = document.getElementById('chat-toast');
  el.textContent = txt;
  el.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('show'), ms);
}

// ── Mobile: toggle channel sidebar ───────────────────────────────────────
document.getElementById('ch-header-toggle').addEventListener('click', () => {
  if (window.innerWidth >= 768) return;
  const panel = document.getElementById('chat-channels');
  const icon  = document.getElementById('ch-toggle-icon');
  const open  = panel.classList.toggle('open');
  icon.textContent = open ? '▲ cerrar' : '▼ canales';
});

// Cerrar sidebar de canales al seleccionar uno (móvil)
function cerrarSidebarMobile() {
  if (window.innerWidth < 768) {
    document.getElementById('chat-channels').classList.remove('open');
    document.getElementById('ch-toggle-icon').textContent = '▼ canales';
  }
}

// Patch abrirCanal y abrirDm para cerrar sidebar en móvil
const _abrirCanal = abrirCanal;
abrirCanal = function(ch) { _abrirCanal(ch); cerrarSidebarMobile(); };
const _abrirDm = abrirDm;
abrirDm = function(u) { _abrirDm(u); cerrarSidebarMobile(); };

// ── Boot ─────────────────────────────────────────────────────────────────
conectar();
</script>
