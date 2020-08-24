var undoStates = [];
var currentState;
var redoStates = [];

function modifyWith(property) {
    //alert("calling modifyWith");
    addUndoState();
    //start here
    var range = document.createRange();
    var selection = document.getElementById("frame").contentWindow.getSelection();
    range = selection.getRangeAt(0);
    var contents = range.cloneContents();
    var anode = range.startContainer;
    var hasProperty = false;
    if (anode != null) {
        if (hasParentOf(anode, property)) {
            removeProperty(range, property, selection);
            hasProperty = true;
        } else if (hasInitialChildOf(anode, property)) {
            removeProperty(range, property, selection);
            hasProperty = true;
        } else {
            addProperty(range, property, selection);
            hasProperty = false;
        }
    }
    adjustEditButtons(!hasProperty, property);
    cleanUp(document.getElementById("frame").contentWindow.document.getElementById("content"));
    focus();
}
function getTextNodes(node){
    //alert("calling getTextNodes");
    var child;
    var textNodes = [];
    var nodes = [];
    for (var i = 0; i < node.childNodes.length; i++){
        child = node.childNodes[i];
        if (child.nodeName === "#text"){
            //alert("found a child");
            textNodes.push(child);
        } else if (child.hasChildNodes()){
            nodes = getTextNodes(child);
            for (var j = 0; j < nodes.length; j++){
                textNodes.push(nodes[j]);
            }
        }
    }
    return textNodes;
}
function addProperty(range, property, selection) {
    //alert("calling addProperty");
    document.getElementById("frame")
                                 .contentWindow.document.getElementById("content").normalize();
    if (range.endContainer.nodeName === "#text"){
        var endAfter = range.endContainer.splitText(range.endOffset);
        //range.setEndAfter(endAfter);
    }
    if (range.startContainer.nodeName === "#text"){
        var startAfter = range.startContainer.splitText(range.startOffset);
        range.setStartAfter(startAfter);
    }
    var textNodes = getTextNodes(document.getElementById("frame")
                                 .contentWindow.document.getElementById("content"));
    var text;
    var textRange = document.createRange();
    var end;
    var start;
    for (var i = 0; i < textNodes.length; i++){
        text = textNodes[i];
        if (range.intersectsNode(text)){
            if (hasParentOf(text, property)) continue;
            textRange.selectNode(text);
            var wrapper = document.createElement(property);
            textRange.surroundContents(wrapper);
            if (start == null) {
                start = wrapper;
            }
            end = text;
        }
    }
    range.selectNodeContents(start);
    range.setEndAfter(end);
    selection.removeAllRanges();
    selection.addRange(range);
}
function removeProperty(range, property, selection) {
    //alert("calling removeProperty");
    document.getElementById("frame")
                                 .contentWindow.document.getElementById("content").normalize();
    if (range.endContainer.nodeName === "#text"){
        var endAfter = range.endContainer.splitText(range.endOffset);
    }
    if (range.startContainer.nodeName === "#text"){
        var startAfter = range.startContainer.splitText(range.startOffset);
        range.setStartAfter(startAfter);
    }
    var textNodes = getTextNodes(document.getElementById("frame")
                                 .contentWindow.document.getElementById("content"));
    var text;
    var textRange = document.createRange();
    var end;
    var start;
    for (var i = 0; i < textNodes.length; i++){
        text = textNodes[i];
        if (range.intersectsNode(text) && hasParentOf(text, property)){
            var p = property.toLowerCase();
            /*
            var propParent;
            var propNode = getHighestParentOf(text, property);
            propParent = propNode.parentNode;
            */
            var span = document.createElement("SPAN"); //need alternative for ranges that intersect but arent modified
            textRange.selectNode(text);
            textRange.surroundContents(span);
        }
    }
    var innerHTML = document.getElementById("frame")
                                 .contentWindow.document.getElementById("content").innerHTML.toString();

    var newHTML = innerHTML;
    var matches = innerHTML.match(/<span>/g);
    var num = matches.length;

    var startTag = "<em id='range-start'>";
    var endTag = "<em id='range-end'>";
    var closeTag = "</em>";

    for (var n = 0; n < num; n++){
        if (n == 0) {
            if (n == num - 1) { //might be only one text node
                newHTML = newHTML.replace("<span>", "</" + p + ">" + endTag + startTag);
                newHTML = newHTML.replace("</span>", closeTag + "" + closeTag +  "<" + p + ">");
            } else {
                newHTML = newHTML.replace("<span>", "</" + p + ">" + startTag);
                newHTML = newHTML.replace("</span>", closeTag + "<" + p + ">");
            }
        } else if (n == num - 1){
            newHTML = newHTML.replace("<span>", "</" + p + ">" + endTag);
            newHTML = newHTML.replace("</span>", closeTag + "<" + p + ">");
        } else {
            newHTML = newHTML.replace("<span>", "</" + p + ">");
            newHTML = newHTML.replace("</span>", "<" + p + ">");
        }
    }
    var innerHTML = document.getElementById("frame")
                                 .contentWindow.document.getElementById("content").innerHTML = newHTML;
    start = document.getElementById("frame")
                                 .contentWindow.document.getElementById("range-start");
    end = document.getElementById("frame")
                                 .contentWindow.document.getElementById("range-end");

    var startTextNode = document.createTextNode(start.textContent);
    start.parentNode.replaceChild(startTextNode, start);
    var endTextNode = document.createTextNode(end.textContent);
    end.parentNode.replaceChild(endTextNode, end);

    range.selectNodeContents(startTextNode);
    range.setEndAfter(endTextNode);
    selection.removeAllRanges();
    selection.addRange(range);
}

