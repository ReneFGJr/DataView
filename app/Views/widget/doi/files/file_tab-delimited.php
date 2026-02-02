<?php
$url = str_replace('://', '%3A%2F%2F', $url);
$url = substr($url,0,strpos($url,'/api'));
$fileId = $f['dataFile']['id'];
$dataView = base_url('');
$dataView .= 'view/tab?';
$dataView .= 'siteUrl=' . $url;
$dataView .= '&PID=' . $DOI;
$dataView .= '&datasetId=';
$dataView .= '&fileid=' . urlencode($fileId);
echo '<a href="' . $dataView . '" class="btn btn-sm btn-primary">Link</a>';
?>