<?php
/**
 * This class manages disk operations.
 */
class CoreFileManager {
	public function transactionStart() {
		throw new CoreException('Method not implemented!');
	}

	public function transactionCommit() {
		throw new CoreException('Method not implemented!');
	}

	public function transactionRollback() {
		throw new CoreException('Method not implemented!');
	}

	/**
	 * Zapisuje plik na dysku i zwraca tablicę:
	 * array('subdir' => 'X', 'baseName' => 'Y', 'extension' => 'Z');
	 * W razie niepowodzenia zwraca null lub rzuca wyjątek.
	 * Parametr $fileCategory jest ogólną kategorią pliku ('image', 'doc').
	 * $preferredFileName NIE zawiera rozszerzenia.
	 *
	 * Ta funkcja ustala ostatedcznie nazwe i rozszerzenie pliku i koniecznie
	 * trzeba te dane zaktualizować w rekordzie po powrocie z tej funkcji.
	 */
	public function saveUploadedFile(&$uploadStruct, $fileCategory, $preferredFileName = null) {
		$extension = $this->checkTypeAndGetExtension($uploadStruct, $fileCategory);		
		if (empty($extension)) {
			throw new CoreException('Unknown extension was not detected until this place.');
		}
		if (empty($preferredFileName)) {
			$preferredFileName = $uploadStruct['name'];
		}
		else {
			$preferredFileName .= '.' . $extension;
		}
		for ($i = 0; $i < 100; $i++) {
			$baseName = $this->createBaseName($preferredFileName, $i);
			$fullPath = $this->getDiskPath($baseName, $extension);
			if (!is_file($fullPath)) {
				$subdirDiskPath = CoreConfig::get('Environment', 'uploadDirDiskPath') . $this->getSubdirName($baseName);
				if (!is_dir($subdirDiskPath)) {
					mkdir($subdirDiskPath, CoreConfig::get('CoreFiles', 'uploadDirSubdirsPermissions'));
				}
				if (!move_uploaded_file($uploadStruct['tmp_name'], $fullPath)) {
					throw new CoreException('Function move_uploaded_file() failed!');
				}
				return array(
					'subdir' => $subdirDiskPath,
					'baseName' => $baseName,
					'extension' => $extension
				);
			}
		}
		throw new CoreException('Unable to save file on disk.');
	}

	public function removeFile($baseName, $extension, $image = False) {
		$fullPath = $this->getDiskPath($baseName, $extension);
		if (is_file($fullPath)) {
			unlink($fullPath);
		}
		if ($image) {
			$this->removeResizedImageFiles($baseName, $extension);
		}
	}

	public function getMaxFileSize() {
		$phpMaxSizeText = ini_get('upload_max_filesize');
		$factor = 1;
		$unitSymbolPos = 0;
		if (($unitSymbolPos = strpos($phpMaxSizeText, 'M')) || ($unitSymbolPos = strpos($phpMaxSizeText, 'm'))) {
			$factor = 1024 * 1024;
		}
		elseif (($unitSymbolPos = strpos($phpMaxSizeText, 'K')) || ($unitSymbolPos = strpos($phpMaxSizeText, 'k'))) {
			$factor = 1024;
		}
		if ($factor == 1) {
			$phpMaxSize = intval($phpMaxSizeText);
		}
		else {
			$phpMaxSize = intval(substr($phpMaxSizeText, 0, $unitSymbolPos)) * $factor;
		}
		return min($phpMaxSize, CoreConfig::get('CoreFiles', 'maxFileSize'));
	}

