/* This file creates a highlight onto a canvas, to be created by 
   Archives User mouse input on 'createSearch.php' and to be saved for later use by Regular User */

// create canvas element and append it to document body
var canvas = document.getElementById('blockRecordImageHighlight');

// get canvas 2D context and set him correct size
var ctx = canvas.getContext('2d');
resize();

// last known position
var pos = { x: 0, y: 0 };
window.addEventListener('resize', resize);
document.addEventListener('mousemove', draw);
document.addEventListener('mousedown', setPosition);
document.addEventListener('mouseenter', setPosition);

// new position from mouse event
function setPosition(e) {
  var rect = canvas.getBoundingClientRect();
  pos.x = (e.clientX - rect.left) / (rect.right - rect.left) * canvas.width;
  pos.y = (e.clientY - rect.top) / (rect.bottom - rect.top) * canvas.height;
}

// resize canvas
function resize() {
  ctx.canvas.width = window.innerWidth;
  ctx.canvas.height = window.innerHeight;

}
function draw(e) {
  // mouse left button must be pressed
  if (e.buttons !== 1){
    return;
  }
  ctx.beginPath(); // begin
  ctx.lineWidth = 35;
  ctx.lineCap = 'round';
  ctx.fillStyle = "#ff0";
  ctx.strokeStyle = 'rgba(255, 255, 0, .1)';
  ctx.moveTo(pos.x, pos.y); // from
  setPosition(e);
  ctx.lineTo(pos.x, pos.y); // to
  ctx.stroke(); // draw it!
}
function clearCanvas() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
}
function save() {
  var data = canvas.toDataURL('image/png');
  document.getElementById('highlightRec').value = data;
  document.forms["save-image"].submit();
}