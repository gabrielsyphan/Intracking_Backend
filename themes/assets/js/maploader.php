$(function(){
    var map = L.map('map').setView([-9.6435441, -35.7257695], 13);
    var markersLayer = L.featureGroup().addTo(map);

    L.tileLayer('https://{s}.tile.openstreetmap.de/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxNativeZoom:19,
        maxZoom: 18,
        minZoom: 12
    }).addTo(map);

    let myMarker = L.icon({
        iconUrl:"https://www.syphan.com.br/physis/themes/assets/img/marker-physis.png",
        shadowUrl:"https://www.syphan.com.br/physis/themes/assets/img/marker-shadow.png",

        iconSize:[31, 40],
        shadowSize:[41, 41],
        iconAnchor:[15, 41],
        shadowAnchor:[13, 41],
        popupAnchor:[0, -41]
    });

    var markers = L.markerClusterGroup();

    <!--L.marker([-9.6435441,-35.7257695],{icon:myMarker}).bindPopup("Lucas Gabriel").addTo(map);-->
});