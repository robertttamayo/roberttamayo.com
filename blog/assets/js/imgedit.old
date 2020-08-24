function showImageOptions(imgHolder){
    if (document.getElementById('img-options-id') != null){
        var removeImgOptions = document.getElementById('img-options-id');
        removeImgOptions.parentNode.removeAttribute("id");
        removeImgOptions.parentNode.removeChild(removeImgOptions);
    }
    
    imgHolder.id = "img-separator";
    var div = "DIV";
    var button = "BUTTON";
    var img = imgHolder.firstChild;
    //assume imgHolder.firstChild = an img node
    //first create an options node
    var options     = create(div);

    var size        = create(div);
    var small       = create(button);
    var med         = create(button);
    var large       = create(button);
    var xlarge      = create(button);

    var position    = create(div);
    var left        = create(button);
    var mid         = create(button);
    var right       = create(button);

    var tools       = create(div);
    var caption     = create(button);
    var remove      = create(button);

    small.appendChild(text("S"));
    med.appendChild(text("M"));
    large.appendChild(text("L"));
    xlarge.appendChild(text("XL"));

    left.appendChild(text("Left"));
    mid.appendChild(text("Middle"));
    right.appendChild(text("Right"));

    caption.appendChild(text("Caption"));
    remove.appendChild(text("Remove"));

    size.appendChild(small);
    size.appendChild(med);
    size.appendChild(large);
    size.appendChild(xlarge);

    position.appendChild(left);
    position.appendChild(mid);
    position.appendChild(right);

    tools.appendChild(caption);
    tools.appendChild(remove);

    options.appendChild(size);
    options.appendChild(position);
    options.appendChild(tools);

    small.setAttribute("onclick", "imgResize('small', document.getElementById('img-separator'));"); 
    med.setAttribute("onclick", "imgResize('med', document.getElementById('img-separator'));"); 
    large.setAttribute("onclick", "imgResize('large', document.getElementById('img-separator'));");
    xlarge.setAttribute("onclick", "imgResize('xlarge', document.getElementById('img-separator'));");

    left.setAttribute("onclick", "imgPosition('left', document.getElementById('img-separator'));");
    mid.setAttribute("onclick", "imgPosition('mid', document.getElementById('img-separator'));");
    right.setAttribute("onclick", "imgPosition('right', document.getElementById('img-separator'));");

    caption.setAttribute("onclick", "imgCaption(document.getElementById('img-separator'));");
    remove.setAttribute("onclick", "imgRemove(document.getElementById('img-separator'));");

    options.id = "img-options-id";
    options.className = "img-options";
    options.style.position = "fixed";
    options.style.display = "block";
    options.style.top = "75%";
    options.style.left = "50%";
    options.style.transform = "translate(-50%, -50%)";
    options.style.WebkitTransform = "translate (-50%, -50%)";
    options.style.backgroundColor = "rgb(15, 192, 252)";
    
    var close = create(button);
    close.style.border="none";
    close.style.backgroundColor="rgb(15, 192, 252)";
    close.style.width="16px";
    close.style.color = "white";
    close.setAttribute("onclick", "closeImageOptions(document.getElementsByClassName('img-options')[0])");
    close.appendChild(text("X"));
    options.appendChild(close);

    imgHolder.parentNode.appendChild(options); 
}
function closeImageOptions(options){
    var imgHolder = document.getElementById('img-separator');
    imgHolder.removeAttribute("id");
    options.parentNode.removeChild(options);
}
function create(tagName){
    return document.createElement(tagName);
}
function text(text){
    return document.createTextNode(text);
}
function imgResize(size, imgHolder){
    
    var imgNode = document.createElement("img");
    imgNode.src = imgHolder.firstChild.src;
    var w = imgNode.width;
    var h = imgNode.height;
    //var w = imgHolder.firstChild.width;
    //var h = imgHolder.firstChild.height;
    var ratio = w/h;
    //console.log("w, h: " + w + ", " + h + "\nratio: " + ratio);
    var wPixels = "320px";
    var yPixels = "240px";
    switch (size) {
        case "small":
            wPixels = "240px";
            imgH = Math.floor(240/ratio);
            yPixels = imgH + "px";
            break;
        case "med":
            wPixels = "360px";
            imgH = Math.floor(360/ratio);
            yPixels = imgH + "px";
            break;
        case "large":
            wPixels = "480px";
            imgH = Math.floor(480/ratio);
            yPixels = imgH + "px";
            break;
        case "xlarge":
            wPixels = "592px";
            imgH = Math.floor(592/ratio);
            yPixels = imgH + "px";
            //alert(yPixels);
            break;
        case "large-":
            wPixels = "592px";
            imgH = Math.floor(240*ratio);
            yPixels = imgH + "px";
            break;
    }
    imgHolder.style.width = wPixels;
    imgHolder.style.height = yPixels;
    
}
function imgPosition(position, imgHolder){
    var left = "auto";
    var right = "auto";

    switch (position) {
        case "left":
            left = "2px";
            break;
        case "right":
            right = "2px";
            break;
    }

    imgHolder.style.marginLeft = left;
    imgHolder.style.marginRight = right;
}
function imgCaption(imgHolder){
    alert("This function is not available yet.");
}
function imgRemove(imgHolder){
    if (confirm("Remove this image?")) {
        closeImageOptions(document.getElementsByClassName('img-options')[0]);
        imgHolder.parentNode.removeChild(imgHolder);
    }
}