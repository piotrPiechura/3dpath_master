<?php
class CoreFileImageHandlerTest extends CoreTestAbstractTest {
	protected $imageHandler = null;
	
	public function __construct($testingEngine) {
		parent::__construct($testingEngine);
		$this->imageHandler = new CoreFileImageHandler();		
	}
	
	public function run() {
		$srcFilesLocation = CoreConfig::get('Environment', 'applicationDir') . 'php/core/file/';
		$dstFilesLocation = CoreConfig::get('Environment', 'imageCacheDirDiskPath');
		
		$srcFile1 = $srcFilesLocation . 'test.jpg';
		$srcFile2 = $srcFilesLocation . 'test.gif';
		$srcFile3 = $srcFilesLocation . 'test.png';

		$dstFile11 = $dstFilesLocation . 'test1.jpg';
		$dstFile21 = $dstFilesLocation . 'test1.gif';
		$dstFile31 = $dstFilesLocation . 'test1.png';
		$options1 = array('width' => 100);
		$newImageParams1 = array('width' => 100, 'height' => 150);
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile1, $dstFile11, $options1, $newImageParams1));
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile2, $dstFile21, $options1, $newImageParams1));
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile3, $dstFile31, $options1, $newImageParams1));

		$dstFile12 = $dstFilesLocation . 'test2.jpg';
		$options2 = array('width' => 150, 'crop' => '1');
		$newImageParams2 = array();
		$this->testingEngine->tryToFail($this, 'checkResized', array($srcFile1, $dstFile12, $options2, $newImageParams2), 'CoreException');

		$dstFile13 = $dstFilesLocation . 'test3.jpg';
		$options3 = array('width' => 150, 'height' => 300, 'crop' => 1);
		$newImageParams3 = array('width' => 150, 'height' => 300);
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile1, $dstFile13, $options3, $newImageParams3));

		$dstFile14 = $dstFilesLocation . 'test4.jpg';
		$options4 = array('width' => 300, 'height' => 200, 'ignoreProportions' => 1);
		$newImageParams4 = array('width' => 300, 'height' => 200);
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile1, $dstFile14, $options4, $newImageParams4));

		$dstFile15 = $dstFilesLocation . 'test5.jpg';
		$options5 = array('width' => 300, 'height' => 200, 'backgroundColor' => '3399ff');
		$newImageParams5 = array('width' => 300, 'height' => 200);
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile1, $dstFile15, $options5, $newImageParams5));

		$dstFile16 = $dstFilesLocation . 'test6.jpg';
		$options6 = array('width' => 225, 'height' => 225);
		$newImageParams6 = array('width' => 150, 'height' => 225);
		$this->testingEngine->tryToPass($this, 'checkResized', array($srcFile1, $dstFile16, $options6, $newImageParams6));
	}

	/**
	 * First three args are the same as for CoreFileImageHandler::resize();
	 * $newImageParams is an array like:
	 *     array(
	 *         'width'  => <widthInPixels>,
	 *         'height' => <heightInPixels>
	 *     )
	 */
	public function checkResized($srcFile, $dstFile, $options, $newImageParams) {
		$oldMetadata = getimagesize($srcFile);
		$this->imageHandler->resize($srcFile, $dstFile, $options);
		$newMetadata = getimagesize($dstFile);
		unlink($dstFile);
		if ($newMetadata['mime'] != $oldMetadata['mime']) {
			echo('Mime types do not match \'' . $newMetadata['mime']  . '\' instead of \'' . $oldMetadata['mime'] . '\'');
		}
		if ($newMetadata[0] != $newImageParams['width']) {
			echo('Wrong width: \'' . $newMetadata[0]  . '\' instead of \'' . $newImageParams['width'] . '\'');
		}
		if ($newMetadata[1] != $newImageParams['height']) {
			echo('Wrong height: \'' . $newMetadata[1]  . '\' instead of \'' . $newImageParams['height'] . '\'');
		}
	}
}
?>