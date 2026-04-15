<?php
$message = $message ?? 'Erro desconhecido';
?>

<div class="container my-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">
            <i class="bi bi-exclamation-triangle"></i> Erro
        </h4>
        <p><?= esc($message) ?></p>
        <hr>
        <p class="mb-0">
            <small class="text-muted">
                Tente novamente ou verifique se os dados estão corretos.
            </small>
        </p>
    </div>
</div>
