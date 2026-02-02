<?php

namespace App\Models;

use CodeIgniter\Model;

class DoiMetadataModel
{
    function  fetchFromDataverse(string $url): string
    {
        $apiUrl = str_replace(
            '/citation?persistentId=',
            '/api/datasets/:persistentId/?persistentId=',
            $url
        );

        $DOI = substr($url, strpos($url, 'doi:')+4);

        $response = $this->httpGet($apiUrl);
        if (!$response) {
            return 'Erro ao acessar o Dataverse.';
        }

        $data = json_decode($response, true); // ← transforma em ARRAY
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "Erro ao decodificar JSON: " . json_last_error_msg();
        }
        return view('widget/doi/dataverse_info', [
            'dataset' => $data['data'] ?? [],
            'url'     => $apiUrl,
            'DOI'     => $DOI ?? '',
        ]);
    }

    /**
     * Consulta DOI primeiro no DataCite e,
     * caso não exista, tenta Crossref
     */
    public function fetchDoiMetadata(string $doi): array
    {
        $doi = str_replace('doi:', '', trim($doi));
        $doi = str_replace('https://doi.org/', '', trim($doi));

        // 1️⃣ Tenta DataCite
        $datacite = $this->fetchFromDataCite($doi);
        if ($datacite['success']) {
            return $datacite;
        }

        // 2️⃣ Caso não exista, tenta Crossref
        $crossref = $this->fetchFromCrossref($doi);
        if ($crossref['success']) {
            return $crossref;
        }

        // 3️⃣ Não encontrado em nenhuma base
        return [
            'success' => false,
            'source'  => null,
            'doi'     => $doi,
            'message' => 'DOI não encontrado no DataCite nem na Crossref'
        ];
    }

    /**
     * 🔎 Consulta DataCite
     */
    private function fetchFromDataCite(string $doi): array
    {
        $doi = str_replace('/', '%2F', $doi);
        $url = 'https://api.datacite.org/dois/' . $doi;


        $response = $this->httpGet($url);
        if (!$response) {
            return ['success' => false];
        }

        $json = json_decode($response, true);

        if (!isset($json['data'])) {
            return ['success' => false];
        }

        return [
            'success' => true,
            'source'  => 'datacite',
            'doi'     => $doi,
            'metadata' => $this->normalizeDataCite($json['data'])
        ];
    }

    /**
     * 🔎 Consulta Crossref
     */
    private function fetchFromCrossref(string $doi): array
    {
        $url = 'https://api.crossref.org/works/' . urlencode($doi);

        $response = $this->httpGet($url);
        if (!$response) {
            return ['success' => false];
        }

        $json = json_decode($response, true);

        if (!isset($json['message'])) {
            return ['success' => false];
        }

        return [
            'success' => true,
            'source'  => 'crossref',
            'doi'     => $doi,
            'metadata' => $this->normalizeCrossref($json['message'])
        ];
    }

    /**
     * 🌐 GET HTTP simples (cURL)
     */
    private function httpGet(string $url): ?string
    {
        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json'
            ],
            // 🔴 DESATIVA verificação SSL
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $result = curl_exec($ch);
        $http   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($http >= 200 && $http < 300) ? $result : null;
    }

    /**
     * 🔧 Normaliza metadados DataCite
     */
    private function normalizeDataCite(array $data): array
    {
        $attr = $data['attributes'] ?? [];

        return [
            'title'        => $attr['titles'][0]['title'] ?? null,
            'creators'     => array_column($attr['creators'] ?? [], 'name'),
            'publisher'    => $attr['publisher'] ?? null,
            'publicationYear' => $attr['publicationYear'] ?? null,
            'resourceType' => $attr['types']['resourceTypeGeneral'] ?? null,
            'url'          => $attr['url'] ?? null
        ];
    }

    /**
     * 🔧 Normaliza metadados Crossref
     */
    private function normalizeCrossref(array $data): array
    {
        return [
            'title'        => $data['title'][0] ?? null,
            'creators'     => array_map(function ($a) {
                return trim(($a['given'] ?? '') . ' ' . ($a['family'] ?? ''));
            }, $data['author'] ?? []),
            'publisher'    => $data['publisher'] ?? null,
            'publicationYear' => $data['issued']['date-parts'][0][0] ?? null,
            'resourceType' => $data['type'] ?? null,
            'url'          => $data['URL'] ?? null
        ];
    }
}
