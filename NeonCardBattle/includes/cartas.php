<?php
class Cartas {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function inserirCarta($nome, $ataque, $defesa, $imagemPath) {
        if (empty($nome) || !is_numeric($ataque) || !is_numeric($defesa)) {
            throw new Exception("Dados inválidos para a carta.");
        }

        if (!file_exists($imagemPath)) {
            throw new Exception("Imagem não encontrada: $imagemPath");
        }

        $imagem = file_get_contents($imagemPath);
        $tipoImagem = mime_content_type($imagemPath);

        $stmt = $this->conn->prepare("
            INSERT INTO cartas (nome, ataque, defesa, imagem, tipo_imagem) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bindParam(1, $nome);
        $stmt->bindParam(2, $ataque);
        $stmt->bindParam(3, $defesa);
        $stmt->bindParam(4, $imagem, PDO::PARAM_LOB);
        $stmt->bindParam(5, $tipoImagem);

        return $stmt->execute();
    }

    public function getAllCartas() {
        $stmt = $this->conn->query("SELECT * FROM cartas");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCartaById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM cartas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarCarta($id, $dados) {
        $imagem = null;
        $tipoImagem = null;

        if (!empty($dados['imagemPath']) && file_exists($dados['imagemPath'])) {
            $imagem = file_get_contents($dados['imagemPath']);
            $tipoImagem = mime_content_type($dados['imagemPath']);
        } else {
            $cartaAtual = $this->getCartaById($id);
            $imagem = $cartaAtual['imagem'];
            $tipoImagem = $cartaAtual['tipo_imagem'];
        }

        $stmt = $this->conn->prepare("
            UPDATE cartas 
            SET nome = ?, ataque = ?, defesa = ?, imagem = ?, tipo_imagem = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $dados['nome'],
            $dados['ataque'],
            $dados['defesa'],
            $imagem,
            $tipoImagem,
            $id
        ]);
    }

    public function deletarCarta($id) {
        $stmt = $this->conn->prepare("DELETE FROM cartas WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRandomDeck($size) {
        $stmt = $this->conn->prepare("SELECT id, nome, ataque, defesa FROM cartas ORDER BY RAND() LIMIT :size");
        $stmt->bindValue(':size', (int)$size, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
