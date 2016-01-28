<?php
class FileHushvizStaticController extends FileOperationAbstractController {
	protected $record = null;
	protected $diskPath = null;

	public function prepareData() {
		parent::prepareData();
		$this->initRecord();
		$this->checkUserPrivileges();
		$this->initDiskPath();
	}

	protected function initRecord() {
		$request = CoreServices2::getRequest();
		$id = $request->getFromGet('id');
		$fileDAO = new FileDAO();
		$this->record = $fileDAO->getRecordById($id);
		if (empty($this->record['id'])) {
			$this->error(1);
		}
	}

	protected function checkUserPrivileges() {
		$logic = new FileHushvizLogic();
		if (!$logic->isCurrentUserAllowed($this->record, 1)) {
			$this->error(2);
		}
	}

	protected function initDiskPath() {
		if ($this->record['fileCategory'] == 'image') {
			$request = CoreServices2::getRequest();
			$width = $request->getFromGet('width');
			$height = $request->getFromGet('height');
			$ignoreProportions = $request->getFromGet('ignoreProportions');
			$crop = $request->getFromGet('crop');
			$backgroundColor = $request->getFromGet('backgroundColor');
			$keepSmall = $request->getFromGet('keepSmall');
		}
		$files = CoreServices2::getFiles();
		if (empty($width) && empty($height)) {
			$this->diskPath = $files->getDiskPath(
				$this->record['fileBaseName'],
				$this->record['fileExtension']
			);
		}
		else {
			$options = array(
				'width' => $width,
				'height' => $height,
				'ignoreProportions' => $ignoreProportions,
				'crop' => $crop,
				'backgroundColor' => $backgroundColor,
				'keepSmall' => $keepSmall
			);
			$this->diskPath = $files->getResizedImageDiskPath(
				$this->record['fileBaseName'],
				$this->record['fileExtension'],
				$options
			);
			if (!is_file($this->diskPath)) {
				$files->resizeImage(
					$this->diskPath,
					$this->record['fileBaseName'],
					$this->record['fileExtension'],
					$options
				);
			}
		}
		if (!is_file($this->diskPath)) {
			$this->error(3);
		}
	}

	public function sendHeaders() {
		if ($this->record['fileCategory'] == 'image') {
			// obrazki cache'ujemy
			header('Cache-Control: public');
			header('Pragma: public');
			header('Expires: ' . date('D, d M Y', strtotime('+1 Month')) . ' 12:00:00 GMT');
			header('Content-type: ' . $this->record['fileMimeType']);
			// @TODO: ? header('Content-Disposition: attachment; filename="' . $this->record['fileBaseName'] . '.' . $this->record['fileExtension'] . '"');
		}
		else {
			// modeli nie cache'ujemy
			// @TODO: czy te nagłówki na pewno sa prawidlowe?
			header('Cache-Control: private');
			header('Pragma: private');
			header('Expires: ' . date('D, d M Y', strtotime('-1 Month')) . ' 12:00:00 GMT');
			header('Content-type: ' . $this->record['fileMimeType']);
			header('Content-Disposition: attachment; filename="' . $this->record['fileBaseName'] . '.' . $this->record['fileExtension'] . '"');
		}
	}

	public function display() {
		if (@readfile($this->diskPath) === false) {
			throw new CoreException('Problem displaying file \'' . $this->diskPath . '\'');
		}
	}

	protected function error($i) {
		// @TODO: potrzeba wysylac maila o bledzie?
		header("HTTP/1.0 404 Not Found");
		exit();
	}
}
?>