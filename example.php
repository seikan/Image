<?php

// Include core Image class
require_once 'class.Image.php';

// Initialize Image object
$image = new Image();

// Open example.jpg
$image->open('example.jpg');

// Display image size
// echo $image->getWidth().' x '.$image->getHeight();

// Get the HEX color code on 10,10 of the image
// echo $image->getColorFromPixel(10, 10);

// Scale image to 20%
$image->scale(50);
 $image->show();
die;
// Save edited image as GIF
//$image->save('edited.gif', 'gif');

// Resize to 200 width
// $image->resizeToWidth(200);
// $image->show();

// Resize to 200 height
// $image->resizeToHeight(200);
// $image->show();

// Resize to 200 x 200
// $image->resize(200, 200);
// $image->show();

// Crop image
// $image->crop(200, 200, 400, 0);
// $image->show();

// Crop perfectly by position
$image->smartCrop(600, 300, 'center');
// $image->show();

// Add watermark
$image->addWatermark('watermark.png', 'bottomRight');
$image->show();
