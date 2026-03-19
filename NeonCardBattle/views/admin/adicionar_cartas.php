<?php
session_start();
// Verificação única de login e permissão
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../game.php");
    exit();
}

require '../../includes/Database.php';
require '../../includes/Cartas.php';

$database = new Database();
$db = $database->getConnection();
$cartas = new Cartas($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o arquivo foi enviado e se não houve erro
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $imagemPath = $_FILES['imagem']['tmp_name']; // Caminho temporário do arquivo
        $tipoImagem = $_FILES['imagem']['type']; // Tipo da imagem

        // Verifica se o arquivo existe
        if (!file_exists($imagemPath)) {
            echo "O caminho da imagem não é válido.";
            exit();
        }

        // Armazena apenas o caminho da imagem
        $dados = [
            'nome' => $_POST['nome'],
            'ataque' => $_POST['ataque'],
            'defesa' => $_POST['defesa'],
            'imagem' => $imagemPath, // Armazena o caminho da imagem
            'tipo_imagem' => $tipoImagem
        ];

        // Insere a carta no banco de dados
        if ($cartas->inserirCarta(
            $dados['nome'],
            $dados['ataque'],
            $dados['defesa'],
            $dados['imagem'],
            $dados['tipo_imagem']
        )) {
            header("Location: gerenciar_cartas.php");
            exit();
        } else {
            echo "Erro ao inserir carta.";
        }
    } else {
        echo "Erro ao fazer upload da imagem.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Criar Cartas Futurísticas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #e0e0ff;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(100, 80, 255, 0.1) 0%, transparent 70%);
            pointer-events: none;
            z-index: -1;
        }

        .admin-container {
            width: 100%;
            max-width: 800px;
            background: rgba(20, 20, 40, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 50px rgba(100, 80, 255, 0.5);
            border: 1px solid rgba(100, 100, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .admin-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(100, 80, 255, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #ffffff;
            text-shadow: 0 0 10px rgba(100, 150, 255, 0.7);
            margin-bottom: 10px;
        }

        .header p {
            color: #a0a0ff;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .header-icon {
            font-size: 3rem;
            color: #6a5af9;
            margin-bottom: 20px;
            text-shadow: 0 0 15px rgba(106, 90, 249, 0.7);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #b0b0ff;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 12px;
            border: none;
            font-size: 1.1rem;
            background: rgba(30, 30, 60, 0.8);
            color: #ffffff;
            border: 1px solid rgba(100, 100, 255, 0.3);
            box-shadow: 0 0 15px rgba(100, 100, 255, 0.2);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #6a5af9;
            box-shadow: 0 0 20px rgba(106, 90, 249, 0.5);
            background: rgba(40, 40, 80, 0.9);
        }

        .file-upload {
            position: relative;
            margin: 25px 0;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px dashed rgba(100, 100, 255, 0.5);
            border-radius: 15px;
            background: rgba(30, 30, 60, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            min-height: 200px;
            text-align: center;
        }

        .file-upload-label:hover {
            border-color: #6a5af9;
            background: rgba(40, 40, 80, 0.6);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: #6a5af9;
            margin-bottom: 15px;
        }

        .file-upload-text {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .file-upload-hint {
            color: #a0a0d0;
            font-size: 0.9rem;
        }

        .file-upload input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .preview-container {
            margin-top: 20px;
            text-align: center;
        }

        .card-preview {
            width: 200px;
            height: 280px;
            perspective: 1000px;
            margin-top: 20px;
        }

        .card-inner {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5); 
            margin-left: 14vw;
        }
        .card-front,
        .card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
            overflow: hidden;
        }

        .card-back {
            background: linear-gradient(135deg, #1a1a3e, #2d2b65);
            display: flex;
            align-items: center;
            justify-content: center;
            transform: rotateY(180deg);
        }

        .card-back-pattern {
            width: 90%;
            height: 90%;
            background: 
                linear-gradient(45deg, transparent 45%, rgba(106, 90, 249, 0.2) 46%, rgba(106, 90, 249, 0.2) 54%, transparent 55%),
                linear-gradient(-45deg, transparent 45%, rgba(106, 90, 249, 0.2) 46%, rgba(106, 90, 249, 0.2) 54%, transparent 55%);
            background-size: 30px 30px;
            border: 2px solid rgba(106, 90, 249, 0.4);
            border-radius: 10px;
        }

        .card-logo {
            position: absolute;
            font-size: 3rem;
            color: rgba(255, 255, 255, 0.1);
        }

        .card-front {
            background: linear-gradient(135deg, #2d2b65, #3a3785);
            display: flex;
            flex-direction: column;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .card-name {
            font-weight: bold;
            font-size: 1.2rem;
            color: white;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
        }

        .card-stats {
            display: flex;
            justify-content: space-between;
            margin-top: auto;
        }

        .stat {
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            padding: 5px 10px;
            text-align: center;
            width: 45%;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #b0b0ff;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: bold;
            color: white;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 18px;
            background: linear-gradient(45deg, #6a5af9, #d66efd);
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 30px;
            box-shadow: 0 0 20px rgba(106, 90, 249, 0.7);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 30px rgba(106, 90, 249, 0.9);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #a0a0ff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s;
            padding: 10px 20px;
            border-radius: 8px;
            background: rgba(30, 30, 60, 0.5);
        }

        .back-link i {
            margin-right: 8px;
        }

        .back-link:hover {
            color: #6a5af9;
            background: rgba(40, 40, 80, 0.7);
            text-shadow: 0 0 10px rgba(100, 150, 255, 0.5);
        }

        .grid-pattern {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(100, 100, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(100, 100, 255, 0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            pointer-events: none;
            z-index: -1;
        }

        /* Animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 5s ease-in-out infinite;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .admin-container {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .card-preview {
                width: 180px;
                height: 252px;
            }
            .preview-image{
                width: 150px;
            }

        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="grid-pattern"></div>
        
        <div class="header">
            <div class="header-icon floating">
                <i class="fas fa-cards"></i>
            </div>
            <h1>Criar Nova Carta</h1>
            <p>Adicione uma carta poderosa ao seu jogo com atributos únicos e design impressionante</p>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nome"><i class="fas fa-font"></i> Nome da Carta</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: Dragão Cósmico" required>
                </div>
                
                <div class="form-group">
                    <label for="ataque"><i class="fas fa-bolt"></i> Valor de Ataque</label>
                    <input type="number" id="ataque" name="ataque" placeholder="0-100" min="0" max="100" required>
                </div>
                
                <div class="form-group">
                    <label for="defesa"><i class="fas fa-shield-alt"></i> Valor de Defesa</label>
                    <input type="number" id="defesa" name="defesa" placeholder="0-100" min="0" max="100" required>
                </div>
            </div>
            
            <div class="file-upload">
                <label class="file-upload-label">
                    <div class="file-upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="file-upload-text">Arraste e solte a imagem da carta aqui</div>
                    <div class="file-upload-hint">ou clique para selecionar (PNG, JPG, max 5MB)</div>
                    <input type="file" name="imagem" accept="image/*" required>
                </label>
            </div>
            
            <div class="preview-container">
                        <div class="card-preview">
                            <div class="card-inner">
                                <div class="card-front">
                                            <div class="current-image">
                                                    <img id="preview-image" style="width: 200px; height:29vh; " >
                                            </div>

                                </div>
                                <div class="card-back">
                                    <div class="card-back-pattern"><img src="../../assets/img/verso.png" alt="" style="width: 200px; height:28vh; margin-left:-0.5vw"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-plus-circle"></i> Criar Carta
            </button>
        </form>
        
        <a href="gerenciar_cartas.php" class="back-link">
            <i class="fas fa-arrow-left"></i> Voltar para Gerenciar Cartas
        </a>
    </div>

    <script>
        // Atualizar prévia em tempo real
        document.getElementById('nome').addEventListener('input', function() {
            document.getElementById('preview-nome').textContent = this.value;
        });

        document.getElementById('ataque').addEventListener('input', function() {
            document.getElementById('preview-ataque').textContent = this.value;
        });

        document.getElementById('defesa').addEventListener('input', function() {
            document.getElementById('preview-defesa').textContent = this.value;
        });

        // Animação de hover no cartão
        const cardInner = document.querySelector('.card-inner');
cardInner.addEventListener('mouseenter', () => {
    cardInner.style.transform = 'rotateY(180deg)';
});
cardInner.addEventListener('mouseleave', () => {
    cardInner.style.transform = 'rotateY(0deg)';
});

const fileUploadLabel = document.querySelector('.file-upload-label');
const fileInput = document.querySelector('input[name="imagem"]');

fileInput.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileUploadLabel.style.borderColor = '#00c9ff';
    fileUploadLabel.style.background = 'rgba(50, 50, 90, 0.7)';
});

fileInput.addEventListener('dragleave', () => {
    fileUploadLabel.style.borderColor = 'rgba(100, 100, 255, 0.5)';
    fileUploadLabel.style.background = 'rgba(30, 30, 60, 0.5)';
});

// UM único listener — atualiza label SEM destruir o input + atualiza prévia
fileInput.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        // Atualiza textos sem usar innerHTML
        fileUploadLabel.querySelector('.file-upload-icon i').className = 'fas fa-check-circle';
        fileUploadLabel.querySelector('.file-upload-text').textContent = 'Arquivo selecionado';
        fileUploadLabel.querySelector('.file-upload-hint').textContent = this.files[0].name;

        // Atualiza prévia da imagem
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});
    </script>
</body>
</html>