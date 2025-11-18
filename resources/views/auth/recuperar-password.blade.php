<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: #333;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .error-container {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .error-container ul {
            list-style: none;
        }

        .error-container li {
            color: #c33;
            font-size: 14px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .error-container li::before {
            content: '⚠';
            margin-right: 8px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2036b2ff;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .password-input-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
        }

        .toggle-password:hover {
            color: #333;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #2036b2ff 100%);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .security-info {
            background: #f0f7ff;
            border: 1px solid #b3d9ff;
            border-radius: 8px;
            padding: 12px;
            margin-top: 20px;
            font-size: 13px;
            color: #0066cc;
            text-align: center;
        }

        .security-info::before {
            content: '🔒';
            margin-right: 6px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
                margin: 10px;
            }

            .header h2 {
                font-size: 24px;
            }
        }

        .strength-meter {
            height: 4px;
            background: #e1e5e9;
            border-radius: 2px;
            margin-top: 8px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { background: #ff4757; width: 25%; }
        .strength-fair { background: #ffa502; width: 50%; }
        .strength-good { background: #2ed573; width: 75%; }
        .strength-strong { background: #1e90ff; width: 100%; }

        .strength-text {
            font-size: 12px;
            margin-top: 4px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Restablecer Contraseña</h2>
            <p>Ingresa tu nueva contraseña segura</p>
        </div>

        @if ($errors->any())
            <div class="error-container">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('actualizar-password') }}">
            @csrf
            @if ($correo)
                <input type="hidden" name="correo" value="{{ $correo }}">
            @elseif ($telefono)
                <input type="hidden" name="telefono" value="{{ $telefono }}">
            @endif

            <div class="form-group">
                <label for="new_password">Nueva contraseña:</label>
                <div class="password-input-container">
                    <input type="password" id="new_password" name="new_password" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('new_password')">👁</button>
                </div>
                <div class="strength-meter">
                    <div class="strength-fill" id="strength-fill"></div>
                </div>
                <div class="strength-text" id="strength-text"></div>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Confirmar contraseña:</label>
                <div class="password-input-container">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                    <button type="button" class="toggle-password" onclick="togglePassword('new_password_confirmation')">👁</button>
                </div>
            </div>

            <button type="submit" class="submit-btn">Actualizar Contraseña</button>
        </form>

        <div class="security-info">
            Tu contraseña será encriptada y almacenada de forma segura
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '❌';
            } else {
                input.type = 'password';
                button.textContent = '👁';
            }
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            let text = '';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            const fill = document.getElementById('strength-fill');
            const textEl = document.getElementById('strength-text');
            
            fill.className = 'strength-fill';
            
            switch(strength) {
                case 0:
                case 1:
                    fill.classList.add('strength-weak');
                    text = 'Muy débil';
                    textEl.style.color = '#ff4757';
                    break;
                case 2:
                    fill.classList.add('strength-fair');
                    text = 'Débil';
                    textEl.style.color = '#ffa502';
                    break;
                case 3:
                case 4:
                    fill.classList.add('strength-good');
                    text = 'Buena';
                    textEl.style.color = '#2ed573';
                    break;
                case 5:
                    fill.classList.add('strength-strong');
                    text = 'Muy fuerte';
                    textEl.style.color = '#1e90ff';
                    break;
            }
            
            textEl.textContent = password.length > 0 ? `Seguridad: ${text}` : '';
        }

        document.getElementById('new_password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });
    </script>

    <script>
    window.addEventListener('DOMContentLoaded', function () {
        const success = '{{ session("success") }}'; 
        if (success) {
            alert('✅ Contraseña actualizada con éxito');
            window.location.href = 'http://localhost:4200';
        }
    });
</script>

</body>
</html>