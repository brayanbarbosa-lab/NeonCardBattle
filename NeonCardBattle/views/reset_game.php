<?php
// Inicia a sessão
session_start();

// Verifica se a ação é 'reset'
if (isset($_GET['action']) && $_GET['action'] == 'reset') {
    // Destroi a sessão atual
    session_destroy(); 
    // Reinicia a sessão
    session_start(); 
    $_SESSION = array(); // Limpa todas as variáveis de sessão


    exit(); // Certifique-se de sair após o redirecionamento
}
?>

<a href="routes.php?action=game">Reiniciar Jogo</a>