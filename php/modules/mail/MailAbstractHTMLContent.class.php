<?php
require_once('php/external/simplehtmldom/simple_html_dom.php');

/**
 * W atrybutach "src" znaczników "img" w templacie emaila należy umieszczać:
 * - dla obrazków statycznych napis "path,<path>", gdzie <path> to śiezka dyskowa do
 *   pliku względem katalogu aplikacji, np. "img/email/logo.jpg"
 * - dla obrazków użytkownika napis
 *   "fileRecord,<id>,<width>,<height>,<ignoreProportions>,<crop>,<backgroundColor>",
 *   gdzie <id> jest identyfikatorem rekordu, <width> i <height> są obowiązkowe, natomiast
 *   elementy <ignoreProportions>, <crop>, <backgroundColor> są opcjonalne, analogicznie
 *   jak w metodzie iCoreFileLocationManager::getImageLinkHTML().
 */
abstract class MailAbstractHTMLContent extends MailAbstractContent {
	public function __construct($params = null) {
		parent::__construct($params);
		$this->initImages();
	}

	protected function addStaticImageInfo(&$imageData) {
		if (count($imageData) != 2) {
			throw new CoreException('Invalid number of arguments supplied for static image in email template!');
		}
		$fileName = basename($imageData[1]);
		$fileExtension = end(explode(".", $fileName));
		$filePath = $imageData[1];
		$mimeTypes = array_flip(CoreConfig::get('CoreFiles', 'defaultExtensions'));
		if(!empty($mimeTypes[$fileExtension])) {
			$fileMimeType = $mimeTypes[$fileExtension];
		}
		else {
			throw new CoreException('Unrecognized mimetype for extension "' . $fileExtension . '"');
		}
		$this->attachments[] = array(
			'cid' => $cid,
			'fileName' => $fileName,
			'filePath' => $filePath,
			'mimeType' => $fileMimeType
		);
	}

	protected function getDynamicImageOptions(&$imageData) {
		$options = array(
			'width' => $imageData[2],
			'height' => $imageData[3]
		);
		if(!empty($imageData[4])) {
			$options['ignoreProportions'] = $imageData[4];
		}
		if(!empty($imageData[5])) {
			$options['crop'] = $imageData[5];
		}
		if(!empty($imageData[6])) {
			$options['backgroundColor'] = $imageData[6];
		}
		return $options;
	}

	protected function addDynamicImageInfo(&$imageData) {
		if (count($imageData) < 4 || count($imageData) > 7) {
			throw new CoreException('Invalid number of arguments supplied for image from database in email template!');
		}
		$image = $fileDAO->getRecordById($imageData[1]);
		CoreUtils::checkConstraint(!empty($image['id']));
		$fullName = $image['fileBaseName'] . '.' . $image['fileExtension'];
		$options = $this->getDynamicImageOptions($imageData);
		$resizedImagePath = $files->getResizedImageDiskPath(
			$image['fileBaseName'],
			$image['fileExtension'],
			$options
		);
		if(!file_exists($resizedImagePath)) {
			$imagePath = $files->getDiskPath($image['fileBaseName'], $image['fileExtension']);
			$files->resizeImage($resizedImagePath, $image['fileBaseName'], $image['fileExtension'], $options);
		}
		$this->attachments[] = array(
			'cid' => $cid,
			'fileName' => $fullName,
			'filePath' => $resizedImagePath,
			'mimeType' => $image['fileMimeType']
		);
	}

	protected function initImages() {
		$files = CoreServices::get('files');
		$fileDAO = new FileDAO();
		$html = new simple_html_dom();
		$html->load($this->getContent());
		$images = $html->find('img[src^=cid:]');
		foreach($images as $image) {
			$src = $image->src;
			$tmp = explode(':', $src);
			$cid = $tmp[1];
			$parts = explode(',', $cid);
			switch($parts[0]) {
				case 'path':
					$this->addStaticImageInfo($parts);
					break;
				case 'fileRecord':
					$this->addDynamicImageInfo($parts);
					break;
			}
		}
	}
}
?>