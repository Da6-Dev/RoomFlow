<?php
ob_start();

/**
 * Função para retornar a classe do badge com base no status do histórico.
 * @param string $status O status da reserva arquivada.
 * @return array Um array com a 'class' e o 'text'.
 */
function getStatusHistoricoBadge($status)
{
    switch (strtolower($status)) {
        case 'cancelada':
            return ['class' => 'bg-gradient-danger', 'text' => 'Cancelada'];
        case 'finalizada':
            return ['class' => 'bg-gradient-secondary', 'text' => 'Finalizada'];
        default:
            return ['class' => 'bg-gradient-light', 'text' => ucfirst($status)];
    }
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-3 border-bottom">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                            <h6 class="mb-0"><i class="material-symbols-rounded opacity-10 me-2">history</i>Histórico de Reservas</h6>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-3">
                        <table class="table table-striped table-hover align-items-center mb-0" id="historicoTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Hóspede / Acomodação</th>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7 ps-2">Período</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Valor</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Arquivado em</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($historico)) : ?>
                                    <?php foreach ($historico as $reserva) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-md"><?php echo htmlspecialchars($reserva['nome_hospede'] ?? 'Hóspede não encontrado'); ?></h6>
                                                        <p class="text-sm text-secondary mb-0"><?php echo htmlspecialchars($reserva['nome_acomodacao'] . ' ' . $reserva['numero_acomodacao'] ?? 'Acomodação não encontrada'); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-sm font-weight-bold mb-0"><?php echo date("d/m/Y", strtotime($reserva['data_checkin'])); ?></p>
                                                <p class="text-sm text-secondary mb-0"><?php echo date("d/m/Y", strtotime($reserva['data_checkout'])); ?></p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-success text-sm font-weight-bold">R$ <?php echo number_format($reserva['valor_total'], 2, ',', '.'); ?></span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <?php $badge = getStatusHistoricoBadge($reserva['status']); ?>
                                                <span class="badge badge-sm <?php echo $badge['class']; ?>"><?php echo $badge['text']; ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-sm font-weight-bold"><?php echo date("d/m/Y", strtotime($reserva['data_arquivamento'])); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="material-symbols-rounded" style="font-size: 3rem;">search_off</i>
                                            <h6 class="mt-2">Nenhum registro encontrado</h6>
                                            <p class="text-sm text-secondary">Ainda não há reservas finalizadas ou canceladas no histórico.</p>
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

<script>
    // Espera o documento carregar para inicializar os plugins
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o DataTable na tabela do histórico, se houver dados
        <?php if (!empty($historico)) : ?>
            const historicoTable = new simpleDatatables.DataTable("#historicoTable", {
                searchable: true,
                fixedHeight: false,
                perPage: 10,
                labels: {
                    placeholder: "Buscar no histórico...",
                    perPage: "registros por página",
                    noRows: "Nenhum registro encontrado",
                    info: "Mostrando {start} a {end} de {rows} registros"
                }
            });
        <?php endif; ?>
    });
</script>