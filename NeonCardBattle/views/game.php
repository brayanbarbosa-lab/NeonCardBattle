<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once '../includes/game_logic.php';

include_once 'header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Neon Card Battle - Modo Visual</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/game.css">
     <script src="../js/game.js"></script>
</head>



    <div class="battle-container">
        <!-- Área do Inimigo -->
        <div class="ai-area">
            <div class="ai-header">
                <div class="ai-title">
                    <i class="fas fa-robot"></i> INIMIGO
                </div>
                <div class="health-container">
                    <div class="health-text"><?= $_SESSION['vida_inimigo'] ?>%</div>
                    <div class="health-bar enemy-health">
                        <div class="health-fill" style="width: <?= $_SESSION['vida_inimigo'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="cards-container" id="enemy-cards">
                <?php for ($i = 0; $i < count($_SESSION['deck_inimigo']); $i++): ?>
                    <?php
                    // Verificar se a carta deve ser revelada
                    $deve_exibir = isset($_SESSION['carta_revelada'][$i]) && $_SESSION['carta_revelada'][$i];
                    $carta_inimigo = $_SESSION['deck_inimigo'][$i];
                    ?>
                    <div class="card enemy-card <?= $deve_exibir ? 'flipped' : '' ?>" data-index="<?= $i ?>">
                        <div class="card-inner">
                            <!-- Verso da carta (lado oculto) -->
                            <div class="card-front">
                                <div class="card-image" style="background-image: url(../assets/img/verso.png)"></div>
                            </div>
                            <!-- Frente da carta (lado revelado) -->
                            <div class="card-back">
                                <div class="card-image" style="background-image: url('../includes/get_image.php?id=<?= $carta_inimigo['id'] ?>')"></div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Área do Jogador -->
        <div class="player-area">
            <div class="player-header">
                <div class="player-title">
                    <i class="fas fa-user"></i> JOGADOR
                </div>
                <div class="health-container">
                    <div class="health-text"><?= $_SESSION['vida_jogador'] ?>%</div>
                    <div class="health-bar player-health">
                        <div class="health-fill" style="width: <?= $_SESSION['vida_jogador'] ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="cards-container" id="player-cards">
                <?php foreach ($_SESSION['deck_jogador'] as $index => $carta): ?>
                    <div class="card player-card" data-index="<?= $index ?>">
                        <div class="card-inner">
                            <div class="card-front">

                                <div class="card-image" style="background-image: url('../includes/get_image.php?id=<?= $carta['id'] ?>')"></div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="action-buttons">
        <button id="attack-btn" class="btn attack-btn" disabled>
            <i class="fas fa-fist-raised"></i> ATACAR
        </button>
        <button id="defend-btn" class="btn defend-btn" disabled>
            <i class="fas fa-shield-alt"></i> DEFENDER
        </button>
    </div>

    <!-- Log de Batalha -->
    <div class="battle-log-container">
        <div class="battle-log-title">
            <i class="fas fa-scroll"></i> HISTÓRICO DE BATALHA
        </div>
        <div class="battle-log" id="battle-log">
            <?php foreach ($_SESSION['historico'] as $log): ?>
                <div class="log-entry <?= $log['tipo'] === 'player' ? 'player-log' : 'enemy-log' ?>">
                    <div class="log-icon">
                        <?= $log['tipo'] === 'player' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>' ?>
                    </div>
                    <?= htmlspecialchars($log['mensagem']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tela de Game Over -->
    <?php if ($_SESSION['game_over']): ?>
        <div class="game-over" id="game-over">
            <div class="game-over-content">
                <h2>FIM DE JOGO!</h2>
                <p><?= $_SESSION['vida_jogador'] <= 0 ? 'Você foi derrotado!' : 'Você venceu!' ?></p>
                <form method="POST">
                    <button type="submit" name="reiniciar" class="btn restart-btn">
                        <i class="fas fa-redo"></i> JOGAR NOVAMENTE
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let selectedCardIndex = -1;
            let processing = false;

            // Selecionar carta do jogador
            $('.player-card').click(function() {
                if (processing) return;

                selectedCardIndex = $(this).data('index');
                $('.player-card').removeClass('selected');
                $(this).addClass('selected');

                // Habilitar botões de ação
                $('#attack-btn, #defend-btn').prop('disabled', false);
            });

            // Botão de atacar
            $('#attack-btn').click(function() {
                if (selectedCardIndex === -1 || processing) return;
                processAction('atacar');
            });

            // Botão de defender
            $('#defend-btn').click(function() {
                if (selectedCardIndex === -1 || processing) return;
                processAction('defender');
            });

            function processAction(action) {
                processing = true;
                $('.action-buttons button').prop('disabled', true);

                // Obter a carta selecionada
                const selectedCard = $(`.player-card[data-index="${selectedCardIndex}"]`);

                // Primeiro, revelar a carta do inimigo com animação
                const firstEnemyCard = $('.enemy-card').first();
                if (!firstEnemyCard.hasClass('flipped')) {
                    firstEnemyCard.addClass('flipped');

                    // Aguardar um pouco para mostrar a revelação
                    setTimeout(function() {
                        executePlayerAction(selectedCard, action);
                    }, 800);
                } else {
                    executePlayerAction(selectedCard, action);
                }
            }

            function executePlayerAction(selectedCard, action) {
                // Animação da carta sendo jogada
                selectedCard.css({
                    'position': 'absolute',
                    'z-index': '100',
                    'transition': 'all 0.8s ease'
                });

                // Mover carta para o centro
                const centerX = $(window).width() / 2 - selectedCard.width() / 2;
                const centerY = $(window).height() / 2 - selectedCard.height() / 2;

                selectedCard.animate({
                    left: centerX,
                    top: centerY,
                    transform: 'scale(1.2) rotate(10deg)'
                }, 800, function() {
                    // Enviar ação via AJAX
                    $.ajax({
                        type: 'POST',
                        url: 'game.php',
                        data: {
                            carta_index: selectedCardIndex,
                            acao: action,
                            ajax: true
                        },
                        dataType: 'json',
                        success: function(response) {
                            // Atualizar interface com a resposta
                            updateGameState(response);

                            // Resetar seleção e estado
                            setTimeout(function() {
                                selectedCardIndex = -1;
                                $('.player-card').removeClass('selected');
                                processing = false;

                                // Recarregar cartas se necessário
                                if (!response.game_over) {
                                    updateCardDisplay(response);
                                }
                            }, 1500);
                        },
                        error: function() {
                            alert('Erro ao processar a ação. Recarregando...');
                            location.reload();
                        }
                    });
                });
            }

            function updateCardDisplay(data) {
                // Atualizar display das cartas do inimigo
                $('#enemy-cards').html('');
                for (let i = 0; i < data.deck_inimigo.length; i++) {
                    const carta = data.deck_inimigo[i];
                    const isRevealed = data.carta_revelada[i] || false;

                    $('#enemy-cards').append(`
            <div class="card enemy-card ${isRevealed ? 'flipped' : ''}" data-index="${i}">
                <div class="card-inner">
                    <!-- VERSO da carta (quando NÃO está flipped) -->
                    <div class="card-front">
                        <div class="card-image" style="background-image: url(../assets/img/verso.png)"></div>
                    </div>
                    <!-- FRENTE da carta (quando está flipped) -->
                    <div class="card-back">
                        <div class="card-image" style="background-image: url('../includes/get_image.php?id=${carta.id}')"></div>
                    </div>
                </div>
            </div>
        `);
                }

                // Atualizar cartas do jogador (sempre mostram a frente)
                $('#player-cards').html('');
                data.deck_jogador.forEach((carta, index) => {
                    $('#player-cards').append(`
            <div class="card player-card" data-index="${index}">
                <div class="card-inner">
                    <div class="card-front">
                        <div class="card-image" style="background-image: url('../includes/get_image.php?id=${carta.id}')"></div>     
                    </div>
                </div>
            </div>
        `);
                });

                // Reativar eventos de clique nas cartas do jogador
                $('.player-card').click(function() {
                    if (processing) return;

                    selectedCardIndex = $(this).data('index');
                    $('.player-card').removeClass('selected');
                    $(this).addClass('selected');

                    $('#attack-btn, #defend-btn').prop('disabled', false);
                });
            }

            // Função melhorada para processar ação com debug
            function processAction(action) {
                processing = true;
                $('.action-buttons button').prop('disabled', true);

                console.log('Processando ação:', action, 'Carta:', selectedCardIndex);

                // Obter a carta selecionada
                const selectedCard = $(`.player-card[data-index="${selectedCardIndex}"]`);

                // Primeiro, revelar a carta do inimigo com animação
                const firstEnemyCard = $('.enemy-card').first();

                console.log('Primeira carta inimigo tem classe flipped?', firstEnemyCard.hasClass('flipped'));

                if (!firstEnemyCard.hasClass('flipped')) {
                    console.log('Revelando carta do inimigo...');
                    firstEnemyCard.addClass('flipped');

                    // Aguardar animação de flip
                    setTimeout(function() {
                        executePlayerAction(selectedCard, action);
                    }, 800);
                } else {
                    console.log('Carta já revelada, executando ação diretamente');
                    executePlayerAction(selectedCard, action);
                }
            }


            function updateGameState(data) {
                // Atualizar barras de vida com animação
                $('.player-health .health-fill').css('width', data.vida_jogador + '%');
                $('.enemy-health .health-fill').css('width', data.vida_inimigo + '%');
                $('.health-text').first().text(data.vida_inimigo + '%');
                $('.health-text').last().text(data.vida_jogador + '%');

                // Atualizar histórico de batalha
                $('#battle-log').html('');
                data.historico.forEach(log => {
                    const logClass = log.tipo === 'player' ? 'player-log' : 'enemy-log';
                    const icon = log.tipo === 'player' ? '<i class="fas fa-user"></i>' : '<i class="fas fa-robot"></i>';

                    $('#battle-log').append(`
                        <div class="log-entry ${logClass}">
                            <div class="log-icon">${icon}</div>
                            ${log.mensagem}
                        </div>
                    `);
                });

                // Atualizar pontuação
               

                // Mostrar tela de game over se necessário
                if (data.game_over) {
                    setTimeout(() => {
                        $('#game-over').remove();
                        $('body').append(`
                            <div class="game-over" id="game-over">
                                <div class="game-over-content">
                                    <h2>FIM DE JOGO!</h2>
                                    <p>${data.vida_jogador <= 0 ? 'Você foi derrotado!' : 'Você venceu!'}</p>
                                    
                                    <form method="POST">
                                        <button type="submit" name="reiniciar" class="btn restart-btn">
                                            <i class="fas fa-redo"></i> JOGAR NOVAMENTE
                                        </button>
                                    </form>
                                </div>
                            </div>
                        `);
                    }, 1000);
                }
            }
        });
    </script>
</body>

</html>