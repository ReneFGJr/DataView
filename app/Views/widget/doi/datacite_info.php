<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- Cabeçalho -->
                    <div class="mb-4">
                        <span class="badge bg-success mb-2">
                            Fonte: <?= esc(strtoupper($source)) ?>
                        </span>

                        <h2 class="fw-bold mb-2">
                            <?= esc($metadata['title']) ?>
                        </h2>

                        <p class="text-muted mb-0">
                            <strong>DOI:</strong>
                            <a href="https://doi.org/<?= esc(urldecode($doi)) ?>" target="_blank">
                                <?= esc(urldecode($doi)) ?>
                            </a>
                        </p>
                        <p>
                            <strong>URL:</strong>
                            <?= esc($metadata['url']) ?>
                        </p>
                    </div>

                    <hr class="my-4">

                    <!-- Metadados principais -->
                    <div class="row g-4">

                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted fw-semibold small">Publicador</h6>
                            <p class="mb-0"><?= esc($metadata['publisher']) ?></p>
                        </div>

                        <div class="col-md-3">
                            <h6 class="text-uppercase text-muted fw-semibold small">Ano</h6>
                            <p class="mb-0"><?= esc($metadata['publicationYear']) ?></p>
                        </div>

                        <div class="col-md-3">
                            <h6 class="text-uppercase text-muted fw-semibold small">Tipo</h6>
                            <span class="badge bg-primary">
                                <?= esc($metadata['resourceType']) ?>
                            </span>
                        </div>

                    </div>

                    <!-- Autores -->
                    <div class="mt-5">
                        <h5 class="fw-semibold mb-3">Autores / Criadores</h5>

                        <ul class="list-group list-group-flush">
                            <?php foreach ($metadata['creators'] as $creator): ?>
                                <li class="list-group-item px-0">
                                    👤 <?= esc($creator) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Ações -->
                    <div class="mt-5 d-flex flex-wrap gap-2">
                        <a href="<?= esc($metadata['url']) ?>"
                            target="_blank"
                            class="btn btn-outline-primary btn-lg">
                            🔗 Acessar Dataset
                        </a>

                        <a href="https://doi.org/<?= esc(urldecode($doi)) ?>"
                            target="_blank"
                            class="btn btn-outline-secondary btn-lg">
                            🌐 Resolver DOI
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </div>

</div>