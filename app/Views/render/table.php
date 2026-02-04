<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Table + Map View</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
        body {
            background: #f5f7fa;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        thead th {
            background: #005f9e !important;
            color: white;
            font-weight: 600;
        }

        #map {
            height: 600px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .truncate-alert {
            border-left: 5px solid #dc3545;
        }
    </style>

</head>

<body>

    <div class="container my-5">

        <!-- NAV TABS -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">

            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="table-tab" data-bs-toggle="tab"
                    data-bs-target="#table"
                    type="button" role="tab">Tabela</button>
            </li>
            <!---- Mapa ---->
            <?php if (isset($mapa)) {?>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="map-tab" data-bs-toggle="tab"
                    data-bs-target="#map-tab-content"
                    type="button" role="tab">Mapa</button>
            </li>
            <?php } ?>
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content">

            <!-- TAB: TABELA -->
            <div class="tab-pane fade show active" id="table" role="tabpanel">

                <div class="table-container mt-4">
                    <div class="table-responsive">

                        <table class="table table-striped table-hover align-middle">
                            <thead>
                                <tr>
                                    <?php foreach ($header as $h): ?>
                                        <th class="text-white"><?= esc($h) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($data as $line): ?>
                                    <?php
                                    $line = str_replace("\t", ';', $line);
                                    $cols = explode(';', $line);
                                    ?>
                                    <tr>
                                        <?php foreach ($cols as $value): ?>
                                            <?php $value = str_replace('"', '', $value); ?>
                                            <td><?= esc($value) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                        </table>

                    </div>
                </div>

            </div>

            <!-- TAB: MAPA -->
             <?php if (isset($mapa)) {?>
            <div class="tab-pane fade" id="map-tab-content" role="tabpanel">
                    <?= $mapa ?>
            </div>
            <?php } ?>

        </div>

        <?php if ($limit < 0): ?>
            <div class="alert alert-danger mt-4 truncate-alert">
                <strong>Atenção:</strong> esta visualização exibe apenas as primeiras 100 linhas do arquivo.
            </div>
        <?php endif; ?>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>