<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Vehículos Urbanos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            min-height: 100vh;
            padding: 40px 20px;
            color: #1a202c;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 32px 40px;
            margin-bottom: 32px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #718096;
            font-size: 0.95rem;
            font-weight: 400;
        }

        #dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
            gap: 24px;
        }

        .vehicle-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .vehicle-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 24px 20px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        .vehicle-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2d3748;
            letter-spacing: -0.3px;
        }

        .status-indicator {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .status-connected {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-disconnected {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .video-wrapper {
            width: 100%;
            padding-top: 56.25%;
            position: relative;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            overflow: hidden;
        }

        #video-stream {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }

        #loading-message {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #94a3b8;
            font-size: 1rem;
            text-align: center;
            font-weight: 500;
        }

        .card-body {
            padding: 24px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .info-value {
            color: #1e293b;
            font-size: 0.95rem;
            font-weight: 600;
        }

        .people-counter {
            text-align: center;
            padding: 28px 24px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-top: 2px solid #bae6fd;
        }

        .count-number {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 8px;
        }

        .count-label {
            display: block;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 12px;
            }

            header {
                padding: 24px 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            #dashboard-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="container">


        <div id="dashboard-container">
            
            <div class="vehicle-card" id="card-combi-1">
                <div class="card-header">
                    <span class="vehicle-name">Combi 1</span>
                    <span id="estado-combi-1" class="status-indicator status-disconnected">Desconectado</span>
                </div>

                <div class="video-wrapper">
                    <img id="video-stream" src="/placeholder.svg" alt="Video Stream Combi 1">
                    <p id="loading-message">Esperando conexión...</p>
                </div>

                <div class="people-counter">
                    <span id="people-count-combi-1" class="count-number">0</span>
                    <span class="count-label">Personas Detectadas</span>
                </div>
            </div>

            <div class="vehicle-card" id="card-combi-2">
                <div class="card-header">
                    <span class="vehicle-name">Combi 2</span>
                    <span class="status-indicator status-connected">Activo</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Velocidad</span>
                        <span class="info-value">45 km/h</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ubicación</span>
                        <span class="info-value">Av. Principal</span>
                    </div>
                </div>
                <div class="people-counter">
                    <span id="people-count-combi-2" class="count-number">12</span>
                    <span class="count-label">Personas Detectadas</span>
                </div>
            </div>

            <div class="vehicle-card" id="card-combi-3">
                <div class="card-header">
                    <span class="vehicle-name">Combi 3</span>
                    <span class="status-indicator status-connected">Activo</span>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Velocidad</span>
                        <span class="info-value">20 km/h</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Ubicación</span>
                        <span class="info-value">Terminal de Buses</span>
                    </div>
                </div>
                <div class="people-counter">
                    <span id="people-count-combi-3" class="count-number">5</span>
                    <span class="count-label">Personas Detectadas</span>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        const WS_URL = "wss://rutasws-f6hhc6bmekbbekfe.mexicocentral-01.azurewebsites.net/";
        let ws;

        const statusElement = document.getElementById("estado-combi-1");
        const videoElement = document.getElementById("video-stream");
        const loadingMessage = document.getElementById("loading-message");
        const peopleCountElement = document.getElementById("people-count-combi-1");

        function conectar() {
            console.log("Intentando conectar al servidor...");
            ws = new WebSocket(WS_URL);

            ws.onopen = () => {
                statusElement.textContent = "Conectado";
                statusElement.className = "status-indicator status-connected";
                console.log("Conexión WebSocket establecida.");
            };

            ws.onmessage = (event) => {
                try {
                    const data = JSON.parse(event.data);

                    if (data.tipo === "video" && data.frame) {
                        loadingMessage.style.display = 'none';
                        videoElement.style.display = 'block';
                        const dataUrl = 'data:image/jpeg;base64,' + data.frame;
                        videoElement.src = dataUrl;
                    }
                    
                    if (data.tipo === "conteo" && data.peopleCount !== undefined) {
                        peopleCountElement.textContent = data.peopleCount;
                    }
                    
                    if (data.tipo === "datos_completos") {
                        if (data.frame) {
                            loadingMessage.style.display = 'none';
                            videoElement.style.display = 'block';
                            videoElement.src = 'data:image/jpeg;base64,' + data.frame;
                        }
                        if (data.peopleCount !== undefined) {
                            peopleCountElement.textContent = data.peopleCount;
                        }
                    }

                } catch (e) {
                    console.error("Error al procesar el mensaje:", e);
                }
            };

            ws.onerror = (e) => {
                console.error("Error en la conexión WebSocket.");
            };

            ws.onclose = () => {
                statusElement.textContent = "Desconectado";
                statusElement.className = "status-indicator status-disconnected";
                console.log("Conexión cerrada. Intentando reconectar en 3 segundos...");
                setTimeout(conectar, 3000);
            };
        }

        conectar();
    </script>

</body>
</html>