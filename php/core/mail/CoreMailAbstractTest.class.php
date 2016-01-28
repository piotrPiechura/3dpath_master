<?php
abstract class CoreMailAbstractTest extends CoreTestAbstractTest {
	abstract protected function getMail();

	public function run() {
		$mail = $this->getMail();
		// TEST 1
		$from = 'biuro@deator.pl';
		$listTo = array('biuro@deator.pl');
		$listCC = array('biuro@deator.pl');
		$subject = 'test (ąćęłńóśźż ĄĆĘŁŃÓŚŹŻАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧч1)';
		$content = 
			"1' \\ \" &<>; //\n\t" . '" \\ \' &<>; // ąćęłńóśźż ĄĆĘŁŃÓŚŹŻАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧч1';
		$this->testingEngine->tryToPass($mail, 'sendPlainText',  array($from, $listTo, $listCC, $subject, $content),   0);
		// TEST 2
		$from = 'biuro@deator.pl';
		$listTo = array('biuro@deator.pl');
		$listCC = array('biuro@deator.pl');
		$subject = 'test HTML (ąćęłńóśźż ĄĆĘŁŃÓŚŹŻАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧч1)';
		$html = '
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2">
</head>
<body>
	<div>
		ąćęłńóśźż ĄĆĘŁŃÓŚŹŻАаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧч1<br>
		<b>I<b><i>ma</i>ges:<br>
		gif:<br>
		<img src="cid:test.gif" alt="" width="200" height="300" style="display: block; border: none;">
		jpg:<br>
		<img src="cid:test.jpg" alt="" width="200" height="300" style="display: block; border: none;">
		png:<br>
		<img src="cid:test.png" alt="" width="200" height="300" style="display: block; border: none;">
	</div>
</body>
</html>';
		$images = array();
		$images[] = array(
			'cid' => 'test.gif',
			'fileName' => 'test.gif',
			'filePath' => '/var/www/IP/php/core/mail/test.gif',
			'mimeType' => 'image/gif'
		);
		$images[] = array(
			'cid' => 'test.jpg',
			'fileName' => 'test.jpg',
			'filePath' => '/var/www/IP/php/core/mail/test.jpg',
			'mimeType' => 'image/gif'
		);
		$images[] = array(
			'cid' => 'test.png',
			'fileName' => 'test.png',
			'filePath' => '/var/www/IP/php/core/mail/test.png',
			'mimeType' => 'image/gif'
		);
		$this->testingEngine->tryToPass($mail, 'sendHTML',  array($from, $listTo, $listCC, $subject, $html, $images),   0);
	}
}
?>