<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class Files extends Model
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

    function download()
        {
            $DataViewer = new \App\Models\DataViewer();
            $dv = $DataViewer->getPOST();
            $filename = 'pdf_'.date("YmdHi").'.pdf';
            $url = $dv['siteUrl'];
            $url = $url . 'api/access/datafile/' . $dv['fileid'];
            $file = md5($url);
            if (!file_exists('.tmp/.')) {
                mkdir('.tmp');
            }
            if (!file_exists('.tmp/.tmp/.')) {
                mkdir('.tmp/.tmp');
            }
            $file = '.tmp/.tmp/' . $file;

            if (!file_exists(($file))) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $st = curl_exec($ch);
                $fd = fopen($file, 'w');
                fwrite($fd, $st);
                fclose($fd);
                curl_close($ch);

                $txt = file_get_contents($url);
                file_put_contents($file,$txt);
            }
            return ($file);
        }


}
