<?php

/**
 * Image: A very simple PHP image editing library.
 *
 * Copyright (c) 2017 Sei Kan
 *
 * Distributed under the terms of the MIT License.
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright  2017 Sei Kan <seikan.dev@gmail.com>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 * @see       https://github.com/seikan/Image
 */
class Image
{
	/**
	 * Image resource.
	 *
	 * @var resource
	 */
	private $image;

	/**
	 * Image type.
	 *
	 * @var int
	 */
	private $imageType;

	/**
	 * Initialze Image.
	 */
	public function __construct()
	{
		if (!extension_loaded('gd')) {
			throw new Exception('GD extension is not installed.');
		}
	}

	/**
	 * Clean up resources.
	 */
	public function __destruct()
	{
		$this->imageType = null;

		if (is_resource($this->image)) {
			imagedestroy($this->image);
		}
	}

	/**
	 * Open image for editing.
	 *
	 * @param string $imagePath
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 */
	public function open($imagePath)
	{
		if (!file_exists($imagePath)) {
			throw new Exception('Not able to open image "'.$imagePath.'".');
		}

		$data = getimagesize($imagePath);
		$this->imageType = $data[2];

		switch ($this->imageType) {
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($imagePath);
			break;

			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($imagePath);
			break;

			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($imagePath);
			break;

			default:
				throw new Exception('Invalid image type.');
		}

		imagealphablending($this->image, false);
		imagesavealpha($this->image, true);

		return true;
	}

	/**
	 * Save edited image to a location.
	 *
	 * @param string $ouput
	 * @param string $imageType
	 * @param int    $compression
	 */
	public function save($output, $imageType = IMAGETYPE_JPEG, $compression = 75)
	{
		switch ($imageType) {
			case IMAGETYPE_JPEG:
			case 'jpg':
			case 'jpeg':
				imagejpeg($this->image, $output, $compression);
			break;

			case IMAGETYPE_GIF:
			case 'gif':
				imagegif($this->image, $output);
			break;

			case IMAGETYPE_PNG:
			case 'png':
				imagepng($this->image, $output, round(abs(($compression - 100) / 11.111111)));
			break;
		}

		@chmod($file, 0666);
	}

	/**
	 * Display edited image directly.
	 *
	 * @param string $ouput
	 * @param string $imageType
	 * @param bool   $showHeader
	 */
	public function show($imageType = IMAGETYPE_JPEG, $showHeader = true)
	{
		switch ($imageType) {
			case IMAGETYPE_JPEG:
			case 'jpg':
			case 'jpeg':
				if ($showHeader) {
					header('Content-Type: image/jpeg');
				}
				imagejpeg($this->image);
			break;

			case IMAGETYPE_GIF:
			case 'gif':
				if ($showHeader) {
					header('Content-Type: image/gif');
				}
				imagegif($this->image);
			break;

			case IMAGETYPE_PNG:
			case 'png':
				if ($showHeader) {
					header('Content-Type: image/png');
				}
				imagepng($this->image);
			break;
		}
	}

	/**
	 * Get image type.
	 *
	 * @return int
	 */
	public function getImageType()
	{
		return $this->imageType;
	}

	/**
	 * Get image width.
	 *
	 * @return int
	 */
	public function getWidth()
	{
		return imagesx($this->image);
	}

	/**
	 * Get image height.
	 *
	 * @return int
	 */
	public function getHeight()
	{
		return imagesy($this->image);
	}

	/**
	 * Get the HEX color of a specific pixel location.
	 *
	 * @param int $x
	 * @param int $y
	 *
	 * @return string
	 */
	public function getColorFromPixel($x, $y)
	{
		$color = imagecolorsforindex($this->image, imagecolorat($this->image, $x, $y));

		return dechex($color['red'] & 240).dechex($color['red'] & 240).dechex($color['red'] & 240);
	}

	/**
	 * Convert HEX into RGB array.
	 *
	 * @param string $hex
	 *
	 * @return array
	 */
	public function hex2rgb($hex)
	{
		list($r, $g, $b) = array_map('hexdec', str_split(ltrim($hex, '#'), 2));
		return [
			'r' => $r, 'g' => $g, 'b' => $b,
		];
	}

	/**
	 * Convert RGB color into HEX.
	 *
	 * @param int $r
	 * @param int $g
	 * @param int $b
	 *
	 * @return string
	 */
	public function rgb2hex($r, $g, $b)
	{
		return str_pad(dechex($r), 2, '0', STR_PAD_LEFT).str_pad(dechex($g), 2, '0', STR_PAD_LEFT).str_pad(dechex($b), 2, '0', STR_PAD_LEFT);
	}

	/**
	 * Resize image to specified height and remain the ratio.
	 *
	 * @param int $height
	 */
	public function resizeToHeight($height)
	{
		$width = $this->getWidth() * ($height / $this->getHeight());
		$this->resize($width, $height);
	}

	/**
	 * Resize image to specified width and remain the ratio.
	 *
	 * @param int $width
	 */
	public function resizeToWidth($width)
	{
		$height = $this->getheight() * ($width / $this->getWidth());
		$this->resize($width, $height);
	}