function changeTextAlign(align) {
    //alert("calling changeTextAlign");
    addUndoState();
    var selection = document.getElementById("frame").contentWindow.getSelection();
    var range = selection.getRangeAt(0);

    var divs = document.getElementById("frame").contentWindow.document
    .getElementById("content").getElementsByTagName("DIV");
    var headers = document.getElementById("frame").contentWindow.document
    .getElementById("content").getElementsByTagName("H3");

    for (var i = divs.length-1; i >= 0; i--){
        if (divs[i].textContent == "") {
            continue;
        }
        if (!range.intersectsNode(divs[i])) {
            continue;
        }
        divs[i].style.textAlign = align;
    }
    for (var i = headers.length-1; i >= 0; i--){
        if (headers[i].textContent == "") {
            continue;
        }
        if (!range.intersectsNode(headers[i])) {
            continue;
        }
        headers[i].style.textAlign = align;
    }
}
function myUndo(){
    //alert("calling myUndo");
    if (undoStates.length <= 0) {
        //alert("there is nothing to undo!");
        return;
    }
    var iframe = document.getElementById("frame");
    var iWindow = iframe.contentWindow;
    var focus = document.getElementById("content");
    redoStates.push(currentState);
    focus.innerHTML = undoStates.pop();
    //focus.innerHTML = undoStates[undoStates.length - 1];
    currentState = focus.innerHTML;

    adjustUndoButtons();
}
function myRedo(){
    //alert("calling myRedo");
    if (redoStates.length <= 0) {
        //alert("there is nothing to redo!");
        return;
    }
    var iframe = document.getElementById("frame");
    var iWindow = iframe.contentWindow;
    var focus = document.getElementById("content");
    
    //alert(redoStates[redoStates.length - 1]);
    focus.innerHTML = redoStates[redoStates.length - 1];
    undoStates.push(redoStates.pop());
    
    adjustUndoButtons();
}

