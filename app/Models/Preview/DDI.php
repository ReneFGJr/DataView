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

        $file = $SERVER_URL . '/api/datasets/export?exporter=ddi&persistentId=' . $PERSISTENT_ID;
        $file = troca($file,'doi:', 'doi%3A');

        if (strlen($API_TOKEN) > 0) {
            $file .= '&key=' . $API_TOKEN;
        }

        $file = $Cache->download($file);
        $txt = file_get_contents($file);

        if (strpos(' '. $txt,'{"'))
            {
                $json = json_decode($txt, true);
                $file = $json['message'];

                echo '<h1>ERRO</h1>';
                echo '<p>'. $json['message'].'</p>';
                echo '<p style="font-size: 4em; font-color: red; text-align: center;">'.lang('O Dataset precisa estar publicado').'</p>';
                exit;
            }

        $xml = (array)simplexml_load_file($file);
//pre($xml);
        $sx = '';
        $sx .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">' . chr(13);
        $sx .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>' . chr(13);
        $sx .= '' . chr(13);
        if (isset($xml['dataDscr'])) {
            $var = (array)$xml['dataDscr'];
            $var = (array)$var['var'];
            for ($r = 0; $r < count($var); $r++) {
                $line = (array)$var[$r];
                $var_name = $line['labl'];

                /************************************************* @ATTRIBUTeS */
                $attr = (array)$line['@attributes'];
                $sx .= '<hr>';
                $sx .= 'Variable Name: '.$attr['name'].' ('. $attr['ID'].')';


                /************************************************* @LOCATION */
                $location = (array)$line['location'];
                $location = (array)$location['@attributes'];
                $location = (string)$location['fileid'];

                /************************************************* @CATEGORY */
                $catr = array();
                if (isset($line['catgry']))
                {
                $catg = (array)$line['catgry'];
                for ($q=0;$q < count($catg);$q++)
                    {
                        $catgi = (array)$catg[$q];
                        $catr[$catgi['catValu'].' '. $catgi['labl']] = $catgi['catStat'];
                    }
                }
                /************************************************* @FORMAT */
                foreach ($catr as $key => $value) {
                    $sx .= '<br>';
                    $sx .= $key . ' = ' . $value;
                }

                $form = (array)$line['varFormat'];
                $form = (array)$form['@attributes'];
                $sx .= '<br>';
                $sx .= 'Form type: <i>'.$form['type']. '</i> ';

                if (isset($form['category']))
                    {
                        $sx .= '- Category: <i>'.$form['category']. '</i> ';
                        $sx .= ' - Format: <i>' . $form['formatname'] . '</i> ';
                    }


                if (isset($line['sumStat'])) {
                    $sx .= h($var_name, 6);

                    /********************* Variáveis */

                    $sumStat = (array)$line['sumStat'];
                    $sx .= '<table width="100%" style="border: 1px solid #000;">';
                    $sa = '';
                    $sb = '';
                    $sz = round(100 / 7);
                    for ($z = 0; $z <= 7; $z++) {
                        $sa .= '<th width="' . $sz . '" style="font-size: 50%">' . lang('dataview.sumSata_' . $z) . '</th>';
                        $vlr = $sumStat[$z];
                        if ($pos = strpos($vlr, '.')) {
                            $vlr = substr($vlr, 0, $pos + 3);
                        }
                        $sb .= '<td width="12%" style="text-align: center; border: 1px solid #000;" >' . $vlr . '</td>';
                    }
                    $sx .= '<tr>' . $sa . '</tr>';
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
