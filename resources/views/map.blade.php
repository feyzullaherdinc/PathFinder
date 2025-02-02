<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harita</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>

    <div id="map" style="height: 1000px;"></div>

    <script>
        var map = L.map('map').setView([{{ $location->latitude }}, {{ $location->longitude }}], 10);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        L.marker([{{ $location->latitude }}, {{ $location->longitude }}]).addTo(map)
            .bindPopup("{{ $location->name }}") // Veritabanındaki isim
            .openPopup();
    </script>

</body>
</html>
