#!/bin/bash

# ElectroMax Chat Server Startup Script
# Usage: ./start.sh [port]

PORT=${1:-8181}

# Check if composer dependencies are installed
if [ ! -d "vendor" ]; then
    echo "📦 Installing dependencies..."
    composer install
fi

# Start the server
echo "🚀 Starting ElectroMax Chat Server on port $PORT..."
WS_PORT=$PORT php ratchet-server.php