function changeWriter(type){
    //alert("calling changeWriter");
    //might not add a different state...
    addUndoState();
    var isType = false;
    var selection = document.getElementById("frame").contentWindow.getSelection();
    var range = selection.getRangeAt(0);
    var anode = range.startContainer;
    
    var tag;
    var old;
    
    switch(type){
        case "header":
            tag = "H3";
            old = "DIV";
            break;
        case "normal":
            tag = "DIV";
            old = "H3";
            break;
    }
    
    var divs = document.getElementById("frame").contentWindow.document
    .getElementById("content").getElementsByTagName(old);
    var replacement;
    for (var i = divs.length-1; i >= 0; i--){
        if (divs[i].textContent == "") {
            continue;
        }
        if (!range.intersectsNode(divs[i])) {
            continue;
        }
        replacement = document.createElement(tag);
        replacement.innerHTML = divs[i].innerHTML;
        replacement.textContent = divs[i].textContent;
        divs[i].parentNode.replaceChild(replacement, divs[i]);
    }
    
}

function cleanUp(node){
    //alert("calling cleanUp");
    if (!node.hasChildNodes() && (node.nodeName != "BR" && node.nodeName != "#text")) {
        //alert("node.nodeName " + node.nodeName + "\nnode.textContent: " + node.textContent);
        node.parentNode.removeChild(node);
    } else if ((node.textContent == "" || node.textContent == " ") 
               && node.nodeName != "BR" && node.nodeName != "DIV" && node.nodeName != "IMG"){ 
        //if (node.firstChild.nodeName != "BR"){
            node.parentNode.removeChild(node);

    } else {
        for (var i = 0; i < node.childNodes.length; i++){
            cleanUp(node.childNodes[i]);
        }
    }
}

function adjustEditButtons(add, property) {
    //alert("calling adjustEditButtons on rdnewpost.php");
    if (property === italic) {
        if (add) {
            document.getElementById("italic").style.backgroundColor = "#dcdcdc";
        } else {
            document.getElementById("italic").style.backgroundColor = "white";
        }
    } else if (property === bold) {
        if (add) {
            document.getElementById("bold").style.backgroundColor = "#dcdcdc";
        } else {
            document.getElementById("bold").style.backgroundColor = "white";
        }
    } else if (property === underline) {
        if (add) {
            document.getElementById("underline").style.backgroundColor = "#dcdcdc";
        } else {
            document.getElementById("underline").style.backgroundColor = "white";
        }
    } else if (property === strike) {
        if (add) {
            document.getElementById("strike").style.backgroundColor = "#dcdcdc";
        } else {
            document.getElementById("strike").style.backgroundColor = "white";
        }
   }
}

function isNodeNameSpecial(node){
    //alert("calling isNodeNameSpecial");
    return (
        node.nodeName === bold ||
        node.nodeName === italic ||
        node.nodeName === underline ||
        node.nodeName === strike ||
        node.nodeName === "SPAN" ||
        node.nodeName === "A" ||
        node.nodeName === "LI" //testing!!!!
           );
}

function hasParentOf(anode, property) {
    //alert("calling hasParentOf");
    if (anode.parentNode) {
        if (anode.parentNode.nodeName === property) {
            return true;
        } else if (isNodeNameSpecial(anode.parentNode)) {
            return hasParentOf(anode.parentNode, property);
        } else if (anode.nodeName === property) {
            return true;
        } else {
            //alert("parentof parent: " + anode.parentNode.nodeName);
            //alert("anode.parentNode.parentNode.nodeName: " + anode.parentNode.parentNode.nodeName);
        }
    } else if (anode.nodeName === property) {
        return true;
    }

    return false;
}

