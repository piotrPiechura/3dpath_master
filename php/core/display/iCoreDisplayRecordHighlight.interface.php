<?php
interface iCoreDisplayRecordHighlight {
	/**
	 * @param unknown_type $record
	 * 
	 * @return: string, one of the following:
	 * 'normal', 'grey', 'lightRed', 'strongRed', 'lightGreen', 'strongGreen'
	 */
	public function getValue(&$record);
}
?>