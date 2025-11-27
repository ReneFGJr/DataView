<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class STL extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'stl';
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

    function index()
    {
        $Cache = new \App\Models\IO\Cache();

        $SERVER_URL = $_GET['siteUrl'];
        $PERSISTENT_ID = $_GET['PID'];
        if (isset($_GET['key'])) {
            $API_TOKEN = $_GET['key'];
        } else {
            $API_TOKEN = '';
        }
        $datasetId = $_GET['datasetId'];
        $fileid = $_GET['fileid'];

        /**** */
        //$SERVER_URL = 'http://localhost:8080';

        $file = $SERVER_URL . '/api/datasets/export?exporter=ddi&persistentId=' . $PERSISTENT_ID;
        $file = troca($file, 'doi:', 'doi%3A');

        if (strlen($API_TOKEN) > 0) {
            $file .= '&key=' . $API_TOKEN;
        }

        $file = $Cache->download($file);

        echo strlen($file);
    }

}