function hasInitialChildOf(node, property) {
    //alert("calling hasInitialChildOf");
    if (node.nodeName == property) {
        return node;
    }
    /*
    if (node.hasChildNodes()) {
        for (var i = 0; i < node.childNodes.length; i++) {
            var child = node.childNodes[i];
            var candidate = hasInitialChildOf(child, property);
            if (candidate != null) {
                return candidate;
            }
        }
    }
    */
    if (node.hasChildNodes()) {
        var child = node.firstChild;
        var candidate = hasInitialChildOf(child, property);
        if (candidate != null) {
            return candidate;
        }
    }
    return null;
}
function initCurrentState(){
    //alert("calling initCurrentState");
    var iframe = document.getElementById("frame");
    var iWindow = iframe.contentWindow;
    
    var focus = document.getElementById("content");
    currentState = focus.innerHTML;
    //alert(currentState);
}
function addUndoState(){
    //alert("calling addUndoState");
    var iframe = document.getElementById("frame");
    var iWindow = iframe.contentWindow;
    
    var focus = document.getElementById("content");
    undoStates.push(currentState);
    currentState = focus.innerHTML;
    //undoStates.push(focus.innerHTML);
    //alert("undoStates length: " + undoStates.length);
    if (undoStates.length > 20) {
        undoStates.shift();
    }
    redoStates = [];
    adjustUndoButtons();
}
var shadow = "0px 1px 2px";
var canColor = "#222";
var cannotColor = "#999999";
function adjustUndoButtons(){
    //alert("calling adjustUndoButtons");
    var undoButton = document.getElementById("undo");
    var redoButton = document.getElementById("redo");
    if (undoStates.length <= 0) {
        undoButton.style.color = cannotColor;
        undoButton.style.boxShadow = shadow + cannotColor;
    } else {
        undoButton.style.color = "black";
        undoButton.style.boxShadow = shadow + canColor;
    }
    if (redoStates.length <= 0) {
        redoButton.style.color = cannotColor;
        redoButton.style.boxShadow = shadow + cannotColor;
    } else {
        redoButton.style.color = "black";
        redoButton.style.boxShadow = shadow + canColor;
    }
}
function focus(){
    //alert("calling focus");
    document.getElementById("frame").contentWindow.document.getElementById("content").focus();
}
var imgPosition;
function showDialog(){
    //alert("calling showDialog");
    var selection = document.getElementById("frame").contentWindow.getSelection();
    var range = selection.getRangeAt(0);
    var anode = range.startContainer;
    imgPosition = range;
    document.getElementById("upload-preview").src = "";
    document.getElementById("img-dialog").showModal();
}
function cancelDialog(){
    //alert("calling cancelDialog");
    document.getElementById("img-dialog").close();
}
function closeDialog(){
    //alert("calling closeDialog");
    addUndoState();
    var imgPath = document.getElementById("upload-preview").src;
    var sepNode = document.createElement("DIV");
    //sepNode.setAttribute("onclick", "showImageOptions(this);");
    sepNode.className = "separator";
    sepNode.style.clear = "both";
    sepNode.style.textAlign = "center";
    sepNode.style.zIndex = "1";
    sepNode.style.width = "360px";
    sepNode.style.height = "270px";
    sepNode.style.cursor = "pointer";
    sepNode.style.margin = "auto";
    
    var imgNode = document.createElement("img");
    imgNode.src = imgPath;
    sepNode.appendChild(imgNode);
    imgNode.style.maxWidth = "100%";
    imgNode.style.maxHeight = "100%";
    imgNode.style.position = "absolute";
    imgNode.style.top = "50%";
    imgNode.style.left = "50%";
    imgNode.style.objectFit = "contain";
    imgNode.style.transform = "translate(-50%, -50%)";
    
    
    //document.getElementById("frame").contentWindow.document.getElementById("content").appendChild(sepNode);
    imgPosition.insertNode(sepNode);
    sepNode.setAttribute("onclick", "showImageOptions(this);");

    document.getElementById("img-dialog").close();

    return false;
}

