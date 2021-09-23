<?php

namespace App\Models;

use CodeIgniter\Model;

class DataViewer extends Model
{
    protected $DBGroup              = 'default';
    protected $table                = 'dataviewers';
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

    function index()
        {
            $tela = 'x';
            $dv = array();
            $dv['fileid'] = '';
            $dv['siteUrl'] = '';
            $dv['PID'] = '';
            $dv['key'] = '';
            $dv['datasetId'] = '';
            $dv['localeCode'] = '';
            $dv['preview'] = '';
            foreach($_POST as $field => $value)
                {
                    $dv[$field] = $value;
                }
            $act = '';
            switch($act)
                {

                    /********************************* Default */
                    default:
                    //return $this->metadata();
                    $tela .= $this->download_file_metadata();
                    break;
                }
            return $tela;
        }

    function show_variables($xml)
        {
            $var = $xml->dataDscr->var;

            $sx = '<h1>Total de variáveis: '.count($var).'<h1>';

            for ($r=0;$r < count($var);$r++)
                {
                    $x = (array)$var[$r];
                    $xo = $var[$r];
                    $op = $x['@attributes'];

                    //$cat = (array)$x->catgry;       
   
                    $lc = (array)$x['location'];
                    $ft = (array)$x['varFormat'];

                    $label = (string)$x['labl'];
                    
                    if (isset($x['catgry']))
                        {
                            $catgrp = (array)$x['catgry'];
                            $tot = 0;
                            $cats = array();
                            for ($t=0;$t < count($catgrp);$t++)
                                {
                                    if (isset($catgrp[$t]))
                                    {
                                        $c = (array)$catgrp[$t];
                                        $vlr = $c['catStat'];                                    
                                        $vr = $c['catValu'];
                                        $cats[$vr] = array('label'=>$c['labl'],'freq'=>$vlr,'percent'=>0);
                                        $tot = $tot + $vlr;
                                    } else {
                                        $c = (array)$catgrp;
                                        $vlr = $c['catStat'];                                    
                                        $vr = $c['catValu'];
                                        $cats[$vr] = array('label'=>$c['labl'],'freq'=>$vlr,'percent'=>0);
                                        $tot = $tot + $vlr;
                                    }
                                }
                            $st = '<table class="tablex" style="width: 100%; border: 1px solid #000000;">';
                            foreach($cats as $ct => $dc)                            
                            {
                                $st .= '<tr>';
                                $st .= '<td class="p-1">'.$dc['label'].'</td>';
                                $st .= '<td class="p-1" style="text-align: right;">'.number_format($dc['freq'],0,',','.').'</td>';
                                if ($tot > 0)
                                {
                                    $st .= '<td class="p-1" style="text-align: right;">'.number_format($dc['freq']/$tot*100,1,',','.').'%</td>';
                                } else {
                                    $st .= '<td></td>';
                                }
                                $st .= '</tr>';
                            }
                            $st .= '</table>';                                                        
                        } else {
                            $st = '';
                        }
                    
                    $notes = (array)$x['notes'];
                    $fileid = (string)$lc['@attributes']['fileid'];

                    /*********************************************** */
                    if (isset($var->catgry[0]))
                        {
                            $cat = (array)$var->catgry;
                            print_r($cat);
                        }
                    
                    $sx .= ('<h4>'.$label.'</h4>');
                    $sx .= ($op['ID']);
                    $sx .= ('<b>'.$op['name'].'</b>');
                    $sx .= ($op['intrvl']);
                    $sx .= ($fileid);
                    $sx .= ('<small>Type</small>: '.$ft['@attributes']['type']);
                    $sx .= ('x');
                    $sx .= ('&nbsp;');
                    $sx .= ($st);

                    /************************************************************************** */
                    $vls = array('max'=>0,'min'=>'','mean'=>''
                                    ,'medn'=>'','mode'=>'','stdev'=>''
                                    ,'invd'=>'','vald'=>'');

                    if (isset($xo->sumStat[0]))
                    {
                        for ($t=0;$t < 8;$t++)
                            {
                                $sum = (array)$xo->sumStat[$t];                            
                                $vlr = $sum[0];
                                $ind = $sum['@attributes']['type'];
                                $vls[$ind] = $vlr;
                            }
                       
                            $sv = '<table>';
                            $n = 0;
                            foreach($vls as $vn => $vl)
                                {
									if (($vn == 'invd') or ($vn == 'vald')) { $vl = number_format($vl,0,',','.'); }
									if (($vn == 'stdev') or ($vn == 'mean')) { $vl = number_format($vl,4,',','.'); }
                                    $sv .= '<tr>';
                                    $sv .= '<td style="text-align: right;" class="px-1">'.lang('dataverse.'.$vn).'</td>';
                                    $sv .= '<td style="text-align: left;" class="px-1">'.$vl.'</td>';
                                    $sv .= '</tr>';
                                    $n++;
                                }
                            $sv .= '</table>';
                            $sx .= $sv;
                    }

                }
            return $sx;
        }

