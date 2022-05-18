<!DOCTYPE html>
<html>
  <head>
    <title>Add Map</title>

   <style>
/* Set the size of the div element that contains the map */
#map {
  height: 400px;
  /* The height is 400 pixels */
  width: 100%;
  /* The width is the width of the web page */
}


   </style>
    <script>
      // Initialize and add the map
function initMap() {
  // The location of Uluru
  const uluru = { lat: 30.0916433, lng: 31.303275 };
  // The map, centered at Uluru
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 50,
    center: uluru,
  });
  // The marker, positioned at Uluru
  const marker = new google.maps.Marker({
    position: uluru,
    map: map,
  });
}
    </script>
  </head>
  <body>
    <h3>My Google Maps Demo</h3>
    <!--The div element for the map -->
    <div id="map"></div>

    <!-- Async script executes immediately and must be after any DOM elements used in callback. -->
    <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBsUA-IB8VVFWRDxXsIlH21wPyQSh_T5eU&callback=initMap&libraries=&v=weekly"
      async
    ></script>
  </body>
</html>

<!-- key= AIzaSyBsUA-IB8VVFWRDxXsIlH21wPyQSh_T5eU -->