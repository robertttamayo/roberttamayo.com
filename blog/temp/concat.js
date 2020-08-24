;var WELCOME = 1;
var EDITOR = 2;

var postid;

var iconLeftJustify = "<i class=\"fa fa-align-left\" aria-hidden=\"true\"></i>";
var iconCenterJustify = "<i class=\"fa fa-align-center\" aria-hidden=\"true\"></i>";
var iconRightJustify = "<i class=\"fa fa-align-right\" aria-hidden=\"true\"></i>";
var iconRemove = "<i class=\"fa fa-trash\" aria-hidden=\"true\"></i>";
var iconClose = "<i class=\"fa fa-window-close-o\" aria-hidden=\"true\"></i>";

var ChangeCatcher = function($) {

    var _unsavedChanges = false;
    var _window = window;
    var _this = this;
    
    $(document).on('focus click', '.change-watch', function(){
        console.log('focus or click on .change-watch element');
        _this._unsavedChanges = true;
        _setWatcher();
    });

    $(document).on('click', '.change-reset', function(){
        console.log('click on .change-reset element');
        _this._unsavedChanges = false;
        _unsetWatcher();
    });
    
    var _setWatcher = function() {
        console.log('Unsaved changes');
        _window.onbeforeunload = function() {
            return 'There are unsaved changes. Are you sure you want to leave?';
        }
    }
    var _unsetWatcher = function() {
        console.log('Resetting changes. No unsaved changes');
        _window.onbeforeunload = null;
    }
    
    return {
        get unsavedChanges() {
            return _unsavedChanges;
        },
        set unsavedChanges(v) {
            if (typeof(v) == 'boolean') {
                _unsavedChanges = v;
                if (_unsavedChanges == true) {
                    _setWatcher();
                } else {
                    _unsetWatcher();
                }
            } else {
                console.error('invalid parameter passed. boolean expected, received ' + typeof(v));
            }
        },
        reset: function() {
            _unsavedChanges = false;
        }
    }
}
var changeCatcher = new ChangeCatcher(jQuery);

var BobBlog = function() {
    var _State = {
        home: 0,
        edit: 1,
        stats: 2
    }
    var _state = 0;
    return {
        get state() {
            return _state;
        }
    }
}

