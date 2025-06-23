<?php 
ob_start();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3">
                        <h6 class="text-white text-capitalize ps-3">Histórico de Reservas</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Hóspede / Acomodação</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Período da Estadia</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Valor Total</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Data de Arquivamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($historico)): ?>
                                    <?php foreach ($historico as $reserva): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <i class="material-symbols-rounded avatar avatar-sm me-3">history</i>
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($reserva['nome_hospede'] ?? 'Hóspede não encontrado'); ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?php echo htmlspecialchars($reserva['nome_acomodacao'] . ' ' . $reserva['numero_acomodacao'] ?? 'Acomodação não encontrada'); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">Check-in: <?php echo date("d/m/Y", strtotime($reserva['data_checkin'])); ?></p>
                                                <p class="text-xs text-secondary mb-0">Check-out: <?php echo date("d/m/Y", strtotime($reserva['data_checkout'])); ?></p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="badge badge-sm bg-gradient-secondary"><?php echo htmlspecialchars(ucfirst($reserva['status'])); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">R$ <?php echo number_format($reserva['valor_total'], 2, ',', '.'); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?php echo date("d/m/Y H:i", strtotime($reserva['data_arquivamento'])); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-secondary">Nenhum registro encontrado no histórico.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>