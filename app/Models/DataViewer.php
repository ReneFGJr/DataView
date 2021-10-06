<?php
namespace App\Models;

use CodeIgniter\Model;

class DataViewer extends Model
{
    var $version = '0.21.09.24';
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

    function logo()
        {
            $tela = '<div style="position: fixed; right:0px; text-align: right;"><img src="/dataview/img/logo_cedapdados.png" align="right" width="20px"></div>';
            return $tela;
        }

    function header($dv)
        {
            $tela = '';
            $tela .= '<title>DataView - '.$dv['PID'].' - Brapci</title>';
            $tela .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">';
            $tela .= '</head>';
            return $tela;
        }
    
    function footer()
        {
            $tela = '';
            $tela .= '<hr>';
            $tela .= lang('Data visualization').' - '.lang('version').' '.$this->version.' <a href="https://github.com/ReneFGJr/DataView">https://github.com/ReneFGJr/DataView</a>';
            $tela .= '<br>'.lang('development by').' <a href="https://github.com/ReneFGJr">Rene Faustino Gabriel junior</a>';
            return $tela;
        }

    function index()
        {
            $tela = '';
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
            foreach($_GET as $field => $value)
                {
                    $dv[$field] = $value;
                }                
            if ($dv['fileid'] != '')
            {
            $act = '';
            $tela .= $this->header($dv);
            $tela .= $this->logo();
            switch($act)
                {
                    /********************************* Default */
                    default:
                    //return $this->metadata();
                    $file = $this->download_file_metadata($dv);
                    $tela .= $this->show_variables_v2($file);
                    $tela .= $this->footer();
                    break;
                }
            } else {
                $tela = lang("FileID not infomed");
            }
            return $tela;
        }

    function header_study($xml)
        {
            $title = $xml->stdyDscr->citation->titlStmt->titl;
            $IDno = $xml->stdyDscr->citation->titlStmt->IDNo;
            $cnt = small(lang('dataview.study.title'));
            $cnt .= h($title,2);
            $tela = bsc($cnt,8);

            $cnt = small(lang('dataview.study.idno'));
            $cnt .= h($IDno,4);
            $cnt = '<div data-spy="scroll" data-target="#navbar-example3" data-offset="0">'.$cnt.'</div>';
            $tela .= bsc($cnt,4);
            $tela .= '<style> body{ position: relative; /* required */ } .list-group{ position: sticky; top: 15px; } </style>';
            $tela = bs($tela, array('fluid'=>FALSE));
            return $tela;
        }

    function notes($xml)
        {
            $tela = '';            
            $xml = (array)$xml;
            if (isset($xml['notes']))
                {
                    $tela = lang('dataview.notes');
                    $notas = (string)$xml['notes'];

                    if (isset($notas[0]))
                        {
                            foreach($notas as $id=>$nota)
                                {
                                    echo '<br>===>'.(string)$nota;
                                }
                        } else {
                            $tela .= ': '.$notas;    
                        }
                }
            return $tela;
        }

    function variables($xml)
        {
            ;
            $dataDscr = $xml->dataDscr->var;
            $ss = '<select size=20 onchange="location.href=this.value;">';
            $sc = '';
            foreach($dataDscr as $id=>$var)
                {
                    $vara = (array)$var;
                    $attrib = $vara['@attributes'];
                    //$ss .= '<a class="list-group-item list-group-item" href="#'.$attrib['ID'].'" style="font-size: 70%; border: 0px; padding: 0px;">';
                    //$ss .= '<option value="'.base_url($_SERVER['URLSITE'].'#'.$attrib['ID']).'">';
                    $ss .= '<option value="#'.($attrib['ID']).'">';
                    $ss .= $attrib['name'];
                    $ss .= '</option>';

                    /************************************************************************** */     
                    $sc .= '<hr>';               
                    $sc .= '<div id="'.$attrib['ID'].'">';
                    $sc .= h($var->labl . ' ('.$attrib['name'].')',4);

                    if (isset($vara['catgry']))
                        {
                            $vcat = $vara['catgry'];
                            $avcat = (array)$vara['catgry'];
                            if (isset($avcat['catValu']))
                                {
                                        $vcat = array($avcat);
                                } else {
                                    $vcat = (array)$vcat;
                                }
                            $sc .= $this->variables_resume($vcat);
                        }
                    $sc .= '</div>';

                $sc .= $this->notes($var);
                $sc .= $this->summary($var);                    

                /*
                echo '<pre>';
                print_r($var);
                echo '</pre>';
                echo '<hr>';
                */
                }



                $ss .= '</select>';


                $sx = '<div class="col-sm-3" id="myScrollspy">
                            <div class="list-group">'.$ss.'</div>
                            </div>
                            '.bsc($sc,9).'
                        </div>
                        ';
                $sx = '<div class="container-fluid"><div class="row">'.$sx.'</div></div>';
            return $sx;
        }

