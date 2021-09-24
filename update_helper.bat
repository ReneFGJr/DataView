echo off
echo "Copiando Helper"
copy ..\Brapci3.0\app\Helpers\boostrap_helper*.* app\Helpers\*.* 
//copy ..\Brapci3.0\app\Models\Social*.* app\Models\*.*

echo "RDP"
//copy ..\Brapci3.0\app\Models\RDF.php app\Models\*.* 
//copy ..\Brapci3.0\app\Models\RDFConcept.php app\Models\*.* 
//copy ..\Brapci3.0\app\Models\RDFLiteral.php app\Models\*.* 
//copy ..\Brapci3.0\app\Models\RDFData.php app\Models\*.* 
echo "Images"
//copy ..\Brapci3.0\app\Models\Images.php app\Models\*.* 

echo "Bibliotecas de Idiomas"
md app\Language\pt-BR
//copy ..\Brapci3.0\app\Language\pt-BR\social.* app\Language\pt-BR\*.*
