<link rel="stylesheet"
       href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<style>
     #map {
         height: 600px;
         border: 1px solid #444;
     }
</style>
    <div id="map"></div>

    <script>
        // Converte dados PHP para JS
        let coords = <?= json_encode($coords); ?>;

        // Se quiser centralizar no primeiro ponto
        let center = [coords[0].lat, coords[0].lng];

        // Inicializa o mapa
        var map = L.map('map').setView(center, 6);

        // Camada base (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Adiciona os pontos no mapa
        coords.forEach(c => {
            L.marker([c.lat, c.lng]).addTo(map)
                .bindPopup(c.label + "<hr>Lat: " + c.lat + "<br>Lng: " + c.lng);
        });
    </script>

</body>

</html>