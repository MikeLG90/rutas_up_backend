<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planificador de Rutas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }
        
        .container-fluid {
            padding: 0;
            height: 100vh;
        }
        
        #map {
            cursor: crosshair;
            height: 100vh;
            width: 100%;
            position: relative;
            z-index: 1;
        }
        
        .map-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 90%;
            width: auto;
        }
        
        .map-header {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .map-header h2 {
            margin: 0;
            font-size: 1.5rem;
        }
        
        .marker-info {
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        
        .leaflet-routing-container {
            font-size: 12px; 
            position: absolute;
            top: 70px;
            right: 10px;
            left: auto;
            z-index: 1000; 
            max-width: 300px; 
            max-height: 60vh;
            overflow-y: auto;
            background-color: rgba(255, 255, 255, 0.9); 
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        
        .leaflet-routing-alt {
            max-height: 50vh;
            overflow-y: auto;
            display: block !important;
        }
        
        .instructions-toggle {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1001;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 8px 12px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .map-controls {
                width: 90%;
                padding: 10px;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .leaflet-routing-container {
                max-width: 80%;
                top: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div id="map"></div>
        
        <div class="map-header">
            <h2>Planificador de Rutas</h2>
        </div>
        
        <div class="map-controls">
            <div class="marker-info">Marcadores colocados: <span id="marker-count">0</span></div>
            <div class="btn-group">
                <button id="reset-button" class="btn btn-primary">Reiniciar Marcadores</button>
                <button id="generate-route" class="btn btn-primary">Generar Ruta</button>
                <button class="btn btn-primary" onclick="abrirModal()">Guardar Ruta</button>
            </div>
            <input id="coordenadas-input" class="form-control" type="hidden">
            <input id="distancia-input" class="form-control" type="hidden">
        </div>
        
        <div class="instructions-toggle" id="toggle-instructions">
            <i class="bi bi-list"></i> Instrucciones
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Guardar ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form onsubmit="event.preventDefault(); crearRuta();">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre_ruta" class="form-label">Nombre de ruta:</label>
                            <input type="text" class="form-control" id="nombre_ruta" placeholder="Nombre de ruta" name="nombre" required>
                            <input id="coordenadas-input2" class="form-control" type="hidden" name="punteros">
                        </div>
                        <div class="mb-3">
                            <label for="distancia" class="form-label">Distancia en KM:</label>
                            <input type="text" class="form-control" id="distancia-input2" placeholder="Distancia" name="distancia" required> 
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function abrirModal() {
            // Obtener el valor del input principal (fuera del modal)
            const valor = document.getElementById('coordenadas-input').value;
            const valor2 = document.getElementById('distancia-input').value;
            
            // Pasar el valor al input dentro del modal
            document.getElementById('coordenadas-input2').value = valor;
            document.getElementById('distancia-input2').value = valor2;
            
            // Mostrar el modal usando Bootstrap
            const modal = new bootstrap.Modal(document.getElementById('modal'));
            modal.show();
        }

        async function crearRuta() {
            const nuevaRuta = {
                nombre_ruta: document.getElementById('nombre_ruta').value,
                puntos_geograficos: document.getElementById('coordenadas-input2').value,
                distancia: document.getElementById('distancia-input2').value,
            };

            // Validar que los campos no estén vacíos
            if (!nuevaRuta.nombre_ruta || !nuevaRuta.puntos_geograficos || !nuevaRuta.distancia) {
                alert('Por favor, completa todos los campos requeridos.');
                return; // Detiene la ejecución si falta algún campo
            }

            try {
                const response = await fetch('https://rutas-up-backend.onrender.com/api/rutas/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(nuevaRuta)
                });

                if (!response.ok) { 
                    const errorResponse = await response.json(); // Captura la respuesta de error
                    throw new Error(errorResponse.message || 'Error al crear la ruta');
                }

                const result = await response.json();
                console.log(result);
                Swal.fire({
                    title: '¡Éxito!',
                    text: 'Ruta creada con éxito',
                    icon: 'success',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        container: 'swal-container',
                        popup: 'swal-popup',
                        header: 'swal-header',
                        title: 'swal-title',
                        text: 'swal-text',
                        closeButton: 'swal-close-button',
                        icon: 'swal-icon',
                        image: 'swal-image',
                        content: 'swal-content',
                        input: 'swal-input',
                        actions: 'swal-actions',
                        confirmButton: 'swal-confirm-button',
                        cancelButton: 'swal-cancel-button',
                        footer: 'swal-footer'
                    }
                });
                const modal = bootstrap.Modal.getInstance(document.getElementById('modal'));
                modal.hide(); 
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: '¡Error!',
                    text: 'Ha ocurrido un error al crear la ruta',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar',
                    customClass: {
                        container: 'swal-container',
                        popup: 'swal-popup',
                        header: 'swal-header',
                        title: 'swal-title',
                        text: 'swal-text',
                        closeButton: 'swal-close-button',
                        icon: 'swal-icon',
                        image: 'swal-image',
                        content: 'swal-content',
                        input: 'swal-input',
                        actions: 'swal-actions',
                        confirmButton: 'swal-confirm-button',
                        cancelButton: 'swal-cancel-button',
                        footer: 'swal-footer'
                    }
                });
            }
        }
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([18.5001, -88.3000], 13);
            let markers = [];
            let routeControl;
            let instructionsVisible = true;
            const markerCount = document.getElementById('marker-count');
            const resetButton = document.getElementById('reset-button');
            const toggleInstructions = document.getElementById('toggle-instructions');
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Añadir escala al mapa
            L.control.scale({
                imperial: false,
                position: 'bottomright'
            }).addTo(map);
            
            map.on('click', function(e) {
                const { lat, lng } = e.latlng;
                const marker = L.marker([lat, lng], {
                    draggable: true // Hacer los marcadores arrastrables para mejor UX
                }).addTo(map);
                
                markers.push(marker);
                markerCount.textContent = markers.length;
                marker.bindPopup(`Parada ${markers.length}`).openPopup();
                
                // Permitir eliminar marcadores con clic derecho
                marker.on('contextmenu', function() {
                    map.removeLayer(marker);
                    markers = markers.filter(m => m !== marker);
                    markerCount.textContent = markers.length;
                    
                    // Actualizar numeración de los marcadores
                    markers.forEach((m, index) => {
                        m.bindPopup(`Parada ${index + 1}`);
                    });
                });
                
                // Actualizar ruta si ya existe cuando se mueve un marcador
                marker.on('dragend', function() {
                    if (routeControl) {
                        updateRoute();
                    }
                });
            });
            
            // Función para actualizar la ruta
            function updateRoute() {
                if (markers.length < 2) {
                    return;
                }
                
                // Obtener las coordenadas de los marcadores en orden
                const waypoints = markers.map(marker => {
                    return L.latLng(marker.getLatLng().lat, marker.getLatLng().lng);
                });
                
                // Eliminar ruta anterior si existe
                if (routeControl) {
                    map.removeControl(routeControl);
                }
                
                // Concatenar coordenadas para almacenar
                const concatenatedCoordinates = markers.map(marker => {
                    const { lat, lng } = marker.getLatLng();
                    return `${lat},${lng}`;
                }).join(' | ');
                
                document.getElementById('coordenadas-input').value = concatenatedCoordinates;
                
                // Trazar o dibujar ruta
                routeControl = L.Routing.control({
                    waypoints: waypoints,
                    routeWhileDragging: true,
                    showAlternatives: false,
                    addWaypoints: false,
                    fitSelectedRoutes: true,
                    createMarker: function () { return null; },
                    lineOptions: {
                        styles: [{ color: '#3b82f6', opacity: 0.8, weight: 5 }]
                    },
                    router: L.Routing.osrmv1({
                        serviceUrl: 'https://router.project-osrm.org/route/v1'
                    }),
                    show: instructionsVisible
                }).addTo(map);
                
                routeControl.on('routeselected', function (e) {
                    var rutaSeleccionada = e.route;
                    var distanciaTotal = rutaSeleccionada.summary.totalDistance;
                    var distanciaEnKm = distanciaTotal / 1000;
                    
                    console.log('Distancia total de la ruta:', distanciaEnKm.toFixed(2), 'km');
                    
                    var distanciaInput = document.getElementById('distancia-input');
                    if (distanciaInput) {
                        distanciaInput.value = distanciaEnKm.toFixed(2);
                    }
                });
            }
            
            // Configuración de botón para generar ruta dinámica
            document.getElementById('generate-route').addEventListener('click', function () {
                if (markers.length < 2) {
                    Swal.fire({
                        title: 'Atención',
                        text: 'Debes seleccionar al menos 2 puntos para generar la ruta',
                        icon: 'warning',
                        confirmButtonText: 'Entendido'
                    });
                    return;
                }
                
                updateRoute();
            });
            
            // Botón para reiniciar marcadores
            resetButton.addEventListener('click', function() {
                if (routeControl) {
                    map.removeControl(routeControl);
                    routeControl = null;
                }
                
                // Limpiar input
                document.getElementById('coordenadas-input').value = "";
                document.getElementById('distancia-input').value = "";
                
                // Remover marcadores
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];
                markerCount.textContent = 0;
                
                Swal.fire({
                    title: 'Reiniciado',
                    text: 'Se han eliminado todos los marcadores',
                    icon: 'info',
                    confirmButtonText: 'Aceptar',
                    timer: 2000,
                    timerProgressBar: true
                });
            });
            
            // Toggle para mostrar/ocultar instrucciones
            toggleInstructions.addEventListener('click', function() {
                instructionsVisible = !instructionsVisible;
                
                if (routeControl) {
                    const container = document.querySelector('.leaflet-routing-container');
                    if (container) {
                        if (instructionsVisible) {
                            container.style.display = 'block';
                            toggleInstructions.innerHTML = '<i class="bi bi-list"></i> Ocultar Instrucciones';
                        } else {
                            container.style.display = 'none';
                            toggleInstructions.innerHTML = '<i class="bi bi-list"></i> Mostrar Instrucciones';
                        }
                    }
                }
            });
            
            // Mostrar instrucciones iniciales
            Swal.fire({
                title: 'Planificador de Rutas',
                html: `
                    <ul class="text-start">
                        <li>Haz clic en el mapa para añadir puntos</li>
                        <li>Clic derecho en un marcador para eliminarlo</li>
                        <li>Arrastra los marcadores para ajustar la posición</li>
                        <li>Usa "Generar Ruta" cuando hayas colocado al menos 2 puntos</li>
                    </ul>
                `,
                icon: 'info',
                confirmButtonText: 'Entendido'
            });
        });
    </script>
</body>
</html>