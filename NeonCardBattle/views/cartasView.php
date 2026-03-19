<?php
require_once '../includes/Cartas.php';
require_once '../includes/Database.php';

// Configurar conexão com o banco de dados
try {
    $database = new Database();
    $db = $database->getConnection();

    $cartasManager = new Cartas($db);
    $cartas = $cartasManager->getAllCartas();
} catch (Exception $e) {
    // Tratar erro
    die("Erro ao carregar cartas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Card Battle - Galeria de Cartas</title>
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
            --accent: #00c9ff;
            --dark: #0f0c29;
            --darker: #080616;
            --light: #e0e0ff;
            --success: #3ddc84;
            --danger: #ff3860;
            --neon-green: #00ff9d;
            --neon-red: #ff2975;
            --neon-blue: #00f3ff;
            --neon-purple: #9d4edd;
            --glass-bg: rgba(25, 25, 50, 0.6);
            --glass-border: rgba(255, 255, 255, 0.1);
            --glow-primary: 0 0 15px rgba(106, 90, 249, 0.5);
            --glow-accent: 0 0 15px rgba(0, 201, 255, 0.5);
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--darker), var(--dark));
            color: var(--light);
            overflow-x: hidden;
            position: relative;
        }

        /* Background grid pattern */
        body::before {
            content: '';
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
            z-index: -2;
        }

        /* Subtle gradient effects */
        body::after {
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

        /* Header */
        .header {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo h1 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            background: linear-gradient(to right, var(--neon-green), var(--neon-blue));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 10px rgba(0, 243, 255, 0.3);
            letter-spacing: 0.05rem;
            font-weight: 700;
        }

        .logo i {
            color: var(--neon-purple);
            font-size: 1.8rem;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
            text-decoration: none;
        }

        .nav-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            z-index: -1;
            transform: translateX(-100%);
            transition: transform 0.4s;
        }

        .nav-btn:hover::before {
            transform: translateX(0);
        }

        .home-btn {
            background: linear-gradient(to right, var(--neon-purple), var(--secondary));
            color: white;
            box-shadow: 0 5px 15px rgba(157, 78, 221, 0.4);
        }

        .game-btn {
            background: linear-gradient(to right, var(--neon-green), var(--accent));
            color: white;
            box-shadow: 0 5px 15px rgba(0, 255, 157, 0.4);
        }

        .nav-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Container principal */
        .cards-container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 2rem;
        }

        .page-title {
            text-align: center;
            margin-bottom: 3rem;
            z-index: 1;
        }

        .page-title h2 {
            font-size: clamp(2rem, 5vw, 3rem);
            background: linear-gradient(to right, var(--neon-purple), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1rem;
            letter-spacing: 0.05rem;
            font-weight: 800;
        }

        .page-title p {
            font-size: clamp(1rem, 2.5vw, 1.2rem);
            color: rgba(255, 255, 255, 0.7);
            max-width: 700px;
            margin: 0 auto;
        }

        /* Filtros e busca */
        .filters {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 3rem;
        }

        .search-box {
            position: relative;
            max-width: 400px;
            width: 100%;
        }

        .search-box input {
            width: 100%;
            padding: 0.9rem 1.2rem 0.9rem 3rem;
            border-radius: 50px;
            border: none;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            color: var(--light);
            font-size: 1rem;
            border: 1px solid var(--glass-border);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .search-box i {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neon-purple);
        }

        .sort-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .sort-filter select {
            padding: 0.8rem 1.2rem;
            border-radius: 50px;
            border: none;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            color: var(--light);
            font-size: 1rem;
            border: 1px solid var(--glass-border);
            box-shadow: 0 0.5vh 1.5vh rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .card-item {
            background: var(--glass-bg);
            backdrop-filter: blur(1vh);
            border-radius: 3vh;
            position: relative;
            box-shadow: 0 1vh 3vh rgba(0, 0, 0, 0.3);
            border: 0.1vh solid var(--glass-border);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex;
            align-items: center;
            justify-content: center;

        }

        .card-item:hover {
            transform: translateY(-1vh);
            box-shadow: 0 1vh 4vh rgba(0, 0, 0, 0.4), 0 0 3vh rgba(157, 78, 221, 0.4);
        }

        .card-image {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            border-radius: 1vh;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .card-item:hover .card-image img {
            transform: scale(1.05);
        }

        /* Mensagem sem cartas */
        .no-cards-message {
            grid-column: 1 / -1;
            text-align: center;
            padding: 3rem;
            background: var(--glass-bg);
            border-radius: 20px;
            border: 1px solid var(--glass-border);
        }

        .no-cards-message i {
            font-size: 4rem;
            color: var(--neon-purple);
            margin-bottom: 1.5rem;
        }

        .no-cards-message h3 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--neon-blue);
        }
        
        /* Placeholder para sem imagem */
        .no-image {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.5);
        }

        .no-image i {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        /* Responsividade */
        @media (max-width: 62em) {
            .cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .card-item {
                height: 35vh;
                min-height: 250px;
            }
        }

        @media (max-width: 48em) {
            .header {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .filters {
                flex-direction: column;
                align-items: center;
            }

            .sort-filter {
                width: 100%;
                justify-content: center;
            }

            .cards-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }

            .card-item {
                height: 30vh;
                min-height: 200px;
            }
        }

        @media (max-width: 30em) {
            .cards-grid {
                grid-template-columns: 1fr;
            }

            .nav-buttons {
                width: 100%;
                justify-content: center;
            }

            .nav-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }

            .card-item {
                height: 40vh;
                min-height: 250px;
            }
        }

        /* Animações */
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

        .card-item {
            animation: fadeIn 0.6s ease-out;
            animation-fill-mode: both;
        }

        .card-item:nth-child(1) {
            animation-delay: 0.1s;
        }

        .card-item:nth-child(2) {
            animation-delay: 0.2s;
        }

        .card-item:nth-child(3) {
            animation-delay: 0.3s;
        }

        .card-item:nth-child(4) {
            animation-delay: 0.4s;
        }

        .card-item:nth-child(5) {
            animation-delay: 0.5s;
        }

        .card-item:nth-child(6) {
            animation-delay: 0.6s;
        }

        .card-item:nth-child(7) {
            animation-delay: 0.7s;
        }

        .card-item:nth-child(8) {
            animation-delay: 0.8s;
        }

        .card-item:nth-child(9) {
            animation-delay: 0.9s;
        }

        .card-item:nth-child(10) {
            animation-delay: 1.0s;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="logo">
            <i class="fas fa-cards"></i>
            <h1>NEON CARD BATTLE</h1>
        </div>
        <div class="nav-buttons">
            <a href="index.php" class="nav-btn home-btn">
                <i class="fas fa-home"></i> Início
            </a>
            <a href="game.php" class="nav-btn game-btn">
                <i class="fas fa-gamepad"></i> Jogar
            </a>
        </div>
    </header>

    <main class="cards-container">
        <div class="page-title">
            <h2>GALERIA DE CARTAS</h2>
            <p>Visualize todas as imagens das cartas do jogo</p>
        </div>

        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Buscar cartas...">
            </div>

            <div class="sort-filter">
                <span>Ordenar por:</span>
                <select id="sortSelect">
                    <option value="name">Nome (A-Z)</option>
                    <option value="attack">Ataque (Maior)</option>
                    <option value="defense">Defesa (Maior)</option>
                </select>
            </div>
        </div>

        <div class="cards-grid" id="cardsGrid">
            <?php if (empty($cartas)): ?>
                <div class="no-cards-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Nenhuma carta encontrada</h3>
                    <p>Adicione cartas ao seu banco de dados para vê-las aqui</p>
                </div>
            <?php else: ?>
                <?php foreach ($cartas as $carta): ?>
                    <div class="card-item"
                        data-name="<?= htmlspecialchars($carta['nome']) ?>"
                        data-attack="<?= $carta['ataque'] ?>"
                        data-defense="<?= $carta['defesa'] ?>">
                        <div class="card-image">
                            <?php if (!empty($carta['imagem']) && !empty($carta['tipo_imagem'])): ?>
                                <img src="data:<?= $carta['tipo_imagem'] ?>;base64,<?= base64_encode($carta['imagem']) ?>"
                                    alt="<?= htmlspecialchars($carta['nome']) ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                    <p>Sem imagem</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </main>



    <script>
        // Filtro de busca
        const searchInput = document.getElementById('searchInput');
        const cardsGrid = document.getElementById('cardsGrid');
        const cards = Array.from(cardsGrid.querySelectorAll('.card-item'));

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();

            cards.forEach(card => {
                const cardName = card.dataset.name.toLowerCase();
                if (cardName.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Ordenação
        const sortSelect = document.getElementById('sortSelect');
        sortSelect.addEventListener('change', function() {
            const sortBy = this.value;
            const cardItems = Array.from(cardsGrid.querySelectorAll('.card-item'));

            cardItems.sort((a, b) => {
                if (sortBy === 'name') {
                    const nameA = a.dataset.name.toLowerCase();
                    const nameB = b.dataset.name.toLowerCase();
                    return nameA.localeCompare(nameB);
                } else if (sortBy === 'attack') {
                    const attackA = parseInt(a.dataset.attack);
                    const attackB = parseInt(b.dataset.attack);
                    return attackB - attackA; // Maior primeiro
                } else if (sortBy === 'defense') {
                    const defenseA = parseInt(a.dataset.defense);
                    const defenseB = parseInt(b.dataset.defense);
                    return defenseB - defenseA; // Maior primeiro
                }
                return 0;
            });

            // Limpar e reordenar o grid
            cardsGrid.innerHTML = '';
            cardItems.forEach(card => cardsGrid.appendChild(card));
        });
    </script>
</body>

</html>