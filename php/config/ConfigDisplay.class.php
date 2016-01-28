<?php
class ConfigDisplay extends CoreConfigAbstractConfig {
	protected function init() {
		// articles
		$this->values['archivesDatesLimit'] = 12;
		$this->values['articlesInArchiveDateLimit'] = 5;

		// models
		$this->values['recentlyAddedModelsLimit'] = 20;
		$this->values['searchedModelsOnPage'] = 20;
		$this->values['relatedModelsCount'] = 10;
		$this->values['featuredModelsCount'] = 20;
		$this->values['logsOnPage'] = 100;
		$this->values['searchedModelsDefaultThumbsSize'] = 'medium';
		$this->values['forumSearchPostsOnPage'] = 20;

		// downloads
		$this->values['downloadRecordsOnPage'] = 10;

		// credis packages
		$this->values['creditsPackageRecordsOnPage'] = 5;

	}
}
?>