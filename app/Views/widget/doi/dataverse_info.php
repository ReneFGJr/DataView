<?php
$ds  = $dataset;
$lv  = $ds['latestVersion'];
$cit = $lv['metadataBlocks']['citation']['fields'];
?>

<div class="container my-4">

    <!-- Cabeçalho -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="mb-1">
                <?= esc($cit[0]['value']) ?>
            </h3>

            <div class="d-flex flex-wrap gap-2 mt-2">
                <span class="badge bg-primary">Dataset</span>
                <span class="badge bg-success"><?= esc($lv['versionState']) ?></span>
                <span class="badge bg-dark"><?= esc($ds['publisher']) ?></span>
            </div>

            <p class="mt-3 mb-1">
                <strong>DOI:</strong>
                <a href="<?= esc($ds['persistentUrl']) ?>" target="_blank">
                    <?= esc($ds['persistentUrl']) ?>
                </a>
            </p>

            <small class="text-muted">
                Publicado em <?= esc($lv['publicationDate']) ?>
            </small>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="datasetTab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#geral">Visão geral</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#autores">Autores</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#descricao">Descrição</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#assuntos">Assuntos</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#arquivos">Arquivos</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#licenca">Licença</button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 p-4 bg-white shadow-sm">        
        <!-- VISÃO GERAL -->
        <div class="tab-pane fade show active" id="geral">
            <table class="table table-sm">
                <tr>
                    <th>Identificador</th>
                    <td><?= esc($ds['identifier']) ?></td>
                </tr>
                <tr>
                    <th>UNF</th>
                    <td><?php if (isset($lv['UNF'])) { echo displayDV($lv['UNF']); } ?></td>
                </tr>
                
                <tr>
                    <th>Versão</th>
                    <td><?= esc($lv['versionNumber']) ?>.<?= displayDV($lv['versionMinorNumber']) ?></td>
                </tr>
                <tr>
                    <th>Depositante</th>
                    <td><?php if (isset($cit[6]['value'])) { echo displayDV($cit[6]['value']); } ?></td>
                </tr>
                
                <tr>
                    <th>Data de depósito</th>
                    <td><?php if (isset($cit[7]['value'])) { echo displayDV($cit[7]['value']); } ?></td>
                </tr>
            </table>
        </div>

        

        <!-- AUTORES -->
        <div class="tab-pane fade" id="autores">
            <?php foreach ($cit[1]['value'] as $a): ?>
                <div class="border rounded p-3 mb-2">
                    <strong><?= esc($a['authorName']['value']) ?></strong><br>
                    <small><?= esc($a['authorAffiliation']['value'] ?? '-') ?></small><br>
                    <?php if (!empty($a['authorIdentifier']['value'])): ?>
                        <a href="<?= esc($a['authorIdentifier']['value']) ?>" target="_blank">
                            ORCID
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- DESCRIÇÃO -->
        <div class="tab-pane fade" id="descricao">
            <p style="text-align: justify;">
                <?= esc($cit[3]['value'][0]['dsDescriptionValue']['value']) ?>
            </p>
        </div>

        <!-- ASSUNTOS -->
        <div class="tab-pane fade" id="assuntos">
            <p><strong>Área do conhecimento:</strong></p>
            <span class="badge bg-secondary">
                <?= esc($cit[4]['value'][0]) ?>
            </span>

            <hr>

            <p><strong>Palavras-chave:</strong></p>
            <?php foreach ($cit[5]['value'] as $k): ?>
                <span class="badge bg-info text-dark me-1">
                    <?= esc($k['keywordValue']['value']) ?>
                </span>
            <?php endforeach; ?>
        </div>

        <!-- ARQUIVOS -->
        <div class="tab-pane fade" id="arquivos">
            <?php foreach ($lv['files'] as $f): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <strong><?= esc($f['label']) ?></strong><br>
                        <small><?= esc($f['dataFile']['friendlyType']) ?></small><br>
                        <small>
                            <?= number_format($f['dataFile']['filesize'] / 1024, 1) ?> KB
                        </small>

                        <span class="float-end">
                            <?php
                            $type = strtolower($f['dataFile']['contentType']);
                            switch($type)
                                {
                                    case 'text/tab-separated-values':
                                        echo view('widget/doi/files/file_tab-delimited', ['f' => $f]);
                                        break;
                                    default:
                                        $btnClass = 'btn-secondary';
                                        $btnText  = 'Baixar';
                                        break;
                                }
                            ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- LICENÇA -->
        <div class="tab-pane fade" id="licenca">
            <p>
                <strong><?= esc($lv['license']['name']) ?></strong>
            </p>
            <a href="<?= esc($lv['license']['uri']) ?>" target="_blank">
                <img src="<?= esc($lv['license']['iconUri']) ?>" alt="Licença">
            </a>
        </div>

    </div>
</div>