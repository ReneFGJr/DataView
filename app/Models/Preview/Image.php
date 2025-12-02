<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class Image extends Model
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
    function redimensionarImagem($origem, $destino, $larguraNova = 800)
    {
        ini_set('memory_limit', '1024M');  // segurança para imagens grandes

        // Verifica MIME real
        $mime = mime_content_type($origem);

        # TIFF
        if ($mime === 'image/tiff' || $mime === 'image/tif') {

            // Caminho do script Python que você criou
            $pythonScript = '../python/tiff_jpg.py'; // <- ajuste o caminho real

            // Garante que o diretório existe
            if (!file_exists($pythonScript)) {
                echo "Erro: Script Python não encontrado: " . $pythonScript;
                exit;
            }

            // Executa o comando Python
            $cmd = "python " . escapeshellarg($pythonScript) .
                " --input " . escapeshellarg($origem) .
                " --output " . escapeshellarg($destino);


            exec($cmd . " 2>&1", $output, $retorno);

            // Debug opcional:
            // echo "<pre>CMD: $cmd\nRETORNO: $retorno\n"; print_r($output); exit;

            if ($retorno !== 0) {
                echo '<tt>'.$cmd.'</tt><br>';
                echo "Erro ao converter TIFF para JPG via Python: " . implode("\n", $output);
            }

            // Sucesso na conversão → retorna imediatamente
            return true;
        }

        // --- FORMATO JPG / PNG NORMAL COM GD --------------------------------

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $imagemOriginal = imagecreatefromjpeg($origem);
                $formato = 'jpg';
                break;

            case 'image/png':
                $imagemOriginal = imagecreatefrompng($origem);
                $formato = 'png';
                break;

            default:
                return false; // tipo não suportado
        }

        // Obtém dimensões
        list($larguraOriginal, $alturaOriginal) = getimagesize($origem);

        // Calcula altura proporcional
        $ratio = $larguraOriginal / $alturaOriginal;
        $alturaNova = $larguraNova / $ratio;

        // Cria nova imagem
        $nova = imagecreatetruecolor($larguraNova, $alturaNova);

        // PNG mantém transparência
        if ($formato === 'png') {
            imagealphablending($nova, false);
            imagesavealpha($nova, true);
            $transparente = imagecolorallocatealpha($nova, 0, 0, 0, 127);
            imagefill($nova, 0, 0, $transparente);
        }

        // Redimensiona
        imagecopyresampled(
            $nova,
            $imagemOriginal,
            0,
            0,
            0,
            0,
            $larguraNova,
            $alturaNova,
            $larguraOriginal,
            $alturaOriginal
        );

        // Salva
        if ($formato === 'jpg') {
            imagejpeg($nova, $destino, 85);
        } else {
            imagepng($nova, $destino, 6);
        }

        // Libera memória
        imagedestroy($imagemOriginal);
        imagedestroy($nova);

        return true;
    }




    function index()
    {
        $Cache = new \App\Models\IO\Cache();

        $SERVER_URL = $_GET['siteUrl'];
        if (isset($_GET['PID'])) {
            $PERSISTENT_ID = $_GET['PID'];
        } else {
            $PERSISTENT_ID = '';
        }

        if (isset($_GET['key'])) {
            $API_TOKEN = $_GET['key'];
        } else {
            $API_TOKEN = '';
        }

        if (isset($_GET['datasetId'])) {
            $datasetId = $_GET['datasetId'];
            $fileid = $_GET['fileid'];
            $file = $SERVER_URL . '/api/access/datafile/' . $fileid . '?key=' . $API_TOKEN;
        } else {
            $datasetId = '';
            $fielid = '';
            $file = $SERVER_URL;
        }

        $file = $Cache->download($file);
        $file2 = $file . '_nini';
        $info = getimagesize($file);

        if (!file_exists($file2)) {

            switch ($info['mime']) {
                case 'image/jpeg':
                    $this->redimensionarImagem($file, $file2);
                    // É JPEG
                    break;
                case 'image/png':
                    $this->redimensionarImagem($file, $file2);
                    // É PNG
                    break;
                case 'image/tiff':
                    // É TIFF
                    $this->redimensionarImagem($file, $file2);
                    break;
                default:
                    echo ('Tipo de imagem não suportado para redimensionamento. ');
                    pre($info);
                    exit;
                    break;
            }
        }

        if (!file_exists($file)) {
            exit('Erro ao baixar o arquivo.');
        }

        if (!file_exists($file2)) {
            exit('Erro ao baixar o arquivo da miniatura.');
        }


        $data = [];
        $data['img'] = base_url('/imageproxy?file=' . $file2);
        $data['info'] = $info;
        $data['file'] = $file;
        $data['preview'] = base_url('/imageproxy?file=' . $file);
        $sx = view('widget/image', $data);

        echo $sx;
    }

    function view($d1 = '', $d2 = '', $d3 = '')
    {
        $Files = new \App\Models\Preview\Files();
        $files = $Files->download();
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="the.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($files));
        @readfile($files);

        exit;
        $sx = '';
        $sx .= '
            <!doctype html> 
            <html lang="en">
            <head>
              <meta charset="utf-8">
              <meta name="viewport" content="width=device-width, initial-scale=1">
              <title>My PDF Viewer</title>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.0.943/pdf.min.js"></script>              
            </head>
            <body>
                 <div id="my_pdf_viewer">
                    <div id="canvas_container">
                        <canvas id="pdf_renderer"></canvas>
                    </div>
             
                    <div id="navigation_controls">
                        <button id="go_previous">Previous</button>
                        <input id="current_page" value="1" type="number"/>
                        <button id="go_next">Next</button>
                    </div>
             
                    <div id="zoom_controls">  
                        <button id="zoom_in">+</button>
                        <button id="zoom_out">-</button>
                    </div>
                </div>
            </body>
            <script>
                var myState = {
                    pdf: null,
                    currentPage: 1,
                    zoom: 1
                }            
                // more code here
            </script>            
            </html>';
        $sx .= 'https://code.tutsplus.com/tutorials/how-to-create-a-pdf-viewer-in-javascript--cms-32505';
        exit;

        $sx = '';
        $sx .= '<div class="container">';
        $sx .= '<div class="row">';
        $sx .= '<h1>PDF</h1>';
        $sx .= '</div>';
        $sx .= '</div>';

        $sx = '
                <html>
                <head>
                <meta charset="utf-8">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script type="text/javascript" src="/dataverse-previewers/previewers/js/xss.js"></script>
                <script type="text/javascript" src="/dataverse-previewers/previewers/js/pdfpreview.js"></script>
                <script type="text/javascript" src="/dataverse-previewers/previewers/js/pdf.js"></script>
                <script type="text/javascript" src="/dataverse-previewers/previewers/js/pdf.worker.js"></script>
                <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.min.js"></script>
                <script src="lib/jquery.i18n.js"></script>
                <script src="lib/jquery.i18n.messagestore.js"></script>
                <script src="lib/jquery.i18n.language.js"></script>
                <script type="text/javascript" src="/dataverse-previewers/previewers/js/retriever.js"></script>
                <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
                <!-- Optional theme -->
                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous"><link type="text/css" rel="stylesheet" href="/dataverse-previewers/previewers/css/preview.css"/>
                <link type="text/css" rel="stylesheet" href="/dataverse-previewers/previewers/css/preview.css"/>
                <link type="text/css" rel="stylesheet" href="/dataverse-previewers/previewers/css/lds-spinner.css"/>

                </head>
                <body class="container">
                <img id="logo"></img>
                <H1 class="page-title pdfPreviewText">PDF Preview
                </H1>
                <div class="preview-container">
                <div class="preview-header"></div>
                <div class="preview">
                <div>
                <button id="prev">Previous</button>
                <button id="next">Next</button>
                &nbsp; &nbsp;
                <span class="pageText">Page:</span> <span id="page_num"></span> / <span id="page_count"></span>
                </div>
                <div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                <canvas id="the-canvas"></canvas>
                </div>
                </div>
                </body>
                </html>
            ';
        return $sx;
    }
}
