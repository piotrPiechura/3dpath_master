<?php
interface iCoreFileLocationManager {
	public function getLinkHTML(&$record);

	public function getImageLinkHTML(
		&$record,
		$width = null,
		$height = null,
		$ignoreProportions = null,
		$crop = null,
		$backgroundColor = null
	);
}
?>
