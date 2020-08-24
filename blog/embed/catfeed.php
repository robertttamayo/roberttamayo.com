<?php 

$bobblog_api_url = "http://localhost/bobblog2/api/";

$url = "$bobblog_api_url?key&type=category";

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

$cat_data = json_decode($data, true);

?>

<div class="catfeed-wrapper">
    <div class="catfeed-main-categories">
        <div class="main-category">
            <div class="main-category-top">
                <div class="main-category-image personal"></div>
                <div class="main-category-caption">Personal</div>
            </div>
        </div>
        <div class="main-category">
            <div class="main-category-top">
                <div class="main-category-image for-brides"></div>
                <div class="main-category-caption">For Brides</div>
            </div>
        </div>
        <div class="main-category">
            <div class="main-category-top">
                <div class="main-category-image weddings"></div>
                <div class="main-category-caption">Weddings</div>
            </div>
        </div>
        <div class="main-category">
            <div class="main-category-top">
                <div class="main-category-image engagements"></div>
                <div class="main-category-caption">Engagements</div>
            </div>
        </div>
    </div>
    <div class="catfeed-more-categories">
        <div class="catfeed-more-categories-title">
            More Categories
        </div>
        <?php
        $size = sizeof($cat_data);
        for ($i = 0; $i < $size; $i++) {
            $cat = $cat_data[$i];
            $catname = $cat['catname'];
            if ($catname != 'personal' 
                && $catname != 'weddings'
                && $catname != 'travel') { ?>

            <div class="catfeed-catname">
                <a href="<?= BLOG_URL ?>category/<?= $cat['catpermalink'] ?>" title="View posts in the <?= $cat['catname'] ?> category">
                    <?= $catname ?>
                </a>
            </div>
            <?php } ?>
        
        <?php } ?>
    </div>
</div>

<script>

</script>