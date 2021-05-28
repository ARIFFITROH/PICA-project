/* Initial Map */
var map = L.map("map").setView([-0.089322, 110.389981], 15); //lat, long, zoom

/* asupkeun */
const queryString = window.location.search;
/* end asupkeun */

/* Tile Basemap */
var basemap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  //attribution akan muncul di pojok kanan bawah
  attribution: "<?php echo $attribution;?>",
  titleSize: 512,
});
basemap.addTo(map);

var googleSat = L.tileLayer("http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}", {
  maxZoom: 20,
  subdomains: ["mt0", "mt1", "mt2", "mt3"],
  titleSize: 512,
});

var polygonsWithCenters = L.layerGroup();

/* GeoJSON Polygon */
var adminpolygon = L.geoJson(bl, {
  /* Style polygon */
  style: function (feature) {
    if (feature.properties.NIM == 1) {
      return {
        opacity: 0.5,
        color: "white",
        weight: 0.5,
        fillOpacity: 0.7,
        fillColor: "rgb(218, 165, 32, 0.9)",
      };
    } else if (feature.properties.NIM == 2) {
      return {
        opacity: 0.5,
        color: "white",
        weight: 0.5,
        fillOpacity: 0.7,
        fillColor: "rgb(127, 255, 1, 0.9)",
      };
    } else if (feature.properties.NIM == 3) {
      return {
        opacity: 0.5,
        color: "white",
        weight: 0.5,
        fillOpacity: 0.7,
        fillColor: "rgb(255, 255, 0, 0.9)",
      };
    } else if (feature.properties.NIM == 4) {
      return {
        opacity: 0.5,
        color: "white",
        weight: 0.5,
        fillOpacity: 0.7,
        fillColor: "rgb(255, 0, 0, 0.9)",
      };
    } else if (feature.properties.NIM == 5) {
      return {
        opacity: 0.5,
        color: "white",
        weight: 0.5,
        fillOpacity: 0.7,
        fillColor: "rgb(0, 0, 0, 0.9)",
      };
    } else {
      if (typeof feature.properties.NIM == "undefined") {
        return {
          opacity: 0,
          color: "white",
          weight: 0.5,
          fillOpacity: 0,
          fillColor: "rgb(218, 165, 32, 0.9)",
        };
      } else {
        return {
          opacity: 0.5,
          color: "white",
          weight: 0.5,
          fillOpacity: 0.7,
          fillColor: "rgb(112, 128, 145, 0.9)",
        };
      }
    }
  },
  /* Highlight & Popup */
  onEachFeature: function (feature, layer) {
    console.log(feature);
    // if (feature.geometry.type === "Polygon") {
    //   // var bounds = layer.getBounds();
    //   // var center = bounds.getCenter();

    //   // var markerTitle = feature.properties.name;
    //   // layer.id = markerTitle;

    //   // var popUpFormat = dataPopUp(feature);
    //   // layer.bindPopup(popUpFormat, customPopUpOptions);
    //   var center = layer.getBounds().getCenter();
    //   var marker = L.marker(center);
    //   var polygonAndItsCenter = L.layerGroup([layer, marker]);
    //   polygonAndItsCenter.addTo(polygonsWithCenters);
    // }

    layer.on({
      mouseover: function (e) {
        //Fungsi ketika mouse berada di atas obyek
        var layer = e.target; //variabel layer
        layer.setStyle({
          //Highlight style
          weight: 2, //Tebal garis tepi polygon
          color: "gray", //Warna garis tepi polygon
          opacity: 1, //Transparansi garis tepi polygon
          fillColor: "cyan", //Warna tengah polygon
          fillOpacity: 0.7, //Transparansi tengah polygon
        });
      },
      mouseout: function (e) {
        //Fungsi ketika mouse keluar dari area obyek
        adminpolygon.resetStyle(e.target); //Mengembalikan style polygon ke style awal
        map.closePopup(); //Menutup popup
      },
      click: function (e) {
        //Fungsi ketika obyek di-klik
        // console.log(feature.properties);
        var content =
          "<div class='card'>" +
          "<div class='card-header alert-primary text-center p-2'><strong>" +
          feature.properties.KEBUN +
          " <br> BLOK. " +
          feature.properties.BLOK +
          "</strong></div>" +
          "<div class='card-body p-0'>" +
          "<table class='table table-responsive-sm m-0'>" +
          "<tr><th class='p-2'>AFDELING</th><th class='p-2'>" +
          feature.properties.AFDELING +
          "</th></tr>" +
          "<tr><th class='p-2'>TAHUN TANAM</th><th class='p-2'>" +
          feature.properties.TT +
          "</th></tr>" +
          "<tr><th class='p-2'>BLOK</th><th class='p-2'>" +
          feature.properties.BLOK +
          "</th></tr>" +
          "<tr><th class='p-2'>LUAS</th><th class='p-2'>" +
          feature.properties.LUAS +
          "</th></tr>" +
          "<tr><th class='p-2'>PROTAS</th><th class='p-2'>" +
          feature.properties.SEMBUH +
          "</th></tr>" +
          "<tr><th class='p-2'>KLASIFIKASI</th><th class='p-2'>" +
          feature.properties.MENINGGAL +
          "</th></tr>" +
          "</table>" +
          "</div>";
        adminpolygon.bindPopup(content); //Popup
      },
    });
  },
});

