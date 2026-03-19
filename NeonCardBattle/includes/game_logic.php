<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include_once 'Database.php';
include_once 'Cartas.php';
include_once 'Jogo.php';
include_once 'SessionManager.php';

$database = new Database();
$db = $database->getConnection();
$cartas = new Cartas($db);

// Inicialização da sessão do jogo
if (!isset($_SESSION['vida_jogador'])) {
    $_SESSION['vida_jogador'] = 100;
    $_SESSION['vida_inimigo'] = 100;
    $_SESSION['deck_jogador'] = $cartas->getRandomDeck(5);
    $_SESSION['deck_inimigo'] = $cartas->getRandomDeck(5);
    $_SESSION['historico'] = [];
    $_SESSION['pontos'] = 0;
    $_SESSION['game_over'] = false;
    $_SESSION['carta_revelada'] = array_fill(0, 5, false);
    $_SESSION['indice_revelacao'] = 0;
}

if (isset($_POST['reiniciar'])) {
    $_SESSION['vida_jogador'] = 100;
    $_SESSION['vida_inimigo'] = 100;
    $_SESSION['deck_jogador'] = $cartas->getRandomDeck(5);
    $_SESSION['deck_inimigo'] = $cartas->getRandomDeck(5);
    $_SESSION['historico'] = [];
    $_SESSION['pontos'] = 0;
    $_SESSION['game_over'] = false;
    $_SESSION['carta_revelada'] = array_fill(0, 5, false);
    $_SESSION['indice_revelacao'] = 0;
    header("Location: game.php");
    exit();
}

if (!isset($_SESSION['carta_inimigo'])) {
    $_SESSION['carta_inimigo'] = null;
}

if (isset($_POST['sair'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Processar ações via AJAX
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$_SESSION['game_over'] && isset($_POST['acao'])) {
    $carta_index = $_POST['carta_index'];
    $acao_jogador = $_POST['acao'];

    if (isset($_SESSION['deck_jogador'][$carta_index]) && !empty($_SESSION['deck_inimigo'])) {
        $carta_jogador = $_SESSION['deck_jogador'][$carta_index];

        // Revelar a primeira carta do inimigo antes do combate
        if (!$_SESSION['carta_revelada'][0]) {
            $_SESSION['carta_revelada'][0] = true;
        }

        $_SESSION['carta_inimigo'] = $_SESSION['deck_inimigo'][0];

        $jogo = new Jogo($carta_jogador, $_SESSION['carta_inimigo'], $_SESSION['vida_jogador'], $_SESSION['vida_inimigo']);
        $acao_ia = $jogo->iaDecide();
        $resultado = $jogo->processarTurno($acao_jogador, $acao_ia);

        // Atualizar estados
        $_SESSION['vida_jogador'] = $resultado['vida_jogador'];
        $_SESSION['vida_inimigo'] = $resultado['vida_inimigo'];

        // Remover carta do jogador
        unset($_SESSION['deck_jogador'][$carta_index]);
        $_SESSION['deck_jogador'] = array_values($_SESSION['deck_jogador']);

        // Remover carta do inimigo e ajustar array de revelação
        array_shift($_SESSION['deck_inimigo']);
        array_shift($_SESSION['carta_revelada']);

        // Adicionar nova carta não revelada no final se necessário
        if (count($_SESSION['carta_revelada']) < count($_SESSION['deck_inimigo'])) {
            $_SESSION['carta_revelada'][] = false;
        }

        // Recarregar decks quando necessário
        if (empty($_SESSION['deck_jogador'])) {
            $_SESSION['deck_jogador'] = $cartas->getRandomDeck(5);
        }

        if (empty($_SESSION['deck_inimigo'])) {
            $_SESSION['deck_inimigo'] = $cartas->getRandomDeck(5);
            $_SESSION['carta_revelada'] = array_fill(0, 5, false);
            $_SESSION['indice_revelacao'] = 0;
        }

        // Atualizar histórico
        if ($acao_jogador === 'atacar') {
            $_SESSION['pontos'] += $resultado['dano_jogador'];
            array_unshift($_SESSION['historico'], [
                'tipo' => 'player',
                'mensagem' => "Você atacou com {$carta_jogador['nome']} causando {$resultado['dano_jogador']} de dano!"
            ]);
        } else {
            array_unshift($_SESSION['historico'], [
                'tipo' => 'player',
                'mensagem' => "Você se defendeu com {$carta_jogador['nome']}."
            ]);
        }

        if ($acao_ia === 'atacar') {
            array_unshift($_SESSION['historico'], [
                'tipo' => 'enemy',
                'mensagem' => "A IA atacou com {$_SESSION['carta_inimigo']['nome']} causando {$resultado['dano_ia']} de dano!"
            ]);
        } else {
            array_unshift($_SESSION['historico'], [
                'tipo' => 'enemy',
                'mensagem' => "A IA se defendeu com {$_SESSION['carta_inimigo']['nome']}."
            ]);
        }

        $_SESSION['historico'] = array_slice($_SESSION['historico'], 0, 10);

        // Verificar condições de fim de jogo
        if ($_SESSION['vida_jogador'] <= 0 || $_SESSION['vida_inimigo'] <= 0) {
            $_SESSION['game_over'] = true;
        }

        // Retornar dados atualizados para AJAX
        if (isset($_POST['ajax'])) {
            $response = [
                'vida_jogador' => $_SESSION['vida_jogador'],
                'vida_inimigo' => $_SESSION['vida_inimigo'],
                'deck_jogador' => $_SESSION['deck_jogador'],
                'deck_inimigo' => $_SESSION['deck_inimigo'],
                'historico' => $_SESSION['historico'],
                'carta_revelada' => $_SESSION['carta_revelada'],
                'game_over' => $_SESSION['game_over'],
                'pontos' => $_SESSION['pontos'],
                'carta_inimigo_usada' => $_SESSION['carta_inimigo'] // Carta que foi usada no combate
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
    }
}