<?php

namespace App\Models\IO;

use CodeIgniter\Model;

class Cache extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = '*';
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

    function clear()
    {
        $dir = '../.tmp/.cached';
        $files = scandir($dir);
        $n = 0;
        for ($r = 0; $r < count($files); $r++) {
            $file = $files[$r];
            if (strlen($file) > 10) {
                unlink($dir . '/' . $file);
                $n++;
            }
        }
        return $n;
    }

    /**
     * Faz download de um arquivo remoto com cache local.
     * Retorna o caminho do arquivo local OU false em caso de erro.
     */
    function download($file, $msg = false)
    {
        try {
            // Diretórios
            $baseDir = '../.tmp';
            $cacheDir = $baseDir . '/.cached';

            if (!dircheck($baseDir) || !dircheck($cacheDir)) {
                throw new \Exception("Erro ao criar diretórios de cache.");
            }

            // Nome do arquivo no cache (hash para evitar caracteres inválidos)
            $cachedFile = $cacheDir . '/' . md5($file);

            /* =======================
           1. RETORNA DO CACHE
        ======================== */
            if (file_exists($cachedFile)) {

                if ($msg) {
                    echo '<span style="font-size: 0.8em; color: red;">Cached</span>';
                }

                return $cachedFile;
            }

            echo "DOWNLOAD – {$file}<br>";

            /* =======================
           2. DOWNLOAD VIA CURL
        ======================== */
            $fp = fopen($cachedFile, 'w');

            if (!$fp) {
                throw new \Exception("Não foi possível criar o arquivo de cache: $cachedFile");
            }
            echo '<h1>'.$file.'</h1>';
            $ch = curl_init($file);
            curl_setopt_array($ch, [
                CURLOPT_FILE            => $fp,
                CURLOPT_FOLLOWLOCATION  => true,
                CURLOPT_SSL_VERIFYPEER  => false,
                CURLOPT_CONNECTTIMEOUT  => 20,
                CURLOPT_TIMEOUT         => 90,
                CURLOPT_FAILONERROR     => true, // faz curl_exec falhar em 400/500
            ]);

            $ok = curl_exec($ch);
            $error = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            fclose($fp);

            // Falha na transferência
            if (!$ok) {
                unlink($cachedFile);
                throw new \Exception("cURL error: " . $error);
            }

            // HTTP não 200
            if ($info['http_code'] !== 200) {
                unlink($cachedFile);
                throw new \Exception("Erro HTTP: " . $info['http_code']);
            }

            echo "Arquivo baixado com sucesso.<br>";

            return $cachedFile;
        } catch (\Exception $e) {

            // Remove arquivo incompleto
            if (isset($cachedFile) && file_exists($cachedFile)) {
                unlink($cachedFile);
            }

            // Log opcional
            // log_message('error', 'Download falhou: ' . $e->getMessage());

            echo "ERRO: " . $e->getMessage();

            return false;
        }
    }

    /**
     * Salva conteúdo diretamente no cache
     */
    function saveCache($file, $txt)
    {
        $baseDir = '../.tmp';
        $cacheDir = $baseDir . '/.cached';

        if (!dircheck($baseDir) || !dircheck($cacheDir)) {
            return false;
        }

        $cachedFile = $cacheDir . '/' . md5($file);

        try {
            file_put_contents($cachedFile, $txt);
            return true;
        } catch (\Exception $e) {
            // log_message('error', "Erro ao gravar cache: " . $e->getMessage());
            return false;
        }
    }
}
