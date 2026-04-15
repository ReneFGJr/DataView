<?php if ($ddiUrl !== ''): ?>
    <p class="mb-3">
        <a class="btn btn-outline-primary btn-sm" href="<?= esc($ddiUrl) ?>" target="_blank" title="Abrir o arquivo DDI em XML bruto">
            <i class="bi bi-download"></i> DDI XML Bruto
        </a>
    </p>
<?php endif; ?>

<?php if ($ddiError !== ''): ?>
    <div class="alert alert-warning mb-0">
        <strong>Aviso:</strong> <?= esc($ddiError) ?>
    </div>
<?php elseif (empty($ddiFiles)): ?>
    <div class="alert alert-info mb-0">
        <strong>Informação:</strong> Nenhum arquivo com variáveis encontrado no DDI para este dataset.
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
                                            (<?= esc($variable['name']) ?>)
                                            <span class="badge bg-secondary ms-1"><?= esc($variable['type']) ?></span>
                                            <?php if (!empty($variable['categories'])): ?>
                                                <span class="badge bg-info ms-1"><?= count($variable['categories']) ?> valores</span>
                                            <?php endif; ?>
                                        </span>
                                    </button>
                                </h2>
                                <div id="var-<?= $fileIndex ?>-<?= $varIndex ?>"
                                    class="accordion-collapse collapse <?= $varIndex === 0 ? 'show' : '' ?>"
                                    data-bs-parent="#accordion-file-<?= $fileIndex ?>">
                                    <div class="accordion-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-sm mb-0 table-hover">
                                                <tbody>
                                                    <tr class="table-light">
                                                        <th style="width: 20%">ID:</th>
                                                        <td><code><?= esc($variable['id']) ?></code></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nome da Variável:</th>
                                                        <td><code><?= esc($variable['name']) ?></code></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Descrição:</th>
                                                        <td><?= esc($variable['label']) ?></td>
                                                    </tr>
                                                    <tr class="table-light">
                                                        <th>Tipo de Dados:</th>
                                                        <td>
                                                            <span class="badge bg-secondary">
                                                                <?php
                                                                    $typeLabel = $variable['type'];
                                                                    switch($variable['type']) {
                                                                        case 'numeric': $typeLabel = 'Numérico'; break;
                                                                        case 'character': $typeLabel = 'Texto'; break;
                                                                        case 'date': $typeLabel = 'Data'; break;
                                                                        case 'time': $typeLabel = 'Hora'; break;
                                                                    }
                                                                ?>
                                                                <?= esc($typeLabel) ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Estatísticas -->
                                        <?php if (!empty($variable['stats'])): ?>
                                            <div class="p-3 border-top bg-light">
                                                <h6 class="mb-3">
                                                    <i class="bi bi-graph-up"></i>
                                                    <strong>Resumo Estatístico</strong>
                                                </h6>
                                                <div class="row g-2">
                                                    <?php
                                                        $statLabels = [
                                                            'min' => ['Mínimo', 'bi-arrow-down-circle', 'primary'],
                                                            'max' => ['Máximo', 'bi-arrow-up-circle', 'danger'],
                                                            'mean' => ['Média', 'bi-graph-up', 'success'],
                                                            'median' => ['Mediana', 'bi-diagram-2', 'info'],
                                                            'mode' => ['Moda', 'bi-bar-chart', 'warning'],
                                                            'stdev' => ['Desvio Padrão', 'bi-scatter', 'secondary'],
                                                            'variance' => ['Variância', 'bi-diagram-3', 'secondary'],
                                                            'vald' => ['Válidos', 'bi-check-circle', 'success'],
                                                            'invd' => ['Inválidos', 'bi-x-circle', 'danger'],
                                                        ];
                                                    ?>
                                                    <?php foreach ($variable['stats'] as $statType => $statValue): ?>
                                                        <?php
                                                            $stat = $statLabels[$statType] ?? [$statType, 'bi-info-circle', 'secondary'];
                                                            list($label, $icon, $color) = $stat;
                                                        ?>
                                                        <div class="col-md-6 col-lg-4">
                                                            <div class="card border-<?= $color ?> bg-white mb-0">
                                                                <div class="card-body p-2">
                                                                    <small class="text-muted">
                                                                        <i class="bi <?= $icon ?>"></i>
                                                                        <?= esc($label) ?>
                                                                    </small>
                                                                    <div class="mt-1">
                                                                        <strong class="text-<?= $color ?>">
                                                                            <?php
                                                                                $val = esc($statValue);
                                                                                // Limita para não quebrar layout
                                                                                if (strlen($val) > 30) {
                                                                                    $val = substr($val, 0, 27) . '...';
                                                                                }
                                                                                echo $val;
                                                                            ?>
                                                                        </strong>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Dicionário de Dados (Categorias/Classes) -->
                                        <?php if (!empty($variable['categories'])): ?>
                                            <div class="p-3 border-top">
                                                <h6 class="mb-3">
                                                    <i class="bi bi-list-check"></i>
                                                    <strong>Classificação/Dicionário de Dados</strong>
                                                    <span class="badge bg-info ms-2"><?= count($variable['categories']) ?> classe(s)</span>
                                                </h6>
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover border">
                                                        <thead>
                                                            <tr class="table-dark text-white">
                                                                <th style="width: 25%" class="text-center">
                                                                    <i class="bi bi-key"></i> Código
                                                                </th>
                                                                <th style="width: 75%">
                                                                    <i class="bi bi-tag"></i> Descrição
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $catIndex = 0;
                                                                foreach ($variable['categories'] as $category):
                                                                    $catIndex++;
                                                                    $bgClass = $catIndex % 2 === 0 ? 'table-light' : '';
                                                            ?>
                                                                <tr class="<?= $bgClass ?>">
                                                                    <td class="text-center align-middle">
                                                                        <code class="bg-light p-2 rounded">
                                                                            <strong><?= esc($category['value']) ?></strong>
                                                                        </code>
                                                                    </td>
                                                                    <td class="align-middle">
                                                                        <?= esc($category['label']) ?>
                                                                    </td>
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