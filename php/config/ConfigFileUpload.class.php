<?php
class ConfigFileUpload extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['swfUploadAttachmentTypes'] = array(
			'article' => array(
				'gallery' => array(
					'fileCategory' => 'image',
					'minItems' => 0,
					'maxItems' => null
				)
			),
			'subpage' => array(
				'gallery' => array(
					'fileCategory' => 'image',
					'minItems' => 0,
					'maxItems' => null
				)
			),			
			'model' => array(
				'gallery' => array(
					'fileCategory' => 'image',
					'minItems' => 3,
					'maxItems' => 30
				)
			)
		);

		$this->values['simpleAttachmentTypes'] = array(
			/*'home' => array(
				'box1Image' => array(
					'fileCategory' => 'image',
					'minItems' => 1,
					'maxItems' => 1
				),
				'box2Image' => array(
					'fileCategory' => 'image',
					'minItems' => 1,
					'maxItems' => 1
				)
			),*/
			'banner' => array(
				'banner' => array(
					'fileCategory' => 'image',
					'minItems' => 1,
					'maxItems' => 1
				)
			),
			'model' => array(
				'main' => array(
					'fileCategory' => 'archive',
					'minItems' => 1,
					'maxItems' => null
				),
				'specification' => array(
					'fileCategory' => 'doc',
					'minItems' => 0,
					'maxItems' => 1
				)
			),
			'user' => array(
				'avatar' => array(
					'fileCategory' => 'image',
					'minItems' => 0,
					'maxItems' => 1
				)
			)
		);

	}	
}
?>
