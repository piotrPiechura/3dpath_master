<?php
interface iCoreMail {
	/**
	 * Rzuca wyjątek w przypadku porażki
	 */
	public function sendPlainText($from, &$listTo, &$listCC, $subject, $content, &$attachments = null);

	/**
	 * Rzuca wyjątek w przypadku porażki
	 * $images to lista tablic:
	 *  array(
	 * 		'cid' => <cid>,
	 * 		'fileName' => <nazwa pliku>,
	 * 		'filePath' => <�cie�ka dyskowa, mo�e by� wzgl�dna do pliku index.php>,
	 * 		'mimeType' => <standardowy MIME>
	 * 	)
	 */
	public function sendHTML($from, &$listTo, &$listCC, $subject, $contentHTML, &$attachments = null);
}
?>