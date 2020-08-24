var Guests = (function(){
    var url = "https://www.roberttamayo.com/blog/api/";

    var userCookieName = "bobblogguest";
    var loggedInClassName = 'user-signed-in';
    var UserInfo = {
        guestname: '',
        guestemail: '',
        guestid: ''
    }
    
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
    
    var showUserInfo = function(){
        var info = getCookie(userCookieName);
        if (info !== '') {
            console.log(JSON.parse(info));
        } else {
            console.log("no user information available");
        }
    }
    
    var logInAction = function(guestname, guestemail, callback) {
        var guestdata = {
            guestname: guestname,
            guestemail: guestemail
        }
        var sendUrl = url + "?key";
        sendUrl += "&type=guest&guestname=" + guestname;
        senUrl += "&guestemail=" + guestemail;
        $.ajax({
            url: sendUrl
        }).done(function(_data){
            callback(data);
        });
        setCookie(userCookieName, JSON.stringify(guestdata), 365);
        $('body').removeClass(loggedOutClassName);
    }
    var logOutAction = function() {
        document.cookie = userCookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        $('body').addClass(loggedOutClassName);
    }
    $(document).ready(function(){
        var cookie = getCookie(userCookieName);
        console.log("we are a here");
        if (cookie == '') {
            
        } else {
            $('body').addClass(loggedInClassName);
            var user = JSON.parse(cookie);
            UserInfo.guestemail = user.guestemail;
            UserInfo.guestname = user.guestname;
            UserInfo.guestid = user.guestid;
        }
    });
    return {
        signedIn: false,
        logIn: logInAction,
        logOut: logOutAction,
        userInfo: showUserInfo
    }
}());