    function download_file($dv=array())
        {
            $dv['key'] = '67f260be-822f-4da1-b96f-660ab22c3e1b';
            $dv['siteUrl'] = 'http://20.197.236.31';
            $dv['PID'] = 'doi:10.80102/INEP-POC/IVYFWN';
            $dv['fileid'] = '12';

            $API_TOKEN = $dv['key'];
            $SERVER_URL = $dv['siteUrl'];
            $PERSISTENT_ID = $dv['PID'];
            $FILEID = $dv['fileid'];
            $url = "$SERVER_URL/api/access/dataset/:persistentId/?persistentId=$PERSISTENT_ID";
            $url = "$SERVER_URL/api/access/datafile/$FILEID/metadata/ddi";
            $url = 'http://20.197.236.31/api/access/datafile/12';
            echo '<h1>'.$url.'</h1>';
            ///api/access/datafile/bundle/$id
            ///api/access/datafile/$id/metadata/ddi
            //'http://localhost:8080/api/access/datafile/6?format=subset&variables=123,127'
            $process = curl_init($url);
            $data = json_encode(array('API_TOKEN' => $API_TOKEN,'key' => $API_TOKEN));
            
            curl_setopt($process, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]); // O -d utiliza `application/x-www-form-urlencoded` por padrão.
            curl_setopt($process, CURLOPT_POST, true);
            curl_setopt($process, CURLOPT_POSTFIELDS, $data);
            curl_setopt($process, CURLOPT_RETURNTRANSFER, true); // Para "salvar" a resposta no curl_exec (o $resp).
            $resp = curl_exec($process);            
        }

    function download_file_metadata($dt=array())        
        {
            $dv['key'] = '67f260be-822f-4da1-b96f-660ab22c3e1b';
            $dv['siteUrl'] = 'http://20.197.236.31';
            $dv['PID'] = 'doi:10.80102/INEP-POC/IVYFWN';
            $dv['fileid'] = '12';

            $API_TOKEN = $dv['key'];
            $SERVER_URL = $dv['siteUrl'];
            $PERSISTENT_ID = $dv['PID'];
            $FILEID = $dv['fileid'];

            $url = "$SERVER_URL/api/access/datafile/$FILEID/metadata/ddi";
            $file = $this->download($url);

            $xml = simplexml_load_file($file);
            $tela = $this->show_variables($xml);            
            return $tela;
        }

    function download($url)
        {          
            $file = md5($url);
            if (!is_dir('_temp')) { mkdir ('_temp'); }
            if (!is_dir('_temp/ddi')) { mkdir ('_temp/ddi'); }

            /* Filne name */
            $file = '_temp/ddi/metadata_'.$file.'.xml';
            if (!file_exists($file))
                {
                    $txt = file_get_contents($url);
                    file_put_contents($file,$txt);
                }
        
            $xml = simplexml_load_file($file);
            return $file;       
        }
}
