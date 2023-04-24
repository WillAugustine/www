/* This file facilitates dynamic drawing of a rectangle onto the canvas which resides over the aerial image on 'map.php'. */
window.addEventListener('onload', drawBorder);
var canvas = document.getElementById('mapCanvas');
var image = document.getElementById('mapImage');
var ctx = canvas.getContext('2d');
var rect = canvas.getBoundingClientRect();
var imageHeight = rect.height;
var imageWidth = rect.width;

/* These variables are pulled from the geoposition of the corners 
   of the aerial image used in this project on 'map.php'. */
var changeLat = 0.0039110342445;
var changeLong = 0.00905120630706;
var maxLat = 45.98597697752876;
var maxLong = -112.54546474270519;

/* These variables are obtained from the Blocks table in 'gravelocwebappdb' */
var seLat = parseFloat(document.getElementById('SELatPt').value);
var seLong = parseFloat(document.getElementById('SELongPt').value);
var nwLat = parseFloat(document.getElementById('NWLatPt').value);
var nwLong = parseFloat(document.getElementById('NWLongPt').value);
resize();
drawBorder();
function resize() 
{
    ctx.canvas.width = image.width;
    ctx.canvas.height = image.height;
}
function drawBorder(){
    var sePixels = convertCoords(seLat, seLong);
    var nwPixels = convertCoords(nwLat, nwLong);
    ctx.beginPath();
    ctx.lineWidth = 3;
    ctx.strokeStyle = 'red';
    var length = sePixels[0] - nwPixels[0];
    var height = nwPixels[1] - sePixels[1];
    ctx.rect(nwPixels[0], nwPixels[1], length, height);
    ctx.stroke();
}
function convertCoords(lat, long){
    var deltaLat = maxLat - lat;
    var deltaLong = Math.abs(long - maxLong);
    var convertedX = Math.trunc(deltaLong * (imageWidth / changeLong));
    var convertedY = Math.trunc(deltaLat * (imageHeight / changeLat));
    return [convertedX, convertedY];   
}


