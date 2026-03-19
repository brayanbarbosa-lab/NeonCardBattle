<?php
require_once 'Database.php';

$database = new Database();
$db = $database->getConnection();

// Validação do ID da carta
$carta_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($carta_id === false) {
    http_response_code(400);
    echo "ID inválido.";
    exit();
}

// Prepara a consulta para buscar a imagem e o tipo de imagem
$stmt = $db->prepare("
    SELECT imagem, tipo_imagem 
    FROM cartas 
    WHERE id = ?
");
$stmt->execute([$carta_id]);
$imagem = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se a imagem foi encontrada
if ($imagem) {
    // Define o tipo de conteúdo da resposta
    header("Content-Type: " . $imagem['tipo_imagem']);
    // Exibe a imagem
    echo $imagem['imagem'];
} else {
    // Se a imagem não for encontrada, retorna um erro 404
    http_response_code(404);
    echo "Imagem não encontrada.";
}
exit();
?>
