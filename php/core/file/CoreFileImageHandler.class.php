<?php
class CoreFileImageHandler {
	protected function getImageFileContent($mimeType, $location) {
		// unfortunately GD functions print warning if a file is not valid,
		// so '@' is necessary
		if (in_array($mimeType, array('image/jpg', 'image/jpeg', 'image/pjpeg'))) {
			return @imagecreatefromjpeg($location);
		}
		if ($mimeType == 'image/gif') {
			return @imagecreatefromgif($location);
		}
		if ($mimeType == 'image/png') {
			return @imagecreatefrompng($location);
		}
		// return False;
		throw new CoreException('Invalid image type: \'' . $mimeType . '\'');
	}
	
	protected function saveImage($mimeType, $location, $originalImageObject) {
		if (in_array($mimeType, array('image/jpg', 'image/jpeg', 'image/pjpeg'))) {
			$res = @imagejpeg(
				$originalImageObject,
				$location,
				CoreConfig::get('CoreFilesJPG', 'defaultQuality')
			);
		}
		elseif ($mimeType == 'image/gif') {
			$res = @imagegif(
				$originalImageObject,
				$location
			);
		}
		elseif ($mimeType == 'image/png') {
			$res = @imagepng(
				$originalImageObject,
				$location,
				CoreConfig::get('CoreFilesPNG', 'defaultCompression')
			);
		}
		if (!$res) {
			throw new CoreException('Unable to save file: \'' . $location . '\'');
		}
	}
	
	public function checkImageFileContent($mimeType, $location) {
		return ($this->getImageFileContent($mimeType, $location) != False);
	}

	/**
	 * $options is an array like:
	 *     array('optionName' => 'optionValue', ...)
	 * Following options are possible:
	 *     'width'  (in pixels)
	 *     'height' (in pixels)
	 *     'ignoreProportions' (default false, which means that proportions are kept)
	 *     'crop' (only makes sense if ignoreProportions not set)
	 *     'backgroundColor' (value is color in css format, but without '#', like 000000;
	 *         only makes sense if crop and ignoreProportions are not set)
	 */
	public function resize($fileName, $newFileName, $options) {
		$metadata = @getimagesize($fileName);
		if (!$metadata) {
			throw new CoreException('File \'' . $fileName . '\' does not exist or is corrupted');
		}
		$originalImageObject = $this->getImageFileContent($metadata['mime'], $fileName);
		$resizedImageObject = $this->getResizedImageObject($originalImageObject, $metadata, $options);
		$this->saveImage($metadata['mime'], $newFileName, $resizedImageObject);
		imagedestroy($originalImageObject);
		imagedestroy($resizedImageObject);
	}
	
	protected function getResizedImageObject($originalImageObject, $metadata, $options) {
		if (
			empty($metadata[0]) || empty($metadata[1])
		) {
			throw new CoreException('Invalid metadata for function getResizedImageObject()');
		}
		if (
			!is_array($options)
			|| empty($options['width']) && empty($options['height'])
			|| (empty($options['width']) || empty($options['height'])) && (!empty($options['crop']) || !empty($options['ignoreProportions']) || !empty($options['backgroundColor']))
			|| !empty($options['ignoreProportions']) && !empty($options['crop'])
			|| !empty($options['ignoreProportions']) && !empty($options['backgroundColor'])
			|| !empty($options['crop']) && !empty($options['backgroundColor'])
			|| !empty($options['keepSmall']) && (!empty($options['crop']) || !empty($options['ignoreProportions']) || !empty($options['backgroundColor']))
		) {
			throw new CoreException('Invalid options for function getResizedImageObject()');
		}

		$newWidth = !empty($options['width']) ? $options['width'] : null;
		$newHeight = !empty($options['height']) ? $options['height'] : null;

		if (!empty($options['crop'])) {
			return $this->getResampledAndCropped($originalImageObject, $metadata[0], $metadata[1], $newWidth, $newHeight);
		}
		elseif (!empty($options['ignoreProportions'])) {
			return $this->getResampledIgnoreProportions($originalImageObject, $metadata[0], $metadata[1], $newWidth, $newHeight);
		}
		elseif (!empty($options['backgroundColor'])) {
			return $this->getResampledFillBackground($originalImageObject, $metadata[0], $metadata[1], $newWidth, $newHeight, $options['backgroundColor']);
		}
		else {
			return $this->getResampledKeepProportions($originalImageObject, $metadata[0], $metadata[1], $newWidth, $newHeight, $options['keepSmall']);
		}
	}

