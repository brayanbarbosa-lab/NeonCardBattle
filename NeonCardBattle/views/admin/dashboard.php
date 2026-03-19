<?php
session_start();
// Verificação única de login e permissão
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../game.php");
    exit();
}
require '../../includes/Database.php';

// Verificar autenticação e permissões
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Obter estatísticas
$totalCartas = $db->query("SELECT COUNT(*) FROM cartas")->fetchColumn();
$totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$admins = $db->query("SELECT COUNT(*) FROM usuarios WHERE role = 'admin'")->fetchColumn();
$jogadores = $totalUsuarios - $admins;
$ultimasCartas = $db->query("SELECT * FROM cartas ORDER BY id DESC LIMIT 5")->fetchAll();
$ultimosUsuarios = $db->query("SELECT * FROM usuarios ORDER BY id DESC LIMIT 5")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - Dashboard Futurista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
        }

        body {
            background: linear-gradient(135deg, var(--darker), var(--dark));
            min-height: 100vh;
            color: var(--light);
            padding: 20px;
            overflow-x: hidden;
        }

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
            z-index: -1;
        }

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
        }

        .admin-container {
            max-width: 1400px;
            margin: 20px auto;
            background: rgba(20, 20, 40, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 50px rgba(106, 90, 249, 0.3);
            border: 1px solid rgba(100, 100, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .admin-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(106, 90, 249, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 15px 20px;
            background: rgba(25, 25, 50, 0.5);
            border-radius: 15px;
            border: 1px solid rgba(100, 100, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-title {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 15px rgba(106, 90, 249, 0.3);
        }

        .nav-links {
            display: flex;
            gap: 10px;
        }

        .nav-link {
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--light);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(106, 90, 249, 0.2);
            box-shadow: 0 0 15px rgba(106, 90, 249, 0.3);
        }

        .nav-link.active {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
        }

        .nav-link i {
            font-size: 1.2rem;
        }

        .logout-btn {
            padding: 10px 20px;
            border-radius: 10px;
            background: rgba(255, 56, 96, 0.15);
            color: var(--danger);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 56, 96, 0.3);
        }

        .logout-btn:hover {
            background: rgba(255, 56, 96, 0.25);
            box-shadow: 0 0 15px rgba(255, 56, 96, 0.3);
        }

        .welcome-section {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
            background: rgba(30, 30, 60, 0.3);
            border-radius: 15px;
            border: 1px solid rgba(100, 100, 255, 0.2);
        }

        .welcome-title {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: white;
            text-shadow: 0 0 10px rgba(100, 150, 255, 0.5);
        }

        .welcome-subtitle {
            font-size: 1.2rem;
            color: var(--accent);
            max-width: 800px;
            margin: 0 auto;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: rgba(30, 30, 60, 0.5);
            border-radius: 15px;
            padding: 25px;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(100, 100, 255, 0.1);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            border-color: rgba(100, 100, 255, 0.4);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .stat-icon {
            font-size: 2.5rem;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(106, 90, 249, 0.1);
            color: var(--primary);
        }

        .stat-card h3 {
            color: var(--accent);
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 2.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(106, 90, 249, 0.3);
            margin-bottom: 10px;
        }

        .stat-label {
            color: var(--light);
            opacity: 0.8;
            font-size: 1rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .dashboard-card {
            background: rgba(25, 25, 50, 0.5);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(100, 100, 255, 0.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(100, 100, 255, 0.2);
        }

        .card-header h2 {
            font-size: 1.8rem;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h2 i {
            color: var(--accent);
        }

        .view-all {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .view-all:hover {
            color: var(--accent);
            text-shadow: 0 0 10px rgba(0, 201, 255, 0.5);
        }

        .list-item {
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 15px;
            background: rgba(40, 40, 70, 0.3);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .list-item:hover {
            background: rgba(106, 90, 249, 0.2);
            transform: translateX(5px);
        }

        .list-item-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(106, 90, 249, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
            color: var(--primary);
        }

        .list-item-content {
            flex: 1;
        }

        .list-item-title {
            font-size: 1.2rem;
            color: white;
            margin-bottom: 5px;
        }

        .list-item-subtitle {
            color: var(--light);
            opacity: 0.8;
            font-size: 0.9rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .stat-badge {
            background: rgba(30, 30, 60, 0.5);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border: 1px solid rgba(100, 100, 255, 0.1);
        }

        .stat-badge-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .stat-badge-label {
            color: var(--light);
            opacity: 0.8;
            font-size: 1rem;
        }

        footer {
            text-align: center;
            padding: 20px;
            color: var(--light);
            opacity: 0.7;
            font-size: 0.9rem;
            border-top: 1px solid rgba(100, 100, 255, 0.1);
            margin-top: 30px;
        }

        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 5s ease-in-out infinite;
        }

        /* Responsive design */
        @media (max-width: 1100px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 15px;
            }
            
            .admin-nav {
                flex-direction: column;
                gap: 15px;
            }
            
            .nav-left {
                flex-direction: column;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .welcome-title {
                font-size: 2rem;
            }
            
            .stat-card {
                padding: 20px;
            }
            
            .stat-value {
                font-size: 2.2rem;
            }
        }

        @media (max-width: 480px) {
            .nav-link {
                padding: 8px 15px;
                font-size: 0.9rem;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-value {
                font-size: 1.8rem;
            }
            
            .dashboard-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="grid-pattern"></div>
    
    <div class="admin-container">
        <div class="admin-nav">
            <div class="nav-left">
                <div class="nav-title">
                    <i class="fas fa-crown"></i> Painel Admin
                </div>
                <div class="nav-links">
                    <a href="dashboard.php" class="nav-link active">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="gerenciar_cartas.php" class="nav-link">
                        <i class="fas fa-id-card"></i> Cartas
                    </a>
                    <a href="gerenciar_usuarios.php" class="nav-link">
                        <i class="fas fa-users"></i> Usuários
                    </a>
                    <a href="manualadmin.php" class="nav-link">
                        <i class="fas fa-book"></i> Manual
                    </a>
                </div>
            </div>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
        
        <div class="welcome-section">
            <h1 class="welcome-title">Bem-vindo, Administrador!</h1>
            <p class="welcome-subtitle">
                Gerencie seu sistema de cartas e usuários com ferramentas poderosas e insights em tempo real
            </p>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3>Total de Cartas</h3>
                    <div class="stat-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $totalCartas ?></div>
                <div class="stat-label">Cartas no sistema</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3>Total de Usuários</h3>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $totalUsuarios ?></div>
                <div class="stat-label">Usuários cadastrados</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3>Administradores</h3>
                    <div class="stat-icon">
                        <i class="fas fa-crown"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $admins ?></div>
                <div class="stat-label">Com acesso total</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-card-header">
                    <h3>Jogadores</h3>
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $jogadores ?></div>
                <div class="stat-label">Jogadores ativos</div>
            </div>
        </div>
        
        <div class="dashboard-grid">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-id-card"></i> Últimas Cartas Adicionadas</h2>
                    <a href="gerenciar_cartas.php" class="view-all">
                        Ver todas <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <?php foreach ($ultimasCartas as $carta): ?>
                <div class="list-item">
                    <div class="list-item-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="list-item-content">
                        <div class="list-item-title"><?= htmlspecialchars($carta['nome']) ?></div>
                        <div class="list-item-subtitle">
                            Ataque: <?= $carta['ataque'] ?> | Defesa: <?= $carta['defesa'] ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($ultimasCartas)): ?>
                    <div class="list-item">
                        <div class="list-item-content">
                            <div class="list-item-title">Nenhuma carta encontrada</div>
                            <div class="list-item-subtitle">Adicione novas cartas no sistema</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-users"></i> Últimos Usuários Registrados</h2>
                    <a href="gerenciar_usuarios.php" class="view-all">
                        Ver todos <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <?php foreach ($ultimosUsuarios as $usuario): ?>
                <div class="list-item">
                    <div class="list-item-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="list-item-content">
                        <div class="list-item-title"><?= htmlspecialchars($usuario['username']) ?></div>
                        <div class="list-item-subtitle">
                            <?= $usuario['email'] ?> | 
                            <span style="color: <?= $usuario['role'] === 'admin' ? '#6a5af9' : '#00c9ff' ?>">
                                <?= $usuario['role'] === 'admin' ? 'Administrador' : 'Jogador' ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <?php if (empty($ultimosUsuarios)): ?>
                    <div class="list-item">
                        <div class="list-item-content">
                            <div class="list-item-title">Nenhum usuário encontrado</div>
                            <div class="list-item-subtitle">Nenhum usuário registrado recentemente</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-chart-bar"></i> Visão Geral do Sistema</h2>
            </div>
            
            <div class="stats-grid">
                <div class="stat-badge">
                    <div class="stat-badge-value"><?= $totalCartas ?></div>
                    <div class="stat-badge-label">Cartas Cadastradas</div>
                </div>
                
                <div class="stat-badge">
                    <div class="stat-badge-value"><?= $totalUsuarios ?></div>
                    <div class="stat-badge-label">Usuários Registrados</div>
                </div>
                
                <div class="stat-badge">
                    <div class="stat-badge-value"><?= $admins ?></div>
                    <div class="stat-badge-label">Administradores</div>
                </div>
                
                <div class="stat-badge">
                    <div class="stat-badge-value"><?= $jogadores ?></div>
                    <div class="stat-badge-label">Jogadores</div>
                </div>
            </div>
        </div>
        
        <footer>
            Painel Admin &copy; <?= date('Y') ?> | Sistema de Gerenciamento de Cartas
        </footer>
    </div>

    <script>
        // Efeito de hover nos cards de estatísticas
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.3)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = 'none';
            });
        });
        
        // Rotação de ícones ao passar o mouse
        const listItems = document.querySelectorAll('.list-item');
        listItems.forEach(item => {
            const icon = item.querySelector('.list-item-icon i');
            
            item.addEventListener('mouseenter', () => {
                icon.style.transform = 'rotate(15deg)';
            });
            
            item.addEventListener('mouseleave', () => {
                icon.style.transform = 'rotate(0)';
            });
        });
        
        // Atualização dinâmica de dados (simulação)
        setInterval(() => {
            const statValues = document.querySelectorAll('.stat-value');
            statValues.forEach(value => {
                const original = parseInt(value.textContent);
                const variation = Math.floor(Math.random() * 3);
                const newValue = original + variation;
                
                // Efeito de contagem
                let current = original;
                const interval = setInterval(() => {
                    current++;
                    value.textContent = current;
                    if (current >= newValue) clearInterval(interval);
                }, 50);
            });
        }, 10000);
    </script>
</body>
</html>