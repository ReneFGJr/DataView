<?php if ($ddiUrl !== ''): ?>
    <p class="mb-3">
        <a class="btn btn-outline-primary btn-sm" href="<?= esc($ddiUrl) ?>" target="_blank">
            Abrir DDI bruto (XML)
        </a>
    </p>
<?php endif; ?>

<?php if ($ddiError !== ''): ?>
    <div class="alert alert-warning mb-0">
        <?= esc($ddiError) ?>
    </div>
<?php elseif (empty($ddiFiles)): ?>
    <div class="alert alert-info mb-0">
        Nenhum arquivo encontrado no DDI para este dataset.
    </div>
<?php else: ?>
    <!-- Abas para cada arquivo -->
    <ul class="nav nav-tabs mb-3" id="ddiFileTabs" role="tablist">
        <?php foreach ($ddiFiles as $fileIndex => $file): ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= $fileIndex === 0 ? 'active' : '' ?>"
                    id="ddi-file-<?= $fileIndex ?>-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#ddi-file-<?= $fileIndex ?>"
                    type="button" role="tab">
                    <?= esc($file['name']) ?>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Conteúdo das abas de arquivo -->
    <div class="tab-content" id="ddiFileTabContent">
        <?php foreach ($ddiFiles as $fileIndex => $file): ?>
            <div class="tab-pane fade <?= $fileIndex === 0 ? 'show active' : '' ?>"
                id="ddi-file-<?= $fileIndex ?>"
                role="tabpanel">

                <!-- Info do arquivo -->
                <div class="alert alert-light border mb-4">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Tipo:</strong> <?= esc($file['type']) ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Casos:</strong> <?= number_format((int)$file['cases'], 0, ',', '.') ?>
                        </div>
                        <div class="col-md-3">
                            <strong>Variáveis:</strong> <?= count($file['variables']) ?>
                        </div>
                    </div>
                </div>

                <?php if (empty($file['variables'])): ?>
                    <div class="alert alert-info">
                        Nenhuma variável encontrada para este arquivo.
                    </div>
                <?php else: ?>
                    <!-- Accordion de variáveis -->
                    <div class="accordion" id="accordion-file-<?= $fileIndex ?>">
                        <?php foreach ($file['variables'] as $varIndex => $variable): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button <?= $varIndex === 0 ? '' : 'collapsed' ?>"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#var-<?= $fileIndex ?>-<?= $varIndex ?>">
                                        <strong><?= esc($variable['label']) ?></strong>
                                        <span class="ms-2 text-muted small">
                                            (<?= esc($variable['name']) ?>) -
                                            <span class="badge bg-secondary"><?= esc($variable['type']) ?></span>
                                        </span>
                                    </button>
                                </h2>
                                <div id="var-<?= $fileIndex ?>-<?= $varIndex ?>"
                                    class="accordion-collapse collapse <?= $varIndex === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#accordion-file-<?= $fileIndex ?>">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 15%">ID:</th>
                                                        <td><?= esc($variable['id']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nome:</th>
                                                        <td><?= esc($variable['name']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Descrição:</th>
                                                        <td><?= esc($variable['label']) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tipo:</th>
                                                        <td><?= esc($variable['type']) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Estatísticas -->
                                        <?php if (!empty($variable['stats'])): ?>
                                            <div class="p-3 border-top">
                                                <h6 class="mb-2">Estatísticas:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover">
                                                        <tbody>
                                                            <?php foreach ($variable['stats'] as $statType => $statValue): ?>
                                                                <tr>
                                                                    <td style="width: 40%">
                                                                        <strong><?= esc(ucfirst(str_replace('_', ' ', $statType))) ?>:</strong>
                                                                    </td>
                                                                    <td><?= esc($statValue) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Dicionário de Dados (Categorias) -->
                                        <?php if (!empty($variable['categories'])): ?>
                                            <div class="p-3 border-top">
                                                <h6 class="mb-2">Dicionário de Dados:</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-condensed">
                                                        <thead>
                                                            <tr class="table-light">
                                                                <th>Valor</th>
                                                                <th>Rótulo</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($variable['categories'] as $category): ?>
                                                                <tr>
                                                                    <td>
                                                                        <code><?= esc($category['value']) ?></code>
                                                                    </td>
                                                                    <td><?= esc($category['label']) ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>