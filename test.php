<?php
$im = new imagick( 'image_1.jpg' );
// resize by 200 width and keep the ratio
$im->thumbnailImage( 50, 0);
// write to disk
$im->writeImage( 'a_thumbnail.jpg' );
?>
