<div align="center">

# 🃏 NEON CARD BATTLE

### O Último Humano Contra os Senhores da Nova Terra

[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)]()
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)]()
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)]()
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)]()

> Em um mundo onde os humanos desapareceram, animais geneticamente modificados com armaduras tech dominam a Terra. Você é o único humano restante — e sua única chance de sobrevivência é dominar a arte das batalhas de cartas.

</div>

---

## 🌌 Lore

Em um futuro distante, o mundo como conhecemos deixou de existir. Os humanos sucumbiram ao tempo, e os animais herdaram a Terra. Porém, eles já não são mais apenas criaturas selvagens — a fusão entre biologia e tecnologia os transformou em **Cyber-Animais**, seres que unem instinto, circuitos e inteligência artificial.

Cada Cyber-Animal carrega implantes, armaduras robóticas e energia pulsante em seus corpos, tornando-se guerreiros implacáveis. Eles lutam em arenas digitais para decidir quem governará o planeta.

**Você** é o único ser humano restante. Sem implantes, sem armadura — apenas sua mente estratégica e um baralho de cartas lendárias. Comande as criaturas híbridas e prove quem será o verdadeiro **mestre supremo do novo mundo**.

---

## 🎮 Sobre o Jogo

**Neon Card Battle** é um jogo de batalha de cartas inspirado no clássico **Super Trunfo**, com mecânicas de ataque e defesa. O jogador enfrenta uma **IA** em duelos estratégicos usando cartas de Cyber-Animais, cada uma com atributos únicos de força e defesa, em um universo cyberpunk neon.

> 🎓 Projeto desenvolvido como trabalho escolar no curso de Desenvolvimento de Sistemas.

---

## ✨ Funcionalidades

- 🃏 **57 cartas únicas** — cada Cyber-Animal com força e defesa exclusivos
- ⚔️ **Atacar ou Defender** — escolha sua estratégia a cada turno
- ❤️ **Sistema de vida** — gerencie sua vida e derrote o inimigo
- 📜 **Registro de ações em tempo real** — acompanhe cada jogada
- 🔄 **Cartas aleatórias** — distribuição diferente a cada turno
- 👤 **Login e cadastro** — acesse com email ou nome de usuário
- 🗂️ **Explorar cartas** — veja toda a coleção antes de jogar
- 📖 **Manual do jogo** — aprenda as mecânicas com a lore completa
- 🔄 **Reiniciar partida** — comece uma nova batalha quando quiser
- 🛡️ **Painel Administrativo completo** — veja abaixo!

---

## 🐾 As Cartas

Existem **57 cartas**, cada uma representando um **Cyber-Animal** único:

| Atributo | Variação |
|---|---|
| ⚔️ **Força** | 5 a 37 |
| 🛡️ **Defesa** | 5 a 35 |

Cada carta possui uma imagem exclusiva do animal com sua armadura tech e implantes cibernéticos.

---

## 🛡️ Painel Administrativo

> O jogo conta com um **painel admin completo** para gerenciamento total do conteúdo do jogo!

O administrador tem acesso a uma área exclusiva onde pode:

- ➕ **Adicionar novas cartas** — com nome, imagem, força e defesa
- ✏️ **Editar cartas existentes** — atualize qualquer atributo ou imagem
- 🗑️ **Excluir cartas** — remova cartas do jogo com confirmação
- 👥 **Gerenciar usuários** — veja e remova usuários cadastrados
- 📊 **Ver estatísticas** — total de cartas, maior ataque e maior defesa registrados

### 🔑 Acesso Admin

| Campo | Valor |
|---|---|
| **Usuário** | `brayan` |
| **Senha** | `12345678` |

> ⚠️ Apenas usuários com perfil `admin` têm acesso ao painel. Jogadores comuns são redirecionados automaticamente para o jogo.

---

## 🚀 Tecnologias Utilizadas

| Tecnologia | Função |
|---|---|
| **PHP** | Backend, sessões e lógica do servidor |
| **HTML5** | Estrutura das páginas |
| **CSS3** | Estilização, animações e efeitos neon |
| **JavaScript** | Interatividade, partículas e transições |
| **MySQL** | Banco de dados (usuários e cartas) |

---

## 🛠️ Como Rodar o Projeto

### Pré-requisitos

- [Laragon](https://laragon.org/) ou qualquer servidor PHP local (XAMPP, WAMP)
- PHP 8.1+
- MySQL

### 📦 Instalação

```bash
# 1. Clone o repositório
git clone https://github.com/brayanbarbosa-lab/neon-card-battle.git

# 2. Mova para a pasta do servidor
# Laragon: C:\laragon\www\neon-card-battle
# XAMPP:   C:\xampp\htdocs\neon-card-battle
```

### 🗄️ Banco de Dados

1. Abra o **phpMyAdmin** (`http://localhost/phpmyadmin`)
2. Crie um banco chamado `neon_card_battle`
3. Importe o arquivo `database.sql` da raiz do projeto

### ⚙️ Configuração

Edite o arquivo `includes/Database.php` com suas credenciais do banco de dados.

### ▶️ Rodando

Acesse no navegador:
```
http://localhost/neon-card-battle
```

---

## 🎮 Como Jogar

1. Acesse a **tela inicial** e clique em **Iniciar Batalha**
2. **Cadastre-se** informando nome, e-mail e senha — ou faça login
3. O jogo começa automaticamente após o login
4. As cartas são **distribuídas aleatoriamente** a cada turno
5. **Selecione uma carta** antes de agir
6. Escolha sua ação:
   - ⚔️ **Atacar** — usa a força da carta para reduzir a vida do inimigo
   - 🛡️ **Defender** — usa a defesa da carta para reduzir o dano recebido
7. Acompanhe o **registro de ações** em tempo real
8. Vença reduzindo a vida do inimigo a zero!

---

## 🖥️ Telas do Jogo

| Tela | Descrição |
|---|---|
| **Tela Inicial** | Menu com opções de jogar, explorar cartas e manual |
| **Login** | Acesso com email ou nome de usuário |
| **Cadastro** | Criação de nova conta |
| **Jogo** | Arena de batalha principal |
| **Explorar Cartas** | Visualização de toda a coleção |
| **Manual** | Lore e instruções do jogo |
| **Painel Admin** | Gerenciamento completo de cartas e usuários |

---

## 📄 Licença

MIT License — veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

<div align="center">

O último humano não vai desistir. 🃏⚡

**NEON CARD BATTLE**

</div>
