$(document).ready(function(){
    if ($('body').hasClass('page-index')) {
        Clouds.run();
    }
    
    if ($('body').hasClass('page-about')) {
        StarBackground.init();
        StarScroller.start();
    }
});
$('body').on('mouseover', '.button-wrap', function(){
    $('.button-wrap').removeClass('active');
    $(this).addClass('active');
});
var Clouds = (function(Clouds, $) {
    
    var _clouds = [];
    var collisions = 0;
    return {
        run: function() {
            for (var i = 0; i < 10; i++) {
                var top = Math.floor(Math.random() * 200);
                var right = Math.floor(Math.random() * $(window).width());
                var rectangle = new Rectangle();
                var collision = false;
                var testWidth = 80;
                var testHeight = 80;
                for (var j = 0; j < _clouds.length; j++) {
                    rectangle.set(testWidth, testHeight, _clouds[j].top, _clouds[j].right);
                    collision = rectangle.contains(top, right) || 
                        rectangle.contains(top + testHeight, right) || 
                        rectangle.contains(top, right + testWidth) || 
                        rectangle.contains(top + testHeight, right + testWidth);
                    if (collision) {
                        break;
                    }
                }
                if (collision) {
                    collisions++;
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
            var _cloudElems = $('.cloud');
            
            if ($(window).width() > 768) { 
                checkClouds();
            }
            
            var cloudChecker = window.setInterval(checkClouds, 500);
            function checkClouds() {
                _cloudElems.addClass('running');

                _cloudElems.each(function(){
                    if ($(this).offset().left < 0 - $(this).width()) {
                        $(this).removeClass('running');
                        $(this).css('transform', 'translate(0, 0)');
                    }
                });
            }
        }
    }
    
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
        return ( (_self.right < right && _self.right + _self.width > right) &&
                (_self.top + _self.height > top && _self.top < top) );
    }
}
var StarBackground = (function(StarBackground){
    var _maxDiameter = 2;
    var _diameterUnits = 'px';
    var _starCount = 400;
    var _starClass = 'star';
    var _twinkle = 'twinkle';
    
    return {
        init: function(){
            var template;
        
            for (var i = 0; i < _starCount; i++) {
                template = $('#star-template').html();
                var diameter = Math.ceil(Math.random() * _maxDiameter) + _diameterUnits;
                var data = {
                    width: diameter,
                    height: diameter,
                    top: (Math.random() * 1000) / 10 + '%',
                    left: (Math.random() * 1000) / 10 + '%',
                    twinkle: (Math.random() > .75) ? 'twinkle' : '',
                    delay: Math.floor((Math.random() * 10)) / 10 + 's'
                }
                var html = Template.process(template, data);
                $('body').append(html);
            }
        }
    }
}(StarBackground || {}));

var StarScroller = (function(StarScroller){
    var _yTranslate = 50;
    var _yTranslateUnits = 'vh';
    var _stepInterval;
    var _stepIntervalPeriod = 200;
    var _self = this;
    var _step = function() {
        var yTranslate = _yTranslate-- + _yTranslateUnits;
        $('.main-text-wrap').css('transform', 'rotateX(45deg) translate(-50%, ' + yTranslate + ')');
    }
    
    return {
        stepInterval: {},
        
        start: function() {
            StarScroller.stepInterval = window.setInterval(_step, _stepIntervalPeriod);
        }
    }
}(StarScroller || {}));

var StarTitle = (function(StarTitle){
    return {
        init: function(){
            
        }
    }
}(StarTitle || {}));

var Template = (function(Template){
    var _regex = /{{[\w]+}}/g;
    return {
        process: function(template, data) {
            var name;
            var matches = template.match(_regex);
            for (var i = 0; i < matches.length; i++) {
                name = matches[i].replace("{{", "").replace("}}", "");
                if (data[name]) {
                    template = template.replace(matches[i], data[name]);
                } else {
                    template = template.replace(matches[i], "");
                }
            }
            return template;
        }
    }
}(Template || {}));
