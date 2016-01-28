<?php
class FileHushvizUrlManager implements iCoreFileLocationManager {
	protected function getCommonOptions(&$record) {
		// Parametr version jest przekazywany po to, żeby w przypadku zmiany pliku dyskowego
		// przypisanego do tego samego rekordu nie byĹ‚ pobierany plik z cache'a, tylko ĹĽeby
		// nastepowaĹ‚o faktyczne odwoĹ‚anie do serwera.
		// Ten parametr w ogĂłle nie jest obsĹ‚ugiwany przez FileHushvizStaticController, bo ten
		// kontroler i tak zawsze zwrĂłci po prostu aktualnÄ… wersjÄ™ pliku.
		$options = array(
			'_m', 'FileHushviz',
			'_o', 'Static',
			'id', $record['id'],
			'version', strtotime($record['fileUpdateTime'])
		);
		$logic = new FileHushvizLogic();
		if ($logic->isProtectedFile($record)) {
			$view = CoreServices2::getRequest()->getSessionName() == 'CMSSession' ? 'c' : 'w';
			$options[] = 'view';
			$options[] = $view;
		}
		return $options;
	}

	public function getLinkHTML(&$record) {
		return CoreServices2::getUrl()->createHTML($this->getCommonOptions($record));
	}

	public function getAddress(&$record) {
		return CoreServices2::getUrl()->createAddress($this->getCommonOptions($record));
	}

	public function getImageLinkHTML(
		&$record,
		$width = null,
		$height = null,
		$ignoreProportions = null,
		$crop = null,
		$backgroundColor = null,
		$keepSmall = null
	) {
		$options = $this->getCommonOptions($record);
		if ($width) {
			$options[] = 'width';
			$options[] = $width;
		}
		if ($height) {
			$options[] = 'height';
			$options[] = $height;
		}
		if ($ignoreProportions) {
			$options[] = 'ignoreProportions';
			$options[] = $ignoreProportions;
		}
		if ($crop) {
			$options[] = 'crop';
			$options[] = $crop;
		}
		if ($backgroundColor) {
			$options[] = 'backgroundColor';
			$options[] = $backgroundColor;
		}
		if ($keepSmall) {
			$options[] = 'keepSmall';
			$options[] = $keepSmall;
		}
		return CoreServices2::getUrl()->createHTML($options);
	}

	public function getImageLink(
		&$record,
		$width = null,
		$height = null,
		$ignoreProportions = null,
		$crop = null,
		$backgroundColor = null,
		$keepSmall = null
	) {
		$options = $this->getCommonOptions($record);
		if ($width) {
			$options[] = 'width';
			$options[] = $width;
		}
		if ($height) {
			$options[] = 'height';
			$options[] = $height;
		}
		if ($ignoreProportions) {
			$options[] = 'ignoreProportions';
			$options[] = $ignoreProportions;
		}
		if ($crop) {
			$options[] = 'crop';
			$options[] = $crop;
		}
		if ($backgroundColor) {
			$options[] = 'backgroundColor';
			$options[] = $backgroundColor;
		}
		if ($keepSmall) {
			$options[] = 'keepSmall';
			$options[] = $keepSmall;
		}
		return CoreServices2::getUrl()->createAddress($options);
	}
/*

@TODO:
JEĹ»ELI TO NIE JEST GĹ�OWNY PLIK MODELU TO MOĹ»NA PO PROSTU RZUCIÄ† LINK DO PLIKU...
ALE Ĺ»EBY TAK ZROBIÄ† TO TRZEBA PLIKI MODELI TRZYMAÄ† W INNYM MIEJSCU, GDZIE BÄ�DZIE Deny From All
BO INACZEJ WYSTARCZY ZGADNÄ„Ä† NAZWÄ� (LUB ZNALEĹąÄ† NA FORUM) I MOĹ»NA ZA DARMO ĹšCIÄ„GNÄ„Ä† MODEL!

*/
}
?>
