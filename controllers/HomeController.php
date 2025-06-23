<?php

    Class HomeController extends RenderView {
        public function Index(){
        // Instanciar os models
        $reservationsModel = new ReservationsModel();
        $accommodationsModel = new AccommodationsModel();
        $guestModel = new GuestModel(); // Certifique-se que o arquivo GuestModel.php existe

        // --- DADOS BÁSICOS (JÁ EXISTIAM) ---
        $checkinsHoje = $reservationsModel->getCheckinsHoje();
        $checkoutsHoje = $reservationsModel->getCheckoutsHoje();
        $reservasPendentes = $reservationsModel->getReservasPendentes();
        $statusAcomodacoes = $accommodationsModel->getStatusAcomodacoes();
        
        $disponiveis = 0;
        $ocupadas = 0;
        $manutencao = 0;
        foreach($statusAcomodacoes as $status) {
            if ($status['status'] === 'disponivel') $disponiveis = $status['total'];
            if ($status['status'] === 'ocupado') $ocupadas = $status['total'];
            if ($status['status'] === 'manutencao') $manutencao = $status['total'];
        }
        $totalAcomodacoes = $disponiveis + $ocupadas + $manutencao;

        // --- NOVOS DADOS ---
        // 1. Métricas Financeiras
        $inicioMes = date('Y-m-01');
        $fimMes = date('Y-m-t');
        $receitaMes = $reservationsModel->getReceitaNoPeriodo($inicioMes, $fimMes);
        $reservasFinalizadasMes = $reservationsModel->getContagemReservasFinalizadasNoPeriodo($inicioMes, $fimMes);
        $diariaMedia = ($reservasFinalizadasMes > 0) ? ($receitaMes / $reservasFinalizadasMes) : 0;

        // 2. Performance e Ocupação
        $taxaOcupacao = ($totalAcomodacoes > 0) ? ($ocupadas / $totalAcomodacoes) * 100 : 0;
        $novosHospedesHoje = $guestModel->getContagemNovosHospedesHoje();

        // 3. Informações Operacionais
        $acomodacoesManutencaoLista = $accommodationsModel->getAcomodacoesEmManutencao();

        // Carregar a View com todos os dados
        $this->LoadView('Home',[
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