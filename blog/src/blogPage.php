<?php

if (!($bb)) {
    $bb = new BobBlog();
} 


if (($category_uri)){
    // load all posts of a category
    echo "this is a category page. load all posts of a category";
    $category = $bb->getCategoryFromPermalink($permalink);
    if ($category == null) {
        // error!
        echo "there has been an error. category does not exist.";
    } else {
        include (SRC_DIR . 'html/template/category_page.html');
    }
} else {
    // load up the post
    $post = $bb->getPostFromPermalink($permalink);
    if ($post == null){
        echo "there has been an error. post not found.";
    } else {
        include (SRC_DIR . 'html/template/blog_page.html');
    }
}

