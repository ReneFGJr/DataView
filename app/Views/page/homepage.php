<!DOCTYPE html>
<html lang="pt-br">
<?= view('header/header'); ?>

<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background-color: #830705;
        /* Bordô */
        display: flex;
        flex-direction: column;
    }

    .screen {
        flex: 1;
        display: flex;
        height: 100%;
    }

    .left-pane {
        width: 50%;
        background-color: #830705;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .left-pane img {
        width: 380px;
        opacity: 0.95;
    }

    .right-pane {
        width: 50%;
        background-color: #ffffff;
        padding: 60px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        /* Alinha no canto esquerdo */
        justify-content: flex-start;
        /* Alinha no topo */
    }

    .title {
        font-size: 48px;
        font-weight: 900;
        color: #222;
        margin-bottom: 10px;
    }

    .subtitle {
        font-size: 22px;
        color: #666;
        margin-bottom: 20px;
    }

    .git-btn {
        margin-top: 25px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: #222;
        color: #fff;
        padding: 12px 20px;
        border-radius: 6px;
        transition: 0.2s;
        font-size: 18px;
    }

    .git-btn:hover {
        background: #000;
        transform: scale(1.04);
    }

    .footer-min {
        text-align: center;
        padding: 10px;
        color: #ddd;
        font-size: 14px;
    }
</style>

<body>

    <!-- TELA DIVIDIDA -->
    <div class="screen">

        <!-- ESQUERDA (LOGO GRANDE) -->
        <div class="left-pane">
            <img src="<?= base_url('img/logo/logo_dataview-pb.png') ?>" alt="DataView Logo">
        </div>

        <!-- DIREITA (INFORMAÇÕES DO SISTEMA NO TOPO) -->
        <div class="right-pane">
            <div class="title">DataView</div>
            <div class="subtitle">Sistema de análise, visualização e integração de dados.</div>

            <div style="width: 100%; display: flex; justify-content: flex-end;">
                <a href="https://github.com/ReneFGJr/DataView" target="_blank"">
                    <i class=" bi bi-github text-black" style="font-size: 26px;"></i>
                </a>
            </div>

            <br/>
            <a href="<?= base_url('sample') ?>">Exemplos</a>
        </div>



    </div>

    <!-- RODAPÉ MINIMALISTA -->
    <!-- RODAPÉ MINIMALISTA -->
    <div class="footer-min">
        DataView © <?= date("Y") ?> —
        Development by
        <a href="mailto:renefgj@gmail.com" class="text-white text-decoration-none">
            Rene Faustino Gabriel Junior
        </a>
    </div>

</body>

</html>