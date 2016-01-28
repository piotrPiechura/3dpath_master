<?php
class ConfigCoreLangs extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['langsCMS'] = array('en');
		$this->values['defaultLangCMS'] = 'en';

		$this->values['langsWebsite'] = array('en');
		$this->values['defaultLangWebsite'] = 'en';

		$this->values['allLocalCharVariants'] = array(
			'A' => array('A', 'Ą', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ'),
			'B' => array('B', 'Þ'),
			'C' => array('C', 'Ć', 'Ç'),
			'D' => array('D', 'Ð'),
			'E' => array('E', 'Ę', 'È', 'É', 'Ê', 'Ë', 'Ę'),
			'F' => array('F'),
			'G' => array('G'),
			'H' => array('H'),
			'I' => array('I', 'Ì', 'Í', 'Î', 'Ï'),
			'J' => array('J'),
			'K' => array('K'),
			'L' => array('L', 'Ł'),
			'M' => array('M'),
			'N' => array('N', 'Ń', 'Ñ'),
			'O' => array('O', 'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'Ø'),
			'P' => array('P'),
			'Q' => array('Q'),
			'R' => array('R'),
			'S' => array('S', 'Ś', 'ß'),
			'T' => array('T'),
			'U' => array('U', 'Ù', 'Ú', 'Û', 'Ü'),
			'V' => array('V'),
			'W' => array('W'),
			'X' => array('X'),
			'Y' => array('Y', 'Ý'),
			'Z' => array('Z', 'Ź', 'Ż')
		);
		// array('Ą' => 'A', 'Ć' => 'C', 'Ę' => 'E', ...)
		$this->values['localCharsToLatinSource'] = array();
		$this->values['localCharsToLatinTarget'] = array();
		foreach ($this->values['allLocalCharVariants'] as $latin => $locals) {
			foreach ($locals as $local) {
				if ($latin != $local) {
					$this->values['localCharsToLatinSource'][] = $local;
					$this->values['localCharsToLatinTarget'][] = $latin;
					$this->values['localCharsToLatinSource'][] = mb_convert_case($local, MB_CASE_LOWER, "UTF-8");
					$this->values['localCharsToLatinTarget'][] = mb_convert_case($latin, MB_CASE_LOWER, "UTF-8");
				}
			}
		}
	}
}
?>