	protected function getResampledAndCropped($srcImg, $originalWidth, $originalHeight, $newWidth, $newHeight) {
		$factor = max($newWidth / $originalWidth, $newHeight / $originalHeight);
		$rescaledWidth = round($factor * $originalWidth);
		$rescaledHeight = round($factor * $originalHeight);
		$tmpImg = imagecreatetruecolor($rescaledWidth, $rescaledHeight);
		imagecopyresampled($tmpImg, $srcImg, 0, 0, 0, 0, $rescaledWidth, $rescaledHeight, $originalWidth, $originalHeight);
		$posX = round(($rescaledWidth - $newWidth) / 2);
		$posY = round(($rescaledHeight - $newHeight) / 2);
		$dstImg = imagecreatetruecolor($newWidth, $newHeight);
		imagecopy($dstImg, $tmpImg, 0, 0, $posX, $posY, $newWidth, $newHeight);
		imagedestroy($tmpImg);
		return $dstImg;
	}
	
	protected function getResampledIgnoreProportions($srcImg, $originalWidth, $originalHeight, $newWidth, $newHeight) {
		$dstImg = imagecreatetruecolor($newWidth, $newHeight);
		imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
		return $dstImg;
	}

	protected function getResampledFillBackground($srcImg, $originalWidth, $originalHeight, $newWidth, $newHeight, $backgroundColor) {
		$factor = min($newWidth / $originalWidth, $newHeight / $originalHeight);
		$rescaledWidth = round($factor * $originalWidth);
		$rescaledHeight = round($factor * $originalHeight);
		$tmpImg = imagecreatetruecolor($rescaledWidth, $rescaledHeight);
		imagecopyresampled($tmpImg, $srcImg, 0, 0, 0, 0, $rescaledWidth, $rescaledHeight, $originalWidth, $originalHeight);
		$posX = round(($newWidth - $rescaledWidth) / 2);
		$posY = round(($newHeight - $rescaledHeight) / 2);
		$dstImg = imagecreatetruecolor($newWidth, $newHeight);
		$colorRGB = array(
			'red' => hexdec(substr($backgroundColor, 0, 2)),
			'green' => hexdec(substr($backgroundColor, 2, 2)),
			'blue' => hexdec(substr($backgroundColor, 4, 2))
		);
		$color = imagecolorallocate($dstImg, $colorRGB['red'], $colorRGB['green'], $colorRGB['blue']);
		imagefill($dstImg, 0, 0, $color);
		imagecopy($dstImg, $tmpImg, $posX, $posY, 0, 0, $rescaledWidth, $rescaledHeight);
		imagedestroy($tmpImg);
		return $dstImg;
	}

	protected function getResampledKeepProportions($srcImg, $originalWidth, $originalHeight, $newWidth, $newHeight, $keepSmall = null) {
		if (!$newWidth) {
			$newWidth = CoreConfig::get('CoreFiles', 'defaultImageWidthPX');
		}
		if (!$newHeight) {
			$newHeight = CoreConfig::get('CoreFiles', 'defaultImageHeightPX');
		}
		$factor = min($newWidth / $originalWidth, $newHeight / $originalHeight);
		if ($keepSmall && $factor >= 1) {
			$rescaledWidth = $originalWidth;
			$rescaledHeight = $originalHeight;
		}
		else {
			$rescaledWidth = round($factor * $originalWidth);
			$rescaledHeight = round($factor * $originalHeight);
		}
		$dstImg = imagecreatetruecolor($rescaledWidth, $rescaledHeight);
		imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $rescaledWidth, $rescaledHeight, $originalWidth, $originalHeight);
		return $dstImg;
	}
}
?>