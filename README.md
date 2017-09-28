# Image

This is a very simple PHP image editing library. It required GD library enable to work. Examples in this documentation will use the following photo I took in Japan.

![example](https://user-images.githubusercontent.com/73107/30966237-d694854c-a48a-11e7-8b01-2592932bb269.jpg)



## Usage

### Getting Started

> \$image= new Image( );

```php
// Include core Image library
require_once 'class.Image.php';

// Initialize Image object
$cart = new Image();
```



### Open Image

Opens an image for editing.

> **bool** \$image->open( **string** $imagePath );

```php
$image->open('example.jpg');
```



### Save Image

Saved image to a location when finish editing. Default output format is JPG with 75% compression level.

> **bool** \$image->save( **string** \$output, **string** $imageType\[, **int** \$compression\] );

```php
// Save image as output.png
$image->save('output.png', 'png');
```



### Show Image

Displays image directly to browser without saving it to a local directory. Image will show as JPG by default.

> \$image->show(\[**string** $imageType\]\[, **bool** \$showHeader\]);

```php
$image->show();
```



### Get Image Type

Gets the type of image currently open.

> **int** \$image->getImageType( );

```php
// Get image type
$type = $image->getImageType();

if (IMAGETYPE_JPEG == $type) {
	echo 'This is a JPG image.';
} else if (IMAGETYPE_GIF == $type) {
	echo 'This is a GIF image.';
} else if (IMAGETYPE_PNG == $type) {
	echo 'This is a PNG image.';
}
```



### Get Image Width

Gets image width.

> **int** \$image->getWidth( );

```php
$width = $image->getWidth();
```



### Get Image Height

Gets image height.

> **int** \$image->getHeight( );

```php
$width = $image->getHeight();
```



### Get HEX Code For a Pixel

Gets HEX color code for a pixel in the image.

> **string** \$image->getColorFromPixel( **int** \$x, **int** \$y );

```php
echo $image->getColorFromPixel(10, 10);
```

**Result:**

```
505050
```



### Convert HEX to RGB

Converts a HEX color code into RGB array.

> **array** \$image->hex2rgb( **string** $hex );

```php
$color = $image->hex2rgb('505050');

print_r($color);
```

**Result:**

```
Array ( [r] => 80 [g] => 80 [b] => 80 ) 
```



### Convert RGB to HEX

Converts RGB color into HEX code.

> **string** \$image->rgb2hex(**int** \$r, **int** \$g, **int** \$b);

```php
echo $image->rgb2hex(80, 80, 80);
```

**Result:**

```
505050
```



### Scale

Scales image to specified percent.

> \$image->scale( **int** \$percent );

```php
// Make the image 50% smaller
$image->scale(50);
```

**Result:**

![example](https://user-images.githubusercontent.com/73107/30970717-70d3b0bc-a498-11e7-87d3-5c3277f44a9d.jpg)



### Resize to Width

Resizes to a widthand keep the image dimension ratio.

> resizeToWidth(**int** \$width);

```php
$image->resizeToWidth(200);
```

**Result:**

![example](https://user-images.githubusercontent.com/73107/30969413-e313eace-a494-11e7-88a0-71c4225365d3.jpg)



### Resize to Height

Resizes to a height and keep the image dimension ratio.

> resizeToHeight(**int** \$height);

```php
$image->resizeToHeight(200);
```

**Result:**

![example](https://user-images.githubusercontent.com/73107/30969318-934cb8f4-a494-11e7-8eb7-a4c0e67c662d.jpg)



### Resize to Width and Height

Resizes image into specified width and height.

> \$image->resize( **int** \$width, **int** \$height );

```php
$image->resize(200, 150);
```

**Result:**

![example](https://user-images.githubusercontent.com/73107/30969880-45c65d04-a496-11e7-8c21-a26a10ae9399.jpg)



### Crop

Crops image to a specified width and height.

> \$image->crop(**int** \$width, **int** \$height\[, **int** \$top\]\[, **int** \$left\]);

```php
$image->crop(300, 300, 0, 0);
```

**Result:**

![example](https://user-images.githubusercontent.com/73107/30970043-c11d83f6-a496-11e7-97b8-0938875bdd60.jpg)



### Smart Crop

Crops image to perfect position with specified width and height.

> \$image->smartCrop(**int** \$width, **int** \$height\[, **string** \$position\]);

Available cropping position: `topLeft`, `topCenter`, `topRight`, `middleLeft`, `center`, `middleRight`, `botomLeft`, `bottomCenter`, `bottomRight`.

```php
$image->smartCrop(300, 300, 'center');
```

 **Result:**

![example](https://user-images.githubusercontent.com/73107/30970338-7f611580-a497-11e7-8658-98c3e2866de3.jpg)



### Watermark

Adds watermark to the image. Default watermark will be added at bottom right.

> \$image->addWatermark(**string** $watermarkImage\[, **string** \$position\]);

Available position: `topLeft`, `topCenter`, `topRight`, `middleLeft`, `center`, `middleRight`, `botomLeft`, `bottomCenter`, `bottomRight`.

```php
$image->addWatermark('watermark.png');
```

**Result:**

![example](https://user-images.githubusercontent.com/73107/30970565-16a8d0cc-a498-11e7-822a-87b21cc680c0.jpg)

