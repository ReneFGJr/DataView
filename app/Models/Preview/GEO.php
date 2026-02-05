<?php

namespace App\Models\Preview;

use CodeIgniter\Model;

class GEO extends Model
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

    function indexIframe()
        {
            pre($_GET,false);
        $url = get("siteUrl");
        $url .= 'dataview/view/geo?siteUrl=' . get("siteUrl");;
        $url .= '&PID=' . get("PID");
        $url .= '&fileid=' . get("fileid");
        pre($url);
        }

    function index()
    {
        $Cache = new \App\Models\IO\Cache();

        $SERVER_URL = $_GET['siteUrl'];
        $PERSISTENT_ID = $_GET['PID'];

        $API_TOKEN = $_GET['key'] ?? '';
        $datasetId = $_GET['datasetId'];
        $fileid = $_GET['fileid'];

        $file = $SERVER_URL . '/api/access/datafile/' . $fileid;

        if (strlen($API_TOKEN) > 0) {
            $file .= '?key=' . $API_TOKEN;
        }

        $file = $Cache->download($file);

        if (!file_exists($file)) {
            exit('Erro ao baixar o arquivo.');
        }

        $limit = 20;
        $ln = 0;
        $data = array();

        if ($file = fopen($file, "r")) {
            while (!feof($file)) {

                $line = trim(fgets($file));

                // remove aspas externas
                $line = str_replace('"', '', $line);
                $line = str_replace("\t", ';', $line);

                if ($ln == 0) {
                    // troca TAB por ;
                    $header = explode(";", $line);
                } else {
                    $line = explode(";", $line);
                    $data[] = $line;
                }

                $ln++;
                if (($limit--) < 0) {
                    break;
                }
            }
            fclose($file);

            $lat = ['latitude', 'lat', ' latitude', 'lat','decimallatitude'];
            $long = ['longitude', 'long', ' longitude', 'long','decimallongitude'];
            $names = ['scientificname'];
            $place = ['locality', 'location', 'place'];
            $ilat = -1;
            $ilong = -1;
            $iname = -1;
            $iplace = -1;
            foreach ($header as $k => $v) {
                $v = trim(strtolower($v));
                if (in_array($v, $lat)) {
                    $ilat = $k;
                }
                if (in_array($v, $long)) {
                    $ilong = $k;
                }
                if (in_array($v, $names)) {
                    $iname = $k;
                }
                if (in_array($v, $place)) {
                    $iplace = $k;
                }
            }

            $dec = 1;
            if ($header[$ilat] == 'decimallatitude') {
                $dec = 1;
            }

            if (($ilat >= 0) and ($ilong >= 0)) {
                $points = [];
                foreach ($data as $line) {
                    $ldata = (float)str_replace(',', '.', $line[$ilat]);
                    $ldata = $ldata / $dec;
                    $ldlong = (float)str_replace(',', '.', $line[$ilong]);
                    $ldlong = $ldlong / $dec;

                    $label = 'none';
                    if ($iname >= 0) {
                        $label = '<i>' . $line[$iname] . "</i><br>";
                    }
                    if ($iplace >= 0) {
                        $label .= "\r".$line[$iplace];
                    }

                    $points[] = [
                        'lat' => $ldata,
                        'long' => $ldlong,
                        'label' => $label
                    ];
                }
                $coords = $this->normalizeGeo($points);
                return view('render/geo/mapa', ['coords' => $coords]);
            } else {
                return 'Latitude e Longitude não encontrados.';
            }

        }

    }

    function hasGeo($header)
    {
        $lat = ['latitude', 'lat', ' latitude', 'lat','decimallatitude'];
        $long = ['longitude', 'long', ' longitude', 'long','decimallongitude'];

        $ilat = -1;
        $ilong = -1;
        foreach ($header as $k => $v) {
            $v = trim(strtolower($v));
            if (in_array($v, $lat)) {
                $ilat = $k;
            }
            if (in_array($v, $long)) {
                $ilong = $k;
            }
        }

        if (($ilat >= 0) and ($ilong >= 0)) {
            return true;
        } else {
            return false;
        }
    }

    function normalizeGeo($data)
    {
        $out = [];

        foreach ($data as $item) {
            $lat = str_replace(',', '.', $item['lat']);
            $lng = str_replace(',', '.', $item['long']);
            $label = $item['label'] ?? 'none';

            $out[] = [
                'lat' => floatval($lat),
                'lng' => floatval($lng),  // leaflet usa "lng",
                'label' => $label
            ];
        }

        return $out;
    }

}