    function summary($xml)
        {
            $sx = '';
            $axml = (array)$xml;

            if (!isset($axml['sumStat'])) { return ''; }

            $vls = array('max'=>0,'min'=>'','mean'=>''
                            ,'medn'=>'','mode'=>'','stdev'=>''
                            ,'invd'=>'','vald'=>'');            



            for ($t=0;$t < 8;$t++)
                {
                    $sum = (array)$xml->sumStat[$t];                            
                    $vlr = $sum[0];
                    $ind = $sum['@attributes']['type'];
                    $vls[$ind] = $vlr;
                }

                $sv = '<table width="100%" class="small" border=1>';
                $n = 0;
                $sa = ''; 
                $sb = '';
                foreach($vls as $vn => $vl)
                    {
                        if (($vn == 'invd') or ($vn == 'vald')) { $vl = number_format($vl,0,',','.'); }
                        if (($vn == 'stdev') or ($vn == 'mean')) { $vl = number_format($vl,4,',','.'); }

                        $sa .= '<th style="text-align: center;" class="px-1" width="11%">'.lang('dataview.'.$vn).'</th>';
                        $sb .= '<td style="text-align: center;" class="px-1">'.$vl.'</td>';
                        $n++;
                    }
                $sv .= '<tr>'.$sa.'</tr>';
                $sv .= '<tr>'.$sb.'</tr>';
                $sv .= '</table>';
            return $sv;
        }

    function variables_resume($cats)
        {
                $tot = 0;
                $st = '<table class="tablex" style="width: 100%; border: 1px solid #000000;">';

                /* Calcula frequencia total **************************************************/
                for($r=0;$r < count($cats);$r++)                   
                {
                    $dc = (array)$cats[$r];
                    $tot = $tot + (float)$dc['catStat'];
                }

                for($r=0;$r < count($cats);$r++)                        
                {
                    $dc = (array)$cats[$r];
                    $vlr = (float)$dc['catStat'];
                    $vlrCat = (string)$dc['catValu'];
                    $label = (string)$dc['labl'];
                    //print_r($dc);
                    //echo '<hr>';
                    $st .= '<tr>';
                    $st .= '<td class="p-1">'.$label.' <sup>('.$vlrCat.')</sup></td>';
                    $st .= '<td class="p-1" style="text-align: right;" width="10%">'.number_format($vlr,0,',','.').'</td>';
                    if ($tot > 0)
                    {
                        $st .= '<td class="p-1" style="text-align: right;" width="10%">'.number_format(($vlr/$tot)*100,1,',','.').'%</td>';
                    } else {
                        $st .= '<td width="10%"></td>';
                    }
                    $st .= '</tr>';
                }
                $st .= '</table>';    
                $st .= 'Total : '.number_format($tot,0,',','.');          
                return $st;
        }


    function show_variables_v2($file)
        {
            $xml = simplexml_load_file($file);            
            $var = $xml->dataDscr->var;
            /* Study Header *************************************/
            $sx = $this->header_study($xml);
            $sx .= $this->variables($xml);     

            /*
            echo '<pre>';
            print_r($xml);      
            echo '</pre>';
            */
            return $sx;
        }        

    function download_file($dv=array())
        {
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

    function download_file_metadata($dv=array())        
        {
            $API_TOKEN = $dv['key'];
            $SERVER_URL = $dv['siteUrl'];
            $PERSISTENT_ID = $dv['PID'];
            $FILEID = $dv['fileid'];

            $url = "$SERVER_URL/api/access/datafile/$FILEID/metadata/ddi";
            $file = $this->download($url);        
            return $file;
        }

    function download($url)
        {          
            $file = md5($url);
            if (!is_dir('_temp')) { mkdir ('_temp'); }
            if (!is_dir('_temp/ddi')) { mkdir ('_temp/ddi'); }

            /* Filne name */
            $file = '_temp/ddi/metadata_'.$file.'.xml';
            if ((!file_exists($file)) or (1==1))
                {
                    $txt = file_get_contents($url);
                    file_put_contents($file,$txt);
                }
        
            $xml = simplexml_load_file($file);
            return $file;       
        }
}
