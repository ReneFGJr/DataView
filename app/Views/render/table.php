<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Table view</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 20px;
        }

        thead th {
            background: #005f9e !important;
            color: white;
            font-weight: 600;
        }

        .truncate-alert {
            border-left: 5px solid #dc3545;
        }
    </style>

</head>
<body>

<div class="container-full my-5">
    <div class="table-container">
        <div class="table-responsive">

            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr>
                    <?php foreach ($header as $h): ?>
                        <th  class="text-white"><?= esc($h) ?></th>
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

    <?php if ($limit < 0): ?>
        <div class="alert alert-danger mt-4 truncate-alert">
            <strong>Atenção:</strong> esta visualização exibe apenas as primeiras 100 linhas do arquivo.
        </div>
    <?php endif; ?>

</div>

</body>
</html>
