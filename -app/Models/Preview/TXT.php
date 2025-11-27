<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class TXT extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tsvs';
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

        $file = $SERVER_URL . '/api/access/datafile/' . $fileid;
        if (strlen($API_TOKEN) > 0) {
            $file .= '?key=' . $API_TOKEN;
        }

        $file = $Cache->download($file);


        $limit = 20;
        $ln = 0;
        $sx = '';
        $data = array();
        if ($file = fopen($file, "r")) {

            while (!feof($file)) {
                $line = trim(fgets($file));
                $sx .= $line.'<br>';
                $ln++;
                if (($limit--) < 0) {
                    break;
                }
            }
            fclose($file);

            /************************** DATA */


            if ($limit < 0) {
                $sx .= '
                    <div class="alert alert-danger" role="alert">
                    Esta vizualização apresenta uma amostra das primeiras 100 linhas
                    </div>';
            }
            echo $sx;
        }
    }

    function download()
    {
        $DataViewer = new \App\Models\DataViewer();
        $dv = $DataViewer->getPOST();
        $filename = 'tab_' . date("YmdHi") . '.pdf';
        $url = $dv['siteUrl'];
        if (substr($url, strlen($url), 1) != '/') {
            $url = $url . '/';
        }
        $url = $url . 'api/access/datafile/' . $dv['fileid'];
        $file = md5($url);
        if (!file_exists('.tmp/.')) {
            mkdir('.tmp');
        }
        if (!file_exists('.tmp/.tmp/.')) {
            mkdir('.tmp/.tmp');
        }
        $file = '.tmp/.tmp/' . $file;

        if (!file_exists(($file))) {
            $ch = curl_init();
            echo $url;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $st = curl_exec($ch);
            $fd = fopen($file, 'w');
            fwrite($fd, $st);
            fclose($fd);
            curl_close($ch);

            $txt = file_get_contents($url);
            file_put_contents($file, $txt);
        }
        return ($file);
    }
}
