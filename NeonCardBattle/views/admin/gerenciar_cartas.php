<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../game.php");
    exit();
}
require_once '../../includes/database.php';
require_once '../../includes/Cartas.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();
$cartas = new Cartas($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $cartas->deletarCarta($_POST['id']);
    } elseif (isset($_POST['edit'])) {
    }
}

$todasCartas = $cartas->getAllCartas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Cartas - Painel Admin</title>
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
            max-width: 1200px;
            margin: 40px auto;
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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(100, 100, 255, 0.2);
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: white;
            text-shadow: 0 0 10px rgba(100, 150, 255, 0.5);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h1 i {
            color: var(--accent);
            text-shadow: 0 0 15px rgba(0, 201, 255, 0.7);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 25px;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 0 20px rgba(106, 90, 249, 0.5);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 30px rgba(106, 90, 249, 0.8);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn i {
            font-size: 1.2rem;
        }

        .data-table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            position: relative;
            background: rgba(25, 25, 50, 0.5);
            border: 1px solid rgba(100, 100, 255, 0.1);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .data-table th {
            background: linear-gradient(45deg, rgba(106, 90, 249, 0.3), rgba(214, 110, 253, 0.3));
            color: white;
            font-weight: 600;
            text-align: left;
            padding: 18px 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
        }

        .data-table td {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(100, 100, 255, 0.1);
            color: var(--light);
            font-size: 1.1rem;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr {
            transition: background 0.3s ease;
        }

        .data-table tr:hover {
            background: rgba(106, 90, 249, 0.1);
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 10px 18px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .edit-btn {
            background: rgba(0, 201, 255, 0.15);
            color: var(--accent);
            border: 1px solid rgba(0, 201, 255, 0.3);
        }

        .delete-btn {
            background: rgba(255, 56, 96, 0.15);
            color: var(--danger);
            border: 1px solid rgba(255, 56, 96, 0.3);
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .edit-btn:hover {
            background: rgba(0, 201, 255, 0.25);
            box-shadow: 0 0 15px rgba(0, 201, 255, 0.3);
        }

        .delete-btn:hover {
            background: rgba(255, 56, 96, 0.25);
            box-shadow: 0 0 15px rgba(255, 56, 96, 0.3);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
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

        .stat-card h3 {
            color: var(--accent);
            margin-bottom: 15px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 10px rgba(106, 90, 249, 0.3);
        }

        .stat-label {
            color: var(--light);
            opacity: 0.8;
            font-size: 1rem;
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
        @media (max-width: 768px) {
            .admin-container {
                padding: 20px;
            }
            
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .data-table th, .data-table td {
                padding: 14px 15px;
            }
            
            .actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .stat-card {
                padding: 20px;
            }
            
            .stat-value {
                font-size: 2rem;
            }
            
            .btn {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }
                .secondary-btn {
            background: rgba(30, 30, 60, 0.5);
            border: 1px solid rgba(100, 100, 255, 0.3);
        }

    </style>
</head>
<body>
    <div class="grid-pattern"></div>
    
    <div class="admin-container">
        <div class="header">
            <h1>
                <i class="fas fa-cards"></i>
                Gerenciar Cartas
            </h1>
            <a href="dashboard.php" class="btn secondary-btn" style="margin-left: 14vw;">
                <i class="fas fa-arrow-left"></i> Painel Admin
            </a>
            <a href="adicionar_cartas.php" class="btn">
                <i class="fas fa-plus-circle"></i> Nova Carta
            </a>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3><i class="fas fa-database"></i> Total de Cartas</h3>
                <div class="stat-value"><?= count($todasCartas) ?></div>
                <div class="stat-label">No sistema</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-fire"></i> Maior Ataque</h3>
                <div class="stat-value">
                    <?php 
                    $maxAtaque = 0;
                    foreach ($todasCartas as $carta) {
                        if ($carta['ataque'] > $maxAtaque) $maxAtaque = $carta['ataque'];
                    }
                    echo $maxAtaque;
                    ?>
                </div>
                <div class="stat-label">Poder máximo</div>
            </div>
            
            <div class="stat-card">
                <h3><i class="fas fa-shield-alt"></i> Maior Defesa</h3>
                <div class="stat-value">
                    <?php 
                    $maxDefesa = 0;
                    foreach ($todasCartas as $carta) {
                        if ($carta['defesa'] > $maxDefesa) $maxDefesa = $carta['defesa'];
                    }
                    echo $maxDefesa;
                    ?>
                </div>
                <div class="stat-label">Resistência máxima</div>
            </div>
        </div>
        
        <div class="data-table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Ataque</th>
                        <th>Defesa</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($todasCartas as $carta): ?>
                    <tr>
                        <td><?= $carta['id'] ?></td>
                        <td><?= $carta['nome'] ?></td>
                        <td><span class="stat-value"><?= $carta['ataque'] ?></span></td>
                        <td><span class="stat-value"><?= $carta['defesa'] ?></span></td>
                        <td class="actions">
                            <a href="editar_carta.php?id=<?= $carta['id'] ?>" class="action-btn edit-btn">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form method="POST" style="display:inline">
                                <input type="hidden" name="id" value="<?= $carta['id'] ?>">
                                <button type="submit" name="delete" class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Efeito de hover nas linhas da tabela
        const tableRows = document.querySelectorAll('.data-table tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', () => {
                row.style.transform = 'translateX(5px)';
            });
            
            row.addEventListener('mouseleave', () => {
                row.style.transform = 'translateX(0)';
            });
        });
        
        // Confirmação para exclusão
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (!confirm('Tem certeza que deseja excluir esta carta?\nEsta ação não pode ser desfeita.')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>