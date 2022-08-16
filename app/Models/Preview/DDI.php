<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class DDI extends Model
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
        if (isset($_GET['key'])) {
            $API_TOKEN = $_GET['key'];
        } else {
            $API_TOKEN = '';
        }
        $datasetId = $_GET['datasetId'];
        $fileid = $_GET['fileid'];

        /**** */
        //$SERVER_URL = 'http://localhost:8080';
        echo $SERVER_URL . '<hr>';

        $file = $SERVER_URL . '/api/datasets/export?exporter=ddi&persistentId='. $PERSISTENT_ID;
        $file = 'https://vitrinedadosabertos.rnp.br/api/datasets/export?exporter=ddi&persistentId=doi%3A10.34841/rnp/RILP3P';
        $file = $SERVER_URL . '/api/datasets/export?exporter=ddi&persistentId=' . $PERSISTENT_ID;
        echo $file;

        if (strlen($API_TOKEN) > 0) {
            $file .= '?key=' . $API_TOKEN;
        }

        $file = $Cache->download($file);
        $xml = (array)simplexml_load_file($file);

        pre($xml);

        $sx = '';
        $sx .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">' . chr(13);
        $sx .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>' . chr(13);
        $sx .= '' . chr(13);
        if (isset($xml['dataDscr']))
            {
                $var = (array)$xml['dataDscr'];
                $var = (array)$var['var'];
                for ($r=0;$r < count($var);$r++)
                    {
                        $line = (array)$var[$r];
                        $var_name = $line['labl'];
                        $sx .= '<hr>';
                        $sx .= h($var_name,4);

                        /********************* Variáveis */
                        if (isset($line['sumStat']))
                        {
                        $sumStat = (array)$line['sumStat'];
                        $sx .= '<table width="100%" style="border: 1px solid #000;">';
                        $sa = '';
                        $sb = '';
                        $sz = round(100 / 7);
                        for ($z=0;$z <= 7;$z++)
                            {
                                $sa .= '<th width="' . $sz . '" style="font-size: 50%">' . lang('dataview.sumSata_' . $z) . '</th>';
                                $vlr = $sumStat[$z];
                                if ($pos = strpos($vlr,'.'))
                                    {
                                        $vlr = substr($vlr,0,$pos+3);
                                    }
                                $sb .= '<td style="text-align: center" >' . $vlr . '</td>';
                            }
                        $sx .= '<tr>'.$sa.'</tr>';
                        $sx .= '<tr>' . $sb . '</tr>';
                        $sx .= '</table>';
                        } else {
                            //pre($line);
                        }
                    }
            }
        return $sx;
    }
}