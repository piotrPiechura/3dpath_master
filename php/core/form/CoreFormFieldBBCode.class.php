<?php
class CoreFormFieldBBCode extends CoreFormAbstractField {
	public function __construct($name, $defaultValue = null) {
		parent::__construct($name, $defaultValue);
		$this->fieldType = 'BBCode';
	}

	protected function html2BBCode($html) {
		$text = $html;
		$text = str_replace('<br />', "\n", $text);
		$text = str_replace("\n\n", "\n", $text);
		$text = preg_replace("/<pre>(.*?)<\/pre>/si", '[pre]${1}[/pre]', $text);
		$text = preg_replace("/<img src=\"(.*?)\" alt=\"\" \/>/si", '[img]${1}[/img]', $text);
		$text = preg_replace("/<a href=\"(.*?)\" target=\"_blank\">(.*?)<\/a>/si", '[url=${1}]${2}[/url]', $text);
		$text = preg_replace("/<a href=\"mailto:(.*?)\">(.*?)<\/a>/si", '[email=${1}]${2}[/email]', $text);
		$text = preg_replace("/<u>(.*?)<\/u>/si", '[u]${1}[/u]', $text);
		$text = preg_replace("/<em>(.*?)<\/em>/si", '[i]${1}[/i]', $text);
		$text = preg_replace("/<strong>(.*?)<\/strong>/si", '[b]${1}[/b]', $text);
		$text = str_replace(array('<br />', '<strong>', '</strong>', '<em>', '</em>', '<pre>', '</pre>'), "", $text);
		$text = str_replace(array('<', '>'), array('&gt;', '&lt;'), $text);
		return $text;
	}

	protected function bbCode2HTML($data) {
		$data = nl2br(stripslashes(addslashes($data)));

		$search = array("\n", "\r", "[b]", "[/b]", "[i]", "[/i]", "[u]", "[/u]");
		$replace = array("\n", "", "<strong>", "</strong>", "<em>", "</em>", "<u>", "</u>");
		$data = str_replace($search, $replace, $data);

		$search = array(
			"/\[email\](.*?)\[\/email\]/si",
			"/\[email=(.*?)\](.*?)\[\/email\]/si",
			"/\[url\](.*?)\[\/url\]/si",
			"/\[url=(.*?)\](.*?)\[\/url\]/si",
			"/\[img\](.*?)\[\/img\]/si",
			"/\[pre\](.*?)\[\/pre\]/si"
		);
		$replace = array(
			"<a href=\"mailto:\\1\">\\1</a>",
			"<a href=\"mailto:\\1\">\\2</a>",
			"<a href=\"\\1\" target=\"_blank\">\\1</a>",
			"<a href=\"\\1\" target=\"_blank\">\\2</a>",
			"<img src=\"\\1\" alt=\"\" />",
			"<pre>\\1</pre>"
		);
		$data = preg_replace($search, $replace, $data);
		$htmlValidator = new Core_HTML_Validator();
		return $htmlValidator->getValidPartialHTML($data);
	}

	public function getHTMLValue() {
		return str_replace('<br/>', "\n", $this->html2BBCode($this->value));
	}

	public function adjustSubmittedValue($submittedValue) {
		if (!is_scalar($submittedValue)) { // assume hacking attempt
			return null;
		}
		$submittedValue = trim($submittedValue);
		if (empty($submittedValue)) {
			return null;
		}
		$submittedValue = htmlspecialchars(
			$submittedValue,
			ENT_QUOTES,
			CoreConfig::get('CoreDisplay', 'globalCharset')
		);
		$submittedValue = str_replace("\n", '<br/>', $submittedValue);
		$submittedValue = $this->bbCode2HTML($submittedValue);
		return $submittedValue;
	}
}
?>