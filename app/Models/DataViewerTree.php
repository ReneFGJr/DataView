<?php

namespace App\Models;

use CodeIgniter\Model;

class DataViewerTree extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'dataviewertrees';
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

    // https://pocdadosabertos.inep.rnp.br/api/search?q=trees

    ///https://pocdadosabertos.inep.rnp.br/api/dataverses/root
    //{"status":"OK","data":{"id":1,"alias":"root","name":"PoC DE EXPERIMENTAÇÃO DO DATAVERSE","dataverseContacts":[{"displayOrder":0,"contactEmail":"root@mailinator.com"}],"permissionRoot":true,"description":"The root dataverse.","dataverseType":"UNCATEGORIZED","creationDate":"2021-09-18T13:16:26Z"}}t

    ///https://pocdadosabertos.inep.rnp.br/api/dataverses/root/contents
    //{"status":"OK","data":{"id":1,"alias":"root","name":"PoC DE EXPERIMENTAÇÃO DO DATAVERSE","dataverseContacts":[{"displayOrder":0,"contactEmail":"root@mailinator.com"}],"permissionRoot":true,"description":"The root dataverse.","dataverseType":"UNCATEGORIZED","creationDate":"2021-09-18T13:16:26Z"}}t

}
