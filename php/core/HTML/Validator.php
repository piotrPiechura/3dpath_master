<?php
include_once('php/external/htmlpurifier/library/HTMLPurifier.path.php');
include_once('HTMLPurifier.includes.php');
// Opcjonalne funkcjonalniści HTML Purifiera, nie bardzo działają:
// include_once('HTMLPurifier.auto.php');
// ...i HTMLPurifier.includes.php nie ładuje wszystkiego

class Core_HTML_Validator {
	public function getValidPartialHTML(
		$html,
		$docType = null,
		$encoding = null
	) {
		if (empty($html)) {
			return $html;
		}
		if (empty($docType)) {
			$docType = CoreConfig::get('CoreDisplay', 'htmlDocType');
		}
		if (empty($encoding)) {
			$encoding = CoreConfig::get('CoreDisplay', 'globalCharset');
		}
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Cache', 'SerializerPath', CoreConfig::get('Environment', 'htmlPurifierCacheDirDiskPath'));
		$config->set('Core', 'Encoding', $encoding);
		$config->set('HTML', 'Doctype', $docType);

		$config->set('HTML', 'DefinitionID', 'enduser-customize.html tutorial');
		$config->set('HTML', 'DefinitionRev', 1);
		$def = $config->getHTMLDefinition(true);
		$def->addAttribute('a', 'target', new HTMLPurifier_AttrDef_Enum(
			array('_blank','_self','_target','_top')
		));
		$purifier = new HTMLPurifier($config);
		return $purifier->purify($html);
	}
}
?>