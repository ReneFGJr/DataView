<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .small {
        font-size: 0.8em;
    }
</style>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="row">
                        
                        <!-- IMAGEM À ESQUERDA -->
                        <div class="col-md-10 text-center">
                            <?php if ($preview): ?>
                                <img src="<?= $preview ?>"
                                     class="img-fluid rounded shadow-sm"
                                     style="width: 100%;">
                            <?php else: ?>
                                <p class="text-muted">Nenhuma imagem para visualizar</p>
                            <?php endif; ?>
                        </div>

                        <!-- INFORMAÇÕES À DIREITA -->
                        <div class="col-md-2">
                            <?php 
                                $largura = $info[0];
                                $altura = $info[1];
                                $megapixels = round(($largura * $altura) / 1000000, 2);
                                $proporcao = round($largura / $altura, 3);
                            ?>

                            <table class="table table-sm table-borderless small">
                                <tbody>
                                    <tr>
                                        <td><strong class="small">Largura x Lagura</strong><br><?= $largura ?>x<?= $altura ?>px</td>
                                    </tr>
                                        
                                    </tr>
                                    <tr>
                                        <td><strong class="small">Megapixels</strong>
                                        <br><?= $megapixels ?> px</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="small">Proporção</strong>: <?= $proporcao ?> : 1</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="small">Bits: </strong><?= $info['bits'] ?? '—' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="small">Canais</strong>: <?= $info['channels'] ?? '—' ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="small">MIME</strong><br><?= $info['mime'] ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="small">Tipo</strong><br><?= image_type_to_mime_type($info[2]) ?></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div> <!-- row -->

                </div> <!-- card-body -->
            </div> <!-- card -->

        </div>
    </div>
</div>
