$(document).ready(function(){
    $('.cloud').addClass('running');
});
$('body').on('mouseover', '.button-wrap', function(){
    $('.button-wrap').removeClass('active');
    $(this).addClass('active');
});
var Clouds = (function(Clouds, $) {
    
    var _clouds = [];
    var collisions = 0;
    for (var i = 0; i < 10; i++) {
        var top = Math.floor(Math.random() * 200);
        var right = Math.floor(Math.random() * $(window).width());
        var rectangle = new Rectangle();
        var collision = false;
        console.log('i: ' + i);
        for (var j = 0; j < _clouds.length; j++) {
            rectangle.set(90, 90, _clouds[j].top, _clouds[j].right);
//            console.log(rectangle);
            console.log('j: ' + j);
            collision = rectangle.contains(top, right);
            if (collision) {
                break;
            }
        }
        if (collision) {
            collisions++;
            console.log('collision!');
            continue;
        }
        _clouds.push({top: top, right: right});
        
        var div = $(document.createElement('div'));
        div.addClass('cloud');
        if (Math.random() < .15) {
            div.addClass('double');
        }
        div.css('top', top + 'px');
        div.css('transform', 'translate(-' + right + 'px, 0');
        div.appendTo($('body'));
    }
    console.log('collisions: ' + collisions);
    
}(Clouds || {}, jQuery));
    

function Rectangle(width, height, top, right){
    this.width = width || 0;
    this.height = height || 0;
    this.right = right || 0;
    this.top = top || 0;
    
    _self = this;
    this.set = function(width, height, top, right) {
        _self.width = width || 0;
        _self.height = height || 0;
        _self.right = right || 0;
        _self.top = top || 0;
    }
    
    this.contains = function(top, right) {
        console.log(_self);
        console.log('top: ' + top);
        console.log('right: ' + right);
        return ( (_self.right < right && _self.right + _self.width > right) &&
                (_self.top + _self.height > top && _self.top < top) );
    }
}