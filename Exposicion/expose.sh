#!/bin/bash

LOCAL_PORT=8080      # El puerto de tu proyecto en tu PC
REMOTE_PORT=2026     # El puerto que configuramos en el Proxy de Plesk
SERVER="root@castelancarpinteyro.com"

echo "🔗 Conectando túnel: Tu PC ($LOCAL_PORT) <---> Servidor ($REMOTE_PORT)"
echo "🌍 URL pública: https://preview.castelancarpinteyro.com"

ssh -R $REMOTE_PORT:localhost:$LOCAL_PORT $SERVER -N
