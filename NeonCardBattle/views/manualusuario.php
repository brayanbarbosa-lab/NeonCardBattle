<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Neon Card Battle - Manual do Usuário</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(135deg, #0a0a1a, #1b0030, #0f0c29);
      color: #e0e0ff;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 2rem;
      line-height: 1.6;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      text-align: center;
      font-size: 3rem;
      background: linear-gradient(to right, #00ff9d, #00f3ff, #9d4edd);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      text-shadow: 0 0 25px rgba(0,243,255,0.7);
      margin-bottom: 2rem;
      letter-spacing: 2px;
    }

    h2 {
      color: #ff2975;
      margin-top: 2rem;
      border-bottom: 2px solid #6a5af9;
      padding-bottom: 0.3rem;
    }

    .container {
      width: 80%;
      margin-bottom: 2rem;
    }

    .box {
      background: rgba(25, 25, 50, 0.6);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-left: 5px solid #6a5af9;
      border-radius: 15px;
      padding: 1.5rem;
      margin: 1.5rem 0;
      box-shadow: 0 0 20px rgba(106, 90, 249, 0.4);
      transition: transform 0.3s ease;
    }

    .box:hover {
      transform: scale(1.02);
      box-shadow: 0 0 25px rgba(214, 110, 253, 0.6);
    }

    ul {
      margin-left: 20px;
    }

    .lore {
      border-left: 5px solid #00f3ff;
      box-shadow: 0 0 25px rgba(0,243,255,0.3);
    }

    a.voltar {
      display: inline-block;
      margin-top: 2rem;
      padding: 1rem 2rem;
      border-radius: 50px;
      text-decoration: none;
      background: linear-gradient(to right, #ff2975, #ff3860);
      color: white;
      font-weight: bold;
      transition: 0.3s;
      box-shadow: 0 0 20px rgba(255, 41, 117, 0.6);
    }

    a.voltar:hover {
      opacity: 0.9;
      transform: scale(1.08);
    }

    p {
      margin-bottom: 0.8rem;
    }

    @media (max-width: 768px) {
      .container { width: 95%; }
      h1 { font-size: 2.5rem; }
    }
  </style>
</head>
<body>
  <h1><i class="fas fa-book"></i> Manual do Jogo</h1>

  <!-- Primeira box: apenas a lore -->
  <div class="container">
    <div class="box lore">
      <h2>🌌 Lore do Jogo</h2>
      <p>Em um futuro distante, o mundo como conhecemos deixou de existir. Os humanos sucumbiram ao tempo, e os animais herdaram a Terra. Porém, eles já não são mais apenas criaturas selvagens: a fusão entre biologia e tecnologia os transformou em <strong>cyber-animais</strong>, seres que unem instinto, circuitos e inteligência artificial.</p>
      <p>Cada cyber-animal carrega implantes, armaduras robóticas e energia pulsante em seus corpos, tornando-se guerreiros implacáveis. Eles lutam em arenas digitais para decidir quem governará o planeta. Cabe a você comandar essas criaturas híbridas e provar quem será o verdadeiro <strong>mestre supremo</strong> do novo mundo.</p>
    </div>
  </div>

  <!-- Segunda box: todo o resto do manual em uma única box -->
  <div class="container">
    <div class="box">
      <h2>1. Como Jogar</h2>
      <ul>
        <li>Clique em <strong>Jogar</strong> na tela inicial.</li>
        <li>Cadastre-se informando <strong>nome, e-mail e senha</strong>.</li>
        <li>Entre com seu e-mail e senha cadastrados.</li>
        <li>Assim que logar, o jogo começa automaticamente.</li>
      </ul>

      <h2>2. Cartas</h2>
      <p>Existem <strong>57 cartas</strong>, cada uma com:</p>
      <ul>
        <li><strong>Força:</strong> varia de 5 a 37.</li>
        <li><strong>Defesa:</strong> varia de 5 a 35.</li>
      </ul>
      <p>A cada turno, as cartas são <strong>distribuídas aleatoriamente</strong>.</p>

      <h2>3. Mecânica de Batalha</h2>
      <ul>
        <li>A <strong>vida</strong> de cada jogador aparece <strong>acima de suas cartas</strong>.</li>
        <li>Você deve <strong>selecionar uma carta</strong> antes de escolher a ação.</li>
        <li>A cada turno as cartas são distribuídas aleatoriamente.</li>
        <li><strong>Atacar:</strong> usa a força da carta escolhida para reduzir a vida do inimigo.</li>
        <li><strong>Defender:</strong> usa a defesa da carta para reduzir o dano recebido.</li>
      </ul>

      <h2>4. Registro das Ações</h2>
      <p>Todas as ações ficam registradas em tempo real, incluindo:</p>
      <ul>
        <li>Ataques e defesas executados.</li>
        <li>Vida atualizada de cada jogador.</li>
        <li>Resultado de cada turno.</li>
      </ul>

      <h2>5. Pontuação e Reinício</h2>
      <ul>
        <li>A cada <strong>vitória</strong>, você ganha pontos.</li>
        <li>Se desejar começar uma nova partida, clique em <strong>Reiniciar</strong>.</li>
      </ul>

      <h2>6. Objetivo</h2>
      <p>Derrote seus inimigos com estratégia e inteligência. Equilibre bem suas cartas, ataques e defesas para alcançar a glória e se tornar o <strong>mestre supremo dos cyber-animais</strong>.</p>

      <a href="index.php" class="voltar"><i class="fas fa-arrow-left"></i> Voltar ao Início</a>
    </div>
  </div>
</body>
</html>
