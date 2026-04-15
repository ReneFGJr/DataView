<?php

namespace App\Controllers;

use App\Controllers\BaseController;

helper(['boostrap', 'url', 'form', 'sisdoc_forms']);
$this->session = \Config\Services::session();
$language = \Config\Services::language();


define("PATH", getenv("app.baseURL") . getenv("app.baseURLprefix"));
define("URL", getenv("app.baseURL"));
define("MODULE", 'dataview');

class Dataview extends BaseController
{
    public function content($txt = '')
    {
        $DataViewer = new \App\Models\DataViewer();
        //$sx = $DataViewer->index();
        $sx = view('page/homepage', ['txt' => $txt]);
        return $sx;
    }

    function index()
    {
        $sx = $this->content('ONLINE');
        return $sx;
    }

    function open()
    {
        $sx = '';
        $OPEN = new \App\Models\Forms\DOI();
        $Codebook = new \App\Models\Preview\Codebook();

        $url = get("url");
        $doi = get("doi");
        if ((substr($url, 0, 4) == 'http') and ($doi != '')) {
            $sx .= $Codebook->show($url, $doi);
        } else {
            $sx .= $this->content($OPEN->form());
        }
        return $sx;
    }

    function clear()
    {
        $Cache = new \App\Models\IO\Cache();
        $nr = $Cache->clear();
        $sx = $this->content('<center>' . bsmessage($nr . ' cache cleared.' . '</center>', 1));
        return $sx;
    }

    function file($id = '')
    {
        $FILE = new \App\Models\Preview\File();
        $this->file($id);
    }

    function stf($id = '')
    {
        $FILE = new \App\Models\Preview\File();
        $this->file($id);
    }

    function pdf()
    {
        $PDF = new \App\Models\Preview\PDF();
        $PDF->view();
        return "Erro open file";
    }

    function help($d1 = '', $d2 = '', $d3 = '', $d4 = '')
    {
        $sx = view('header/header');
        $sx .= bs(bsc(h('Help'), 12));
        $sx .= bs(bsc('<a href="https://github.com/ReneFGJr/DataView">See Documentation</a>'));
        return $sx;
    }

    function cab()
    {
        $sx = '';
        $sx .= '
            <!-- CSS only -->
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
            <!-- JavaScript Bundle with Popper -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
            ';
        return $sx;
    }

    function dataset()
    {
        $DataViewer = new \App\Models\DataViewer();
        $DDImodel = new \App\Models\DDI();
        $DoiMetadataModel = new \App\Models\DoiMetadataModel();
        $sx = '';

        $doi = $this->request->getVar("doi");
        if ($doi != '') {

            if (strpos($doi,'doi.org')){

                $result = $DoiMetadataModel->fetchDoiMetadata($doi);
                if (isset($result['metadata'])) {
                    if (isset($result['metadata']['url'])) {
                        $url = $result['metadata']['url'];
                        $sx = $this->cab();
                        $sx .= $DoiMetadataModel->fetchFromDataverse($url);
                    }
                } else {
                    $sx = $this->cab();
                    $sx .= view('widget/doi/datacite_info', [
                        'source'   => $result['source'],
                        'doi'      => $result['doi'],
                        'metadata' => $result['metadata']
                    ]);
                }
            } else {
                /**************** URL/DOI direto do Dataverse */
                $result = $DDImodel->fetchFromDDI($doi);

                // Processar dados DDI coletados
                if (!empty($result)) {
                    $ddiData = $this->processDDIData($result);
                    pre($ddiData);

                    if ($ddiData['success'] && isset($ddiData['url'])) {
                        $sx = $this->cab();
                        $sx .= $DoiMetadataModel->fetchFromDataverse($ddiData['url']);
                    } else {
                        $sx = $this->cab();
                        $sx .= view('widget/error', [
                            'message' => 'Não foi possível processar os dados DDI'
                        ]);
                    }
                } else {
                    $sx = $this->cab();
                    $sx .= view('widget/error', [
                        'message' => 'Erro ao obter dados do repositório'
                    ]);
                }
            }
        } else {
            $sx = $this->cab();
            $sx .= view('widget/dataset');
        }
        return $sx;
    }

    function sample()
    {
        $DataViewer = new \App\Models\DataViewer();
        $sx = $this->cab();
        $sx .= view('widget/samples');
        return $sx;
    }

