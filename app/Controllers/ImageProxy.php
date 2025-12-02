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

        // Detecta o tipo real do arquivo
        $mime = mime_content_type($real);

        // Envia headers corretos
        header("Content-Type: $mime");
        header("Content-Length: " . filesize($real));

        // Envia a imagem
        readfile($real);

        exit;
    }
}
