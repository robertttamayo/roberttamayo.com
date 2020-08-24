var Comments = (function(){
    /**
    config start
    */
    var url = "https://www.roberttamayo.com/blog/api/";
    var userCookieName = "bobblogguest";
    /**
    config end
    */
    
    var setCookie = function(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+ d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    
    var getCookie = function(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i <ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
    var sendCount = 0;
    var _init = function(){
        function getComments(limit, offset) {
            var sendUrl = url;
            sendUrl += '?key&type=comment';
            if (limit) {
                sendUrl += '&limit=' + limit;
            }
            if (offset) {
                sendUrl += '&offset=' + offset;
            }
            sendUrl += '&postid=' + postid;
            $.ajax(sendUrl,{
                
            }).done(function(_data){
                _data = JSON.parse(_data);
                var template = $('#comment-template').html();
                var html = "";
                var styleIndex = 1;
                for (var i = 0; i < _data.length; i++) {
                    _data[i].styleIndex = styleIndex;
                    console.log(_data[i]);
                    html += Template.render(template, _data[i]);
                    styleIndex = 1 + (styleIndex % 4);
                }
                $('.blog-comment-section-comments').append(html);
            });
        }
        getComments();
        var anchor;
        $('body').on('click', '.submit-comment', function(){
            console.log("it's a click!");
            var isReply = false;
            var replyTo = '';
            var commentContent = '';
            anchor = $(this).closest('.comment-form');
            if ($(this).hasClass('submit-reply')) {
                isReply = true;
                replyTo = anchor.attr('data-replyto');
                console.log('it is a reply');
            }
            var data = {};
            var cookie = getCookie(userCookieName);
            if (cookie !== '') {
                submitComment();
            } else {
                console.log('logging in guest first, doing guest API call');
                var guestname = anchor.find('input[name=guestname]').val();
                var guestemail = anchor.find('input[name=guestemail]').val();
                sendUrl = url;
                sendUrl += '?key&type=guest';
                sendUrl += '&guestemail=' + guestemail;
                sendUrl += '&guestname=' + guestname;
                $.ajax(sendUrl,{
                    
                }).done(function(_data){
                    _data = JSON.parse(_data);
                    console.log(_data);
                    if (_data.lastinsertid) {
                        var guestid = _data.lastinsertid;
                        var guestdata = {};
                        guestdata.guestid = guestid;
                        guestdata.guestname = guestname;
                        guestdata.guestemail = guestemail;
                        setCookie(userCookieName, JSON.stringify(guestdata));
                        submitComment();
                    } else {
                        alert('something went wrong');
                    }
                    
                });
            }
            
            function submitComment(){
                sendCount++;
                var cookie = getCookie(userCookieName);
                var cookieData = JSON.parse(cookie);
                var data = {};
                var _commentContent = anchor.find('textarea[name=comment]').val();
                var _guestname = cookieData.guestname;
                data.guestemail = cookieData.guestemail;
                data.guestname = cookieData.guestname;
                data.guestid = cookieData.guestid;
                data.comment = _commentContent;
                var sendUrl = url + '?key&type=comment';
                sendUrl += '&guestname=' + cookieData.guestname;
                sendUrl += '&guestemail=' + cookieData.guestemail;
                sendUrl += '&guestid=' + cookieData.guestid;
                sendUrl += '&postid=' + postid;
                if (isReply) {
                    sendUrl += '&replyto=' + replyTo;
                }
                $.ajax(sendUrl,{
                    method: "POST",
                    data: data
                }).done(function(_data){
                    _data = JSON.parse(_data);
                    console.log(_data);
                    if (isReply) {
                        var blogComment =  $('.blog-comment[data-comment-id=' + replyTo + ']');
                        blogComment.removeClass('reply-active');
                        
                        if (blogComment
                                .find('.view-replies')
                                    .attr('data-replies-loaded') == 'false') {
                            blogComment.find('.view-replies').click();
                        } else {
                            var data = {
                                commentid: _data.lastinsertid,
                                guestname: _guestname,
                                comment: _commentContent
                            }
                            var template = $('#comment-template').html();
                            var html = Template.render(template, data);
                            blogComment.find('.comment-replies-content').append(html);
                            blogComment.attr('data-replies-visible', 'true');
                        }
                    } else {
                        getComments(100, $('.blog-comment').length);
                    }
//                    $('.blog-comment-section-comments').append(html);
                });
            }
        });
        $('body').on('click', '.view-replies', function(){
            console.log('view-replies');
            var blogComment = $(this).closest('.blog-comment');
            var commentid = blogComment.attr('data-comment-id');
            
            if ($(this).attr('data-replies-loaded') == 'true') {
                blogComment.attr('data-replies-visible', "true");
            } else {
                var sendUrl = url;
                sendUrl += '?key&type=replies';
                sendUrl += '&commentid=' + commentid;
                var trigger = $(this);
                $.ajax(sendUrl, {

                }).done(function(_data){
                    _data = JSON.parse(_data);
                    var template = $('#comment-template').html();
                    var html = "";
                    for (var i = 0; i < _data.length; i++) {
                        html += Template.render(template, _data[i]);
                    }
                    blogComment.attr('data-replies-visible', "true");
                    trigger.attr('data-replies-loaded', true);
                    $('.comment-replies-content[data-commentid=' + commentid + ']').append(html);
                });
            }
        });
        $('body').on('click', '.hide-replies', function(){
            console.log('hide-replies');
            var blogComment = $(this).closest('.blog-comment');
            blogComment.attr('data-replies-visible', "false");
        });
        $('body').on('click', '.comment-reply', function(){
            var blogComment = $(this).closest('.blog-comment');
            blogComment.addClass('reply-active');
        });
    }
    $(document).ready(function(){
        _init();
    });
    
    return {
        
    }
    
}());