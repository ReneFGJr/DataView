<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class PDF extends Model
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

    function url()
        {
            $DataViewer = new \App\Models\DataViewer();
            $Cache = new \App\Models\IO\Cache();
            $dv = $DataViewer->getPOST();
            $url = $dv['siteUrl'];
            if (substr($url,strlen($url)-1,1) != '/') { $url .= '/'; }
            $url = $url . 'api/access/datafile/' . $dv['fileid'];

            if ((!isset($dv['fileid'])) or ($dv['fileid'] ==''))
                {
                    echo "fielid is empty";
                    exit;
                }

            return $url;
        }

    function view($d1='',$d2='',$d3='')
        {
            $Cache = new \App\Models\IO\Cache();
            $file = $Cache->download($this->url());

            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename="dataview_'.md5($file).'.pdf"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit;
        }
}