	/**
	 * @param array $uploadStruct
	 * @param string $fileCategory
	 * @return string rozszerzenie
	 * 
	 * Sprawdza czy rzeczywisty typ pliku jest zgodny z tym co jest dopuszczalne
	 */
	// @TODO: to jest strict check a jeszcze można zrobić bardziej lightowy check;
	// poza tym tego nie warto powtarzać bo jest czasochłonny!!!
	public function checkTypeAndGetExtension($uploadStruct, $fileCategory) {
		CoreUtils::checkConstraint(is_array($uploadStruct));
		$allowedMimeTypes = CoreConfig::get('CoreFiles', 'allowedMimeTypes');
		$defaultExtensions = CoreConfig::get('CoreFiles', 'defaultExtensions');
		$mimeType = mime_content_type($uploadStruct['tmp_name']);
		if (
			!array_key_exists($mimeType, $defaultExtensions)
			|| !array_key_exists($fileCategory, $allowedMimeTypes)
			|| !in_array($mimeType, $allowedMimeTypes[$fileCategory])
		) {
			return null;
		}
		return $defaultExtensions[$mimeType];
	}

	public function resizeImage($diskPath, $baseName, $extension, $imageResizeOptions) {
		$diskSubdir = $this->getResizedImageDiskSubdir($baseName, $extension);
		if (!is_dir($diskSubdir)) {
			mkdir($diskSubdir, CoreConfig::get('CoreFiles', 'imageCacheDirSubdirsPermissions'));
		}
		CoreServices2::getImages()->resize(
			$this->getDiskPath($baseName, $extension),
			$diskPath,
			$imageResizeOptions
		);
	}

	public function getDiskPath($baseName, $extension) {
		return CoreConfig::get('Environment', 'uploadDirDiskPath') . $this->getSubdirName($baseName) . $baseName . '.' . $extension;
	}

	protected function getResizedImageName($baseName, $extension, $imageResizeOptions) {
		$result = $baseName . CoreConfig::get('CoreFiles', 'fileNameSuffixJoinChar');
		foreach ($imageResizeOptions as $key => $value) {
			$result .= $key . $value;
		}
		$result .= '.' . $extension;
		return $result;
	}

	protected function getResizedImageDiskSubdir($baseName, $extension) {
		return CoreConfig::get('Environment', 'imageCacheDirDiskPath') . $this->getSubdirName($baseName);
	}

	public function getResizedImageDiskPath($baseName, $extension, $imageResizeOptions) {
		return
			$this->getResizedImageDiskSubdir($baseName, $extension) .
			$this->getResizedImageName($baseName, $extension, $imageResizeOptions);
	}

	protected function getSubdirName($baseName) {
		return substr(md5($baseName), 0, 2) . '/';
	}

	protected function removeResizedImageFiles($baseName, $extension) {
		$dirName = $this->getResizedImageDiskSubdir($baseName, $extension);
		if (is_dir($dirName)) {
			$dirObject = dir($dirName);
			while (False !== ($entry = $dirObject->read())) {
				if (substr($entry, 0, strlen($baseName) + 1) == $baseName . CoreConfig::get('CoreFiles', 'fileNameSuffixJoinChar')) {
					unlink($dirName . $entry);
				}
			}
			$dirObject->close();
		}
	}

	protected function createBaseName($preferredFileName, $iteration = 0) {
		$pointPos = strrpos($preferredFileName, '.');
		$badCharsReplaced = $this->replaceInvalidChars(substr($preferredFileName, 0, $pointPos));
		if (strlen($badCharsReplaced) == 0) {
			$badCharsReplaced = mt_rand(0, 1000000);
		}
		$shortened = substr($badCharsReplaced, 0, CoreConfig::get('CoreFiles', 'maxBaseNameLength'));
		$baseName = ($iteration == 0 ? $shortened : $shortened . mt_rand(0, 1000000));
		return $baseName;
	}

	protected function replaceInvalidChars($fileName) {
		$result = '';
		$len = strlen($fileName);
		for ($i = 0; $i < $len; $i++) {
			$char = substr($fileName, $i, 1);
			if (strpos(CoreConfig::get('CoreFiles', 'fileNameAllowedCharacters'), $char) !== False) {
				$result .= $char;
			}
			elseif (in_array($char, CoreConfig::get('CoreFiles', 'fileNameSpaceChars'))) {
				$result .= CoreConfig::get('CoreFiles', 'fileNameSpaceSubstitute');
			}
		}
		return $result;
	}
}
?>