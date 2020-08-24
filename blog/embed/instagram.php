<?php

$ig_request_url = "https://api.instagram.com/v1/tags/nofilter/media/recent?access_token=260197666.4dde398.eedeccbdadea414880152ae61ee042c7";
$ig_request_url = "http://api.instagram.com/v1/users/self/media/recent/?access_token=260197666.4dde398.eedeccbdadea414880152ae61ee042c7&limit=4";
$access_token = "260197666.4dde398.eedeccbdadea414880152ae61ee042c7";
$ig_user = "f_dominique_a";
$ig_user_link = "https://www.instagram.com/f_dominique_a/";

$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $ig_request_url); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
$data = curl_exec($curl);

// Check if any error occurred
if(curl_errno($curl))
{
    //echo 'Curl error: ' . curl_error($curl);
}
curl_close($curl);

$ig_data = json_decode($data, true);
$length = sizeof($ig_data['data']);

$length = $length > 4 ? 4 : $length;

?>
<div class="ig-section-wrapper">
    <div class="ig-section">
        <div class="ig-header">
            Follow Along On Our Adventures
        </div>
        <div class="ig-gallery-wrapper">
            <div class="ig-user">
                <a href="<?= $ig_user_link ?>">@<?= $ig_user ?></a>
            </div>
<?php for ($i = 0; $i < $length; $i++) { ?>
            <a href="<?= $ig_data['data'][$i]['link'] ?>" class="ig-img" style="background-image: url(<?= $ig_data['data'][$i]['images']['standard_resolution']['url']; ?>)" title="<?= $ig_data['data'][$i]['caption']['text'] ?>"></a>
<?php } ?>
        </div>
    </div>
</div>


<?php
/*
curl -F 'client_id=4dde398e8915421d8f23af7aec70f8b1' \
    -F 'client_secret=46057cf8c3434607924d4b3b9dbf4bf6' \
    -F 'grant_type=authorization_code' \
    -F 'redirect_uri=http://www.estellaphoto.com' \
    -F 'code=7fa61cfd9f2c43f489c28899aed0005d' \
    https://api.instagram.com/oauth/access_token
*/
?>