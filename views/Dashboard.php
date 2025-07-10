<?php

ob_start();

?>


<div class="container-fluid py-4">
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 position-relative">
                    <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-white border-radius-md position-absolute d-flex align-items-center justify-content-center"
                        style="top: -24px; left: 16px; width: 48px; height: 48px;">
                        <i class="material-symbols-rounded" style="font-size: 26px; line-height: 1; margin-top: -25px;">payments</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Receita do Mês</p>
                        <h4 class="mb-0">R$ <?php echo number_format($receitaMes, 2, ',', '.'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 position-relative">
                    <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-white border-radius-md position-absolute d-flex align-items-center justify-content-center"
                        style="top: -24px; left: 16px; width: 48px; height: 48px;">
                        <i class="material-symbols-rounded" style="font-size: 26px; line-height: 1; margin-top: -25px;">price_check</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Diária Média (Mês)</p>
                        <h4 class="mb-0">R$ <?php echo number_format($diariaMedia, 2, ',', '.'); ?></h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header p-3 pt-2 position-relative">
                    <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-white border-radius-md position-absolute d-flex align-items-center justify-content-center"
                        style="top: -24px; left: 16px; width: 48px; height: 48px;">
                        <i class="material-symbols-rounded" style="font-size: 26px; line-height: 1; margin-top: -25px;">pie_chart</i>
                    </div>
                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Taxa de Ocupação</p>
                        <h4 class="mb-0"><?php echo number_format($taxaOcupacao, 1); ?>%</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header p-3 pt-2 position-relative">
                    <div class="icon icon-shape bg-gradient-primary shadow-primary text-white border-radius-md position-absolute d-flex align-items-center justify-content-center"
                        style="top: -24px; left: 16px; width: 48px; height: 48px;">
                        <i class="material-symbols-rounded" style="font-size: 26px; line-height: 1; margin-top: -20px;">person_add</i>
                    </div>

                    <div class="text-end pt-1">
                        <p class="text-sm mb-0 text-capitalize">Novos Hóspedes Hoje</p>
                        <h4 class="mb-0"><?php echo $novosHospedesHoje; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-header pb-0 p-3">
                    <h6 class="mb-0">Atividades do Dia</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6><i class="material-symbols-rounded text-success">meeting_room</i> Check-ins para Hoje (<?php echo count($checkinsHoje); ?>)</h6>
                            <ul class="list-group">
                                <?php if (!empty($checkinsHoje)): ?>
                                    <?php foreach ($checkinsHoje as $checkin): ?>
                                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm"><?php echo htmlspecialchars($checkin['nome_hospede']); ?></h6>
                                                <span class="text-xs"><?php echo htmlspecialchars($checkin['nome_acomodacao']) . ' ' . htmlspecialchars($checkin['numero']); ?></span>
                                            </div>
                                            <a href="/RoomFlow/Reservas/<?php echo $checkin['id']; ?>" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="material-symbols-rounded" aria-hidden="true">arrow_forward</i></a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm p-2">Nenhum check-in para hoje.</p>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h6><i class="material-symbols-rounded text-danger">exit_to_app</i> Check-outs para Hoje (<?php echo count($checkoutsHoje); ?>)</h6>
                            <ul class="list-group">
                                <?php if (!empty($checkoutsHoje)): ?>
                                    <?php foreach ($checkoutsHoje as $checkout): ?>
                                        <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm"><?php echo htmlspecialchars($checkout['nome_hospede']); ?></h6>
                                                <span class="text-xs"><?php echo htmlspecialchars($checkout['nome_acomodacao']) . ' ' . htmlspecialchars($checkout['numero']); ?></span>
                                            </div>
                                            <a href="/RoomFlow/Reservas/<?php echo $checkout['id']; ?>" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="material-symbols-rounded" aria-hidden="true">arrow_forward</i></a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm p-2">Nenhum check-out para hoje.</p>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6>Status das Acomodações</h6>
                </div>
                <div class="card-body p-3 d-flex justify-content-center align-items-center">
                    <canvas id="acomodacoesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-5 mb-lg-0 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6><i class="material-symbols-rounded text-warning">pending_actions</i> Reservas Pendentes (<?php echo count($reservasPendentes); ?>)</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <?php if (!empty($reservasPendentes)): ?>
                            <?php foreach ($reservasPendentes as $reserva): ?>
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm"><?php echo htmlspecialchars($reserva['nome_hospede']); ?></h6>
                                        <span class="text-xs">Check-in em: <?php echo date("d/m/Y", strtotime($reserva['data_checkin'])); ?></span>
                                    </div>
                                    <a href="/RoomFlow/Reservas/<?php echo $reserva['id']; ?>" class="btn btn-link btn-icon-only btn-rounded btn-sm text-dark icon-move-right my-auto"><i class="material-symbols-rounded" aria-hidden="true">arrow_forward</i></a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm p-2">Nenhuma reserva pendente.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h6><i class="material-symbols-rounded text-secondary">build</i> Acomodações em Manutenção (<?php echo count($acomodacoesManutencaoLista); ?>)</h6>
                </div>
                <div class="card-body p-3">
                    <ul class="list-group">
                        <?php if (!empty($acomodacoesManutencaoLista)): ?>
                            <?php foreach ($acomodacoesManutencaoLista as $item): ?>
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark text-sm"><?php echo htmlspecialchars($item['tipo']); ?> - Número <?php echo htmlspecialchars($item['numero']); ?></h6>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-sm p-2">Nenhuma acomodação em manutenção.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('acomodacoesChart').getContext('2d');
        var acomodacoesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Disponível', 'Ocupado', 'Manutenção'],
                datasets: [{
                    label: 'Status das Acomodações',
                    data: [
                        <?php echo $acomodacoesDisponiveis; ?>,
                        <?php echo $acomodacoesOcupadas; ?>,
                        <?php echo $acomodacoesManutencao; ?>
                    ],
                    backgroundColor: [
                        'rgba(76, 175, 80, 0.7)', // Verde (Success)
                        'rgba(233, 30, 99, 0.7)', // Rosa/Vermelho (Ocupado)
                        'rgba(158, 158, 158, 0.7)' // Cinza (Manutenção)
                    ],
                    borderColor: [
                        'rgba(76, 175, 80, 1)',
                        'rgba(233, 30, 99, 1)',
                        'rgba(158, 158, 158, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>


<?php

$content = ob_get_clean();
include __DIR__ . '/Layout.php'

?>