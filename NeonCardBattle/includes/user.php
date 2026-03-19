<?php
require_once 'Database.php';
class User {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function validarRegistro($dados) {
        $erros = [];
        
        // Validação do username
        if (empty($dados['username'])) {
            $erros['username'] = 'Nome de usuário obrigatório';
        } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $dados['username'])) {
            $erros['username'] = 'Username inválido (3-20 caracteres, apenas letras, números e _)';
        }

        // Validação do email
        if (empty($dados['email'])) {
            $erros['email'] = 'Email obrigatório';
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Formato de email inválido';
        }

        // Validação da senha
        if (empty($dados['password'])) {
            $erros['password'] = 'Senha obrigatória';
        } elseif (strlen($dados['password']) < 8) {
            $erros['password'] = 'Senha deve ter pelo menos 8 caracteres';
        } elseif ($dados['password'] !== $dados['confirm_password']) {
            $erros['confirm_password'] = 'As senhas não coincidem';
        }

        // Verificar se username/email já existe
        if (empty($erros)) {
            $stmt = $this->db->prepare("
                SELECT COUNT(*) FROM usuarios 
                WHERE username = ? OR email = ?
            ");
            $stmt->execute([$dados['username'], $dados['email']]);
            
            if ($stmt->fetchColumn() > 0) {
                $erros['geral'] = 'Username ou email já cadastrados';
            }
        }

        return $erros;
    }

    public function registrarUsuario($dados) {
        $hash_senha = password_hash($dados['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->db->prepare("
            INSERT INTO usuarios 
            (username, email, password, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        
        return $stmt->execute([
            $dados['username'],
            $dados['email'],
            $hash_senha
        ]);
    }
}
?>