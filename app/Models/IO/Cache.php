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

    function cached($file)
    {
        $dir = '../.tmp';
        dircheck($dir);
        $dir = '../.tmp/.cached';
        dircheck($dir);

        $f = $dir . '/' . md5($file);
        if (file_exists($f)) {
            $file = $f;
        }
        return $file;
    }

    function saveCache($file, $txt)
    {
        $dir = '../.tmp';
        dircheck($dir);
        $dir = '../.tmp/.cached';
        dircheck($dir);

        $f = $dir . '/' . md5($file);
        file_put_contents($f, $txt);
        return true;
    }
}