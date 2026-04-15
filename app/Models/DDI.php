<?php

namespace App\Models;

use CodeIgniter\Model;

class DDI extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'ddis';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDeletes       = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];
    public $urlAPI = '';

    public function  fetchFromDDI(string $url)
    {
        $DoiMetadataModel = new \App\Models\DoiMetadataModel();

        // Decompor a URL e recompor com a API endpoint correto
        $parsed = parse_url($url);
        $scheme = $parsed['scheme'] ?? 'https';
        $host = $parsed['host'] ?? '';
        $query = $parsed['query'] ?? '';

        // URL da API DDI
        $apiUrl = $scheme . '://' . $host . '/api/datasets/export?exporter=ddi&' . $query;
        //https://dataverse.ideal.ufpb.br/citation?persistentId=doi:10.71650/DATAPB/YTSI2O
        //https://dataverse.ideal.ufpb.br/api/datasets/export?exporter=ddi&persistentId=doi:10.71650/DATAPB/YTSI2O

        $DOI = substr($url, strpos($url, 'doi:') + 4);
        $this->urlAPI = $apiUrl;

        $response = $DoiMetadataModel->httpGet($apiUrl);
        if (!$response) {
            return 'Erro ao acessar o Dataverse.';
        }

        // O endpoint DDI retorna XML, não JSON
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response);

        if ($xml === false) {
            return 'Erro ao processar resposta DDI (XML inválido).';
        }
        return $xml;
    }

    function index($d1,$d2)
        {
            $apikey = '';
            $tela = '';
            $tela .= '<div class="container">';
            $tela .= '<div class="row">';
            $tela .= '<form>';
            $tela .= '<label>API-KEY</label>';
            $tela .= '<input type="text" id="apikey" value="'.$apikey.'" class="form-control">';
            $tela .= '</form>';

            $tela .= '</div>';
            $tela .= '</div>';

            return $tela;
        }
}
