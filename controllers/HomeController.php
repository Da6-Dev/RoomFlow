<?php

class HomeController extends RenderView
{
    // 1. Propriedades para armazenar as instâncias dos models.
    private $reservationsModel;
    private $accommodationsModel;
    private $guestModel;

    // 2. O construtor é responsável por instanciar todos os models necessários.
    public function __construct()
    {
        $this->reservationsModel = new ReservationsModel();
        $this->accommodationsModel = new AccommodationsModel();
        $this->guestModel = new GuestModel();
    }

    public function dashboard()
    {
        // 3. Os models agora são chamados a partir das propriedades da classe ($this->).
        // --- DADOS BÁSICOS ---
        $checkinsHoje = $this->reservationsModel->getCheckinsHoje();
        $checkoutsHoje = $this->reservationsModel->getCheckoutsHoje();
        $reservasPendentes = $this->reservationsModel->getReservasPendentes();
        $statusAcomodacoes = $this->accommodationsModel->getStatusAcomodacoes();

        $disponiveis = 0;
        $ocupadas = 0;
        $manutencao = 0;
        foreach ($statusAcomodacoes as $status) {
            if ($status['status'] === 'disponivel') $disponiveis = $status['total'];
            if ($status['status'] === 'ocupado') $ocupadas = $status['total'];
            if ($status['status'] === 'manutencao') $manutencao = $status['total'];
        }
        $totalAcomodacoes = $disponiveis + $ocupadas + $manutencao;

        // --- NOVOS DADOS ---
        // 1. Métricas Financeiras
        $inicioMes = date('Y-m-01');
        $fimMes = date('Y-m-t');
        $receitaMes = $this->reservationsModel->getReceitaNoPeriodo($inicioMes, $fimMes);
        $reservasFinalizadasMes = $this->reservationsModel->getContagemReservasFinalizadasNoPeriodo($inicioMes, $fimMes);
        $diariaMedia = ($reservasFinalizadasMes > 0) ? ($receitaMes / $reservasFinalizadasMes) : 0;

        // 2. Performance e Ocupação
        $taxaOcupacao = ($totalAcomodacoes > 0) ? ($ocupadas / $totalAcomodacoes) * 100 : 0;
        $novosHospedesHoje = $this->guestModel->getContagemNovosHospedesHoje();

        // 3. Informações Operacionais
        $acomodacoesManutencaoLista = $this->accommodationsModel->getAcomodacoesEmManutencao();

        // Carregar a View com todos os dados
        $this->LoadView('Dashboard', [
            'Title' => 'Dashboard',
            'father' => 'Visão Geral',
            'page' => 'Dashboard',

            // Dados existentes
            'checkinsHoje' => $checkinsHoje,
            'checkoutsHoje' => $checkoutsHoje,
            'reservasPendentes' => $reservasPendentes,

            // Dados para o gráfico de pizza
            'acomodacoesDisponiveis' => $disponiveis,
            'acomodacoesOcupadas' => $ocupadas,
            'acomodacoesManutencao' => $manutencao,

            // Novos cards
            'receitaMes' => $receitaMes,
            'diariaMedia' => $diariaMedia,
            'taxaOcupacao' => $taxaOcupacao,
            'novosHospedesHoje' => $novosHospedesHoje,

            // Nova lista operacional
            'acomodacoesManutencaoLista' => $acomodacoesManutencaoLista,
        ]);
    }
}