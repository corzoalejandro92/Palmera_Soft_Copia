
 var translatePos = {x: 0, y: 0};

 var scale = 1.0;
 var scaleMultiplier = 0.8;
 var startDragOffset = {};
 var mouseDown = false;
 var heatMap;



$('body').ready(function(){
InitHeatMap(_data,area);
});


function InitHeatMap(_data,area) {
    area = area.replace(/&quot;/g,'"');
    area = area.replace(/\\/g,'\\\\');

var data= {
    area: JSON.parse(area),
    data: JSON.parse(_data)
};

$("#wrapper").mouseover(function(e) {
    $('#status').html(e.pageX + ', ' + e.pageY);
    });

    var bgzise= {
        x: data.area.file.imageProp[0], y: data.area.file.imageProp[1]
    }
    $("#wrapper").css({height: (bgzise.y+30), width: (bgzise.x+30)});

var html = "<canvas id='heatcanvas' width='"+bgzise.x+"' height='"+bgzise.y+"'></canvas>";
var subhtml = "<canvas id='subcanvas' width='"+bgzise.x+"' height='"+bgzise.y+"'></canvas>";
$("#sectionCanvas").html(html)
$("#sectionCanvas").prepend(subhtml);

translatePos.y = 0;
translatePos.x = 0;

//mapa de calor 1
heatMap = simpleheat('heatcanvas', bgzise.x, bgzise.y).data(data.data).max(18);

var background = new Image();
toDataURL(data.area.file.url, (dataUrl)=> {
    background.src = dataUrl.replace('png;base64', 'jpeg;base64');
    background.onload = ()=> {
        heatMap.draw(0.3,scale, translatePos, background, bgzise);
    }
    });

// Draw whatever else over top of it on the canvas.
window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
    window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
}

function simpleheat(canvas, width, height) {
if (!(this instanceof simpleheat)) return new simpleheat(canvas, width, height);

this._canvas = canvas = typeof canvas === 'string' ? document.getElementById(canvas) : canvas;

this._ctx = canvas.getContext('2d');
this._width = width;
this._height = height;

this._max = 1;
this._data = [];
}

simpleheat.prototype = {

defaultRadius:15,
defaultGradient: {
    0.1: 'blue',
    0.2: 'cyan',
    0.4: 'lime',
    0.5: 'yellow',
    0.8: 'orange',
    1.0: 'red'
},

data: function(data) {
    this._data = data;
    return this;
},

max: function(max) {
    this._max = max;
    return this;
},

add: function(point) {
    this._data.push(point);
    return this;
},

clear: function() {
    this._data = [];
    return this;
},

radius: function(r, blur, scale, translatePos) {
    blur = 20;

    // create a grayscale blurred circle image that we'll use for drawing points
    var circle = this._circle = this._createCanvas(),
        ctx = circle.getContext('2d'),
        r2 = this._r = r + blur;

    circle.width = circle.height = r2 * 2;

    ctx.shadowOffsetX = ctx.shadowOffsetY = r2 * 2;
    ctx.shadowBlur = blur;
    ctx.shadowColor = 'black';
    ctx.beginPath();
    ctx.arc(-r2, -r2, r, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();

    

    return this;
},

resize: function() {
    this._width = this._canvas.width;
    this._height = this._canvas.height;
},

gradient: function(grad) {
    // create a 256x1 gradient that we'll use to turn a grayscale heatmap into a colored one
    var canvas = this._createCanvas(),
        ctx = canvas.getContext('2d'),
        gradient = ctx.createLinearGradient(0, 0, 0, 256);

    canvas.width = 1;
    canvas.height = 256;

    for (var i in grad) {
        gradient.addColorStop(+i, grad[i]);
    }

    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, 1, 256);

    this._grad = ctx.getImageData(0, 0, 1, 256).data;

    return this;
},

draw: function(minOpacity, scale, translatePos, background,bgsize) {
    if (!this._circle) this.radius(this.defaultRadius, 20,scale, translatePos);
    if (!this._grad) this.gradient(this.defaultGradient);

    var ctx = this._ctx;
    //var subctx = ctx;

    
    var subctx    = subcanvas.getContext('2d');
    
    subctx.clearRect(0, 0, this._width, this._height);
    // draw a grayscale heatmap by putting a blurred circle at each data point
    for (var i = 0, len = this._data.length, p; i < len; i++) {
        p = this._data[i];
        subctx.globalAlpha = Math.min(Math.max(p[2] / this._max, 0.1), 1);
        subctx.drawImage(this._circle, p[0] - this._r, p[1] - this._r);
        
    }

    // colorize the heatmap, using opacity value of each pixel to get the right color from our gradient
    var colored = subctx.getImageData(0, 0, this._width, this._height);
    this._colorize(colored.data, this._grad);
    //this._white2transparent(colored.data);
    subctx.putImageData(colored, 0, 0);

    ctx.save();
    ctx.clearRect(0, 0, this._width, this._height);
    ctx.translate(translatePos.x, translatePos.y);
    ctx.scale(scale, scale);
    
    subctx.globalAlpha = 1;
    ctx.drawImage( background, 0,0, bgsize.x, bgsize.y );
    ctx.drawImage( subctx.canvas, 0, 0 );
    $('#subcanvas').hide(); 
    ctx.restore();    
    var canvas = document.getElementById("heatcanvas");
    $("#wrapper").remove();  
    var img = new Image();
    img.src = canvas.toDataURL("image/png");
    document.body.appendChild(img);
    return this;
},

_white2transparent: function(pixels)
{


    var r=0, g=1, b=2,a=3;
    for (var p = 0; p<pixels.length; p+=4)
    {
        if (
            pixels[p+r] == 255 &&
            pixels[p+g] == 255 &&
            pixels[p+b] == 255) // if white then change alpha to 0
        {pixels[p+a] = 0;}
    }

},

_colorize: function(pixels, gradient) {
    for (var i = 0, len = pixels.length, j; i < len; i += 4) {
        j = pixels[i + 3] * 4; // get gradient color from opacity value

        if (j) {
            pixels[i] = gradient[j];
            pixels[i + 1] = gradient[j + 1];
            pixels[i + 2] = gradient[j + 2];
        }
    }
},

_createCanvas: function() {
    if (typeof document !== 'undefined') {
        return document.createElement('canvas');
    } else {
        // create a new canvas instance in node.js
        // the canvas class needs to have a default constructor without any parameter
        return new this._canvas.constructor();
    }
}
};


function toDataURL(url, callback) {
var xhr = new XMLHttpRequest();
xhr.onload = function() {
    var reader = new FileReader();
    reader.onloadend = function() {
    callback(reader.result);
    }
    reader.readAsDataURL(xhr.response);
};
xhr.open('GET', url);
xhr.responseType = 'blob';
xhr.send();
}