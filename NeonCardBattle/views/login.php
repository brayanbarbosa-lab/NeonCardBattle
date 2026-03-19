<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirecionar usuários já logados
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: game.php");
    }
    exit();
}

require_once '../includes/Database.php';

$database = new Database();
$db = $database->getConnection();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM usuarios WHERE username = ? OR email = ?");
    $stmt->execute([$identifier, $identifier]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['username'] = $user['username'];

        $db->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?")->execute([$user['id']]);

        // Redirecionamento imediato
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: game.php");
        }
        exit();
    } else {
        $error = "Credenciais inválidas!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Único | Neon Card Battle</title>
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
            --enemy-color: #ff0080;
            --player-color: #00ffff;
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
            0% {
                transform: translateY(0px) translateX(0px);
            }

            50% {
                transform: translateY(-10px) translateX(5px);
            }

            100% {
                transform: translateY(0px) translateX(0px);
            }
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
            0% {
                transform: translateY(0px) translateX(0px);
            }

            50% {
                transform: translateY(-10px) translateX(5px);
            }

            100% {
                transform: translateY(0px) translateX(0px);
            }
        }

        /* Container de login */
        .login-container {
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

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(106, 90, 249, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .login-title {
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

        .login-subtitle {
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
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow:
                0 8px 20px rgba(106, 90, 249, 0.6),
                0 0 15px rgba(0, 201, 255, 0.4);
        }

        .error-message {
            color: var(--danger);
            text-align: center;
            margin-bottom: 20px;
            padding: 12px;
            background: rgba(255, 56, 96, 0.15);
            border-radius: 8px;
            border: 1px solid rgba(255, 56, 96, 0.3);
        }

        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            
        }

        .links a {
            color: var(--accent);
            text-decoration: none;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
       margin-left: auto;
       margin-right: auto;
        }

        .links a:hover {
            color: var(--primary);
            text-shadow: 0 0 10px rgba(106, 90, 249, 0.5);
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(100, 100, 255, 0.2);
            color: var(--light);
            opacity: 0.7;
            font-size: 0.9rem;
        }

        .home-btn {
            background: linear-gradient(to right, var(--neon-purple), var(--secondary));
            color: white;
            
        }

        .nav-btn {
            padding: 0.7rem 1.5rem;
            border-radius: 5vh;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin-bottom: 90vh;
            margin-left: 90vw;
            text-decoration: none;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            position: absolute;
            box-shadow: 0 5px 15px rgba(106, 90, 249, 0.4);

        }

        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }


        .nav-btn:hover::before {
            transform: translateX(0);
        }

        /* Animação de entrada */
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

        .login-container {
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .login-container {
                padding: 30px 25px;
                max-width: 90%;
            }

            .login-title {
                font-size: 1.8rem;
            }
            
            .links {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 25px 20px;
            }

            .login-title {
                font-size: 1.6rem;
            }

            input,
            button {
                padding: 12px 12px 12px 40px;
            }
        }
    </style>
</head>

<body>
    <!-- Efeitos de fundo -->
    <div class="grid-pattern"></div>
    <div class="particle-effect"></div>

    <!-- Container de login -->
    <div class="login-container">
        <div class="login-header">
            <h1 class="login-title"> Neon Card Battle</h1>
            <p class="login-subtitle">Entre no universo estratégico de cartas</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="identifier"
                    placeholder="Email ou Nome de Usuário" required>
            </div>

            <div class="form-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password"
                    placeholder="Senha" required>
            </div>

            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Entrar no Sistema
            </button>
        </form>

        <div class="links">
            <a href="register.php">
                <i class="fas fa-user-plus"></i> Criar Nova Conta
            </a>
        </div>
        

    </div>
    <a href="index.php" class="nav-btn home-btn">
            <i class="fas fa-home"></i> Início
        </a>    
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
    </script>
</body>

</html>