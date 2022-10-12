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
        $SERVER_URL = $_GET['siteUrl'];
        $PERSISTENT_ID = $_GET['PID'];
        if (isset($_GET['key']))
            {
                $API_TOKEN = $_GET['key'];
            } else {
                $API_TOKEN = '';
            }

        $datasetId = $_GET['datasetId'];
        $fileid = $_GET['fileid'];

        $file = $SERVER_URL . '/api/access/datafile/' . $fileid . '?key=' . $API_TOKEN;

        $sx = '';
        $sx .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">'.chr(13);
        $sx .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>'.chr(13);
        $sx .= '<script src="/build/three.min.js"></script>' . chr(13);;
        $sx .= '<script src="/dataview/js/loaders/STLLoader.js"></script> '.chr(13);
        $sx .= ' <script src="/dataview/js/controls/OrbitControls.js"></script> '.chr(13);
        $sx .= '<div id="model" style="width: 500px; height: 500px"> </div>';

        $js = 'function STLViewer(model, elementID) { var elem = document.getElementById(elementID)';

        $js .= 'window.onload = function() { STLViewer("hook.stl", "model") }';
        $sx .= '<script>'.chr(13);
        $sx .= $js;
        $sx .= '</script>'.chr(13);
        echo $sx;
        }

    function view($d1='',$d2='',$d3='')
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
