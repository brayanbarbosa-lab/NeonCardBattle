<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/user.php';

$registro = new User();
$erros = [];
$valores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores = [
        'username' => trim($_POST['username']),
        'email' => trim($_POST['email']),
        'password' => $_POST['password'],
        'confirm_password' => $_POST['confirm_password']
    ];

    $erros = $registro->validarRegistro($valores);

    if (empty($erros)) {
        if ($registro->registrarUsuario($valores)) {
            $_SESSION['sucesso'] = 'Registro realizado com sucesso!';
            header("Location: login.php");
            exit();
        } else {
            $erros['geral'] = 'Erro ao registrar. Tente novamente mais tarde.';
        }
    }
}

// Gerar token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta | Batalha de Cartas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Nunito Sans', sans-serif;
        }

        :root {
            --primary: #6a5af9;
            --secondary: #d66efd;
            --dark: #0f0c29;
            --darker: #080616;
            --accent: #00c9ff;
            --light: #e0e0ff;
            --success: #3ddc84;
            --danger: #ff3860;
            --warning: #ffeb3b;
        }

        body {
            background: linear-gradient(135deg, var(--darker), var(--dark));
            background-attachment: fixed;
            color: var(--light);
            overflow-x: hidden;
            min-height: 100vh;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        /* Efeito de fundo glassmorphism */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 30%, rgba(106, 90, 249, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(214, 110, 253, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -2;
        }

        /* Grid pattern futurista */
        .grid-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(100, 100, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(100, 100, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: -1;
            animation: gridFloat 20s linear infinite;
        }

        @keyframes gridFloat {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-10px) translateX(5px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        /* Efeito de partículas */
        .particle-effect {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, var(--accent), transparent),
                radial-gradient(2px 2px at 40px 70px, var(--secondary), transparent),
                radial-gradient(1px 1px at 90px 40px, var(--warning), transparent),
                radial-gradient(1px 1px at 130px 80px, var(--success), transparent),
                radial-gradient(2px 2px at 160px 30px, var(--primary), transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            animation: particleFloat 20s linear infinite;
            opacity: 0.1;
            z-index: -3;
            pointer-events: none;
        }

        @keyframes particleFloat {
            0% { transform: translateY(0px) translateX(0px); }
            50% { transform: translateY(-10px) translateX(5px); }
            100% { transform: translateY(0px) translateX(0px); }
        }

        /* Container de registro */
        .registro-container {
            max-width: 500px;
            width: 100%;
            background: rgba(20, 20, 40, 0.7);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 
                0 0 50px rgba(106, 90, 249, 0.3),
                0 8px 32px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(100, 100, 255, 0.2);
            position: relative;
            overflow: hidden;
            z-index: 10;
        }

        .registro-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(106, 90, 249, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .registro-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .registro-title {
            font-size: 2.2rem;
            color: var(--light);
            letter-spacing: 2px;
            text-shadow: 
                0 0 5px var(--accent),
                0 0 10px var(--accent);
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .registro-subtitle {
            color: var(--light);
            opacity: 0.8;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 1.2rem;
        }

        input {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(30, 30, 60, 0.5);
            border: 1px solid rgba(100, 100, 255, 0.2);
            border-radius: 12px;
            font-size: 1rem;
            color: var(--light);
            transition: all 0.3s ease;
        }

        input:focus {
            border: 1px solid var(--accent);
            box-shadow: 0 0 15px rgba(0, 201, 255, 0.3);
            outline: none;
        }

        .erro {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 8px;
            padding-left: 10px;
            display: block;
        }

        .password-strength {
            display: flex;
            gap: 5px;
            margin-top: 8px;
        }

        .strength-bar {
            height: 5px;
            flex: 1;
            background: #444;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .strength-bar.active {
            background: var(--success);
        }

        .strength-text {
            font-size: 0.8rem;
            color: var(--light);
            opacity: 0.7;
            margin-top: 5px;
            text-align: right;
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            color: white;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(106, 90, 249, 0.4);
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 8px 20px rgba(106, 90, 249, 0.6),
                0 0 15px rgba(0, 201, 255, 0.4);
        }

        .erro-geral {
            color: var(--danger);
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            background: rgba(255, 56, 96, 0.15);
            border-radius: 8px;
            border: 1px solid rgba(255, 56, 96, 0.3);
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
        }

        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-link a:hover {
            color: var(--primary);
            text-shadow: 0 0 10px rgba(106, 90, 249, 0.5);
        }

        .registro-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(100, 100, 255, 0.2);
            color: var(--light);
            opacity: 0.7;
            font-size: 0.9rem;
        }

        /* Animação de entrada */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .registro-container {
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .registro-container {
                padding: 30px 25px;
                max-width: 90%;
            }
            
            .registro-title {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .registro-container {
                padding: 25px 20px;
            }
            
            .registro-title {
                font-size: 1.6rem;
            }
            
            input, button {
                padding: 12px 12px 12px 40px;
            }
        }
        .aa{
            margin-top: -2vh;
        }
    </style>
</head>
<body>
    <!-- Efeitos de fundo -->
    <div class="grid-pattern"></div>
    <div class="particle-effect"></div>
    
    <!-- Container de registro -->
    <div class="registro-container">
        <div class="registro-header">
            <h1 class="registro-title">CRIAR CONTA</h1>
            <p class="registro-subtitle">Junte-se à batalha de cartas estratégicas</p>
        </div>
        
        <?php if(isset($erros['geral'])): ?>
            <div class="erro-geral">
                <i class="fas fa-exclamation-circle"></i> <?= $erros['geral'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="form-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" 
                       name="username" 
                       placeholder="Nome de Usuário"
                       value="<?= htmlspecialchars($valores['username'] ?? '') ?>"
                       required
                       autofocus>
                <?php if(isset($erros['username'])): ?>
                    <span class="erro"><i class="fas fa-exclamation-circle"></i> <?= $erros['username'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" 
                       name="email" 
                       placeholder="Email"
                       value="<?= htmlspecialchars($valores['email'] ?? '') ?>"
                       required>
                <?php if(isset($erros['email'])): ?>
                    <span class="erro"><i class="fas fa-exclamation-circle"></i> <?= $erros['email'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock input-icon aa"></i>
                <input type="password" 
                       id="password" 
                       name="password" 
                       placeholder="Senha"
                       required>
                <div class="password-strength">
                    <div class="strength-bar"></div>
                    <div class="strength-bar"></div>
                    <div class="strength-bar"></div>
                    <div class="strength-bar"></div>
                </div>
                <div class="strength-text" id="strength-text">Força da senha</div>
                <?php if(isset($erros['password'])): ?>
                    <span class="erro"><i class="fas fa-exclamation-circle"></i> <?= $erros['password'] ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" 
                       name="confirm_password" 
                       placeholder="Confirmar Senha"
                       required>
                <?php if(isset($erros['confirm_password'])): ?>
                    <span class="erro"><i class="fas fa-exclamation-circle"></i> <?= $erros['confirm_password'] ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit">
                <i class="fas fa-user-plus"></i> Criar Conta
            </button>
        </form>

        <div class="login-link">
            <a href="login.php">
                <i class="fas fa-sign-in-alt"></i> Já tem uma conta? Faça login
            </a>
        </div>
        

    </div>

    <script>
        // Adicionar efeito de foco nos inputs
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
        
        // Animação de hover no botão
        const submitBtn = document.querySelector('button');
        submitBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        submitBtn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
        
        // Validação de força da senha
        const passwordInput = document.getElementById('password');
        const strengthBars = document.querySelectorAll('.strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Verificar comprimento
            if (password.length > 7) strength += 1;
            
            // Verificar letras minúsculas
            if (/[a-z]/.test(password)) strength += 1;
            
            // Verificar letras maiúsculas
            if (/[A-Z]/.test(password)) strength += 1;
            
            // Verificar números
            if (/[0-9]/.test(password)) strength += 1;
            
            // Verificar caracteres especiais
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Atualizar barras visuais
            strengthBars.forEach((bar, index) => {
                if (index < strength) {
                    bar.classList.add('active');
                } else {
                    bar.classList.remove('active');
                }
            });
            
            // Atualizar texto
            const strengthMessages = [
                'Muito fraca', 
                'Fraca', 
                'Média', 
                'Forte', 
                'Muito forte'
            ];
            
            strengthText.textContent = strengthMessages[strength - 1] || 'Muito fraca';
            strengthText.style.color = strength > 3 ? 'var(--success)' : 
                                     strength > 1 ? 'var(--warning)' : 'var(--danger)';
        });
    </script>
</body>
</html>