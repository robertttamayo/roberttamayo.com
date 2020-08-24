<?php 

$bobblog_api_url = "http://localhost/bobblog2/api/";

if (isset($category_catid)) {
    $url = "$bobblog_api_url?key&type=post&category_id=$category_catid";
    
} else if (isset($category_permalink)) {
    $url = "$bobblog_api_url?key&type=post&category_permalink=$category_permalink";
    
}

$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
$data = curl_exec($curl);

// Check if any error occurred
if(curl_errno($curl)) {
    echo 'Curl error: ' . curl_error($curl); exit;
}
curl_close($curl);

$blog_data = json_decode($data, true);

?>

<?php

if (sizeof($blog_data) == 0) {
    redirectUrl(BLOG_URL);
} else { ?>

<div class="blog-h1"><h1><?= $blog_data[0]['catname'] ?></h1></div>

<div class="blogfeed-wrapper">
    <div class="blogfeed">

    <?php

    $size = sizeof($blog_data);
    for ($i = 0; $i < $size; $i++) {
        $blog = $blog_data[$i];
        ?>

        <div class="blogfeed-item">
            <div class="blogfeed-item-top">
                <a href="<?= BLOG_URL ?><?= $blog['permalink'] ?>" title="Read '<?= $blog['posttitle'] ?>'">
                    <div class="blogfeed-image" 
                         style="background-image:url('<?= $blog['featuredimage'] ?>')">
                    </div>
                    <?php if ($blog['catname'] != '') { ?>
                    <div class="blogfeed-category">
                        <?= $blog['catname'] ?>
                    </div>
                    <?php } ?>
                </a>
            </div>
            <div class="blogfeed-title">
                <a href="<?= BLOG_URL ?><?= $blog['permalink'] ?>" title="Read '<?= $blog['posttitle'] ?>'">
                    <?= $blog['posttitle'] ?>
                </a>
            </div>
            <div class="blogfeed-text-preview">
                <?= $blog['shortpreview'] ?>
            </div>

        </div>

        <?php
    }

    ?>

    </div>
</div>

<script>
    var data = <?= $data ?>;
    var offset = <?= $count ?>;
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
