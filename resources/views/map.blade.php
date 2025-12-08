<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Mapbox + Rutas reales por calles</title>
  <!-- Mapbox CSS -->
  <link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }

    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    body {
      background: #f5f5f5;
    }

    #map {
      width: 100vw;
      height: 100vh;
    }

    /* Sidebar flotante */
    #sidebar {
      position: fixed;
      top: 20px;
      left: 20px;
      width: 280px;
      max-height: calc(100vh - 40px);
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      z-index: 1000;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    #sidebar:hover {
      background: rgba(255, 255, 255, 0.98);
      box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    #sidebar h3 {
      margin: 0;
      padding: 20px 20px 15px;
      font-size: 18px;
      font-weight: 600;
      color: #2c3e50;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
      background: rgba(255, 255, 255, 0.5);
    }

    #lista-rutas {
      padding: 15px;
      max-height: calc(100vh - 140px);
      overflow-y: auto;
    }

    /* Scrollbar personalizado */
    #lista-rutas::-webkit-scrollbar {
      width: 6px;
    }

    #lista-rutas::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.05);
      border-radius: 3px;
    }

    #lista-rutas::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 3px;
    }

    #lista-rutas::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.3);
    }

    .ruta-item {
      cursor: pointer;
      padding: 12px 15px;
      border-left: 4px solid;
      margin-bottom: 8px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.7);
      transition: all 0.3s ease;
      font-size: 14px;
      font-weight: 500;
      color: #34495e;
      position: relative;
      overflow: hidden;
    }

    .ruta-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .ruta-item:hover {
      background: rgba(255, 255, 255, 0.9);
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .ruta-item:hover::before {
      opacity: 1;
    }

    .ruta-item:active {
      transform: translateX(3px) scale(0.98);
    }

    /* Botón Vista 3D mejorado */
    #reset-3d {
      position: fixed;
      top: 20px;
      right: 40px;
      z-index: 1001;
      padding: 12px 20px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 25px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      color: #2c3e50;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }

    #reset-3d:hover {
      background: rgba(255, 255, 255, 1);
      transform: translateY(-2px);
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
    }

    #reset-3d:active {
      transform: translateY(0);
    }

    /* Toggle sidebar button */
    #toggle-sidebar {
      position: fixed;
      top: 20px;
      left: 320px;
      z-index: 1002;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      font-size: 18px;
      color: #2c3e50;
    }

    #toggle-sidebar:hover {
      background: rgba(255, 255, 255, 1);
      transform: scale(1.1);
    }

    /* Sidebar oculto */
    #sidebar.hidden {
      transform: translateX(-100%);
      opacity: 0;
    }

    #toggle-sidebar.sidebar-hidden {
      left: 20px;
    }

    /* Responsive */
    @media (max-width: 768px) {
      #sidebar {
        width: calc(100vw - 40px);
        max-width: 320px;
      }
      
      #toggle-sidebar {
        left: 20px;
        top: 80px;
      }
      
      #toggle-sidebar.sidebar-hidden {
        left: 20px;
      }
      
      #reset-3d {
        top: 80px;
        right: 20px;
      }
    }

    /* Animación de carga */
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .ruta-item {
      animation: fadeIn 0.5s ease forwards;
    }

    /* Popup personalizado */
    .mapboxgl-popup-content {
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
  </style>
</head>
<body>
  <div id="map"></div>
  
  <div id="sidebar">
    <h3>🚌 Rutas disponibles</h3>
    <div id="lista-rutas"></div>
  </div>

  <button id="toggle-sidebar" title="Mostrar/Ocultar Sidebar">
    ☰
  </button>

  <button id="reset-3d" title="Vista 3D">
    🏙️ Vista 3D
  </button>

  <!-- Mapbox GL JS -->
  <script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>
  <script>
    const combiIconUrl = "{{ asset('images/combi.png') }}";
    
    mapboxgl.accessToken = 'pk.eyJ1IjoiZGFydGhzdGFyc2NyZWFtIiwiYSI6ImNtZGIxY2oxcTBxNWwybG9yeXBxbzd6NmoifQ.2mvR85hwUrywc0L8p7sf5Q';
    
    const map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v12',
      center: [-88.297, 18.501],
      zoom: 15,
      pitch: 60,
      bearing: -20,
      antialias: true
    });

    
    map.addControl(new mapboxgl.NavigationControl(), 'top-right');

    


    const markers = {};
    const lastPositions = {};
    const coloresPorRuta = {};

    function getBearing(from, to) {
      const lat1 = from[1] * Math.PI / 180;
      const lat2 = to[1] * Math.PI / 180;
      const deltaLon = (to[0] - from[0]) * Math.PI / 180;
      const y = Math.sin(deltaLon) * Math.cos(lat2);
      const x = Math.cos(lat1) * Math.sin(lat2) - Math.sin(lat1) * Math.cos(lat2) * Math.cos(deltaLon);
      return (Math.atan2(y, x) * 180 / Math.PI + 360) % 360;
    }

    // Toggle sidebar functionality
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-sidebar');
    
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('hidden');
      toggleBtn.classList.toggle('sidebar-hidden');
      toggleBtn.innerHTML = sidebar.classList.contains('hidden') ? '☰' : '✕';
    });

    const socket = new WebSocket('wss://rutasws-f6hhc6bmekbbekfe.mexicocentral-01.azurewebsites.net/');
    socket.onopen = () => console.log('WebSocket conectado');
    socket.onerror = (error) => console.error('Error en WebSocket:', error);
    
    socket.onmessage = (event) => {
      try {
        const data = JSON.parse(event.data);
        const id = data.id_combi;
        if (!id || !data.latitude || !data.longitude) return;

        const lngLat = [data.longitude, data.latitude];

        if (!markers[id]) {
          const el = document.createElement('img');
          el.src = combiIconUrl;
          el.style.width = '40px';
          el.style.height = '40px';
          el.style.transition = 'transform 0.3s ease';
          el.style.transformOrigin = 'center';
          el.style.filter = 'drop-shadow(0 2px 4px rgba(0,0,0,0.3))';

          const marker = new mapboxgl.Marker(el).setLngLat(lngLat).addTo(map);
          const popup = new mapboxgl.Popup({ offset: 25 }).setText('Esperando datos...');
          marker.setPopup(popup);

          el.addEventListener('click', () => {
            popup.isOpen() ? popup.remove() : popup.addTo(map);
          });

          markers[id] = { marker, popup, el };
          lastPositions[id] = lngLat;
        } else {
          const markerObj = markers[id];
          const from = lastPositions[id];
          const to = lngLat;
          const bearing = getBearing(from, to);

          markerObj.el.style.transform = `rotate(${bearing}deg)`;
          markerObj.marker.setLngLat(to);
          lastPositions[id] = to;

          const info = `
            <div style="padding: 5px;">
              <strong style="color: #2c3e50;">🚌 Vehículo ID: ${id}</strong><br/>
              <small style="color: #7f8c8d;">
                📍 Lat: ${data.latitude.toFixed(6)}<br/>
                📍 Lng: ${data.longitude.toFixed(6)}<br/>
                🚀 Velocidad: ${data.speed} km/h<br/>
                🕒 Hora: ${new Date(data.timestamp).toLocaleTimeString()}
              </small>
            </div>
          `;
          markerObj.popup.setHTML(info);
        }
      } catch (e) {
        console.warn('Mensaje no válido:', event.data);
      }
    };

    document.getElementById('reset-3d').addEventListener('click', () => {
      map.easeTo({
        pitch: 60,
        bearing: -20,
        duration: 1000
      });
    });

    function colorAleatorio() {
      const colores = [
        '#e74c3c', '#3498db', '#2ecc71', '#f39c12', 
        '#9b59b6', '#1abc9c', '#e67e22', '#34495e'
      ];
      return colores[Math.floor(Math.random() * colores.length)];
    }

    async function trazarRutaConCalles(puntos, nombre, color = '#007cbf', id = 'ruta') {
      if (puntos.length < 2) {
        console.warn('Se necesitan al menos 2 puntos para trazar una ruta.');
        return;
      }

      const coordsStr = puntos.map(p => p.join(',')).join(';');
      const url = `https://api.mapbox.com/directions/v5/mapbox/driving/${coordsStr}?geometries=geojson&access_token=${mapboxgl.accessToken}`;

      try {
        const res = await fetch(url);
        const data = await res.json();
        
        if (!data.routes || !data.routes[0]) {
          console.error("No se pudo obtener una ruta de calles.");
          return;
        }

        const routeCoords = data.routes[0].geometry.coordinates;
        const sourceId = `route-${id}`;
        const layerId = `route-${id}`;

        if (map.getSource(sourceId)) {
          map.removeLayer(layerId);
          map.removeSource(sourceId);
        }

        map.addSource(sourceId, {
          type: 'geojson',
          data: {
            type: 'Feature',
            geometry: {
              type: 'LineString',
              coordinates: routeCoords
            }
          }
        });

        map.addLayer({
          id: layerId,
          type: 'line',
          source: sourceId,
          layout: {
            'line-join': 'round',
            'line-cap': 'round'
          },
          paint: {
            'line-color': color,
            'line-width': 5,
            'line-opacity': 0.8
          }
        });

        const bounds = new mapboxgl.LngLatBounds();
        routeCoords.forEach(coord => bounds.extend(coord));
        map.fitBounds(bounds, { padding: 80 });

        const midPoint = routeCoords[Math.floor(routeCoords.length / 2)];
        new mapboxgl.Popup({ closeButton: false })
          .setLngLat(midPoint)
          .setHTML(`<div style="padding: 8px; text-align: center;"><strong style="color: ${color};">🚌 ${nombre}</strong></div>`)
          .addTo(map);
      } catch (e) {
        console.error("Error al trazar ruta con calles:", e);
      }
    }

    map.on('style.load', () => {
      map.addSource('mapbox-dem', {
        type: "raster-dem",
        url: "mapbox://mapbox.terrain-rgb",
        tileSize: 512,
        maxzoom: 14
      });
      map.setTerrain({ source: 'mapbox-dem', exaggeration: 1.5 });

      map.addLayer({
  id: '3d-buildings',
  source: 'composite',
  'source-layer': 'building',
  filter: ['==', 'extrude', 'true'],
  type: 'fill-extrusion',
  minzoom: 15,
  paint: {
    'fill-extrusion-color': '#aaa',
    'fill-extrusion-height': ['get', 'height'],
    'fill-extrusion-base': ['get', 'min_height'],
    'fill-extrusion-opacity': 0.6
  }
});

      // Cargar rutas del backend
      fetch('https://rutas-up-backend.onrender.com/api/rutas')
        .then(res => res.json())
        .then(rutas => {
          const contenedor = document.getElementById('lista-rutas');
          rutas.forEach((ruta, index) => {
            const color = colorAleatorio();
            coloresPorRuta[ruta.ruta_id] = color;

            const div = document.createElement('div');
            div.className = 'ruta-item';
            div.style.borderColor = color;
            div.style.animationDelay = `${index * 0.1}s`;
            div.innerHTML = `
              <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 12px; height: 12px; background: ${color}; border-radius: 50%; flex-shrink: 0;"></div>
                <span>${ruta.nombre_ruta}</span>
              </div>
            `;
            
            div.onclick = () => {
              try {
                const puntos = ruta.puntos_geograficos.split('|').map(p => {
                  const [lat, lng] = p.trim().split(',').map(Number);
                  return [lng, lat];
                });
                trazarRutaConCalles(puntos, ruta.nombre_ruta, color, ruta.ruta_id);
              } catch (e) {
                console.error('Error al procesar puntos de ruta:', e);
              }
            };
            
            contenedor.appendChild(div);
          });
        })
        .catch(err => console.error('Error cargando rutas:', err));
    });
  </script>
</body>
</html>