<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalha de Cartas - Futurista</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


         header{
            margin-left: auto;
            margin-right: auto;
            width: 90vw;
         }

        .game-header {
            position: relative;
            width: 95%;
            
            background: linear-gradient(135deg, rgba(10, 25, 50, 0.95) 0%, rgba(15, 35, 75, 0.95) 50%, rgba(20, 45, 100, 0.95) 100%);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(0, 150, 255, 0.6);
            padding: 1.5rem 3rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 
                0 0 50px rgba(0, 150, 255, 0.4),
                0 5px 30px rgba(0, 0, 0, 0.7),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            z-index: 1000;
            border-radius: 20px;
            position: relative;
            overflow: hidden;
            margin-top: 20px;
        }

        .game-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 150, 255, 0.2), transparent);
            animation: scanline 3s linear infinite;
        }

        .game-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 20% 50%, rgba(0, 150, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(0, 200, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        @keyframes scanline {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            z-index: 2;
        }

        .header-center {
            text-align: center;
            z-index: 2;
        }

        .game-title {
            font-family: 'Orbitron', monospace;
            font-size: 2.2rem;
            font-weight: 900;
            color: #fff;
            text-shadow: 
                0 0 10px rgba(0, 150, 255, 0.8),
                0 0 20px rgba(0, 150, 255, 0.6),
                0 0 30px rgba(0, 150, 255, 0.4);
            letter-spacing: 0.3rem;
            position: relative;
            animation: titleGlow 2s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            from {
                text-shadow: 
                    0 0 10px rgba(0, 150, 255, 0.8),
                    0 0 20px rgba(0, 150, 255, 0.6),
                    0 0 30px rgba(0, 150, 255, 0.4);
            }
            to {
                text-shadow: 
                    0 0 15px rgba(0, 200, 255, 1),
                    0 0 25px rgba(0, 200, 255, 0.8),
                    0 0 35px rgba(0, 200, 255, 0.6);
            }
        }

        .player-info {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: rgba(0, 0, 0, 0.3);
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            border: 1px solid rgba(0, 150, 255, 0.5);
            backdrop-filter: blur(10px);
            box-shadow: 
                0 0 15px rgba(0, 150, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .player-info:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 0 20px rgba(0, 200, 255, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .player-name {
            color: #00c8ff;
            font-weight: 600;
            font-size: 1.1rem;
            text-shadow: 0 0 5px rgba(0, 200, 255, 0.5);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .player-score {
            background: linear-gradient(135deg, #0066cc 0%, #0099ff  50%, #00ccff 100%);
            padding: 0.6rem 1.2rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 1.1rem;
            box-shadow: 
                0 0 15px rgba(0, 150, 255, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2),
                inset 0 -1px 0 rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: scoreGlow 3s ease-in-out infinite;
            min-width: 80px;
            text-align: center;
        }

        @keyframes scoreGlow {
            0%, 100% { 
                box-shadow: 
                    0 0 15px rgba(0, 150, 255, 0.4), 
                    inset 0 1px 0 rgba(255, 255, 255, 0.2); 
            }
            50% { 
                box-shadow: 
                    0 0 25px rgba(0, 200, 255, 0.6), 
                    inset 0 1px 0 rgba(255, 255, 255, 0.3); 
            }
        }

        .header-btn {
            padding: 1rem 2rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-family: 'Exo 2', sans-serif;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.1rem;
            transform-style: preserve-3d;
        }

        .header-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .header-btn:hover::before {
            left: 100%;
        }

        .header-btn::after {
            content: '';
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 30px;
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .restart-btn {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8533 50%, #ffaa66 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 0 15px rgba(255, 107, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .restart-btn::after {
            background: linear-gradient(135deg, #ff6b00 0%, #ff8533 50%, #ffaa66 100%);
            filter: blur(10px);
        }

        .restart-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 
                0 0 25px rgba(255, 107, 0, 0.5),
                0 5px 15px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .restart-btn:hover::after {
            opacity: 0.6;
        }

        .sair-btn {
            background: linear-gradient(135deg, #cc0066 0%, #ff0099 50%, #ff33bb 100%);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 
                0 0 15px rgba(204, 0, 102, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .sair-btn::after {
            background: linear-gradient(135deg, #cc0066 0%, #ff0099 50%, #ff33bb 100%);
            filter: blur(10px);
        }

        .sair-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 
                0 0 25px rgba(204, 0, 102, 0.5),
                0 5px 15px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .sair-btn:hover::after {
            opacity: 0.6;
        }

        .header-btn:active {
            transform: translateY(0) scale(0.98);
        }

        /* Efeitos de partículas */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: -1;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(0, 150, 255, 0.6);
            border-radius: 50%;
            animation: float 6s infinite linear;
        }

        .particle:nth-child(odd) {
            background: rgba(0, 200, 255, 0.4);
            animation-duration: 8s;
        }

        .particle:nth-child(3n) {
            background: rgba(255, 107, 0, 0.5);
            animation-duration: 7s;
        }

        .particle:nth-child(4n) {
            background: rgba(204, 0, 102, 0.4);
            animation-duration: 9s;
        }

        @keyframes float {
            0% {
                transform: translateY(100px) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) translateX(100px);
                opacity: 0;
            }
        }

        /* Efeito de conexão entre elementos */
        .connection-line {
            position: absolute;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(0, 200, 255, 0.6), transparent);
            z-index: 1;
            animation: linePulse 3s infinite;
        }

        @keyframes linePulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 1; }
        }
     

        /* Conteúdo de demonstração */
        .game-content {
            text-align: center;
            padding: 40px;
            margin-top: 30px;
            background: rgba(15, 35, 75, 0.4);
            border-radius: 20px;
            border: 1px solid rgba(0, 150, 255, 0.4);
            max-width: 800px;
            width: 90%;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .game-content h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #00c8ff;
            text-shadow: 0 0 10px rgba(0, 200, 255, 0.5);
        }

        .game-content p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .game-header {
                padding: 1.2rem 2rem;
            }
            
            .game-title {
                font-size: 1.8rem;
            }
            
            .header-btn {
                padding: 0.9rem 1.5rem;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 768px) {
            .game-header {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
            }
            
            .header-left, .header-right {
                gap: 1rem;
            }
            
            .game-title {
                font-size: 1.6rem;
                letter-spacing: 0.2rem;
            }
            
            .player-info {
                padding: 0.6rem 1.2rem;
            }
            
            .mobile-menu-btn {
                display: flex;
            }
        }

        @media (max-width: 480px) {
            .game-header {
                padding: 1rem;
            }
            
            .game-title {
                font-size: 1.4rem;
            }
            
            .header-btn {
                padding: 0.8rem 1.2rem;
                font-size: 0.8rem;
            }
            
            .player-name, .player-score {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Partículas flutuantes -->
    <div class="particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 2.5s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 1.5s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 3.5s;"></div>
    </div>

    <!-- Botão de menu móvel -->
    <div class="mobile-menu-btn">
        <i class="fas fa-bars"></i>
    </div>

    <!-- Header do Jogo -->
    <header class="game-header">
        <!-- Linhas de conexão -->
        <div class="connection-line" style="top: 50%; left: 25%; width: 50%;"></div>
        <div class="connection-line" style="top: 30%; left: 40%; width: 20%; transform: rotate(45deg);"></div>
        <div class="connection-line" style="top: 70%; left: 40%; width: 20%; transform: rotate(-45deg);"></div>
        
        <div class="header-left">
            <form method="POST">
                <button type="submit" name="reiniciar" class="header-btn restart-btn">
                    <i class="fas fa-sync-alt"></i> Reiniciar
                </button>
            </form>
        </div>

        <div class="header-center">
            <h1 class="game-title"><i class="fas fa-bolt"></i>  Neon Card Battle</h1>
        </div>

        <div class="header-right">
            <div class="player-info">
                <span class="player-name"><i class="fas fa-user-astronaut"></i> <?= htmlspecialchars($_SESSION['username'] ?? 'Jogador') ?></span>
        
            </div>
            
            <form method="POST">
                <button type="submit" name="sair" class="header-btn sair-btn">
                    <i class="fas fa-door-open"></i> Sair
                </button>
            </form>
        </div>
    </header>

    