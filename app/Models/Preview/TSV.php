<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class TSV extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'tsvs';
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

    function view($file,$sep=',')
        {
            $limit = 30;
            $handle = fopen($file, "r");
            $nr = 0;
            $hd = array();
            $dt = array();
            /****************************************************** INPORT */
            if ($handle) {
                while (($nr < $limit) and ($line = fgets($handle)) !== false) 
                {
                    /*************************************************** LINE */
                    $eln = explode($sep,$line);
                    if ($nr == 0)
                        {
                            $hd = $eln;
                        } else {
                           array_push($dt,$eln); 
                        } 
                    /*************************************************** LINE */
                    $nr++;
                }
                $sx = '';
                $sx .= '<input type="button" value="View DDI Metadata">';
                $sx .= '<div style="position: fixed; right:0px; text-align: right;"><a href="https://github.com/ReneFGJr/DataView" target="_blank"><img src="'.URL.'img/logo_cedapdados.png" align="right" style="width:20px;"></a></div>';
                $sx .= '<hr>';
                $sx .= '<table class="table table-striped table-bordered table-sm">';
                $sx .= '<thead><tr>';
                foreach($hd as $k=>$v)
                    {
                        $sx .= '<th>'.$v.'</th>';
                    }
                $sx .= '</tr></thead>';

                foreach($dt as $k=>$line)
                    {
                        $sx .= '<tr>';
                        foreach($line as $c=>$v)
                        {
                            $v = str_replace(array('"'),'',$v);
                            $sx .= '<td>'.$v.'</td>';
                        }
                        $sx .= '</tr>';
                    }
                $sx .= '</table>';
                return $sx;
            } else {
                $sx .= h('<h1>File not found</h1>');
            }
            fclose($handle);
        } 
}
