<?php include 'config.php'; ?>
<?php include 'blogrouter.php' ?>

<?php if ($blog_mode == BLOG_POST_PAGE) { ?>
    <?php include 'blog_data.php'; ?>
<?php } ?>

<!DOCTYPE html>
<html>
    <head>
        <?php if ($blog_mode == BLOG_POST_PAGE) { ?>
            <title><?= $blog_data[0]['posttitle'] ?></title>
        <?php } else { ?>
            <title>Robert Tamayo's Blog | Code and Game Development</title>
        <?php } ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css2?family=Bowlby+One+SC&amp;display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Heebo|Roboto" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:100,300,400,700" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/blog/public/assets/css/min/core.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="<?= PUBLIC_ASSETS_URL ?>/js/module/SyntaxHighlighter.js"></script>
        <script src="<?= PUBLIC_ASSETS_URL ?>/js/module/Template.js"></script>
        <script src="<?= PUBLIC_ASSETS_URL ?>/js/module/Guests.js"></script>
        <script src="<?= PUBLIC_ASSETS_URL ?>/js/module/Comments.js"></script>
        <script src="<?= PUBLIC_ASSETS_URL ?>/js/module/Stats.js"></script>
        <script src="https://kit.fontawesome.com/824cab990c.js" crossorigin="anonymous"></script>
        <link rel="icon"  type="image/png" href="/assets/icon/favicon.ico">
        <!-- <script defer src="https://use.fontawesome.com/releases/v5.10.2/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.10.2/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script> -->
    </head>
    <body>
        
        <header>
            <!-- <div class="blog-skull-image">
                <img src="/assets/images/skull4.png" alt="skull">
            </div> -->

            <div class="page-title">
                <h1>Blog</h1>
            </div>
            
            <div class="blog-skull-image-desktop">
                <img src="/assets/images/skull2.png" alt="skull">
            </div>
        </header>
        <div class="main">
            <div class="main-left"></div>
            <div class="main-middle">
                <?php
                    if ($blog_mode == BLOG_HOME_PAGE) { 
                    include HOME_DIR . 'embed/blogfeed.php'; 
                    } else if ($blog_mode == BLOG_CATEGORY_PAGE) { 
                    include HOME_DIR . 'embed/catPage.php'; 
                    } else if ($blog_mode == BLOG_POST_PAGE) { 
                    include HOME_DIR . 'embed/blogPage.php'; 
                    } else if ($blog_mode == BLOG_TAG_PAGE) { 
                    } 
                ?>
            </div>
            <div class="main-right"></div>
        </div>
        <footer></footer>
        <script type="blog/template" id="comment-template">
            <div class="blog-comment" 
            data-replies-visible="false" 
            data-comment-style-index="{{styleIndex}}"
            data-comment-id="{{commentid}}">
                <div class="commentor-name">
                    <span class="blog-comment-color"><i class="fas fa-skull"></i></span> {{guestname}}
                </div>
                <div class="comment-content">
                    {{comment}}
                </div>
                <div class="comment-replies comment-reply-count-{{hasreplies}}">
                    <div class="comment-replies-title view-replies" data-replies-loaded="false">Show {{hasreplies}} Replies <i class="fas fa-angle-down"></i></div>
                    <div class="hide-replies">Hide Replies <i class="fas fa-angle-up"></i></div>
                    <div class="comment-replies-content" data-commentid="{{commentid}}"></div>
                </div>
                <div class="comment-reply"><i class="fas fa-reply"></i> Reply</div>
                
                <div class="comment-leave-a-reply comment-form" data-replyto="{{commentid}}">
                    <div class="enter-comment">
                        <textarea class="leave-a-comment" name="comment" placeholder="Leave a reply..."></textarea>
                    </div>
                    <div class="sign-in-form">
                        <div>
                            <input name="guestname" type="email" placeholder="Your Name">
                        </div>
                        <div>
                            <input type="text" name="guestemail" placeholder="Email Address">
                        </div>
                    </div>
                    <input style="display: none;" type="hidden" name="replyto" value="{{commentid}}"> 
                    <div class="submit-reply submit-comment" >Reply</div>
                </div>
                
            </div>
        </script>
<!--        <script src="/assets/js/core.js"></script>-->
        
    </body>
</html>