$(document).ready(function(){
    $("#main-container").html("").load(homeUrl + "src/welcome.php", function(){
        mainContainerCallbacks();
    });
    $(".home-button").on("click", function(){
        $("#main-container").html("").load(homeUrl + "src/welcome.php", function(){
            mainContainerCallbacks();
        });
    });
    $(".new-post").on("click", function(){
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php", function(){
            load("stop");
            editContainerCallbacks();
        });
    });
    $(".post-edit").on("click", function(){
        var postid = $(this).data("postid");
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php?postid=" + postid, function(){
            editContainerCallbacks();
            load("stop");
        });
    });
});
function catPopCallbacks(){
    $(".create-tag").on("click", function(){
        console.log("event listener fired");
        saveCat();
    });
    $(".tag-name").on("click", function(){
        if ($(this).hasClass("active-tag")) {
            removeCatFromPost($(this), $("#postid-hidden").text());
        } else {
            addCatToPost($(this), $("#postid-hidden").text());
        }
    });
    $(".dim, #close-tag-pop").on("click", function(){
        $(".tag-pop-wrap").fadeOut("medium", function(){
            $(this).remove();
        });
    });
}
function tagPopCallbacks(){
    $(".create-tag").on("click", function(){
        saveTag();
    });
    $(".tag-name").on("click", function(){
        if ($(this).hasClass("active-tag")) {
            removeTagFromPost($(this), $("#postid-hidden").text());
        } else {
            addTagToPost($(this), $("#postid-hidden").text());
        }
    });
    $(".dim, #close-tag-pop").on("click", function(){
        $(".tags-pop-wrap").fadeOut("medium", function(){
            $(this).remove();
        });
    });
}
function imagePopCallbacks(){
    $('body').addClass('dim-active');
    $("#featured-image-form").on("submit", function(event){
        console.log("featured image form submitted");
        event.preventDefault();

        var imgData = new FormData(document.getElementById("featured-image-form"));
        imgData.append("action", actionUploadFeaturedImage);
        imgData.append("postid", getPostId());
        uploadFeaturedImage(imgData);
    });
    $(".dim, #featured-img-dialog-close").on("click", function(){
        console.log("closing featured img dialog, #featured-img-dialog-close clicked");
        $(".featured-image-pop-wrap").fadeOut("medium", function(){
            $(this).remove();
        });
        $('body').removeClass('dim-active');
    });
}
function mainContainerCallbacks(){
    $(".dim, #message-close").on("click", function(){
        $(".dim:not([data*=tags])").hide();
        $("#message").fadeOut();
        $('body').removeClass('dim-active');
    });
    $(".post-edit").on("click", function(){
        var postid = $(this).data("postid");
        load("start");
        $("#main-container").html("").load(homeUrl + "src/editor.php?postid=" + postid, function(){
            editContainerCallbacks();
            load("stop");
        });
    });
}
function editContainerCallbacks(){
    $(".save-post").on("click", function(){
        console.log("Attempting to save post");
        if (!validatePost()){
            return;
        }
        // save and publish
        data = {
            draft: false
        };
        savePost(data);
    });
    $(".save-draft").on("click", function(){
        console.log("Attempting to save post as draft");
        if (!validatePost()){
            return;
        }
        // save and publish
        data = {
            draft: true
        };
        savePost(data);
    });
    $(".unpublish-post").on("click", function(){
        console.log("Attempting to unpublish post");
        var postid = getPostId();
        // save and publish
        data = {
            draft: true,
            postid: postid
        };
        updateDraftStatus(data);
    });
    
    $(".manage-tags").on("click", function(){
        var postid = $("#postid-hidden").text();
        var tagPop = document.createElement("div");
        var url = homeUrl + "src/tags.php?postid=" + postid;
        console.log(url);
        tagPop = $(tagPop);
        $(tagPop).load(url, function(){
            $("body").append($(tagPop));
            $(".dim").show().data("lock", "tags");
            tagPopCallbacks();
        });
    });
    $(".manage-cats").on("click", function(){
        var postid = $("#postid-hidden").text();
        var catPop = document.createElement("div");
        var url = homeUrl + "src/categories.php?postid=" + postid;
        console.log(url);
        catPop = $(catPop);
        $(catPop).load(url, function(){
            $("body").append($(catPop));
            $(".dim").show().data("lock", "tags");
            catPopCallbacks();
        });
    });
    // post permalink setup
    $("#post-title").on("keyup", function(event){
        var text = $(this).val();
        text = formatPermalink(text);
        $("#post-permalink-input").val(text);
    });
    $("#post-permalink-input").on("keyup", function(event){
        var text = $(this).val();
        text = formatPermalink(text);
        $(this).val(text);
    });
    $(".permalink-change").on("click", function(){
        $(this).parent().toggleClass("disabled");
        if ($(this).parent().hasClass("disabled")){
            console.log("Attempting to update permalink");
            
            var permalink = $(this).parent().find("input").val();
            permalink = formatPermalink(permalink);
            var postid = getPostId();
            // update permalink
            data = {
                permalink: permalink,
                postid: postid
            };
            updatePermalink(data);
        }
    });
    $('.feature-image-tool').on('click', function(){
        var postid = $("#postid-hidden").text();
        var imagePop = document.createElement("div");
        var url = homeUrl + "src/featuredimage.php?postid=" + postid;
        console.log(url);
        imagePop = $(imagePop);
        $(imagePop).load(url, function(){
            $("body").append(imagePop);
//            $(".dim").show().data("lock", "tags");
            $('body').addClass('dim-active');
            imagePopCallbacks();
        });
    });
    // end post permalink setup
    initEditor();
    initImages();
    imgEditorCallbacks();
}

