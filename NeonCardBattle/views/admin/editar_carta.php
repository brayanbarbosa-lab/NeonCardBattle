<?php
session_start();

// Verificar permissões
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: ../game.php");
    exit();
}

require_once '../../includes/database.php';
require_once '../../includes/Cartas.php';

$database = new Database();
$db = $database->getConnection();
$cartas = new Cartas($db);

// Obter ID da carta a ser editada
$carta_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$carta = $cartas->getCartaById($carta_id);

// Verificar se a carta existe
if (!$carta) {
    header("Location: gerenciar_cartas.php");
    exit();
}

// Processar atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'id' => $carta_id,
        'nome' => $_POST['nome'],
        'ataque' => (int)$_POST['ataque'],
        'defesa' => (int)$_POST['defesa']
    ];

    // Se uma nova imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $dados['imagem'] = $_FILES['imagem']['tmp_name'];
        $dados['tipo_imagem'] = $_FILES['imagem']['type'];
    } else {
        // Manter a imagem existente
        $dados['imagem'] = null;
        $dados['tipo_imagem'] = null;
    }

    // Atualizar a carta
    if ($cartas->atualizarCarta(
    $dados['id'],
    [
        'nome'        => $dados['nome'],
        'ataque'      => $dados['ataque'],
        'defesa'      => $dados['defesa'],
        'imagemPath'  => $dados['imagem'],   // a função espera 'imagemPath'
    ]
)) {
        header("Location: gerenciar_cartas.php");
        exit();
    } else {
        $erro = "Erro ao atualizar a carta. Tente novamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Carta - Painel Admin</title>
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
            --dark: #0f0c29;
            --darker: #080616;
            --accent: #00c9ff;
            --light: #e0e0ff;
            --success: #3ddc84;
            --danger: #ff3860;
        }

        body {
            background: linear-gradient(135deg, var(--darker), var(--dark));
            min-height: 100vh;
            color: var(--light);
            padding: 20px;
            overflow-x: hidden;
        }

        body::before {
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

        .grid-pattern {
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
            z-index: -1;
        }

        .admin-container {
            max-width: 1200px;
            margin: 40px auto;
            background: rgba(20, 20, 40, 0.7);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 0 50px rgba(106, 90, 249, 0.3);
            border: 1px solid rgba(100, 100, 255, 0.2);
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
            background: radial-gradient(circle, rgba(106, 90, 249, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(100, 100, 255, 0.2);
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: white;
            text-shadow: 0 0 10px rgba(100, 150, 255, 0.5);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header h1 i {
            color: var(--accent);
            text-shadow: 0 0 15px rgba(0, 201, 255, 0.7);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 25px;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .primary-btn {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            box-shadow: 0 0 20px rgba(106, 90, 249, 0.5);
        }

        .secondary-btn {
            background: rgba(30, 30, 60, 0.5);
            border: 1px solid rgba(100, 100, 255, 0.3);
        }

        .btn::before {
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

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 30px rgba(106, 90, 249, 0.8);
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn i {
            font-size: 1.2rem;
        }

        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 20px;
        }

        @media (max-width: 900px) {
            .form-container {
                grid-template-columns: 1fr;
            }
        }

        .form-section {
            background: rgba(25, 25, 50, 0.5);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid rgba(100, 100, 255, 0.1);
        }

        .form-section h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h2 i {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--light);
            opacity: 0.9;
        }

        .form-group input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 12px;
            border: none;
            font-size: 1.1rem;
            background: rgba(30, 30, 60, 0.8);
            color: white;
            border: 1px solid rgba(100, 100, 255, 0.3);
            box-shadow: 0 0 15px rgba(100, 100, 255, 0.2);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 20px rgba(0, 201, 255, 0.5);
            background: rgba(40, 40, 80, 0.9);
        }

        .file-upload {
            position: relative;
            margin-top: 25px;
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
            border-color: var(--accent);
            background: rgba(40, 40, 80, 0.6);
        }

        .file-upload-icon {
            font-size: 3rem;
            color: var(--primary);
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
            text-align: center;
            padding: 20px;
        }

        .card-preview {
            display: inline-block;
            width: 250px;
            height: 350px;
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
            font-size: 1.4rem;
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
            padding: 8px 15px;
            text-align: center;
            width: 45%;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #b0b0ff;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: white;
        }

        .current-image {
            margin-top: 20px;
            text-align: center;
        }

        .current-image img {
            max-width: 100%;
            border-radius: 10px;
            border: 2px solid rgba(100, 100, 255, 0.3);
            box-shadow: 0 0 20px rgba(100, 100, 255, 0.3);
        }

        .current-image p {
            margin-top: 10px;
            color: var(--accent);
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        /* Animations */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .floating {
            animation: float 5s ease-in-out infinite;
        }

        .error-message {
            background: rgba(255, 56, 96, 0.2);
            border: 1px solid var(--danger);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        .success-message {
            background: rgba(61, 220, 132, 0.2);
            border: 1px solid var(--success);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .admin-container {
                padding: 20px;
            }

            .header h1 {
                font-size: 2rem;
            }

            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="grid-pattern"></div>

    <div class="admin-container">
        <div class="header">
            <h1>
                <i class="fas fa-edit"></i>
                Editar Carta: <?= htmlspecialchars($carta['nome']) ?>
            </h1>
            <a href="gerenciar_cartas.php" class="btn secondary-btn">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>

        <?php if (isset($erro)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?= $erro ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-container">
                <div class="form-section">
                    <h2><i class="fas fa-pencil-alt"></i> Dados da Carta</h2>

                    <div class="form-group">
                        <label for="nome"><i class="fas fa-font"></i> Nome da Carta</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($carta['nome']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="ataque"><i class="fas fa-bolt"></i> Valor de Ataque</label>
                        <input type="number" id="ataque" name="ataque" min="0" max="100" value="<?= $carta['ataque'] ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="defesa"><i class="fas fa-shield-alt"></i> Valor de Defesa</label>
                        <input type="number" id="defesa" name="defesa" min="0" max="100" value="<?= $carta['defesa'] ?>" required>
                    </div>

                    <div class="file-upload">
                        <label class="file-upload-label">
                            <div class="file-upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="file-upload-text">Clique para alterar a imagem</div>
                            <div class="file-upload-hint">(PNG, JPG, max 5MB - Opcional)</div>
                            <input type="file" name="imagem" accept="image/*">
                        </label>
                    </div>

                    <div class="btn-group">
                        <button type="submit" class="btn primary-btn">
                            <i class="fas fa-save"></i> Salvar Alterações
                        </button>
                        <a href="gerenciar_cartas.php" class="btn secondary-btn">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>

                <div class="form-section">
                    <h2><i class="fas fa-eye"></i> Prévia da Carta</h2>

                    <div class="preview-container">
                        <div class="card-preview">
                            <div class="card-inner">
                                <div class="card-front">
                                
                                            <div class="current-image">
                                                
                                                <?php if ($carta['imagem']): ?>
                                                    <img id="preview-image" style="width: 20vw; height:37vh; margin-top:-2.2vh;" src="../../includes/get_image.php?id=<?= $carta['id'] ?>" alt="Imagem atual da carta">
                                                <?php else: ?>
                                                    <p>Nenhuma imagem disponível</p>
                                                <?php endif; ?>
                                            </div>
                                        

                                
                                </div>
                                <div class="card-back">
                                <div class="card-back-pattern"><img src="../../assets/img/verso.png" alt="" style="width: 250px; height:42vh; margin-left:-0.8vw; margin-top:-2vh"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </form>
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

// UM único listener que faz tudo: atualiza label + prévia
fileInput.addEventListener('change', function () {
    if (this.files && this.files[0]) {
        // Atualiza o texto do label sem destruir o input
        fileUploadLabel.querySelector('.file-upload-icon i').className = 'fas fa-check-circle';
        fileUploadLabel.querySelector('.file-upload-text').textContent = 'Arquivo selecionado';
        fileUploadLabel.querySelector('.file-upload-hint').textContent = this.files[0].name;

        // Atualiza a prévia da imagem
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview-image').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
    }
});

document.getElementById('nome').addEventListener('input', function () {
    const el = document.getElementById('preview-nome');
    if (el) el.textContent = this.value;
});
document.getElementById('ataque').addEventListener('input', function () {
    const el = document.getElementById('preview-ataque');
    if (el) el.textContent = this.value;
});
document.getElementById('defesa').addEventListener('input', function () {
    const el = document.getElementById('preview-defesa');
    if (el) el.textContent = this.value;
});
    </script>
</body>

</html>