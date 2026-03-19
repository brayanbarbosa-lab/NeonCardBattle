<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neon Card Battle - Tela Inicial</title>
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
            overflow: hidden;
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

        /* Container principal */
        .start-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animação de partículas flutuantes */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: var(--neon-purple);
            border-radius: 50%;
            opacity: 0.5;
            animation: floatParticle 15s infinite linear;
        }

        .particle:nth-child(2n) {
            background: var(--neon-blue);
        }

        .particle:nth-child(3n) {
            background: var(--neon-green);
        }

        /* Título principal */
        .main-title {
            text-align: center;
            margin-bottom: 3rem;
            z-index: 1;
            animation: fadeInDown 1s ease-out, glowTitle 3s infinite alternate;
        }

        .main-title h1 {
            font-size: clamp(2.5rem, 7vw, 4.5rem);
            background: linear-gradient(to right, var(--neon-green), var(--neon-blue), var(--neon-purple));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 15px rgba(0, 243, 255, 0.3);
            margin-bottom: 1rem;
            letter-spacing: 0.125rem;
            font-weight: 800;
        }

        .main-title p {
            font-size: clamp(1rem, 2.5vw, 1.5rem);
            color: var(--light);
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.8;
        }

        /* Container de opções */
        .options-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
            max-width: 1200px;
            width: 100%;
            z-index: 2;
        }

        /* Cartões de opção */
        .option-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 350px;
            min-height: 320px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
        }

        .option-card:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .option-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            z-index: 1;
        }

        .view-cards::before {
            background: linear-gradient(to right, var(--neon-purple), var(--secondary));
            box-shadow: 0 0 15px var(--neon-purple);
        }

        .start-game::before {
            background: linear-gradient(to right, var(--neon-green), var(--accent));
            box-shadow: 0 0 15px var(--neon-green);
        }

        .manual-info::before {
            background: linear-gradient(to right, var(--neon-red), var(--danger));
            box-shadow: 0 0 15px var(--neon-red);
        }

        .option-icon {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .view-cards .option-icon {
            color: var(--neon-purple);
            text-shadow: 0 0 20px rgba(157, 78, 221, 0.7);
        }

        .start-game .option-icon {
            color: var(--neon-green);
            text-shadow: 0 0 20px rgba(0, 255, 157, 0.7);
        }

        .manual-info .option-icon {
            color: var(--neon-red);
            text-shadow: 0 0 20px rgba(255, 0, 0, 0.3);
        }

        .option-card:hover .option-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .option-title {
            font-size: clamp(1.5rem, 3vw, 2rem);
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .view-cards .option-title {
            color: var(--neon-purple);
        }

        .start-game .option-title {
            color: var(--neon-green);
        }

        .manual-info .option-title {
            color: var(--neon-red);
        }

        .option-description {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .option-btn {
            padding: 0.8rem 2.5rem;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .view-cards .option-btn {
            background: linear-gradient(to right, var(--neon-purple), var(--secondary));
            color: white;
            box-shadow: 0 5px 15px rgba(157, 78, 221, 0.4);
        }

        .start-game .option-btn {
            background: linear-gradient(to right, var(--neon-green), var(--accent));
            color: white;
            box-shadow: 0 5px 15px rgba(0, 255, 157, 0.4);
        }

        .manual-info .option-btn {
            background: linear-gradient(to right, var(--neon-red), var(--danger));
            color: white;
            box-shadow: 0 5px 15px rgba(255, 0, 0, 0.1  );
        }

        .option-btn::before {
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

        .option-btn:hover::before {
            transform: translateX(0);
        }

        .option-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .option-btn:active {
            transform: translateY(1px);
        }

        /* Efeito de transição */
        .transition-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--darker);
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.6s ease;
        }


        /* Animações */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes glowTitle {
            0% {
                text-shadow: 0 0 10px rgba(0, 201, 255, 0.5);
            }
            50% {
                text-shadow: 0 0 20px rgba(0, 201, 255, 0.8), 0 0 30px rgba(106, 90, 249, 0.6);
            }
            100% {
                text-shadow: 0 0 15px rgba(157, 78, 221, 0.7);
            }
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.6;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100px) translateX(50px);
                opacity: 0;
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .options-container {
                flex-direction: column;
                align-items: center;
            }
            
            .option-card {
                max-width: 100%;
            }
            
            .main-title {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Overlay de transição -->
    <div class="transition-overlay" id="transitionOverlay"></div>
    
    <!-- Partículas de fundo -->
    <div class="particles" id="particles"></div>
    
    <div class="start-container">
        <div class="main-title pulse">
            <h1>NEON CARD BATTLE</h1>
            <p>Entre em um mundo de estratégia e batalhas épicas com cartas luminosas</p>
        </div>
        
        <div class="options-container">
            <!-- Opção 1: Ver Cartas -->
            <div class="option-card view-cards" onclick="selectOption('cards')">
                <div>
                    <i class="fas fa-layer-group option-icon"></i>
                    <h2 class="option-title">Explorar Cartas</h2>
                    <p class="option-description">
                        Veja sua coleção completa de cartas neon, descubra habilidades especiais e monte seu deck vencedor.
                    </p>
                </div>
                <button class="option-btn">
                    <i class="fas fa-search"></i> Ver Minhas Cartas
                </button>
            </div>
            
            <!-- Opção 2: Iniciar Jogo -->
            <div class="option-card start-game" onclick="selectOption('game')">
                <div>
                    <i class="fas fa-play option-icon"></i>
                    <h2 class="option-title">Iniciar Batalha</h2>
                    <p class="option-description">
                        Enfrente oponentes desafiadores em batalhas estratégicas com efeitos visuais impressionantes.
                    </p>
                </div>
                <button class="option-btn">
                    <i class="fas fa-bolt"></i> Começar a Jogar
                </button>
            </div>

            <div class="option-card manual-info" onclick="selectOption('manual')">
                <div>
                    <i class="fas fa-book option-icon"></i>
                    <h2 class="option-title">Manual</h2>
                    <p class="option-description">
                        Descubra como funciona o jogo para derrotar seus inimigos com maior facilidade.
                    </p>
                </div>
                <button class="option-btn">
                    <i class="fas fa-search"></i> Ler Manual
                </button>
            </div>
        </div>
    
    </div>

    <script>
        // Criar partículas animadas
       
        
        
        // Simular seleção de opção com redirecionamento
        function selectOption(option) {
            const transitionOverlay = document.getElementById('transitionOverlay');
            let targetPage = '';
            
            if (option === 'cards') {
                targetPage = 'cartasView.php';
                document.querySelector('.view-cards').classList.add('pulse');
            } else if (option === 'game') {
                targetPage = 'game.php';
                document.querySelector('.start-game').classList.add('pulse');
            }else{
                targetPage = 'manualusuario.php';
                document.querySelector('.manual-info').classList.add('pulse');
            }
            
            // Mostrar overlay de transição
            transitionOverlay.style.opacity = '1';
            transitionOverlay.style.pointerEvents = 'auto';
            
            // Redirecionar após a animação
            setTimeout(() => {
                window.location.href = targetPage;
            }, 800);
        }
        
        // Inicializar partículas quando a página carregar
        window.addEventListener('load', createParticles);
    </script>
</body>
</html>