function addTagToPost(tagElement, postid) {
    tagElement.toggleClass("active-tag");
    var tagid = tagElement.data("tagid");
    console.log("adding tag " + tagElement.data("tagid") + " to post " + postid);
    $.post(window.location.href, {
        async: true,
        action: actionAddTagToPost,
        tagid: tagid,
        postid: postid,
        async: true
    }, function(_data){
        console.log(_data);
    });
}
function removeTagFromPost(tagElement, postid) {
    tagElement.toggleClass("active-tag");
    var tagid = tagElement.data("tagid");
    console.log("removing tag " + tagElement.data("tagid") + " from post " + postid);
    $.post(window.location.href, {
        async: true,
        action: actionRemoveTagFromPost,
        tagid: tagid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function addCatToPost(catElement, postid) {
    $(".tag-name").removeClass("active-tag");
    catElement.addClass("active-tag");
    var catid = catElement.data("catid");
    console.log("adding tag " + catElement.data("catid") + " to post " + postid);
    $.post(window.location.href, {
        async: true,
        action: actionAddCatToPost,
        catid: catid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function removeCatFromPost(catElement, postid) {
    $(".tag-name").removeClass("active-tag");
    var catid = catElement.data("catid");
    console.log("removing tag " + catElement.data("catid") + " from post " + postid);
    $.post(window.location.href, {
        async: true,
        action: actionRemoveCatFromPost,
        catid: catid,
        postid: postid
    }, function(_data){
        console.log(_data);
    });
}
function saveTag(){
    var tagName = $("#tag-create-input").val();
    var postid = $("#postid-hidden").text();
    console.log(tagName);
    $.post(window.location.href, {
        async: true,
        action: actionSaveTag,
        name: tagName,
        postid: postid
    }, function(_data){
        console.log("callback");
        _data = JSON.parse(_data);
        console.log(_data);
        var event = new CustomEvent("tag_saved", {"detail": _data });
        document.dispatchEvent(event);
        console.log("after dispatching event");
    });
}
function saveCat(){
    console.log("event function fired");
    var catName = $("#tag-create-input").val();
    var postid = $("#postid-hidden").text();
    console.log(catName);
    $.post(window.location.href, {
        async: true,
        action: actionSaveCat,
        name: catName,
        postid: postid
    }, function(_data){
        console.log("callback");
        _data = JSON.parse(_data);
        console.log(_data);
        var event = new CustomEvent("cat_saved", {"detail": _data });
        document.dispatchEvent(event);
        console.log("after dispatching event");
    });
}
function savePost(data){
    var raw = $("#content").html();
    var title = $("#post-title").val();
    var draft = data.draft;
    var postid = $("#postid-hidden").text();
    var wasDraft = $("#is-draft-hidden").text() === "1" ? true : false;
    var permalink = $("#post-permalink-input").val();
    console.log('postid: ' + postid);
    console.log('wasDraft: ' + wasDraft);
    
    $.post(window.location.href, {    
        async: true,
        action: actionSavePost,
        name: title,
        file: raw,
        draft: draft,
        wasdraft: wasDraft,
        postid: postid,
        permalink: permalink
    },
    function(_data){ //callback for debugging
        console.log(_data);
        _data = JSON.parse(_data);
        console.log(_data);
        var draft = _data.draft == "true" ? 1 : 0;
        $("#postid-hidden").text(_data.postid);
        $("#is-draft-hidden").text(draft);
        var event = new Event("post_saved");
        document.dispatchEvent(event);
    });
}
function updatePermalink(data){
    var permalink = data.permalink;
    var postid = data.postid;
    $.post(window.location.href, {   
        async: true,
        action: actionPostPermalink,
        permalink: permalink,
        postid: postid
        },
    function(_data){
        console.log(_data);
        _data = JSON.parse(_data);
        var event = new CustomEvent("permalink_changed", {detail: _data});
        document.dispatchEvent(event);
    });
}
function updateDraftStatus(data){
    var draft = data.draft;
    console.log(data.draft);
    var postid = data.postid;
    $.post(window.location.href, {   
        async: true,
        action: actionPostDraftStatus,
        draft: draft,
        postid: postid
        },
    function(_data){
        console.log(_data);
        _data = JSON.parse(_data);
        var draft = _data.draft == "true" ? 1 : 0;
        $("#postid-hidden").text(_data.postid);
        $("#is-draft-hidden").text(draft);
        var event = new CustomEvent("draft_status_changed", {detail: _data});
        document.dispatchEvent(event);
    });
}
function uploadImage(imgData){
    var data = {
            "action": actionUploadImage, 
            "imgdata": imgData
        };
    $.ajax({
        url: window.location.href,
        type: "POST",
        processData: false,
        contentType: false,
        data: imgData
    }).done(function(_data){
        console.log(_data);
        var data = JSON.parse(_data);
        console.log(data);
        if (data.success){
            var event = new CustomEvent("image_uploaded", {detail: data});
            document.dispatchEvent(event);
        } else {
            alert(data.message);
        }
    });
}
function uploadFeaturedImage(imgData){
    var data = {
        "action": actionUploadFeaturedImage, 
        "imgdata": imgData
    };
    console.log(data);
    $.ajax({
        url: window.location.href,
        type: "POST",
        processData: false,
        contentType: false,
        data: imgData
    }).done(function(_data){
        console.log(_data);
        var data = JSON.parse(_data);
        console.log(data);
        if (data.success){
            var event = new CustomEvent("featured_image_uploaded", {detail: data});
            document.dispatchEvent(event);
        } else {
            alert(data.message);
        }
    });
}
var data;
function createMessage(message){
    $("#message-message").html(message);
    $(".dim").show();
    $("#message").fadeIn();
}
function validatePost(){
    var title = $("#post-title").val();
    if (title == "") {
        createMessage("Your post needs a title.");
        return false;
    }
    return true;
}
// Event listeners
document.addEventListener('featured_image_uploaded', function(event){
    console.log(event.detail);
    var data = event.detail;
    $('.featured-image-pop-wrap').remove();
    createMessage("Your image \"" + data.original_name + "\" is uploaded and saved as this post's featured image!");
    var _html;
    var _template = $('#template-featured-image').html();
    _html = templator.processTemplate(_template, data);
    $('.feature-image-tool').html(_html);
});

document.addEventListener("image_uploaded", function(event){
    console.log(event.detail);
    var data = event.detail;
//    $("#upload-preview").src(data.img_url);
    document.getElementById("img-dialog").close();
    insertImage(data.img_url);
});

document.addEventListener("post_saved", function (event){ 
    createMessage("Your post is safe!");
}, false);

document.addEventListener("draft_status_changed", function (event){ 
    var message;
    var data = event.detail;
    if (data.draft){
        createMessage("This post is now a draft and not visible to the public.");
        $('body').addClass('')
    } else {
        createMessage("Your post is published and visible to the public!");
    }
}, false);

document.addEventListener("tag_saved", function(event){
    console.log(event.detail);
    var data = event.detail;
    createMessage("New tag \"" + data.tag_name + "\" created!");
    // create div and append it to end of tag list with the tag id and tag name
    var tagName = document.createElement("div");
    var tagId = document.createElement("div");
    
    tagName = $(tagName);
    tagId = $(tagId);
    
    tagName.addClass("tag-name")
            .addClass("active-tag")
            .data("tagid", data.tag_id)
            .text(data.tag_name)
            .appendTo("#active-tags");
    tagId.addClass("tag-id")
            .text(data.tag_id)
            .appendTo(tagName);
    
}, false);

document.addEventListener("cat_saved", function(event){
    console.log(event.detail);
    var data = event.detail;
    createMessage("New category \"" + data.cat_name + "\" created!");
    // create div and append it to end of tag list with the tag id and tag name
    var tagName = document.createElement("div");
    var tagId = document.createElement("div");
    
    tagName = $(tagName);
    tagId = $(tagId);
    
    tagName.addClass("tag-name")
            .addClass("active-tag")
            .data("catid", data.cat_id)
            .text(data.cat_name)
            .appendTo("#active-tags");
    tagId.addClass("tag-id")
            .text(data.cat_id)
            .appendTo(tagName);
    
}, false);
document.addEventListener("permalink_changed", function(event){
    console.log(event.detail);
    var data = event.detail;
    createMessage("This post's url is now \"" + homeUrl + data.permalink + ".");
}, false);

document.addEventListener("context_changed", function() {
    changeCatcher.reset();
});

// helpers/tools
function load(mode){
    switch(mode){
        case "start":
            $(".dim").show();
            $(".loading").show();
            break;
        case "stop":
            $(".dim").hide();
            $(".loading").hide();
            break;
    }
}
function getPostId(){
    return $("#postid-hidden").text();
}

/** EDITOR FUNCTIONS */
function initEditor(){
    // bold italic underline strike
    $("#italic").on("click", function(){
        document.execCommand("italic");
    });
    $("#bold").on("click", function(){
        document.execCommand("bold");
    });
    $("#underline").on("click", function(){
        document.execCommand("underline");
    });
    $("#strike").on("click", function(){
        document.execCommand("strikeThrough");
    });
    // justify block
    $("#left").on("click", function(){
        document.execCommand("justifyLeft");
    });
    $("#center").on("click", function(){
        document.execCommand("justifyCenter");
    });
    $("#right").on("click", function(){
        document.execCommand("justifyRight");
    });
    $("#justify").on("click", function(){
        document.execCommand("justifyFull");
    });
    // undo redo
    $("#undo").on("click", function(){
        document.execCommand("undo");
    });
    $("#redo").on("click", function(){
        document.execCommand("redo");
    });
    // font types
    $("#header").on("click", function(){
        document.execCommand("formatBlock", false, "H2");
    });
    //colors
    $("#show-colors-modal").on("click", function(){
       $("#colors-modal").css("display", "inline-block");
    });
    $(".color-choose").on("click", function(){
        console.log("stuff is happen");
        var selection = document.getSelection();
        console.log("selection" + selection);
        var r = $(this).data("r");
        var g = $(this).data("g");
        var b = $(this).data("b");
        var color = "rgb(" + r + ", " + g + ", " + b + ")";
        document.execCommand("foreColor", false, color);
    });
    // links 
    $("#link").on("click", function(){
        var selection = document.getSelection();
        if (selection == "") {
            return;
        } 
        var value = "";
        if (selection.anchorNode.parentNode){
            if (selection.anchorNode.parentNode.href){
                value = selection.anchorNode.parentNode.href;
            }
        }
        var href = prompt("Enter the link address", value);
        
        if (href != null) {
            if (!href.includes("http://") && !href.includes("https://")) {
                href = "http://" + href;
            }
            document.execCommand("createLink", false, href);
        }
    });
    $("#unlink").on("click", function(){
        document.execCommand("unlink");
    });
    // lists
    $("#ol").on("click", function(){
        document.execCommand("insertOrderedList");
    });
    $("#ul").on("click", function(){
        document.execCommand("insertUnorderedList");
    });
    // images
    $("#image").on("click", function(){
        $('#img-dialog').attr('data-mode', 'img');
        document.getElementById("img-dialog").showModal();
    });
        
    $("#image-form").on("submit", function(event){
        console.log("image form submitted");
        event.preventDefault();

        var imgData = new FormData(document.getElementById("image-form"));
        imgData.append("action", actionUploadImage);
        uploadImage(imgData);
    });
    $('.feature-image-tool').on('click', function(){
        
    });
}
// image functions
function insertImage(imgUrl){
    if (document.execCommand("insertImage", false, imgUrl)) {
       $("#content").find("img").each(function(){
           if (!$(this).hasClass("edit-img")){
               var suffix = new Date().getTime();
               var className = "edit-img" + suffix;
               wrapImage($(this), suffix);
               attachImageEditor($(this), suffix);
               $(this).addClass("edit-img").addClass(className).on("click", function(){

               });
               imgEditorCallbacks();
           }
       }); 
    } 
}
function initImages(){
    console.log("init images");
    $(".edit-img").on("click", function(){
        $(this)
            .parent()
                .find(".img-editor-bar")
                    .css("display", "flex");
    });
}
function wrapImage(imgElem, suffix){
    imgElem
        .wrap("<div class=\"inline img-editor-wrap img-editor-wrap" + suffix + "\"></div>")
        .on("click", function(){
        $(this)
            .parent()
            .find(".img-editor-bar")
                .css("display", "flex");
    });
}

function attachImageEditor(imgElem, suffix){
    imgElem.after(
        "<div class='img-editor-bar flex flex-hor container'>" +
            "<div data-imgwrap='.img-editor-wrap" + suffix + "' data-imgtarget='.edit-img" + suffix + "' class='img-editor-group flex flex-hor'>" +
                "<button type='button' class='btn img-small'><i class='fa fa-picture-o' aria-hidden='true'></i></button>" +
                "<button type='button' class='btn img-medium'><i class='fa fa-picture-o' aria-hidden='true'></i></button>" +
                "<button type='button' class='btn img-large'><i class='fa fa-picture-o' aria-hidden='true'></i></button>" +
                "<button type='button' class='btn img-full'><i class='fa fa-arrows-h' aria-hidden='true'></i></button>" +
            "</div>" +
            "<div data-imgwrap='.img-editor-wrap" + suffix + "' data-imgtarget='.edit-img" + suffix + "' class='img-editor-group flex flex-hor'>" +
                "<button class='btn img-left'>" + iconLeftJustify + "</button>" +
                "<button class='btn img-center'>" + iconCenterJustify + "</button>" +
                "<button class='btn img-right'>" + iconRightJustify + "</button>" +
            "</div>" +
            "<div data-imgwrap='.img-editor-wrap" + suffix + "' data-imgtarget='.edit-img" + suffix + "' class='img-editor-group flex flex-hor'>" +
                "<button class='btn img-remove'>" + iconRemove + "</button>" +
                "<button class='btn img-close'>" + iconClose + "</button>" +
            "</div>" +
        "</div>"
    );
}
function imgEditorCallbacks(){
    // size
    $(".img-small").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "40%");
    });
    $(".img-medium").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "60%");
    });
    $(".img-large").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "80%");
    });
    $(".img-full").on("click", function(){
        $($(this).parent().data("imgwrap")).css("width", "100%");
    });
    
    // alignment
    $(".img-left").on("click", function(){
        $($(this).parent().data("imgwrap")).css({
            "float": "left",
            "display": "inline-block"
        });
    });
    $(".img-center").on("click", function(){
        $($(this).parent().data("imgwrap"))
            .css({
            "float": "none",
            "margin": "auto",
            "display": "block"
        });
    });
    $(".img-right").on("click", function(){
        getImgWrap($(this)).css({
            "float": "right",
            "display": "inline-block"
        });
    });
    
    // remove
    $(".img-remove").on("click", function(){
        if (confirm("Are you sure you want to remove this image?")) {
            getImgWrap($(this)).remove();
        }
    });
    
    // close
    $(".img-close").on("click", function(){
        getImgEditorBar($(this)).fadeOut();
    });
    
    function getImgWrap(elem){
        return $(elem.parent().data("imgwrap"));
    }
    function getImgEditorBar(elem){
        return (elem.parent().parent());
    }
}
// helpers
var Templator = function() {
    var regex = /{{[\w]+}}/g;
    this.processTemplate = function(html, data) {
        console.log("debug html, data: " );
        console.log(html);
        console.log(data);
        var name;
        var matches = html.match(regex);
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
var templator = new Templator();
// random tools
function formatPermalink(text){
    text = text.replace(/ /g, "-");
    text = text.replace(/[+=\[\]{}|\\/<>;:'"\?!,.&^%\$@\(\)\*\&]/g, "");
    text = text.toLowerCase();
    return text;
}

function editPage(){
    $('div').attr('contenteditable', 'true');
    $('body').on('click', 'a', function(event){
        event.preventDefault();
        event.stopPropagation();
    });
};;