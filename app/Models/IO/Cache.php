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
            for ($r=0;$r < count($files);$r++)
                {
                    $file = $files[$r];
                    if (strlen($file) > 10)
                        {
                            unlink($dir . '/' . $file);
                            $n++;
                        }
                }
            return $n;
        }

    function download($file)
    {
        $dir = '../.tmp';
        dircheck($dir);
        $dir = '../.tmp/.cached';
        dircheck($dir);

        $f = $dir . '/' . md5($file);

        if (file_exists($f)) {
            $query = $_SERVER['QUERY_STRING'];
            echo '<span style="font-size: 08.em; color: red;">';
            echo "Cached ";
            echo '</span>';
            $file = $f;
        } else {
            $meth = 'CURL';
            //$meth = 'GET';
            echo "DOWNLOAD - ".$file;
            switch($meth)
                {
                    case 'CURL':
                    /************************** METHOD CURL */

                    set_time_limit(0); // if the file is large set the timeout.
                    $fn = fopen($f, "w") or die("cannot open" . $f);

                    // Setting the curl operations
                    $cd = curl_init();
                    curl_setopt($cd, CURLOPT_URL, $file);
                    curl_setopt($cd, CURLOPT_FILE, $fn);
                    //curl_setopt($cd, CURLOPT_SSL_VERIFYHOST, false);
                    curl_setopt($cd, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($cd, CURLOPT_TIMEOUT, 60); // timeout is 30 seconds, to download the large files you may need to increase the timeout limit.

                    // Running curl to download file
                    curl_exec($cd);
                    if (curl_errno($cd)) {
                        fclose($fn);
                        unlink($f);
                        echo bsmessage("ERROR: the cURL error is : " . curl_error($cd),3);
                        return "";
                    } else {
                        $status = curl_getinfo($cd);
                        echo $status["http_code"] == 200 ? "The File is Downloaded" : "The error code is : " . $status["http_code"];
                        // the http status 200 means everything is going well. the error codes can be 401, 403 or 404.
                    }

                    // close and finalize the operations.
                    curl_close($cd);
                    fclose($fn);

                    $file = $f;
                    break;

                    case 'GET':
                    /************************** METHOD GET */
                        $txt = file_get_contents($file);
                        if (strlen($txt) > 100) {
                            file_put_contents($f, $txt);
                            $file = $f;
                        }
                }


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