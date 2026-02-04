<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class TAB extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'pdfs';
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

        $API_TOKEN = $_GET['key'] ?? '';
        $datasetId = $_GET['datasetId'];
        $fileid = $_GET['fileid'];

        $file = $SERVER_URL . '/api/access/datafile/' . $fileid;

        if (strlen($API_TOKEN) > 0) {
            $file .= '?key=' . $API_TOKEN;
        }

        $file = $Cache->download($file);
        
        if (!file_exists($file)) {
            exit('Erro ao baixar o arquivo.');
        }

        $limit = 20;
        $ln = 0;
        $data = array();

        if ($file = fopen($file, "r")) {
            while (!feof($file)) {

                $line = trim(fgets($file));

                // remove aspas externas
                if (substr($line, 0, 1) == '"') {
                    $line = substr($line, 1, strlen($line) - 2);
                }

                if ($ln == 0) {
                    // troca TAB por ;
                    $line = str_replace("\t", ';', $line);
                    $header = explode(";", $line);
                } else {
                    $data[] = $line;
                }

                $ln++;
                if (($limit--) < 0) {
                    break;
                }
            }

            fclose($file);

            /************************ Mapa GEO */
            $GEO = new \App\Models\Preview\GEO();
            if ($GEO->hasGeo($header)) {
                $mapa = $GEO->index();
            } else {
                $mapa = null;
            }

            echo view('render/table', [
                'header' => $header,
                'data'   => $data,
                'limit'  => $limit,
                'mapa'   => $mapa,
            ]);            
        }
            
    }

    function download()
    {
        $DataViewer = new \App\Models\DataViewer();
        $dv = $DataViewer->getPOST();
        $filename = 'tab_' . date("YmdHi") . '.pdf';

        $url = $dv['siteUrl'];
        if (substr($url, strlen($url) - 1, 1) != '/') {
            $url .= '/';
        }

        $url .= 'api/access/datafile/' . $dv['fileid'];
        $file = md5($url);

        if (!file_exists('.tmp')) {
            mkdir('.tmp');
        }
        if (!file_exists('.tmp/.tmp')) {
            mkdir('.tmp/.tmp');
        }

        $file = '.tmp/.tmp/' . $file;

        if (!file_exists($file)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $st = curl_exec($ch);
            curl_close($ch);

            $fd = fopen($file, 'w');
            fwrite($fd, $st);
            fclose($fd);
        }

        return $file;
    }
}
