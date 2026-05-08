# ElectroMax Chat Server (Ratchet)

Servidor WebSocket para ElectroMax usando Ratchet (PHP).

## Requisitos

- PHP >= 8.1
- Composer
- MySQL/MariaDB

## Instalación

1. **Instalar dependencias:**

```bash
cd chat-server
composer install
```

## Uso

### Windows

```bash
cd chat-server
start.bat [puerto]
```

Ejemplo: `start.bat 8181`

### Linux/Mac

```bash
cd chat-server
chmod +x start.sh
./start.sh [puerto]
```

Ejemplo: `./start.sh 8181`

### Manual

```bash
cd chat-server
php ratchet-server.php
```

## Variables de Entorno

Se pueden configurar usando variables de entorno del sistema:

- `WS_PORT` - Puerto del servidor WebSocket (default: 8181)
- `DB_HOST` - Host de la base de datos (default: 127.0.0.1)
- `DB_PORT` - Puerto de la base de datos (default: 3309)
- `DB_NAME` - Nombre de la base de datos (default: electromax)
- `DB_USER` - Usuario de la base de datos (default: root)
- `DB_PASS` - Contraseña de la base de datos (default: vacía)

Ejemplo:

```bash
WS_PORT=9000 php ratchet-server.php
```

## Protocolo

El servidor utiliza el mismo protocolo JSON que la versión anterior:

### Auth
```json
{"type": "auth", "token": "..."}
```

### Canal Message
```json
{"type": "msg_canal", "canal_id": 1, "texto": "Mensaje"}
```

### DM
```json
{"type": "msg_dm", "id_dest": 2, "texto": "Mensaje"}
```

### Historia Canal
```json
{"type": "hist_canal", "canal_id": 1}
```

### Historia DM
```json
{"type": "hist_dm", "id_dest": 2}
```

## Estructura de Archivos

```
chat-server/
├── ratchet-server.php      # Servidor principal
├── start.sh               # Script de inicio (Linux/Mac)
├── start.bat              # Script de inicio (Windows)
├── composer.json          # Dependencias
├── src/
│   └── ChatHandler.php    # Handler de WebSocket
└── vendor/                # Dependencias instaladas
```

## Diferencias con Node.js

- **Lenguaje:** PHP en lugar de JavaScript
- **Framework:** Ratchet en lugar de ws
- **Base de datos:** PDO en lugar de mysql2/promise
- **Tipado:** Más explícito con tipos de PHP

## Logs

El servidor imprime logs en la consola:

```
[+] Nueva conexión. Total: 1
[auth] Usuario (rol)
[canal#1] Usuario: Mensaje
[dm] Usuario -> #2
[bye] Usuario
[-] Conexión cerrada. Total: 0
```

## Notas

- El servidor debe estar ejecutándose en la misma máquina o accesible desde el navegador
- Para uso en producción, considera usar un supervisor como Supervisor o systemd
- El puerto debe estar abierto en el firewall si se accede desde otra máquina
