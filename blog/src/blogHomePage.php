<?php

if (!($bb)) {
    $bb = new BobBlog();
} 
if (!($categoryMode)){
    // home page, load all posts
    $bb->initPosts();
    $posts = $bb->getPosts();
} else {
    // category page, load a category
    $catid = $bb->getCatByPermalink($permalink);
    if ($catid == null){
        echo "404 error: category not found using permalink";
    } else {
        $posts = $bb->getPostsByCat($catid);
    }
}
$sorted_posts = sortPosts($posts);

function sortPosts($posts){

    foreach($posts as $post){
        if ($post->draft) {
            continue;
        }
        $sorted[] = $post;
        $dates[] = $post->publishdate;
    }

    // Sort the data with volume descending, edition ascending
    // Add $data as the last parameter, to sort by the common key
    array_multisort($dates, $sorted);

    return $sorted;
}

include (SRC_DIR . 'html/template/blog_home_page.html');


