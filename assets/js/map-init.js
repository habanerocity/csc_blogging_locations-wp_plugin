document.addEventListener('DOMContentLoaded', function() {
    if (typeof mapLocations !== 'undefined') {
        var map = L.map('map').setView([14.554789, 120.990010], 4);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        mapLocations.forEach(function(location) {
            var popupContent = '<div class="flex__col">';
            if (location.thumbnail) {
                popupContent += '<img src="' + location.thumbnail + '" alt="' + location.title + '" style="width:100px;height:auto;margin-bottom:10px;">';
            }
            popupContent += '<a href="' + location.permalink + '" target="_blank"><strong>' + location.title + '</strong></a>';
            popupContent += '<p>' + location.excerpt + '</p>';
            popupContent += '<span><i class="fas fa-map-pin"></i>' + ' ' + location.city + ', ' + location.country + '</span>';
            popupContent += '</div>';

            L.marker([location.destination_latitude, location.destination_longitude]).addTo(map)
                .bindPopup(popupContent);
        });
    }
});