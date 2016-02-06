<?php
function printPhotos($directory) {
    $scanned_directory = array_diff(scandir($directory), array('..', '.'));
    foreach($scanned_directory as $value){
        $foto_loc = $directory . '/' . $value;
        echo '<a href="' . $foto_loc . '" ><img src="' .  $foto_loc . '" height="100" /> </a>';
    }
}
?>

<h2>Photo Gallery</h2>
<p>Photos of Africa and the school sponsored by <a href="http://www.africahopefund.org" target="_blank">The Africa Hope Fund</a><br><br></font></p>

<div class="photo-gallery">
<h3>Photos from Safari on the River 2014</h3>
<div class="gallery-group">
    <?php printPhotos('photo-gallery/sor_2014');  ?>
</div>
</div>
<div class="photo-gallery">
<h3>Photos from Africa</h3>
<div class="gallery-group">
    <?php printPhotos('photo-gallery/sor_2013');  ?>
</div>
</div>