// polygonsWithCenters.addTo(map);
/* memanggil data geojson polygon */
/* asupkeun */
var bl = $.getJSON("data/geojson.php/" + queryString, function (data) {
  adminpolygon.addData(data);
  /* end asupkeun */
  // adminpolygon.bindLabel("test").addTo(map);
  map.addLayer(adminpolygon);
  // var markerOptions = {
  //   title: "MyLocation",
  //   clickable: false,
  //   draggable: false,
  //   // icon: customIcon
  // };
  // console.log(data);
  // var marker = L.marker(data, markerOptions);
  // // Adding marker to the map
  // marker.addTo(map);
  map.fitBounds(adminpolygon.getBounds());
  createline();
  // var label = L.Label();
  // label.setContent("static label");
  // label.setLatLng(adminpolygon.getBounds().getCenter());
});

resetLabels([adminpolygon]);
map.on("zoomend", function () {
  resetLabels([adminpolygon]);
});
map.on("move", function () {
  resetLabels([adminpolygon]);
});
map.on("layeradd", function () {
  resetLabels([adminpolygon]);
});
map.on("layerremove", function () {
  resetLabels([adminpolygon]);
});

/* Scale Bar */
L.control
  .scale({
    maxWidth: 150,
    imperial: false,
  })
  .addTo(map);

/* Legenda */
var legend = new L.Control({ position: "bottomright" });
legend.onAdd = function (map) {
  this._div = L.DomUtil.create("div", "info");
  this.update();
  return this._div;
};
legend.update = function () {
  this._div.innerHTML =
    '<h5>Legenda</h5><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(218, 165, 32, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Excellent<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(127, 255, 1, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Good <br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(255, 255, 0, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Intermediet<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(255, 0, 0, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Weak <br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(0, 0, 0, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Lowless <br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(112, 128, 145, 0.9);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Empty';
};
legend.addTo(map);

var baseLayers = {
  "Open Street Maps": basemap,
  "google satelite": googleSat,
};

// var overlays = {
//   Blok: adminpolygon,
// };

// var overlayMaps = {
//   Line: afdepolygon,
//   Blok: adminpolygon,
// };

// L.control.layers(baseLayers, overlays).addTo(map);

/* Data table */
$(document).ready(function () {
  /* asupkeun */
  $(".ch-afde").on("change", function (e) {
    // e.preferentDefault();
    $(".form").submit();
  });
  /* end asupkeun */
  $("#dataTable").DataTable();
});

function createline() {
  /* GeoJSON Polygon */
  var afdepolygon = L.geoJson(afde, {
    /* Highlight & Popup */
    style: function (feature) {
      return {
        opacity: 0.5,
        color: "blue",
        weight: 2,
        fillOpacity: 0,
        fillColor: "rgb(0, 0, 0, 0.9)",
      };
    },
    onEachFeature: function (feature, layer) {
      layer.bindTooltip(feature.properties.Afdeling, {
        direction: "center",
        permanent: true,
        className: "styleLabel text-center bg-blue",
      });
    },
  });

  // console.log(queryString);
  var afde = $.getJSON("data/garis.php/" + queryString, function (data) {
    afdepolygon.addData(data);
    map.addLayer(afdepolygon);
    map.fitBounds(afdepolygon.getBounds());
  });

  resetLabels([afdepolygon]);
  map.on("zoomend", function () {
    resetLabels([afdepolygon]);
  });
  map.on("move", function () {
    resetLabels([afdepolygon]);
  });
  map.on("layeradd", function () {
    resetLabels([afdepolygon]);
  });
  map.on("layerremove", function () {
    resetLabels([afdepolygon]);
  });

  var overlays = {
    Blok: adminpolygon,
    Line: afdepolygon,
  };

  L.control.layers(baseLayers, overlays).addTo(map);
}
