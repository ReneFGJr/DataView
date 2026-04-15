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

    /**
     * Busca e faz parse de dados DDI do repositório Dataverse
     */
    public function fetchDDIData($persistentId, $dataverseUrl = 'https://dataverse.ideal.ufpb.br')
    {
        if (empty($persistentId)) {
            return [
                'success' => false,
                'error' => 'ID persistente não fornecido',
                'ddiUrl' => '',
                'metadata' => [],
                'study' => [],
                'files' => [],
            ];
        }

        // Tentar URL customizada primeiro, depois URL padrão
        $urls = [
            $dataverseUrl . '/api/datasets/export?exporter=ddi&persistentId=' . rawurlencode($persistentId),
            'https://dataverse.ideal.ufpb.br/api/datasets/export?exporter=ddi&persistentId=' . rawurlencode($persistentId),
            'https://venus.brapci.inf.br/api/datasets/export?exporter=ddi&persistentId=' . rawurlencode($persistentId),
        ];

        $ddiRaw = null;
        $ddiUrl = '';

        foreach ($urls as $url) {
            $ddiRaw = @file_get_contents(
                $url,
                false,
                stream_context_create([
                    'http' => [
                        'timeout' => 15,
                        'user_agent' => 'DataView/1.0',
                    ],
                ])
            );
            if ($ddiRaw !== false && !empty($ddiRaw)) {
                $ddiUrl = $url;
                break;
            }
        }

        if ($ddiRaw === false || empty($ddiRaw)) {
            return [
                'success' => false,
                'error' => 'Não foi possível obter os dados DDI neste momento',
                'ddiUrl' => $ddiUrl,
                'metadata' => [],
                'study' => [],
                'files' => [],
            ];
        }

        libxml_use_internal_errors(true);
        $ddiXml = simplexml_load_string($ddiRaw);

        if ($ddiXml === false) {
            return [
                'success' => false,
                'error' => 'Resposta DDI inválida para este dataset',
                'ddiUrl' => $ddiUrl,
                'metadata' => [],
                'study' => [],
                'files' => [],
            ];
        }

        return $this->parseDDIXml($ddiXml, $ddiUrl);
    }

    /**
     * Parse um XML DDI já carregado (SimpleXMLElement)
     */
    public function parseDDIXml($ddiXml, $ddiUrl = '')
    {
        if ($ddiXml === null || $ddiXml === false) {
            return [
                'success' => false,
                'error' => 'XML DDI inválido',
                'ddiUrl' => $ddiUrl,
                'metadata' => [],
                'study' => [],
                'files' => [],
            ];
        }

        $metadata = $this->parseDocDscr($ddiXml);
        $study = $this->parseStdyDscr($ddiXml);
        $files = $this->parseDDIFiles($ddiXml);

        return [
            'success' => true,
            'ddiUrl' => $ddiUrl,
            'metadata' => $metadata,
            'study' => $study,
            'files' => $files,
        ];
    }

    /**
     * Parse de metadados do documento (docDscr)
     */
    private function parseDocDscr($ddiXml)
    {
        $metadata = [
            'title' => '',
            'doi' => '',
            'distributor' => '',
            'date' => '',
            'version' => '',
            'citation' => '',
        ];

        $docDscr = $ddiXml->xpath('//*[local-name()="docDscr"]');
        if (empty($docDscr)) {
            return $metadata;
        }

        $docNode = $docDscr[0];

        // Título
        $titleNode = $docNode->xpath('.//*[local-name()="titl"]');
        if (!empty($titleNode)) {
            $metadata['title'] = (string) $titleNode[0];
        }

        // DOI
        $idnoNode = $docNode->xpath('.//*[local-name()="IDNo"]');
        foreach ($idnoNode as $node) {
            if ((string) $node === (string) $node && strpos((string) $node, '10.') !== false) {
                $metadata['doi'] = (string) $node;
                break;
            }
        }

        // Distribuidor e Data
        $distStmt = $docNode->xpath('.//*[local-name()="distStmt"]');
        if (!empty($distStmt)) {
            $distrbtr = $distStmt[0]->xpath('.//*[local-name()="distrbtr"]');
            if (!empty($distrbtr)) {
                $metadata['distributor'] = (string) $distrbtr[0];
            }
            $distDate = $distStmt[0]->xpath('.//*[local-name()="distDate"]');
            if (!empty($distDate)) {
                $metadata['date'] = (string) $distDate[0];
            }
        }

        // Versão
        $verStmt = $docNode->xpath('.//*[local-name()="verStmt"]');
        if (!empty($verStmt)) {
            $version = $verStmt[0]->xpath('.//*[local-name()="version"]');
            if (!empty($version)) {
                $metadata['version'] = (string) $version[0];
            }
        }

        // Citação
        $biblCit = $docNode->xpath('.//*[local-name()="biblCit"]');
        if (!empty($biblCit)) {
            $metadata['citation'] = (string) $biblCit[0];
        }

        return $metadata;
    }

    /**
     * Parse de descrição do estudo (stdyDscr)
     */
    private function parseStdyDscr($ddiXml)
    {
        $study = [
            'title' => '',
            'authors' => [],
            'abstract' => '',
            'period' => '',
            'time_period' => '',
            'methods' => '',
        ];

        $stdyDscr = $ddiXml->xpath('//*[local-name()="stdyDscr"]');
        if (empty($stdyDscr)) {
            return $study;
        }

        $studyNode = $stdyDscr[0];

        // Título do estudo
        $titleNode = $studyNode->xpath('.//*[local-name()="titl"]');
        if (!empty($titleNode)) {
            $study['title'] = (string) $titleNode[0];
        }

        // Responsáveis/Autores
        $rspStmt = $studyNode->xpath('.//*[local-name()="rspStmt"]');
        if (!empty($rspStmt)) {
            $authEnties = $rspStmt[0]->xpath('.//*[local-name()="AuthEnty"]');
            foreach ($authEnties as $auth) {
                $authAttrs = $auth->attributes();
                $affiliation = (string) ($authAttrs['affiliation'] ?? 'N/A');
                $study['authors'][] = [
                    'name' => (string) $auth,
                    'affiliation' => $affiliation,
                ];
            }
        }

        // Abstract
        $abstract = $studyNode->xpath('.//*[local-name()="abstract"]');
        if (!empty($abstract)) {
            $study['abstract'] = (string) $abstract[0];
        }

        // Período de coleta
        $timePrd = $studyNode->xpath('.//*[local-name()="timePrd"]');
        if (!empty($timePrd)) {
            $study['time_period'] = (string) $timePrd[0];
        }

        // Período de referência
        $collPrd = $studyNode->xpath('.//*[local-name()="collPrd"]');
        if (!empty($collPrd)) {
            $attrs = $collPrd[0]->attributes();
            if (isset($attrs['date'])) {
                $study['period'] = (string) $attrs['date'];
            } else {
                $study['period'] = (string) $collPrd[0];
            }
        }

        // Métodos
        $method = $studyNode->xpath('.//*[local-name()="method"]');
        if (!empty($method)) {
            $methods = $method[0]->xpath('.//*[local-name()="dataColl"]');
            if (!empty($methods)) {
                $study['methods'] = (string) $methods[0];
            }
        }

        return $study;
    }

    /**
     * Parse dos arquivos e variáveis do DDI
     * As variáveis estão em dataDscr e são associadas aos arquivos via location fileid
     */
    private function parseDDIFiles($ddiXml)
    {
        $files = [];
        $fileNodes = $ddiXml->xpath('//*[local-name()="fileDscr"]') ?: [];

        // Primeiro, extrair todos os arquivos
        foreach ($fileNodes as $fileNode) {
            $attrs = $fileNode->attributes();
            $fileId = (string) ($attrs['ID'] ?? $attrs['id'] ?? '');

            $fileNameNode = $fileNode->xpath('.//*[local-name()="fileName"]');
            $fileTypeNode = $fileNode->xpath('.//*[local-name()="fileType"]');
            $caseCountNode = $fileNode->xpath('.//*[local-name()="caseQnty"]');
            $varCountNode = $fileNode->xpath('.//*[local-name()="varQnty"]');

            $file = [
                'id' => $fileId,
                'name' => (string) ($fileNameNode[0] ?? '-'),
                'type' => (string) ($fileTypeNode[0] ?? '-'),
                'cases' => (string) ($caseCountNode[0] ?? '-'),
                'vars' => (string) ($varCountNode[0] ?? '-'),
                'variables' => [],
            ];

            $files[$fileId] = $file;
        }

        // Depois, extrair as variáveis de dataDscr e associá-las aos arquivos
        $dataDescr = $ddiXml->xpath('//*[local-name()="dataDscr"]');
        if (!empty($dataDescr)) {
            $varNodes = $dataDescr[0]->xpath('.//*[local-name()="var"]') ?: [];

            foreach ($varNodes as $varNode) {
                $variable = $this->parseVariableNode($varNode);

                // Encontrar o arquivo associado via location fileid
                $locationNode = $varNode->xpath('.//*[local-name()="location"]');
                if (!empty($locationNode)) {
                    $locAttrs = $locationNode[0]->attributes();
                    $fileId = (string) ($locAttrs['fileid'] ?? $locAttrs['ID'] ?? '');

                    // Associar variável ao arquivo correto
                    if ($fileId && isset($files[$fileId])) {
                        $files[$fileId]['variables'][] = $variable;
                    }
                }
            }
        }

        // Retornar como array indexado numericamente
        return array_values($files);
    }

    /**
     * Parse de um nó de variável individual
     */
    private function parseVariableNode($varNode)
    {
        $attrs = $varNode->attributes();
        $varId = (string) ($attrs['ID'] ?? $attrs['id'] ?? '');
        $varName = (string) ($attrs['name'] ?? '');

        $nameNode = $varNode->xpath('.//*[local-name()="labl"]');
        $typeNode = $varNode->xpath('.//*[local-name()="varFormat"]');
        $varTypeAttr = $typeNode[0]->attributes() ?? null;
        $varType = isset($varTypeAttr['type']) ? (string) $varTypeAttr['type'] : 'character';

        // Obter localização das categorias de codificação
        $catgryNodes = $varNode->xpath('.//*[local-name()="catgry"]') ?: [];
        $categories = [];
        foreach ($catgryNodes as $catgry) {
            $catValNode = $catgry->xpath('.//*[local-name()="catValu"]');
            $catTxtNode = $catgry->xpath('.//*[local-name()="labl"]');
            if (!empty($catValNode) && !empty($catTxtNode)) {
                $categories[] = [
                    'value' => (string) $catValNode[0],
                    'label' => (string) $catTxtNode[0],
                ];
            }
        }

        // Obter estatísticas simples
        $summStatNode = $varNode->xpath('.//*[local-name()="sumStat"]');
        $stats = [];
        foreach ($summStatNode as $stat) {
            $statAttr = $stat->attributes() ?? null;
            if (isset($statAttr['type'])) {
                $statType = (string) $statAttr['type'];
                $statValue = (string) $stat;
                $stats[$statType] = $statValue;
            }
        }

        return [
            'id' => $varId,
            'name' => $varName,
            'label' => (string) ($nameNode[0] ?? $varName),
            'type' => $varType,
            'categories' => $categories,
            'stats' => $stats,
        ];
    }

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

        $th = '<tr style="font-size: 0.6em; background: #ddd;">
            <th>Cat.</th>
            <th>Descição</th>
            <th>Valor</th>
            <th>Perc.</th>
            </tr>';

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
                    $svba[$ID] .= '<span class="fw-bold" style="font-size: 1.2em;">' . $labl . '</span>' .
                        '<br>'.
                        '<span class="small" style="font-size: 0.7em;">' . $attr['name'] . ' <sup>(' . $ID . ','. $type. ')</sup></span>';
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
                        $catr[$catgi['catValu'] . ';' . $catgi['labl']] = $catgi['catStat'];
                    }
                }


                /************************************* DADOS */
                $tot = 0;
                $dta = array();
                $sc = '<table class="table" style="border: 1px solid #000; width: 100%;">';
                foreach ($catr as $key => $value) {
                    $tot = $tot + $value;
                    $dta[$key] = $value;
                }

                ksort($dta);

                /* Show Categories */
                $totc = count($dta);
                if ($totc > 0) {
                    $sc .= $th;
                    foreach ($dta as $cname => $cvalue) {
                        $ccol = explode(';', $cname);
                        $sc .= '<tr>';
                        $sc .= '<td width="5%" class="text-center"style="font-size: 0.7em"><nobr>' . $ccol[0] . '</nobr></td>';
                        $sc .= '<td width="70%" style="font-size: 0.7em">' . $ccol[1] . '</td>';
                        $sc .= '<td width="10%" class="text-end" style="font-size: 0.7em"><nobr>' . $cvalue . '</nobr></td>';
                        if ($tot > 0)
                            {
                                $sc .= '<td width="10%" class="text-end" style="font-size: 0.8em"><nobr>' . number_format($cvalue/$tot*100,1,'.',',') . '%</nobr></td>';
                            } else {
                                $sc .= '<td width="20%" class="text-center" style="font-size: 0.8em"> - </td>';
                            }
                        $sc .= '</tr>';
                    }
                }

                IF ($tot > 0)
                    {
                        $sc .= '<tr><td colspan=3 class="text-end" style="font-size: 0.9em"><b>Total</b> <b>' . number_format($tot, 0, ',', '.') . '</b></td></tr>';
                    }
                $sc .= '</table>' . cr();


                if ($totc > 0) {
                    $sx .= $sc;
                    $svba[$ID] .= $sc;
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
