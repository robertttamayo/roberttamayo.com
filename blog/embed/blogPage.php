<?php 

// blog_data defined in index.php

if (sizeof($blog_data) == 0) {
    redirectUrl(BLOG_URL);
    
} else { ?>

<div class="blog-h1-wrap" data-style="<?= $post_style ?>">
    <div class="blog-h1">
        <div class="blog-post-meta">
            <div class="blog-author"><?= $blog_data[0]['author'] ?> | <?= date_format(date_create($blog_data[0]['publishdate']), "F d, Y"); ?></div>
        </div>

        <h1><?= $blog_data[0]['posttitle'] ?></h1>
    </div>
</div>

<?php

$size = sizeof($blog_data);
for ($i = 0; $i < $size; $i++) {
    $blog = $blog_data[$i];
    ?>

    <div class="blog-post-wrapper">
        <div class="blog-post">
            <?= $blog['content'] ?>
        </div>
    </div>
    <div class="blog-comment-section">
        <div class="blog-comment-section-top">
            <div class="blog-comment-title">Comments: <?= $blog['hascomments'] ?></div>

            <div class="blog-comment-likes"><?= $blog['likecount'] ?> <span><i class="fas fa-heart"></i></span></div>
        </div>
        <div class="leave-a-comment-section comment-form">
            <div class="leave-a-comment-title">Leave a Comment</div>
            <div class="enter-comment">
                <textarea name="comment" maxlength="280" class="leave-a-comment" id="leave-a-comment" placeholder="Leave a comment..."></textarea>
            </div>
            <div class="sign-in-form">
                <div>
                    <input name="guestname" type="email" id="leave-a-comment-name" placeholder="Your Name">
                </div>
                <div>
                    <input name="guestemail" type="text" id="leave-a-comment-email" placeholder="Email Address">
                </div>
            </div>
            <div class="submit-comment" id="submit-comment">Submit</div>
        </div>
            
        <div class="blog-comment-section-comments"></div>
    </div>
    <script>
        var postid = <?= $blog['id']; ?>;
    </script>

    <?php
}

?>
        

<script>
    var data = <?= $data ?>;
    var offset = <?= $count ? $count : 0 ?>;
//    var url = "<?= $url ?>";
//    var data = {};
//    console.log("here");
//    $.ajax({
//        url: url,
//        type: "GET",
//        processData: false,
//        contentType: false,
//        data: data
//    }).done(function(_data){
//        var data = JSON.parse(_data);
//        console.log(data);
//    });

</script>

<?php } ?>