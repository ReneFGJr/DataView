<?php

namespace App\Models;

use CodeIgniter\Model;

class DataViewerAdmin extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'admins';
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

    private function cab()
        {
            $tela = '';
            $tela .= '<head>';
            $tela .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">';
            $tela .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>';
            $tela .= '</head>';
            return $tela;
        }

    function index($d1,$d2,$d3)
        {
            $tela = $this->cab();
            switch($d1)
                {
                    case 'ddi':
                        $DDI = new \App\Models\DDI();
                        
                        $tela .= $DDI->index($d2,$d3);
                        break;
                    default:
                        $tela .= '<a href="'.base_url('/public/index.php/dataview/admin/ddi').'">DDI</a>';
                }
            return $tela;
        }
}