function uploadPreview(imgPath){
    //alert("calling uploadPreview");
    var fd = new FormData(document.getElementById("image-form"));
    //fd.append("label", "WEBUPLOAD");
    $.ajax({
      url: "uploadimage.php",
      type: "POST",
      data: fd,
        datatype: 'json',
      enctype: 'multipart/form-data',
      processData: false,  // tell jQuery not to process the data
      contentType: false   // tell jQuery not to set contentType
    }).done(function( data ) {
        var imgPath = data;
        alert("imgPath: " + imgPath);
        document.getElementById("upload-preview").src = imgPath;

    });
}
function printChanges(){
    //alert("calling printChanges");
    //document.getElementById("preview").innerHTML = document.getElementById("content").innerHTML;
    var raw = document.getElementById("frame").contentWindow.document.getElementById("content").innerHTML;
    document.getElementById("underhood").textContent = raw;
    document.getElementById("frame").contentWindow.document.getElementById("content").focus();
}
function exitAndSave(){
    if (confirm("Do you really want to exit?")){
        var raw = document.getElementById("frame").contentWindow.document
            .getElementById("content").innerHTML;
        var title = document.getElementById("title").value;
        var draft = "draft";
        var postid = document.getElementById("postid-hidden").textContent;
        $.post("rduploadpost.php", 
        {
          name: title,
          file: raw,
            draft: draft,
            postid: postid
        },
        function(data){ //callback for debugging
            alert(data);
        });
        window.location.href = "http://www.thenoticed.com/rdportal.php";
    }
}
function exitNoSave(){
    if (confirm("Do you really want to exit? Your changes will not be saved.")){
        window.location.href = "http://www.thenoticed.com/rdportal.php";
    }
}
function save(postId){
    var raw = document.getElementById("frame").contentWindow.document
        .getElementById("content").innerHTML;
    var title = document.getElementById("title").value;
    var draft = "draft";
    var postid = document.getElementById("postid-hidden").textContent;
    $.post("rduploadpost.php", 
    {
      name: title,
      file: raw,
        draft: draft,
        postid: postid
    },
    function(data){ //callback for debugging
        alert(data);
    });
}
function saveFirstTime(){
    var raw = document.getElementById("frame").contentWindow.document
        .getElementById("content").innerHTML;
    var title = document.getElementById("title").value;
    var draft = "draft";
    var postid = "";
    $.post("rduploadpost.php", 
    {
      name: title,
      file: raw,
        draft: draft,
        postid: postid
    },
    function(data){ //callback for debugging
        //alert(data);
        window.location.href = "http://www.thenoticed.com/rdeditpost.php?postid=" + data;
    });
}
function updatePost(postId){
    var raw = document.getElementById("frame").contentWindow.document
        .getElementById("content").innerHTML;
    var title = document.getElementById("title").value;
    var draft = "published";
    var postid = document.getElementById("postid-hidden").textContent;
    $.post("rdupdatepost.php", 
    {
      name: title,
      file: raw,
        draft: draft,
        postid: postId
    },
    function(data){ //callback for debugging
        alert(data);
    });
    window.location.href = "http://www.thenoticed.com/rdportal.php";
}
function makeDraft(postId){
    var raw = document.getElementById("frame").contentWindow.document
        .getElementById("content").innerHTML;
    var title = document.getElementById("title").value;
    var draft = "draft";
    $.post("rdupdatepost.php", 
    {
      name: title,
      file: raw,
        draft: draft,
        postid: postId
    },
    function(data){ //callback for debugging
        alert(data);
    });
    window.location.href = "http://www.thenoticed.com/rdportal.php";
}
function publishDraft(postid){
    var raw = document.getElementById("frame").contentWindow.document
    .getElementById("content").innerHTML;
    var title = document.getElementById("title").value;
    var draft = "published";
    if (confirm("Are you sure?")) $.post("rdupdatepost.php", 
    {
      name: title,
      file: raw,
        draft: draft,
        postid: postid
    },
    function(data){ //callback for debugging
        alert(data);
        window.location.href = "http://www.thenoticed.com/rdportal.php";
    });
}
function preview(postid){
    
}