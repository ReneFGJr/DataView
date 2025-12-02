<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class ImageProxy extends Controller
{
    public function index()
    {
        $path = $this->request->getGet('file');

        if (!$path) {
            return "Erro: parâmetro 'file' não informado.";
        }

        // Normaliza caminho (evita ../ subida de diretório)
        $real = realpath($path);

        // Segurança: só permite acesso à pasta .tmp/.cache
        $base = realpath(WRITEPATH . '../.tmp/.cache');

        if (!$real || strpos($real, $base) !== 0) {
            return "Acesso negado ao arquivo.";
        }

        if (!file_exists($real)) {
            return "Arquivo não encontrado: $real";
        }

        if (!is_file($real.'_nini')) {            
            return "O caminho MINI não é um arquivo válido.";
        } else {
            $real = $real.'_nini';
        }

        // Detecta o tipo real do arquivo
        $mime = mime_content_type($real);

        if ($mime == 'image/tiff' || $mime == 'image/tif') {
            echo "Erro de vializarção TIFF";
            exit;
        }

        // Envia headers corretos
        header("Content-Type: $mime");
        header("Content-Length: " . filesize($real));

        // Envia a imagem
        readfile($real);

        exit;
    }
}
