<?php 

$bobblog_api_url = "http://www.redcodebluecode.com/api/";

$count = 100;

$url = "$bobblog_api_url?key&type=post&limit=$count";

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
//echo "<pre>";
//print_r($blog_data);
//echo "</pre>";

?>

<div class="blogfeed-wrapper">
    <div class="blogfeed">
    
    <?php
    
    $max_characters = 280;
    $size = sizeof($blog_data);
    for ($i = 0; $i < $size; $i++) {
        $blog = $blog_data[$i];
        $short_preview = strip_tags($blog['content']);
        $short_preview = trim($short_preview);
        $date = date_format(date_create($blog['publishdate']), "F d, Y");
        
        if (strlen($short_preview) > $max_characters) {
            $short_preview = substr($short_preview, 0, $max_characters - 1) . '...';
        }
        
        ?>

        <div class="blogfeed-item">
            <div class="blogfeed-title">
                <a href="<?= BLOG_URL ?><?= $blog['permalink'] ?>?style=<?= $i % 4 ?>">
                    <?= $blog['posttitle'] ?>
                </a>
                <div class="blogfeed-publishdate"><?= $date; ?></div>
            </div>
            <div class="blogfeed-text-preview">
                <div><?= $short_preview ?></div>
                <div><a class="read-more-link" href="<?= BLOG_URL ?><?= $blog['permalink'] ?>?style=<?= $i % 4 ?>">Continue <i class="fas fa-arrow-alt-circle-right"></i></a></div>
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