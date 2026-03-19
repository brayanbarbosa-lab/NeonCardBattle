<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual do Painel Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;}
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
            background: linear-gradient(135deg, #0a0a1a, #1b0030, #0f0c29);
            color: var(--light);
            min-height: 100vh;
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

        h3 { color: var(--secondary); margin-top: 15px; }

        p, ul { margin-bottom: 12px; }
        ul { padding-left: 20px; }
        code { background: rgba(0,0,0,0.3); padding: 2px 5px; border-radius: 5px; color: #fff; }

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

        @media (max-width: 768px) {
            .container { width: 95%; }
            h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>

    <h1><i class="fas fa-shield-alt"></i> Manual do Painel Admin</h1>

    <div class="container">
        <div class="box">
            <h2>📊 Dashboard / Painel Admin</h2>
            <p>É a página principal do admin. Mostra estatísticas gerais do sistema e acesso rápido às seções de gerenciamento.</p>
            <ul>
                <li>Visualizar o total de cartas cadastradas, maior ataque e maior defesa.</li>
                <li>Visualizar total de usuários, quantidade de administradores e jogadores.</li>
                <li>Acesso rápido para <code>Gerenciar Cartas</code> e <code>Gerenciar Usuários</code>.</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="box">
            <h2>🃏 Gerenciar Cartas</h2>
            <p>Permite listar todas as cartas do sistema, além de criar, editar ou excluir cartas existentes.</p>
            <ul>
                <li>Visualizar ID, Nome, Ataque e Defesa de cada carta.</li>
                <li><code>Editar</code>: redireciona para a página de edição da carta.</li>
                <li><code>Excluir</code>: remove a carta do banco de dados com confirmação.</li>
                <li>Botão <code>Nova Carta</code>: cria uma carta nova.</li>
            </ul>
            <ul>
                <li>Total de cartas cadastradas.</li>
                <li>Maior ataque registrado.</li>
                <li>Maior defesa registrada.</li>
            </ul>

            <h2>✏️ Adicionar / Editar Carta</h2>
            <ul>
                <li><strong>Nome:</strong> Nome da carta.</li>
                <li><strong>Ataque:</strong> Valor numérico de ataque (0–100).</li>
                <li><strong>Defesa:</strong> Valor numérico de defesa (0–100).</li>
                <li><strong>Imagem:</strong> Upload de imagem PNG ou JPG (máx. 5MB).</li>
            </ul>
            <ul>
                <li><code>Salvar</code>: cria ou atualiza a carta.</li>
                <li><code>Cancelar</code>: retorna à página de <code>Gerenciar Cartas</code> sem salvar.</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="box">
            <h2>👥 Gerenciar Usuários</h2>
            <ul>
                <li>Listagem de usuários com ID, Nome, Email, Função e Data de cadastro.</li>
                <li>Badge de função:
                    <ul>
                        <li><code>Administrador</code>: usuário com acesso total.</li>
                        <li><code>Jogador</code>: usuário comum.</li>
                    </ul>
                </li>
                <li><code>Excluir</code>: remove o usuário do sistema com confirmação. Administradores têm alerta especial.</li>
            </ul>
            <ul>
                <li>Total de usuários cadastrados.</li>
                <li>Total de administradores.</li>
                <li>Total de jogadores.</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="box">
            <h2>🔒 Logout / Encerrar Sessão</h2>
            <ul>
                <li>Limpa todas as variáveis de sessão.</li>
                <li>Destrói a sessão atual.</li>
                <li>Redireciona automaticamente para <code>login.php</code>.</li>
            </ul>

            <h2>⚠️ Observações Gerais</h2>
            <ul>
                <li>Somente usuários com a função <code>admin</code> podem acessar todas as páginas do painel.</li>
                <li>O sistema possui proteções para impedir acesso não autorizado.</li>
                <li>Todos os botões de exclusão possuem confirmação para evitar remoções acidentais.</li>
                <li>Estatísticas e badges ajudam na visualização rápida do estado do sistema.</li>
            </ul>

            <a href="dashboard.php" class="voltar"><i class="fas fa-arrow-left"></i> Voltar ao Painel</a>
        </div>
    </div>

</body>
</html>