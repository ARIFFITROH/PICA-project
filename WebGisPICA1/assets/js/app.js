/* Initial Map */
var map = L.map("map").setView([-0.06506, 110.406922], 13); //lat, long, zoom

/* asupkeun */
const queryString = window.location.search;
/* end asupkeun */

/* Tile Basemap */
var basemap = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  //attribution akan muncul di pojok kanan bawah
  attribution: "<?php echo $attribution;?>",
});
basemap.addTo(map);

var googleSat = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
    maxZoom: 20,
    subdomains:['mt0','mt1','mt2','mt3']
});



/* GeoJSON Polygon */
var adminpolygon = L.geoJson(bl, {
  /* Style polygon */
  style: function (feature) {
    if (feature.properties.NIMUT == 1) {
      return {
        opacity: 1,
        color: "black",
        weight: 0.8,
        fillOpacity: 1,
        fillColor: "rgb(255, 119, 1)",
      };
    } else if (feature.properties.NIMUT == 2) {
      return {
        opacity: 1,
        color: "black",
        weight: 0.5,
        fillOpacity: 1,
        fillColor: "rgb(40, 167, 69)",
      };
    } else if (feature.properties.NIMUT == 3 ) {
      return {
        opacity: 1,
        color: "black",
        weight: 0.5,
        fillOpacity: 1,
        fillColor: "rgb(255, 193, 7)",
      };
    } else if (feature.properties.NIMUT == 4 ) {
      return {
        opacity: 1,
        color: "black",
        weight: 0.5,
        fillOpacity: 1,
        fillColor: "rgb(220, 53, 69)",
      };
    } else if (feature.properties.NIMUT == 5 ) {
      return {
        opacity: 1,
        color: "black",
        weight: 0.5,
        fillOpacity: 1,
        fillColor: "rgb(52, 58, 64)",
      };
    } else {
      if (typeof feature.properties.NIMUT == "undefined") {
        return {
          opacity: 0,
          color: "white",
          weight: 0.5,
          fillOpacity: 0,
          fillColor: "rgb(218, 165, 32, 0.9)",
        };
      } else {
        return {
          opacity: 1,
          color: "white",
          weight: 0.5,
          fillOpacity: 1,
          fillColor: "rgb(112, 128, 145, 0.9)",
        };
    }
    }
  },
  /* Highlight & Popup */
  onEachFeature: function (feature, layer) {
    console.log(feature);
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
        console.log(feature.properties);
        var fillColor;
        if (feature.properties.NIMUT == 1) {
          
            fillColor = "bg-orange";
          
        } else if (feature.properties.NIMUT == 2) {
          
          fillColor = "bg-green";
          
        } else if (feature.properties.NIMUT == 3 ) {
          
          fillColor = "bg-yellow";
          
        } else if (feature.properties.NIMUT == 4 ) {
          
          fillColor = "bg-red";
          
        } else if (feature.properties.NIMUT == 5 ) {
         
          fillColor = "bg-black";
          
        } else {
          
          fillColor = "bg-grey";
          
        }
        var content =
          "<div class='card'>" +
          "<div class='card-header alert-primary text-center p-2'><strong>" + feature.properties.KEBUN +" <br> " +
          feature.properties.AFDELING +
          "</strong></div>" +
          "<div class='card-body p-0'>" +
          "<table class='table table-responsive-sm m-0'>" +
          // "<tr><th class='p-2'>AFDELING</th><th class='p-2'>" +
          // feature.properties.AFDELING +
          "</th></tr>" +
          "<tr><th class='p-2'>TAHUN TANAM</th><th class='p-2'>" +
          feature.properties.TT +
          "</th></tr>" +
          "<tr><th class='p-2'>BLOK</th><th class='p-2'>" +
          feature.properties.BLOK +
          "</th></tr>" +
          "<tr><th class='p-2'>LUAS</th><th class='p-2'>" +
          feature.properties.LUAS +
          // "</th></tr>" +
          // "<tr><th class='p-2'>JML POKOK</th><th class='p-2 "+fillColor+"'>" +
          // "" +
          "</th></tr>" +
          "<tr><th class='p-2'>PROTAS</th><th class='p-2'>" +
          feature.properties.PROTAS +
          "</th></tr>" +
          "<tr><th class='p-2'>KLASIFIKASI</th><th class='p-2 "+fillColor+"'>" +
          feature.properties.KLASIFIKASI +
          "</th></tr>" +
          "</table>" +
          "</div>";
        adminpolygon.bindPopup(content); //Popup
      },
    });
    layer.bindTooltip(feature.properties.KECAMATAN, {
      direction: "center",
      permanent: true,
      className: "styleLabel",
    });
  },
});
/* memanggil data geojson polygon */
var bl = $.getJSON("data/geojson.php" + queryString, function (data) {
  adminpolygon.addData(data);
  map.addLayer(adminpolygon);
  map.fitBounds(adminpolygon.getBounds());
  createControl();
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
    '<h5>Legenda</h5><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(255, 119, 1);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Excellent<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(40, 167, 69);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Good <br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(255, 193, 7);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Intermediet<br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(220, 53, 69);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Weak <br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(52, 58, 64);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Lowless <br><svg width="32" height="20"><rect width="32" height="17" style="fill:rgb(108, 117, 125);stroke-width:0.1;stroke:rgb(0,0,0)" /></svg> Empty';
};
legend.addTo(map);

/* GeoJSON afdeling line */
var afdelpolygon = L.geoJson(afdel, {
  /* Style polygon Afdeling */
  style: function (feature) {
      return {
        opacity: 0,
        color: "blue",
        weight: 3,
        fillOpacity: 0,
        fillColor: "rgb(0, 0, 0, 0.9)",
      };
  },

  /* Highlight & Popup */
  onEachFeature: function (feature, layer) {
     layer.bindTooltip(feature.properties.Afdeling, {
      direction: "center",
      permanent: true,
      className: "styleLabe text-center f-adel",
            
    });
  },
});
/* memanggil data geojson polygon */
var afdel = $.getJSON("data/DEKAN1/perafdeling.geojson", function (data) {
  afdelpolygon.addData(data);
  map.addLayer(afdelpolygon);
  map.fitBounds(afdelpolygon.getBounds());
});


var baseLayers = {
  "Open Street Maps": basemap,
  "google satelite"  : googleSat
};


 
     
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

function createControl(){
  
  /* GeoJSON Polygon afdeling poly */
  var afdepolygon = L.geoJson(afde, {
    /* Style polygon Afdeling */
    style: function (feature) {
        return {
          opacity: 1,
          color: "blue",
          weight: 3,
          fillOpacity: 0,
          fillColor: "rgb(0, 0, 0, 0.9)",
        };
    },

  //   /* Highlight & Popup */
    // onEachFeature: function (feature, layer) {
    //   layer.bindTooltip(feature.properties.Afdeling, {
    //     direction: "center",
    //     permanent: true,
    //     className: "styleLabe text-center  f-adel ",
              
    //   });
    // },
  });
  
  /* memanggil data geojson polygon */
  var afde = $.getJSON("data/DEKAN1/perafdelinggaris2.geojson", function (data) {
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
    "garis Afdeling" : afdelpolygon,
    "Ident Blok": adminpolygon,
    "Afdeling" : afdepolygon,
    
    
  };
  
  L.control.layers(baseLayers, overlays).addTo(map);
}
