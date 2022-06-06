clear
echo "L i s t   T o o l s"
echo "by RDPBrasil - renefgj@gmail.com"
echo "==========================================================================="
if [ "$1" == "install" ]; then
    if [ "$2" != "" ]; then
        echo "Install $1"
        if [ "$2" ]; then
                curl -X POST -H 'Content-type: application/json' http://localhost:8080/api/admin/externalTools --upload-file "$2".json
        else
                echo "packeg not informed, ex: dataview install pdf"
        fi
    else
        echo "File not informed, ex: pdf"
    fi
else
        if [ "$1" == 'list' ]; then
                curl http://localhost:8080/api/admin/externalTools | jq
        else
                if [ "$1" == 'delete' ];then
                    curl -X DELETE http://localhost:8080/api/admin/externalTools/"$2"
                else
                    echo "use: dataview install <file>"
                fi
        fi
fi
echo " ";
echo " ";
