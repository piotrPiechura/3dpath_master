<?php
class SubpageUrlManager {
	public function createHTML(
		&$record
	) {
		$params = array('_m', $record['subpageModule'], '_o', $record['subpageMode']);
		if ($record['subpageModule'] == 'Subpage' && $record['subpageMode'] == 'Website') {
			$params = array_merge($params, array('id', $record['id']));
		}
		return CoreServices2::getUrl()->createHTML($params);
	}
}
?>