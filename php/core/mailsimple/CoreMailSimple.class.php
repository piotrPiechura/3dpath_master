<?php
class CoreMailSimple implements iCoreMail {
	protected function getHeaders($from, &$listTo, &$listCC) {
		if (empty($listTo) || !is_array($listTo)) {
			throw new CoreException('Recipient list must be a non-empty array');
		}
		$headers  = 'From: ' . $from . "\r\n";
		$headers .= 'To: ' . implode(', ', $listTo) . "\r\n";
		if (!empty($listCC)) {
			$headers .= 'Cc: ' . implode(', ', $listCC) . "\r\n";
		}
		return $headers;
	}

	public function sendPlainText($from, &$listTo, &$listCC, $subject, $content, &$attachments = null) {
		if (!empty($attachments)) {
			throw new CoreException('Attachments handling not implemented!');
		}
		$headers = $this->getHeaders($from, $listTo, $listCC);
		$headers .= 'Content-type: text/plain; charset=' . CoreConfig::get('CoreDisplay', 'globalCharset') . "\r\n";		
		$this->mail($subject, $content, $headers);
	}

	public function sendHTML($from, &$listTo, &$listCC, $subject, $htmlContent, &$attachments = null) {
		$headers = $this->getHeaders($from, $listTo, $listCC);
		$bound_text = md5("Deator" . microtime());
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: multipart/related; boundary=\"$bound_text\"\r\n"; 
		$bound = "--".$bound_text."\r\n"; 
		$bound_last = "--".$bound_text."--\r\n";  
		$content =
			" \r\n" . $bound
			. "Content-Type: text/html; charset=" . CoreConfig::get('CoreDisplay', 'globalCharset') . "\r\n" 
			. "Content-Transfer-Encoding: base64\r\n\r\n"
			. chunk_split(base64_encode($htmlContent));
		if (!empty($attachments)) {
			foreach ($attachments as $struct) {
				if (is_file($struct['filePath'])) {
					$file = file_get_contents($struct['filePath']);
					$content .=
						$bound
						. "Content-Type: " . $struct['mimeType'] . "; name=" . $struct['fileName'] . "\r\n"
						. "Content-Transfer-Encoding: base64\r\n"
						. "Content-ID: " . $struct['cid'] . "\r\n"
						. "Content-Disposition: inline; filename=" . $struct['fileName'] . "\r\n"
						. "\r\n"
						. chunk_split(base64_encode($file));
				}
			}
		}
		$content .= $bound_last;  
		$this->mail($subject, $content, $headers);
	}

	protected function mail($subject, $content, $headers) {
		$subject = '=?' . CoreConfig::get('CoreDisplay', 'globalCharset') . '?B?' . base64_encode($subject) . '?=';
		if (!@mail('', $subject, $content, $headers)) {
			throw new CoreException('Sending mail failed!');
		}
	}
}
?>