    function view($content = '', $d1 = '', $d2 = '', $d3 = '')
    {
        $sx = '';
        switch ($content) {

            case 'jpg':
                $DataViewer = new \App\Models\Preview\Image();
                echo $DataViewer->index();
                break;

            case 'png':
                $DataViewer = new \App\Models\Preview\Image();
                echo $DataViewer->index();
                break;

            case 'tiff':
                $DataViewer = new \App\Models\Preview\Image();
                echo $DataViewer->index();
                break;

            case 'tab':
                $DataViewer = new \App\Models\Preview\TAB();
                echo $DataViewer->index();
                break;

            case 'geo':
                $DataViewer = new \App\Models\Preview\GEO();
                echo $DataViewer->index();
                break;

            case 'txt':
                $DataViewer = new \App\Models\Preview\TXT();
                echo $DataViewer->index();
                break;

            case 'ddi':
                $DataViewer = new \App\Models\Preview\DDI();
                echo $DataViewer->index();
                break;

            case 'stl':
                $DataViewer = new \App\Models\Preview\STL();
                echo $DataViewer->index();
                break;

            case 'form':
                $DataViewer = new \App\Models\DataViewer();
                $sx .= $this->cab();
                $sa = $DataViewer->formView();
                $sx .= bs(bsc($sa, 12));
                break;

            case 'pdf':
                $PDF = new \App\Models\Preview\PDF();
                $sx = $PDF->view($d1, $d2, $d3);
                break;

            default:
                $sx = '';
                $sx .=  '<div class="container-fluid">';
                $sx .=  '<div class="row">';
                $sx .=  '<div class="col-6" style="background-color: #830705;">';
                $sx .=  '<img src="/img/logo/logo_dataview-pb.png" style="width: 50%">';
                $sx .=  '</div>';
                $sx .=  '<div class="col-6" style="background-color: #830705;">';
                $sx .=  "XXXXXXXXXXXX";
                $sx .=  '</div>';
                $sx .=  '</div>';
                $sx .=  '</div>';
                $sx .=  '<pre>';
                $sx .=  $content;
                return $sx;
                break;
        }
        return $sx;
    }

    function test($d1 = '', $d2 = '', $d3 = '') {}

    function admin($d1 = '', $d2 = '', $d3 = '')
    {
        $DataViewerAdmin = new \App\Models\DataViewerAdmin();
        $tela = '';
        $tela .= '<div class="container">';
        $tela .= '<div class="row">';
        $tela .= '<h1>ADMIN</h1>';
        $tela .= '</div>';
        $tela .= '</div>';
        $tela .= $DataViewerAdmin->index($d1, $d2, $d3);
        return $tela;
    }

    /**
     * Processa dados DDI (SimpleXMLElement) e retorna estrutura padronizada
     */
    private function processDDIData($ddiXml)
    {
        if (empty($ddiXml)) {
            return [
                'success' => false,
                'error' => 'XML DDI vazio',
                'url' => ''
            ];
        }

        // Extrair informações do docDscr
        $title = '';
        $doi = '';
        $publisher = '';
        $year = '';

        if (isset($ddiXml->docDscr)) {
            $docDscr = $ddiXml->docDscr;

            if (isset($docDscr->citation->titlStmt->titl)) {
                $title = (string) $docDscr->citation->titlStmt->titl;
            }

            if (isset($docDscr->citation->titlStmt->IDNo)) {
                $doi = (string) $docDscr->citation->titlStmt->IDNo;
            }

            if (isset($docDscr->citation->distStmt->distrbtr)) {
                $publisher = (string) $docDscr->citation->distStmt->distrbtr;
            }

            if (isset($docDscr->citation->distStmt->distDate)) {
                $dateStr = (string) $docDscr->citation->distStmt->distDate;
                $year = substr($dateStr, 0, 4);
            }
        }

        // Extrair autores do stdyDscr
        $authors = [];
        if (isset($ddiXml->stdyDscr->citation->rspStmt)) {
            $rspStmt = $ddiXml->stdyDscr->citation->rspStmt;

            // AuthEnty pode ser um único elemento ou array
            $authEnties = isset($rspStmt->AuthEnty) ? (is_array($rspStmt->AuthEnty) ? $rspStmt->AuthEnty : [$rspStmt->AuthEnty]) : [];

            foreach ($authEnties as $auth) {
                $authors[] = (string) $auth;
            }
        }

        // Extrair URL do Dataverse a partir de fileDscr
        $dataverseUrl = '';
        $persistentId = '';

        if (isset($ddiXml->fileDscr)) {
            $fileDscr = is_array($ddiXml->fileDscr) ? $ddiXml->fileDscr[0] : $ddiXml->fileDscr;

            if (isset($fileDscr['URI'])) {
                $fileUri = (string) $fileDscr['URI'];
                // Extrair URL base do Dataverse da URI do arquivo
                // Ex: https://venus.brapci.inf.br/api/access/datafile/456
                $parsed = parse_url($fileUri);
                $dataverseUrl = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '');
            }
        }

        // Usar holding URI se disponível
        if (empty($dataverseUrl) && isset($ddiXml->stdyDscr->citation->holdings['URI'])) {
            $holdingUri = (string) $ddiXml->stdyDscr->citation->holdings['URI'];
            $parsed = parse_url($holdingUri);
            $dataverseUrl = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '');
        }

        // Montar URL de citação para fetchFromDataverse
        $citationUrl = '';
        if (!empty($dataverseUrl) && !empty($doi)) {
            $citationUrl = $dataverseUrl . '/citation?persistentId=' . $doi;
        }

        return [
            'success' => !empty($citationUrl),
            'error' => empty($citationUrl) ? 'Não foi possível extrair a URL do Dataverse' : '',
            'url' => $citationUrl,
            'title' => $title,
            'doi' => $doi,
            'authors' => $authors,
            'publisher' => $publisher,
            'year' => $year,
        ];
    }
}
