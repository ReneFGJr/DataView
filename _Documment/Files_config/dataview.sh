echo "List Tools"
curl http://localhost:8080/api/admin/externalTools | jq

if [$1 = "install"]; then
    if [$2 != ""]; then
        echo "Install $1"
        curl -X POST -H 'Content-type: application/json' http://localhost:8080/api/admin/externalTools --upload-file $1.json
    else
        echo "File not informed, ex: pdf"
    fi
else 
    echo "use: dataview install <file>"
fi
