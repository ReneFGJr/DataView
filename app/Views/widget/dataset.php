<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">

                    <!-- Cabeçalho -->
                    <div class="text-center mb-4">
                        <div class="display-6 mb-2">📁</div>
                        <h3 class="fw-bold">Explorar Dataset por DOI</h3>
                        <p class="text-muted mb-0">
                            Informe o DOI do dataset para visualizar seus metadados e relações.
                        </p>
                    </div>

                    <!-- Formulário -->
                    <form>
                        <div class="mb-3">
                            <label for="doi" class="form-label fw-semibold">
                                DOI do Dataset
                            </label>
                            <input
                                type="text"
                                id="doi"
                                name="doi"
                                value="https://doi.org/10.71650/DATAPB/YTSI2O"
                                class=" form-control form-control-lg"
                                placeholder="ex: 10.1234/abcd.5678">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                🔎 Explorar Dataset
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>