<?php

namespace App\Models\Forms;

use CodeIgniter\Model;

class DOI extends Model
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

    function form()
        {
            $sx = '';
            $url = trim(get("url"));
            $doi = trim(get("doi"));
            $doi = troca($doi,'doi:','');

            $sx .= '<div class="row">';
            $sx .= '<div class="col-md-12 text-start">';
            $sx .= '<h1>'.msg('dataview.url').'</h1>';
            $sx .= form_open();
            $sx .= '<span class="small">'.msg('dataview.url_info').'</span>';
            $sx .= form_input($data='url', $url,'class="form-control-lg" style="width: 100%;" placeholder="Url do Dataset"');

            $sx .= '<span class="small">' . msg('dataview.doi') . '</span>';
            $sx .= form_input($data = 'doi', $doi, 'class="form-control-lg" style="width: 100%;" placeholder="DOI do Dataset"');

            $sx .= form_submit('submit',msg('dataview.submit'),'class="btn btn-primary mt-2"');
            $sx .= form_close();

            $sx .= 'Ex: https://vitrinedadosabertos-dev.rnp.br/dataview/index.php/dataview/view/ddi?fileid=85&siteUrl=https://vitrinedadosabertos-dev.rnp.br&PID=doi:10.80102/rnp-dev/WCPAFV&datasetId=84&localeCode=en';
            $sx .= '</div>';
            $sx .= '</div>';
            return($sx);
        }
}