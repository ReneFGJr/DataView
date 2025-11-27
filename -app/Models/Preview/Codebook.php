<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class Codebook extends Model
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

    function show($url, $doi)
    {
        $sx = '';
        $Cache = new \App\Models\IO\Cache();
        $file = $url;
        $file .= '/api/datasets/export?exporter=ddi&persistentId=doi%3A' . $doi;
        $file = troca($file, '//', '/');
        $file = $Cache->download($file);
        //$txt = file_get_contents($file);

        $xml = (array)simplexml_load_file($file);

        $sx .= $this->docDscr($xml);
        $sx .= $this->stdyDscr($xml);
        $sx .= $this->fileDscr($xml);
        return $sx;
    }

    function stdyDscr($xml)
        {
        $sx = '';
        if (isset($xml['stdyDscr'])) {
            $stdyDscr = (array)$xml['stdyDscr'];
            $citation = (array)$stdyDscr['stdyInfo'];
        }
        return $sx;
        }

    function docDscr($xml)
    {
        $sx = '';
        if (isset($xml['docDscr'])) {
            $dscr = (array)$xml['docDscr'];
            $dscr = (array)$dscr['citation'];

            $titlStmt = (array)$dscr['titlStmt'];
            $distStmt = (array)$dscr['distStmt'];
            $verStmt = (array)$dscr['verStmt'];

            $sx .= '<span class="small">' . msg('dataview.title') . '</span><br>';
            $sx .= '<span style="font-weight: bold; font-size: 1.3em;">' . $titlStmt['titl'] . '</span><br>';
            $sx .= '<span>' . $titlStmt['IDNo'] . '</span>';
            $sx .= '<span> - v. ' . $verStmt['version'] . '</span>';
            $sx .= '<span> de ' . stodbr($distStmt['distDate']) . '</span>';
            $sx .= '<br>';
            $sx .= '<span>' . $distStmt['distrbtr'] . '</span>';


        }
        return $sx;
    }
}