	/**
	 * Scale image to a size in percent.
	 *
	 * @param int $scale
	 */
	public function scale($scale)
	{
		$width = $this->getWidth() * $scale / 100;
		$height = $this->getheight() * $scale / 100;
		$this->resize($width, $height);
	}

	/**
	 * Resize image to the specified width and height.
	 *
	 * @param int $width
	 * @param int $height
	 */
	public function resize($width, $height)
	{
		$image = imagecreatetruecolor($width, $height);

		if (IMAGETYPE_GIF == $this->imageType || IMAGETYPE_PNG == $this->imageType) {
			imagealphablending($image, false);
			imagesavealpha($image, true);
			$transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
			imagefilledrectangle($image, 0, 0, $width, $height, $transparent);
		}

		imagecopyresampled($image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
		$this->image = $image;
	}

	/**
	 * Crop image by position in better way.
	 *
	 * @param int    $width
	 * @param int    $height
	 * @param string $position
	 */
	public function smartCrop($width, $height, $position = 'topLeft')
	{
		$top = 0;
		$left = 0;

		$width = ($width > $this->getWidth()) ? $this->getWidth() : $width;
		$height = ($height > $this->getHeight()) ? $this->getHeight() : $height;

		switch ($position) {
			case 'topCenter':
			case 2:
				$left = ($this->getWidth() - $width) / 2;
			break;

			case 'topRight':
			case 3:
				$left = $this->getWidth() - $width;
			break;

			case 'middleLeft':
			case 4:
				$top = ($this->getHeight() - $height) / 2;
			break;

			case 'center':
			case 5:
				$top = ($this->getHeight() - $height) / 2;
				$left = ($this->getWidth() - $width) / 2;
			break;

			case 'middleRight': case 6:
				$top = ($this->getHeight() - $height) / 2;
				$left = $this->getWidth() - $width;
			break;

			case 'bottomLeft': case 7:
				$top = $this->getHeight() - $height;
			break;

			case 'bottomCenter': case 8:
				$top = $this->getHeight() - $height;
				$left = ($this->getWidth() - $width) / 2;
			break;

			case 'bottomRight': case 9:
				$top = $this->getHeight() - $height;
				$left = $this->getWidth() - $width;
			break;
		}

		$this->crop($width, $height, $top, $left);
	}

	/**
	 * Crop image by position provided.
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $top
	 * @param int $left
	 */
	public function crop($width, $height, $top = 0, $left = 0)
	{
		$top = (($top + $height) > $this->getHeight()) ? ($this->getHeight() - $height) : $top;
		$left = (($left + $width) > $this->getWidth()) ? ($this->getWidth() - $width) : $left;

		$image = imagecreatetruecolor($width, $height);

		if (IMAGETYPE_GIF == $this->imageType || IMAGETYPE_PNG == $this->imageType) {
			imagealphablending($image, false);
			imagesavealpha($image, true);
			$transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
			imagefilledrectangle($image, 0, 0, $width, $height, $transparent);
		}

		imagecopy($image, $this->image, 0, 0, $left, $top, $width, $height);
		$this->image = $image;
	}

	/**
	 * Add watermark to existing image.
	 *
	 * @param string $watermarkImage
	 * @param string $position
	 */
	public function addWatermark($watermarkImage, $position = 'bottomRight')
	{
		if (!file_exists($watermarkImage)) {
			throw new Exception('Watermark image "'.$watermarkImage.'" does not exist.');
		}

		$top = 0;
		$left = 0;
		$data = getimagesize($watermarkImage);
		$imageType = $data[2];

		switch ($imageType) {
			case IMAGETYPE_JPEG:
				$watermark = imagecreatefromjpeg($watermarkImage);
			break;

			case IMAGETYPE_GIF:
				$watermark = imagecreatefromgif($watermarkImage);
			break;

			case IMAGETYPE_PNG:
				$watermark = imagecreatefrompng($watermarkImage);
				imagealphablending($this->image, true);
			break;

			default:
				$this->errors[] = 'Invalid watermark image type "'.$this->imageType.'".';

				return false;
		}

		list($width, $height) = getimagesize($watermarkImage);

		switch ($position) {
			case 'topCenter':
			case 2:
				$left = ($this->getWidth() - $width) / 2;
			break;

			case 'topRight':
			case 3:
				$left = $this->getWidth() - $width;
			break;

			case 'middleLeft':
			case 4:
				$top = ($this->getHeight() - $height) / 2;
			break;

			case 'center':
			case 5:
				$top = ($this->getHeight() - $height) / 2;
				$left = ($this->getWidth() - $width) / 2;
			break;

			case 'middleRight':
			case 6:
				$top = ($this->getHeight() - $height) / 2;
				$left = $this->getWidth() - $width;
			break;

			case 'bottomLeft':
			case 7:
				$top = $this->getHeight() - $height;
			break;

			case 'bottomCenter':
			case 8:
				$top = $this->getHeight() - $height;
				$left = ($this->getWidth() - $width) / 2;
			break;

			case 'bottomRight':
			case 9:
				$top = $this->getHeight() - $height;
				$left = $this->getWidth() - $width;
			break;
		}

		imagecopy($this->image, $watermark, $left - 10, $top - 10, 0, 0, $width, $height);
	}
}
