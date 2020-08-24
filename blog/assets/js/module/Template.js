var Template = (function() {
    var _regex = /{{[\w\s:\|]+}}/g;
    
    return {
        render: function(html, data){
            var name;
            var matches = html.match(_regex);

            for (var i = 0; i < matches.length; i++) {
                name = matches[i].replace("{{", "").replace("}}", "");
                if (data[name]) {
                    html = html.replace(matches[i], data[name]);
                } else {
                    html = html.replace(matches[i], "");
                }
            }
            return html;
        }
    }
}());