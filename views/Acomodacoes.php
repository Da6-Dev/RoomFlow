<?php
ob_start();

/**
 * Retorna os detalhes do alerta com base na mensagem da URL.
 * @param string $msg O código da mensagem.
 * @return array|null Um array com 'class' e 'message' ou null se não houver mensagem.
 */
function getAlertDetails($msg)
{
    $alerts = [
        'success_create' => ['class' => 'alert-success', 'message' => 'Cadastro realizado com sucesso!'],
        'error_create'   => ['class' => 'alert-danger', 'message' => 'Erro ao cadastrar a acomodação.'],
        'success_update' => ['class' => 'alert-success', 'message' => 'Dados atualizados com sucesso!'],
        'error_update'   => ['class' => 'alert-danger', 'message' => 'Erro ao atualizar os dados da acomodação.'],
        'success_delete' => ['class' => 'alert-success', 'message' => 'Acomodação excluída com sucesso!'],
        'error_delete'   => ['class' => 'alert-danger', 'message' => 'Erro ao excluir a acomodação.']
    ];
    return $alerts[$msg] ?? null;
}

/**
 * Retorna a classe do badge e o texto com base no status da acomodação.
 * @param string $status O status da acomodação.
 * @return array Um array com 'class', 'text', e 'icon'.
 */
function getAcomodacaoStatusBadge($status)
{
    switch (strtolower($status)) {
        case 'disponivel':
        case 'disponível':
            return ['class' => 'bg-gradient-success', 'text' => 'Disponível', 'icon' => 'check_circle'];
        case 'ocupado':
            return ['class' => 'bg-gradient-warning', 'text' => 'Ocupado', 'icon' => 'no_meeting_room'];
        case 'manutencao':
        case 'manutenção':
            return ['class' => 'bg-gradient-danger', 'text' => 'Manutenção', 'icon' => 'build'];
        default:
            return ['class' => 'bg-gradient-secondary', 'text' => ucfirst($status), 'icon' => 'help'];
    }
}

$alert = getAlertDetails($_GET['msg'] ?? '');
?>

<div class="container-fluid py-4">
    <?php if ($alert): ?>
        <div class="alert <?php echo $alert['class']; ?> text-white alert-dismissible fade show" role="alert" id="alertMessage">
            <span class="alert-icon align-middle"><i class="material-symbols-rounded">check_circle</i></span>
            <span class="alert-text"><strong>Sucesso!</strong> <?php echo $alert['message']; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 d-flex align-items-center">
                            <h6 class="mb-0">Acomodações</h6>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <a class="btn bg-gradient-dark mb-0" href="/RoomFlow/Acomodacoes/Cadastrar">
                                <i class="material-symbols-rounded">add</i>&nbsp;&nbsp;Adicionar Acomodação
                            </a>
                        </div>
                    </div>
                    <hr class="horizontal dark my-3">
                    <div class="row">
                        <div class="col-12">
                            <span class="text-sm me-2">Filtrar por:</span>
                            <button class="btn btn-sm btn-outline-dark mb-0 me-1" onclick="filterAcomodacoes('all')">Todas</button>
                            <button class="btn btn-sm btn-outline-success mb-0 me-1" onclick="filterAcomodacoes('disponivel')">Disponíveis</button>
                            <button class="btn btn-sm btn-outline-warning mb-0 me-1" onclick="filterAcomodacoes('ocupado')">Ocupadas</button>
                            <button class="btn btn-sm btn-outline-danger mb-0" onclick="filterAcomodacoes('manutencao')">Manutenção</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <?php if (!empty($Accommodations)): ?>
            <?php foreach ($Accommodations as $acomodacao): ?>
                <?php
                $imagem_capa_path = $acomodacao['caminho_capa'] ?? 'public/assets/img/placeholder.jpg';
                $statusInfo = getAcomodacaoStatusBadge($acomodacao['status']);
                ?>
                <div class="col-xl-4 col-md-6 acomodacao-card" data-status="<?php echo strtolower($acomodacao['status']); ?>">
                    <div class="card card-blog">
                        <div class="card-header p-0 mt-n4 mx-3">
                            <a class="d-block shadow-xl border-radius-xl">
                                <img src="/RoomFlow/<?php echo htmlspecialchars($imagem_capa_path); ?>"
                                    alt="imagem da acomodação" class="img-fluid shadow-xl border-radius-xl"
                                    style="width: 100%; height: 250px; object-fit: cover;">
                            </a>
                            <span class="badge badge-lg <?php echo $statusInfo['class']; ?>" style="position: absolute; top: 20px; left: 20px;">
                                <i class="material-symbols-rounded align-middle me-1"><?php echo $statusInfo['icon']; ?></i>
                                <?php echo $statusInfo['text']; ?>
                            </span>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlspecialchars($acomodacao['tipo']); ?> - Nº <?php echo htmlspecialchars($acomodacao['numero']); ?></h5>
                                <h4 class="text-gradient text-success mb-0">R$ <?php echo number_format($acomodacao['preco'], 2, ',', '.'); ?></h4>
                            </div>
                            <p class="mb-4 text-sm">
                                <?php echo htmlspecialchars(substr($acomodacao['descricao'], 0, 100)) . (strlen($acomodacao['descricao']) > 100 ? '...' : ''); ?>
                            </p>
                            <div class="d-flex align-items-center justify-content-end">
                                <form action="/RoomFlow/Acomodacoes/Deletar" method="POST" id="form-delete-<?php echo $acomodacao['id']; ?>" class="d-none">
                                    <input type="hidden" name="id" value="<?php echo $acomodacao['id']; ?>">
                                </form>
                                <a href="/RoomFlow/Acomodacoes/<?php echo $acomodacao['id']; ?>" class="btn btn-sm btn-outline-dark mb-0 me-2">Ver / Editar</a>
                                <button onclick="confirmDelete(<?php echo $acomodacao['id']; ?>, '<?php echo htmlspecialchars(addslashes($acomodacao['tipo'])); ?> Nº <?php echo $acomodacao['numero']; ?>')" class="btn btn-sm btn-outline-danger mb-0">Excluir</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="mb-0">Nenhuma acomodação encontrada.</h5>
                        <p class="text-sm">Clique em "Adicionar Acomodação" para começar.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/Layout.php';
?>