<div class="container py-5">

    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-body">

                    <h3 class="mb-4">📁 Exemplos de Arquivos</h3>

                    <div class="list-group">

                        <!-- PDF -->
                        <a href="<?= base_url('view/pdf?siteUrl=https://dataverse.harvard.edu&PID=10.7910/DVN/KJTWDG&datasetId=&fileid=13170944') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-danger">📄</span>
                            Sample PDF File
                        </a>

                        <!-- TAB -->
                        <a href="<?= base_url('view/tab?siteUrl=https://dataverse.harvard.edu&PID=10.7910/DVN/JIZCHD&datasetId=&fileid=13185662') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-primary">📊</span>
                            Sample TAB File
                        </a>

                        <!-- GEO -->
                        <a href="<?= base_url('view/geo?siteUrl=https://dataverse.ideal.ufpb.br&PID=10.71650/DATAPB/H1UK3Y&datasetId=&fileid=274') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-success">🌎</span>
                            Sample GEO File
                        </a>

                        <!-- JPG via DOI -->
                        <a href="<?= base_url('view/jpg?siteUrl=https://dataverse.harvard.edu/api/access/datafile/:persistentId?persistentId=doi:10.7910/DVN/YJ0KEQ/ZDCCYT') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-warning">🖼️</span>
                            Sample JPG File (DOI)
                        </a>

                        <!-- JPG 2 -->
                        <a href="<?= base_url('view/jpg?siteUrl=https://dataverse.ideal.ufpb.br/api/access/datafile/232') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-warning">🖼️</span>
                            Sample JPG File 2
                        </a>

                        <!-- PNG -->
                        <a href="<?= base_url('view/png?siteUrl=https://dataverse.ideal.ufpb.br/api/access/datafile/157?imageThumb=400&pfdrid_c=true') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-info">🖼️</span>
                            Sample PNG File
                        </a>

                        <!-- TIFF -->
                        <a href="<?= base_url('view/tiff?siteUrl=https://dataverse.harvard.edu/api/access/datafile/:persistentId?persistentId=doi:10.7910/DVN/D83IBD/VOKIVD') ?>"
                            class="list-group-item list-group-item-action file-card">
                            <span class="file-icon text-secondary">🖼️</span>
                            Sample TIFF File

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>