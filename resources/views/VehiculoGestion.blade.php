<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Vehículos</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
            line-height: 1.5;
        }

        body.modal-open {
            overflow: hidden;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }

        /* Card Principal */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        /* Header */
        .card-header {
            background: #2563eb;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .card-header h5 {
            color: white;
            font-size: 1.125rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            backdrop-filter: blur(8px);
        }

        /* Toolbar */
        .toolbar {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: space-between;
            align-items: center;
        }

        /* Buscador */
        .search-wrapper {
            position: relative;
            flex: 1;
            min-width: 250px;
            max-width: 500px;
        }

        .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 0.5rem 0.5rem 0.5rem 2.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        /* Botones */
        .btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 9999px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-secondary {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background: #f9fafb;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-edit {
            background: #fef3c7;
            color: #d97706;
        }

        .btn-edit:hover {
            background: #fde68a;
        }

        .btn-delete {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fecaca;
        }

        /* Tabla */
        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f3f4f6;
        }

        thead th {
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-size: 0.75rem;
            font-weight: bold;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        thead th.text-center {
            text-align: center;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
            transition: background 0.2s;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        tbody td {
            padding: 0.75rem 1.5rem;
            font-size: 0.875rem;
            color: #4b5563;
        }

        tbody td.text-center {
            text-align: center;
        }

        .placa-badge {
            background: #dbeafe;
            color: #1e40af;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            border: 1px solid #bfdbfe;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            display: inline-block;
        }

        .anio-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            display: inline-block;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        /* Footer */
        .card-footer {
            background: white;
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .pagination {
            display: flex;
            gap: 0.25rem;
        }

        .pagination button {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
            background: white;
            border: 1px solid #d1d5db;
            color: #6b7280;
            cursor: pointer;
        }

        .pagination button:first-child {
            border-radius: 4px 0 0 4px;
        }

        .pagination button:last-child {
            border-radius: 0 4px 4px 0;
        }

        .pagination button:hover:not(:disabled) {
            background: #f9fafb;
        }

        .pagination button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 50;
            display: none;
        }

        .modal-overlay.active {
            display: block;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }

        .modal-container {
            position: fixed;
            inset: 0;
            z-index: 10;
            overflow-y: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-panel {
            background: white;
            border-radius: 8px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 672px;
        }

        .modal-header {
            background: #2563eb;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: white;
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-close {
            background: transparent;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0.25rem;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: #e5e7eb;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media (min-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .form-group input,
        .form-group select {
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-group input:disabled,
        .form-group select:disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        .modal-footer {
            background: #f9fafb;
            padding: 0.75rem 1.5rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        /* Loading Spinner */
        #loadingOverlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            z-index: 60;
            display: none;
            align-items: center;
            justify-content: center;
        }

        #loadingOverlay.active {
            display: flex;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e5e7eb;
            border-top-color: #2563eb;
            border-bottom-color: #2563eb;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Alert personalizado */
        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 1rem 1.5rem;
            min-width: 300px;
            z-index: 100;
            display: none;
            animation: slideIn 0.3s ease-out;
        }

        .custom-alert.active {
            display: block;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .custom-alert.success {
            border-left: 4px solid #10b981;
        }

        .custom-alert.error {
            border-left: 4px solid #ef4444;
        }

        .custom-alert.warning {
            border-left: 4px solid #f59e0b;
        }

        /* Confirm Dialog */
        .confirm-dialog {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
        }

        .confirm-dialog.active {
            display: flex;
        }

        .confirm-content {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .confirm-content h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1f2937;
        }

        .confirm-content p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .confirm-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-wrapper {
                max-width: 100%;
            }

            .btn {
                width: 100%;
            }

            .card-header {
                flex-direction: column;
                text-align: center;
            }

            .table-wrapper {
                font-size: 0.75rem;
            }

            thead th,
            tbody td {
                padding: 0.5rem;
            }
        }

        /* Iconos simples con símbolos */
        .icon-car::before { content: "🚗"; }
        .icon-search::before { content: "🔍"; }
        .icon-plus::before { content: "+"; }
        .icon-edit::before { content: "✎"; }
        .icon-trash::before { content: "🗑"; }
        .icon-close::before { content: "✕"; }
    </style>
</head>
<body>

    <!-- Spinner de carga -->
    <div id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Alert personalizado -->
    <div id="customAlert" class="custom-alert">
        <div id="alertMessage"></div>
    </div>

    <!-- Confirm Dialog -->
    <div id="confirmDialog" class="confirm-dialog">
        <div class="confirm-content">
            <h3 id="confirmTitle">Confirmar</h3>
            <p id="confirmMessage">¿Estás seguro?</p>
            <div class="confirm-actions">
                <button class="btn btn-secondary" onclick="closeConfirm()">Cancelar</button>
                <button class="btn btn-delete" id="confirmBtn">Confirmar</button>
            </div>
        </div>
    </div>

    <div class="container">
        
        <!-- Tarjeta Principal -->
        <div class="card">
            
            <!-- Encabezado -->
            <div class="card-header">
                <h5>
                    <span></span>
                    Listado de Vehículos
                </h5>
                <span class="badge" id="contadorRegistros">0 registros</span>
            </div>

            <!-- Barra de Herramientas -->
            <div class="toolbar">
                <!-- Buscador -->
                <div class="search-wrapper">
                    <span class="search-icon icon-search"></span>
                    <input type="text" id="inputBuscador" class="search-input" placeholder="Buscar por placa o serie...">
                </div>
                
                <!-- Botón Nuevo -->
                <button onclick="abrirModalCrear()" class="btn btn-primary">
                    <span class="icon-plus"></span>
                    Nuevo Vehículo
                </button>
            </div>

            <!-- Tabla -->
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Serie</th>
                            <th>Placa</th>
                            <th>Económico</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th class="text-center">Año</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaBody">
                        <!-- Las filas se generan con JS aquí -->
                    </tbody>
                </table>
            </div>
            
            <!-- Paginación -->
            <div class="card-footer">
                <span style="font-size: 0.875rem; color: #6b7280;">Mostrando resultados</span>
                <div class="pagination">
                    <button disabled>Anterior</button>
                    <button disabled>Siguiente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="modalVehiculo" class="modal-overlay">
        
        <div class="modal-backdrop" onclick="cerrarModal()"></div>

        <div class="modal-container">
            <div class="modal-panel">
                
                <!-- Header del Modal -->
                <div class="modal-header">
                    <h3 id="modalTitulo">
                        <span class="icon-car"></span> Nuevo Vehículo
                    </h3>
                    <button class="modal-close" onclick="cerrarModal()">
                        <span class="icon-close"></span>
                    </button>
                </div>

                <!-- Body del Modal -->
                <div class="modal-body">
                    <form id="formVehiculo">
                        <input type="hidden" id="vehiculo_id">
                        
                        <div class="form-grid">
                            <!-- Serie -->
                            <div class="form-group">
                                <label for="num_serie">Número de Serie</label>
                                <input type="text" id="num_serie" required pattern="[A-Z0-9\-]+" placeholder="Ej: ABC-12345">
                            </div>

                            <!-- Placa -->
                            <div class="form-group">
                                <label for="placa">Placa</label>
                                <input type="text" id="placa" required placeholder="Ej: XYZ-999">
                            </div>

                            <!-- Económico -->
                            <div class="form-group">
                                <label for="num_economico">Num. Económico</label>
                                <input type="text" id="num_economico" required>
                            </div>

                            <!-- Año -->
                            <div class="form-group">
                                <label for="anio">Año</label>
                                <input type="number" id="anio" min="1900" max="2099" required>
                            </div>

                            <!-- Marca -->
                            <div class="form-group">
                                <label for="marca_id">Marca</label>
                                <select id="marca_id" required onchange="cargarModelos(this.value)">
                                    <option value="">Seleccione Marca...</option>
                                </select>
                            </div>

                            <!-- Modelo -->
                            <div class="form-group">
                                <label for="modelo_id">Modelo</label>
                                <select id="modelo_id" required disabled>
                                    <option value="">Seleccione una marca primero</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer del Modal -->
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="cerrarModal()">Cancelar</button>
                    <button class="btn btn-primary" onclick="guardarVehiculo()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // ==========================================
        //  CONFIGURACIÓN DE APIs
        // ==========================================
        const BASE_URL = "https://rutas-up-backend.onrender.com";

        const API_URLS = {
            GET_VEHICULOS:   `${BASE_URL}/api/vehiculos`, 
            POST_VEHICULO:   `${BASE_URL}/api/vehiculos/store`, 
            PUT_VEHICULO:    `${BASE_URL}/api/vehiculos/update`,
            DELETE_VEHICULO: `${BASE_URL}/api/vehiculos/delete`,
            GET_MARCAS:      `${BASE_URL}/api/marcas`, 
            GET_MODELOS:     `${BASE_URL}/api/modelos/marca`
        };

        // ==========================================
        //  UTILIDADES DE ALERTA
        // ==========================================
        function showAlert(message, type = 'success') {
            const alert = document.getElementById('customAlert');
            const alertMessage = document.getElementById('alertMessage');
            
            alert.className = `custom-alert ${type} active`;
            alertMessage.textContent = message;
            
            setTimeout(() => {
                alert.classList.remove('active');
            }, 3000);
        }

        function showConfirm(title, message, onConfirm) {
            const dialog = document.getElementById('confirmDialog');
            const confirmTitle = document.getElementById('confirmTitle');
            const confirmMessage = document.getElementById('confirmMessage');
            const confirmBtn = document.getElementById('confirmBtn');
            
            confirmTitle.textContent = title;
            confirmMessage.textContent = message;
            
            dialog.classList.add('active');
            
            confirmBtn.onclick = () => {
                closeConfirm();
                onConfirm();
            };
        }

        function closeConfirm() {
            document.getElementById('confirmDialog').classList.remove('active');
        }

        // ==========================================
        //  ESTADO GLOBAL Y UTILIDADES
        // ==========================================
        let listaVehiculos = []; 
        const loading = document.getElementById('loadingOverlay');
        const modal = document.getElementById('modalVehiculo');

        document.addEventListener('DOMContentLoaded', () => {
            cargarDatosDesdeAPI(); 
            cargarMarcas();    
        });

        // ==========================================
        //  LOGICA DE MODAL
        // ==========================================
        function abrirModal() {
            modal.classList.add('active');
            document.body.classList.add('modal-open');
        }

        function cerrarModal() {
            modal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        function abrirModalCrear() {
            document.getElementById('formVehiculo').reset();
            document.getElementById('vehiculo_id').value = "";
            document.getElementById('modalTitulo').innerHTML = '<span class="icon-car"></span> Nuevo Vehículo';
            document.getElementById('modelo_id').innerHTML = '<option value="">Seleccione una marca primero</option>';
            document.getElementById('modelo_id').disabled = true;
            abrirModal();
        }

        function abrirModalEditar(vehiculo) {
            document.getElementById('modalTitulo').innerHTML = '<span class="icon-edit"></span> Editar Vehículo';
            document.getElementById('vehiculo_id').value = vehiculo.vehiculo_id;
            
            document.getElementById('num_serie').value = vehiculo.num_serie;
            document.getElementById('placa').value = vehiculo.placa;
            document.getElementById('num_economico').value = vehiculo.num_economico;
            document.getElementById('anio').value = vehiculo.anio;
            document.getElementById('marca_id').value = vehiculo.marca_id;

            cargarModelos(vehiculo.marca_id, vehiculo.modelo_id);
            abrirModal();
        }

        // ==========================================
        //  FUNCIONES DE DATOS (CRUD)
        // ==========================================

        async function cargarDatosDesdeAPI() {
            loading.classList.add('active');
            try {
                const response = await fetch(API_URLS.GET_VEHICULOS);
                if (!response.ok) throw new Error('Error en la respuesta del servidor');
                
                const data = await response.json();
                listaVehiculos = Array.isArray(data) ? data : (data.data || []); 
                
                renderizarTabla();
            } catch (error) {
                console.error("Error:", error);
                showAlert('No se pudieron cargar los vehículos. Verifica la conexión.', 'error');
            } finally {
                loading.classList.remove('active');
            }
        }

        async function guardarVehiculo() {
            const form = document.getElementById('formVehiculo');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const id = document.getElementById('vehiculo_id').value;
            const datos = {
                num_serie: document.getElementById('num_serie').value,
                placa: document.getElementById('placa').value,
                num_economico: document.getElementById('num_economico').value,
                anio: document.getElementById('anio').value,
                marca_id: document.getElementById('marca_id').value,
                modelo_id: document.getElementById('modelo_id').value
            };

            loading.classList.add('active');
            
            try {
                let url, method;

                if (id) {
                    url = `${API_URLS.PUT_VEHICULO}/${id}`;
                    method = 'PUT';
                } else {
                    url = API_URLS.POST_VEHICULO;
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(datos)
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Error al guardar');
                }

                await cargarDatosDesdeAPI();
                cerrarModal();
                
                showAlert('Operación realizada correctamente', 'success');

            } catch (error) {
                console.error("Error:", error);
                showAlert(error.message || 'Hubo un problema al guardar.', 'error');
            } finally {
                loading.classList.remove('active');
            }
        }

        function confirmarEliminar(id) {
            showConfirm(
                '¿Eliminar vehículo?',
                'No podrás revertir esto',
                async () => {
                    loading.classList.add('active');
                    
                    try {
                        const url = `${API_URLS.DELETE_VEHICULO}/${id}`;
                        
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) throw new Error('Error al eliminar');

                        await cargarDatosDesdeAPI();
                        showAlert('El registro ha sido eliminado', 'success');

                    } catch (error) {
                        console.error(error);
                        showAlert('No se pudo eliminar el registro', 'error');
                    } finally {
                        loading.classList.remove('active');
                    }
                }
            );
        }

        // ==========================================
        //  RENDERIZADO UI
        // ==========================================

        function renderizarTabla() {
            const tbody = document.getElementById('tablaBody');
            const contador = document.getElementById('contadorRegistros');
            const filtro = document.getElementById('inputBuscador').value.toLowerCase();

            tbody.innerHTML = '';
            
            const datosFiltrados = listaVehiculos.filter(item => 
                (item.placa && String(item.placa).toLowerCase().includes(filtro)) || 
                (item.num_serie && String(item.num_serie).toLowerCase().includes(filtro))
            );

            contador.innerText = `${datosFiltrados.length} registros`;

            if(datosFiltrados.length === 0) {
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:2rem;color:#9ca3af;">No se encontraron datos</td></tr>`;
                return;
            }

            datosFiltrados.forEach(vehiculo => {
                const nombreMarca = vehiculo.marca ? (vehiculo.marca.marca || vehiculo.marca.nombre || vehiculo.marca) : 'N/A';
                const nombreModelo = vehiculo.modelo ? (vehiculo.modelo.modelo || vehiculo.modelo.nombre || vehiculo.modelo) : 'N/A';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-weight:500;">${vehiculo.vehiculo_id || vehiculo.id}</td>
                    <td>${vehiculo.num_serie}</td>
                    <td>
                        <span class="placa-badge">
                            ${vehiculo.placa}
                        </span>
                    </td>
                    <td>${vehiculo.num_economico}</td>
                    <td>${nombreMarca}</td>
                    <td>${nombreModelo}</td>
                    <td class="text-center">
                        <span class="anio-badge">
                            ${vehiculo.anio}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="actions">
                            <button onclick='abrirModalEditar(${JSON.stringify(vehiculo)})' 
                                class="btn btn-icon btn-edit" title="Editar">
                                <span class="icon-edit"></span>
                            </button>
                            <button onclick="confirmarEliminar(${vehiculo.vehiculo_id || vehiculo.id})" 
                                class="btn btn-icon btn-delete" title="Eliminar">
                                <span class="icon-trash"></span>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        document.getElementById('inputBuscador').addEventListener('keyup', renderizarTabla);

        // ==========================================
        //  CARGA DE COMBOS
        // ==========================================

        async function cargarMarcas() {
            try {
                const response = await fetch(API_URLS.GET_MARCAS);
                if (!response.ok) throw new Error('Error cargando marcas');
                
                const data = await response.json();
                const marcas = Array.isArray(data) ? data : (data.data || []);

                const select = document.getElementById('marca_id');
                select.innerHTML = '<option value="">Seleccione Marca...</option>';
                
                marcas.forEach(m => {
                    select.innerHTML += `<option value="${m.marca_id || m.id}">${m.marca || m.nombre}</option>`;
                });
            } catch (e) { 
                console.error("Error marcas", e); 
                showAlert('No se pudieron cargar las marcas', 'warning');
            }
        }

        async function cargarModelos(marcaId, modeloSeleccionado = null) {
            const selectModelo = document.getElementById('modelo_id');
            selectModelo.innerHTML = '<option>Cargando...</option>';
            selectModelo.disabled = false;

            if (!marcaId) {
                 selectModelo.innerHTML = '<option value="">Seleccione una marca primero</option>';
                 selectModelo.disabled = true;
                 return;
            }

            try {
                const response = await fetch(`${API_URLS.GET_MODELOS}/${marcaId}`);
                
                if (!response.ok) throw new Error('Error cargando modelos');
                
                const data = await response.json();
                const modelos = Array.isArray(data) ? data : (data.data || []);

                selectModelo.innerHTML = '<option value="">Seleccione Modelo...</option>';
                
                modelos.forEach(m => {
                    const idModelo = m.modelo_id || m.id;
                    const nombreModelo = m.modelo || m.nombre;
                    const selected = (modeloSeleccionado && modeloSeleccionado == idModelo) ? 'selected' : '';
                    
                    selectModelo.innerHTML += `<option value="${idModelo}" ${selected}>${nombreModelo}</option>`;
                });

                if (modelos.length === 0) {
                    selectModelo.innerHTML = '<option value="">Sin modelos disponibles</option>';
                }

            } catch (error) {
                console.error(error);
                selectModelo.innerHTML = '<option value="">Error al cargar</option>';
            }
        }

    </script>
</body>
</html>