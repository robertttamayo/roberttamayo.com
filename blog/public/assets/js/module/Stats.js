$(document).ready(function(){
    var endpoint = '/blog/api/';
    var postid = null;
    if (window.postid) {
        postid = window.postid;
    }
    var pagename = window.location.pathname;
    var date = new Date();
    var dateFormatted = '';
    dateFormatted = date.getFullYear();
    var month = date.getMonth();
    if (month < 10) {
        month = '0' + month;
    }
    var day = date.getDate();
    if (day < 10) {
        day = '0' + day;
    }
    dateFormatted += '-' + month + '-' + day;
    var data = {
        pagename: pagename,
        date: dateFormatted,
        postid: postid
    }
    console.log(data);
    $.ajax(endpoint + '?key&type=stats', {
        method: "POST",
        data: data
    }).done(function(response){
        console.log(response);
    });
});

// pagename, viewcount
// date, viewcount
// postid, viewcount