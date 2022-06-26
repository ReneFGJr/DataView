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
        $SERVER_URL = $_GET['siteUrl'];
        $PERSISTENT_ID = $_GET['PID'];
        $API_TOKEN = $_GET['key'];
        $datasetId = $_GET['datasetId'];
        $fileid = $_GET['fileid'];

        $file = $SERVER_URL . '/api/access/datafile/' . $fileid . '?key=' . $API_TOKEN;
        $limit = 20;
        $ln = 0;
        $data = array();
        if ($file = fopen($file, "r")) {
            while (!feof($file)) {
                $line = trim(fgets($file));
                if (substr($line,0,1) == '"')
                    {
                        $line = substr($line,1,strlen($line)-2);
                    }
                if ($ln == 0) {
                    $header = $line;
                } else {
                    array_push($data,$line);
                }
                $ln++;
                if (($limit--) < 0) {
                    break;
                }
            }
            fclose($file);

            /****************** VIEW */
            $header = explode(';',$header);
            $sx = '';
            $sx .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">'.chr(13);
            $sx .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>'.chr(13);
            $sx .= ''.chr(13);
            $sx .= ''.chr(13);
            $sx .= ''.chr(13);
            $sx .= '<table class="table" class="table table-striped table-hover">';
            $sx .= '<tr>';
            for ($r=0;$r < count($header);$r++)
                {
                    $sx .= '<th>'.$header[$r].'</th>'.chr(13);
                }
            $sx .= '</tr>';

            /************************** DATA */
            $sx .= '<tr>';
            for ($q=0;$q < count($data);$q++)
            {
                $line = $data[$q];
                $line = explode(';',$line);
                for ($r=0;$r < count($line);$r++)
                    {
                        $sx .= '<th>'.$line[$r].'</th>'.chr(13);
                    }
                $sx .= '</tr>';
            }
            $sx .= '</table>';
            if ($limit < 0)
                {
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