<?php
class Jogo {
    private $carta_jogador;
    private $carta_inimigo;
    private $vida_jogador;
    private $vida_inimigo;
    
    public function __construct($carta_jogador, $carta_inimigo, $vida_jogador = 100, $vida_inimigo = 100) {
        $this->carta_jogador = $carta_jogador ?? ['ataque' => 0, 'defesa' => 0, 'nome' => 'Carta Jogador'];
        $this->carta_inimigo = $carta_inimigo ?? ['ataque' => 0, 'defesa' => 0, 'nome' => 'Carta Inimigo'];
        $this->vida_jogador = $vida_jogador;
        $this->vida_inimigo = $vida_inimigo;
    }

    // Calcula o dano causado pelo jogador ao inimigo, considerando defesa do inimigo
    public function danoJogador() {
        return max(0, $this->carta_jogador['ataque'] - $this->carta_inimigo['defesa']);
    }

    // Calcula o dano causado pelo inimigo ao jogador, considerando defesa do jogador
    public function danoInimigo() {
        return max(0, $this->carta_inimigo['ataque'] - $this->carta_jogador['defesa']);
    }

    // Decide ação da IA (atacar ou defender)
    public function iaDecide() {
        $danoEsperado = $this->danoJogador();
        $riscoDano = $this->danoInimigo();
        $diferencaVida = $this->vida_jogador - $this->vida_inimigo;
        $percentualVidaIA = $this->vida_inimigo;
    
        // Aumenta agressividade e defesa ao máximo
        $fatorAgressividade = 5.0; 
        $fatorDefesa = ($percentualVidaIA < 0.5) ? 1 : 0.5; 
    
        // Limiares muito baixos para forçar defesa quando necessário
        $limiarDanoBaixo = 20;
    
        // Scores com pesos bem altos para atacar e defender com segurança
        $scoreAtacar = $danoEsperado * $fatorAgressividade;
        $scoreAtacar += max(0, $diferencaVida) * 2.0;
    
        if ($danoEsperado >= $this->vida_jogador * 0.3) {
            $scoreAtacar *= 2.0; // Bônus forte para ataques decisivos
        }
    
        $scoreDefender = $riscoDano * $fatorDefesa;
        $scoreDefender += max(0, -$diferencaVida) * 2.0;
    
        // Reduz menos a defesa para manter segurança alta
        if ($riscoDano < $limiarDanoBaixo) {
            $scoreDefender *= 0.5;
        }
    
       
        $acoes = [
            'atacar' => max(0, $scoreAtacar),
            'defender' => max(0, $scoreDefender),
        ];
    
        // Ordena ações e escolhe a melhor
        arsort($acoes);
        $melhorAcao = key($acoes);
    
        // Sempre defender se pode morrer no próximo ataque
        if ($riscoDano >= $this->vida_inimigo && $melhorAcao == 'defender') {
            return 'defender';
        }
    
        // Sempre atacar se pode matar o jogador no próximo ataque
        if ($danoEsperado >= $this->vida_jogador && $melhorAcao == 'atacar') {
            return 'atacar';
        }
    
        return $melhorAcao;
    }
    
    // Processa o turno com ações do jogador e da IA, atacando ou defendendo simultaneamente
    public function processarTurno($acao_jogador, $acao_ia) {
        $dano_jogador = 0;
        $dano_ia = 0;

        // Lógica de combate refatorada
        if ($acao_jogador === 'atacar') {
            $dano_base = $this->carta_jogador['ataque'];
            $dano_jogador = ($acao_ia === 'defender') 
                ? max(0, $dano_base - $this->carta_inimigo['defesa'])
                : $dano_base;
        }

        if ($acao_ia === 'atacar') {
            $dano_base = $this->carta_inimigo['ataque'];
            $dano_ia = ($acao_jogador === 'defender')
                ? max(0, $dano_base - $this->carta_jogador['defesa'])
                : $dano_base;
        }

        // Atualização de vida
        $this->vida_jogador = max(0, $this->vida_jogador - $dano_ia);
        $this->vida_inimigo = max(0, $this->vida_inimigo - $dano_jogador);

        return [
            'dano_jogador' => $dano_jogador,
            'dano_ia' => $dano_ia,
            'vida_jogador' => $this->vida_jogador,
            'vida_inimigo' => $this->vida_inimigo,
            'acao_ia' => $acao_ia
        ];
    }

}
?>
