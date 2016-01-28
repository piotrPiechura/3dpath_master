<?php
class ConfigCoreFiles extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['fileNameAllowedCharacters'] = '_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$this->values['fileNameSpaceSubstitute'] = '_';
		$this->values['fileNameSuffixJoinChar'] = '-';
		$this->values['fileNameSpaceChars'] = array(' ', '-', '_', '.');
		// max. length of file name without suffixes and extension
		$this->values['maxBaseNameLength'] = 100; 
		// be careful! php ini variable upload_max_filesize is not taken into account here!
		$this->values['maxFileSize'] = 10 * 1024 * 1024;

		$this->values['defaultImageWidthPX'] = 400;
		$this->values['defaultImageHeightPX'] = 300;

		$this->values['defaultExtensions'] = array(
			'application/pdf' => 'pdf',
			'application/zip' => 'zip',
			'image/jpg' => 'jpg',
			'image/jpeg' => 'jpg',
			'image/pjpeg' => 'jpg',
			'image/png' => 'png',
			'image/gif' => 'gif',
			'text/plain' => 'txt'
		);
		$this->values['allowedMimeTypes'] = array(
			'any' => array_keys(
				$this->values['defaultExtensions']
			),
			'doc' => array(
				'application/pdf'
			),
			'archive' => array(
				'application/zip'
			),
			'image' => array(
				'image/jpg',
				'image/jpeg',
				'image/pjpeg',
				'image/png',
				'image/gif'
			)
		);

		$this->values['knownMimeTypes'] = array(
			//'arj'	=>	'application/arj',
			//'au'	=>	'audio/basic',
			//'avi'	=>	'video/avi',
			//'bmp'	=>	'image/bmp',
			//'bz'	=>	'application/x-bzip',
			//'bz2'	=>	'application/x-bzip2',
			//'css'	=>	'text/css',
			//'doc'	=>	'application/msword',
			//'dvi'	=>	'application/x-dvi',
			//'dxf'	=>	'application/dxf',
			//'eps'	=>	'application/postscript',
			'gif'	=>	'image/gif',
			//'gz'	=>	'application/x-gzip',
			//'gzip'	=>	'application/x-gzip',
			//'htm'	=>	'text/html',
			//'html'	=>	'text/html',
			//'ico'	=>	'image/x-icon',
			'pjpeg'	=>	'image/jpeg',
			'jpeg'	=>	'image/jpeg',
			'jpg'	=>	'image/jpeg',
			//'js'	=>	'text/javascript',
			//'mid'	=>	'audio/midi',
			//'mov'	=>	'video/quicktime',
			//'mp3'	=>	'audio/mpeg3',
			//'mpeg'	=>	'video/mpeg',
			//'mpg'	=>	'video/mpeg',
			//'mpga'	=>	'audio/mpeg',
			//'ods'	=>	'application/vnd.oasis.opendocument.spreadsheet',
			//'odt'	=>	'application/vnd.oasis.opendocument.text',		
			//'pcx'	=>	'image/x-pcx',
			'pdf'	=>	'application/pdf',
			'png'	=>	'image/png',
			//'ppt'	=>	'application/mspowerpoint',
			//'ps'	=>	'application/postscript',
			//'qt'	=>	'video/quicktime',
			//'rtf'	=>	'text/richtext',
			//'rtx'	=>	'text/richtext',
			//'tgz'	=>	'application/x-compressed',
			//'tif'	=>	'image/tiff',
			//'tiff'	=>	'image/tiff',
			//'txt'	=>	'text/plain',
			//'wav'	=>	'audio/wav',
			//'xls'	=>	'application/excel',
			//'xml'	=>	'application/xml',
			'zip'	=>	'application/zip'
		);

		$this->values['uploadDirSubdirsPermissions'] = 0777;
		$this->values['imageCacheDirSubdirsPermissions'] = 0777;
	}
}
?>