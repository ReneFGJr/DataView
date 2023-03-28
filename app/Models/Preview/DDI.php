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

    function hichart_pie($div='grapho',$data=array())
    {
        if (count($data) == 0)
            {
                return "";
            }
        $js = "
            // Data retrieved from https://netmarketshare.com
            Highcharts.chart('$div', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: ''
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
                }
            },";
            $js .= "series: [{name: 'Brands',colorByPoint: true, data: [";
            $d=0;
            foreach($data as $id => $value)
                {
                    if ($d > 0) { $js .= ',';}
                    $js .= '{';
                    $js .= 'name: "'.$id.'",';
                    $js .= 'y: '.$value.',';
                    $js .= 'sliced: true,';
                    $js .= 'selected: true';
                    $js .= '}';
                    $d++;
                }
            $js .= '] }] });';

        $sx = '';
        $sx .= '<figure class="highcharts-figure-x"><div id="'.$div.'"></div></figure>';
        $sx .= '<script>' . $js . '</script>';

        return $sx;
    }

    function index()
    {
        $sx = '
        <script src="/js/highcharts.js"></script>
        /*
        <script src="/js/exporting.js"></script>
        <script src="/js/export-data.js"></script>
        <script src="/js/accessibility.js"></script>
        */
        ';

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
        $txt = file_get_contents($file);

        if (strpos(' ' . $txt, '{"')) {
            $json = json_decode($txt, true);
            $file = $json['message'];

            echo '<h1>ERRO</h1>';
            echo '<p>' . $json['message'] . '</p>';
            echo '<p style="font-size: 4em; font-color: red; text-align: center;">' . lang('O Dataset precisa estar publicado') . '</p>';
            exit;
        }

        $xml = (array)simplexml_load_file($file);

        $sva = '';
        $svb = '';
        $svba = array();
        if (isset($xml['dataDscr'])) {
            $var = (array)$xml['dataDscr'];
            $var = (array)$var['var'];
            for ($r = 0; $r < count($var); $r++) {
                $line2 = $var[$r];
                $line = (array)$var[$r];

                /************************************************* @ATTRIBUTeS */
                $attr = (array)$line['@attributes'];
                $ID = $attr['ID'];
                $svba[$ID] = '';

                /************************************************* @FORMAT */
                $form = (array)$line['varFormat'];
                $form = (array)$form['@attributes'];
                $type = $form['type'];

                switch ($form['type']) {
                    case 'numeric':
                        $icon = '<img title="' . lang('dataview.type_numeric') . ' ' .
                            '" src="' . base_url('img/icone/type_numeric.png') . '" height="20"
                        class="rounded float-end m-2  d-block"
                        >';
                        break;

                    case 'character':
                        $icon = '<img title="' . lang('dataview.character') . ' ' .
                            '" src="' . base_url('img/icone/type_charset.png') . '" height="20"
                        class="rounded float-end m-2  d-block">';
                        break;
                    default:
                        $icon = $form['type'];
                }
                $svba[$ID] .= $icon;

                /*************************************************** NAME VAR */
                $var_name = $line['labl'];

                $sva .= '<li onclick="" style="cursor: pointer;">' .
                    '<a href="#' . $ID . '">' .
                    $attr['name'] .
                    '</a>' .
                    '</li>';


                if (isset($line['labl'])) {
                    $labl = (string)$line['labl'];
                    $svba[$ID] .= '<h6>' . $labl . ' <sup>('. $type.')</sup>'.'<br>' .
                        '<i>' . $attr['name'] . '</i> <sup>(' . $ID . ')</sup></h6>';
                } else {
                    $svba[$ID] .= '<h6><i>' . $attr['name'] . '</i><sup>(' . $ID . ')</sup></h6>';
                }

                /**************************************** varFormat */



                /************************************************* @LOCATION */
                $location = (array)$line['location'];
                $location = (array)$location['@attributes'];
                $location = (string)$location['fileid'];

                /************************************************* @CATEGORY */
                $catr = array();
                if (isset($line['catgry'])) {
                    $catg = (array)$line['catgry'];
                    if (!isset($catg[0])) {
                        $catg = array($catg);
                    }

                    for ($q = 0; $q < count($catg); $q++) {
                        $catgi = (array)$catg[$q];
                        if (!isset($catgi['labl'])) {
                            $catgi['labl'] = '';
                        }
                        $catr[$catgi['catValu'] . ' ' . $catgi['labl']] = $catgi['catStat'];
                    }
                }


                /************************************* DADOS */
                $tot = 0;
                $dta = array();
                $sx .= '<table class="table" style="border: 1px solid #000; width: 100%;">';
                foreach ($catr as $key => $value) {
                    $tot = $tot + $value;
                    $dta[$key] = $value;
                }

                /* Show Categories */
                if (count($dta) > 0) {
                    pre($dta);
                }


                if (count($dta) > 0)
                {
                    /*
                    $sx .= '<tr><td colspan=3"><td>'.
                    $this->hichart_pie('div_'.($tot++),$data).
                    '</td></tr>';
                    */
                }

                /********************************* HIGHCHART */
                /*
                $sx .= '<tr><td colspan=3">'.
                    $this->hichart_pie('div_'.($tot++), $dta).
                    '</td></tr>'.cr();
                */

                foreach ($catr as $key => $value) {
                    $sx .= '<tr>';
                    $sx .= '<td>';
                    $sx .= $key;
                    $sx .= '</td><td class="text-end">';
                    $sx .= number_format($value, 0, ',', '.');
                    $sx .= '</td><td class="text-end">';
                    if ($tot > 0)
                        {
                            $sx .= number_format($value / $tot * 100, 1, ',', '.') . '%';
                        } else {
                            $sx .= '-';
                        }

                    $sx .= '</td>';
                    $sx .= '</tr>';
                }
                $sx .= '<tr><td colspan=2 class="text-end"><b>Total</b> ' . number_format($tot, 0, ',', '.') . '</td></tr>';
                $sx .= '</table>' . cr();

                if ($tot > 0) {
                    $svba[$ID] .= $sx;
                }


                /**************************************************** CATEGORY */
                if (isset($form['category'])) {
                    switch ($form['formatname']) {
                        case 'yyyy-MM-dd':
                            $sx .= '<img title="' . lang('dataview.date') . '" src="' . base_url('img/icone/format_date.png') . '" height="20">';
                            $sx .= '<sup>(yyyy-MM-dd)</sup>';
                            break;
                        default:
                            $sx .= '- Category: <i>' . $form['category'] . '</i> ';
                            $sx .= ' - Format: <i>' . $form['formatname'] . '</i> ';
                            break;
                    }
                }

                /***************************************** Sumário Estatístico */
                if (isset($line['sumStat'])) {
                    $v = array(
                        'min' => 0, 'max' => 0, 'mean' => 0,
                        'median' => 0, 'mode' => 0, 'stdev' => 0,
                        'variance' => 0, 'medn' => 0, 'invd' => 0,
                        'vald' => 0
                    );
                    foreach ($line2->sumStat as $vlr) {
                        $type = (array)$vlr;
                        $data = $type[0];
                        $type = $type['@attributes'];
                        $type = $type['type'];
                        $v[$type] = $data;
                    }

                    /********************* Variáveis */
                    $sumStat = (array)$line2['sumStat'];
                    $sx .= '<table width="100%" style="border: 1px solid #000;">';
                    $sa = '';
                    $sb = '';
                    $sz = round(100 / 10);
                    foreach ($v as $key => $value) {
                        $sa .= '<td width="' . $sz . '%" class="text-center">';
                        switch ($key) {
                            case 'invd':
                                $sa .= '<b>' . number_format($value, 0, ',', '.') . '</b>';
                                break;
                            case 'vald':
                                $sa .= '<b>' . number_format($value, 0, ',', '.') . '</b>';
                                break;
                            case 'max':
                                if ($value == 'NaN') {
                                    $vlr = 'null';
                                } else {
                                    $vlr = number_format($value, 2, ',', '.');
                                    $vlr = troca($vlr, ',00', '');
                                    $sa .= '<b>' . $vlr . '</b>';
                                }
                                break;
                            case 'min':
                                if ($value == 'NaN') {
                                    $vlr = 'null';
                                } else {
                                    $vlr = number_format($value, 2, ',', '.');
                                    $vlr = troca($vlr, ',00', '');
                                }
                                $sa .= '<b>' . $vlr . '</b>';
                                break;
                            default:
                                if ($value == '.') {
                                    $sa .= '.';
                                } else {
                                    if ($value == 'NaN') {
                                        $vlr = 'null';
                                        $sa .= '<b>' . $vlr . '</b>';
                                    } else {
                                        $sa .= '<b>' . number_format($value, 2, ',', '.') . '</b>';
                                    }
                                }
                                break;
                        }
                        $sa .= '</td>';
                        $sb .= '<td width="' . $sz . '%" class="text-center small">';
                        $sb .= lang('dataview.' . $key);
                        $sb .= '</td>';
                    }
                    $sx = '<table width="100%" style="border: 1px solid #000;">';
                    $sx .= '<tr>' . $sb . '</tr>';
                    $sx .= '<tr>' . $sa . '</tr>';
                    $sx .= '</table>';
                    $svba[$ID] .= $sx;
                }
            }
        }

        $sva = '<ol class="p-0">' . $sva . '</ol>';

        foreach ($svba as $id => $txt) {
            $svb .= '<a name="' . $id . '"></a>';
            $svb .= '<div id="' . $id . '" style="width: 100%;" class="mt-5 col-12 col-md-6 col-lg-4">' . cr();
            $svb .= $txt;
            $svb .= '</div>';
            $svb .= cr();
        }

        $sx = '';
        $sx .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">' . chr(13);
        $sx .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>' . chr(13);
        $sx .= '' . chr(13);
        $sx .= bsc($sva, 4);
        $sx .= bsc($svb, 8);
        //$sx .= '<style>div { border: 1px solid #00f; } </style>';
        $sx = bs($sx);

        return $sx;
    }
}
