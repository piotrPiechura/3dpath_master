<?php
class HomeWebsiteController extends WebsiteAbstractControllerStandardLayout {

	protected $modelDAO = null;
	protected $record = null;
	protected $images = null;
	protected $featuredItemsList = null;
	protected $featuredItemsImagesList = null;

	protected $freeModelOfTheMonth = null;
	protected $freeModelOfTheMonthImagesList = null;

	protected function initAdditionalData() {
		parent::initAdditionalData();
		$homeDAO = new HomeDAO();
		$this->record = $homeDAO->getRecord();
		$idArray = array($this->record['id']);
		$image1 = $this->fileDAO->getFirstImageListByRecordList('home', $idArray, 'box1Image');
		$image2 = $this->fileDAO->getFirstImageListByRecordList('home', $idArray, 'box2Image');
		$this->images = array();
		if (!empty($image1)) {
			$this->images[1] = $image1[$this->record['id']];
		}
		if (!empty($image2)) {
			$this->images[2] = $image2[$this->record['id']];
		}
		$this->modelDAO = new ModelDAO();
		$this->initFeaturedItems();
		$this->initFreeModelOfTheMonth();
	}

	protected function initFeaturedItems() {
		$featuredRecords = $this->modelDAO->getFeaturedList(CoreConfig::get('Display', 'featuredModelsCount'));
		$this->featuredItemsList = array();
		foreach ($featuredRecords as $record) {
			$this->featuredItemsList[$record['id']] = $record;
		}
		if(!empty($this->featuredItemsList)) {
			$ids = array_keys($this->featuredItemsList);
			$this->featuredItemsImagesList = $this->fileDAO->getFirstImageListByRecordList(
				'model',
				$ids,
				'gallery'
			);
		}
	}

	protected function initFreeModelOfTheMonth() {
		$year = date("Y");
		$month = date("n");
		$modelOfTheMonthDAO = new ModelOfTheMonthDAO();
		$modelOfTheMonthInfoRecord = $modelOfTheMonthDAO->getFreeModelOfTheMonth($year, $month);
		if (!empty($modelOfTheMonthInfoRecord['modelId'])) {
			
			$this->freeModelOfTheMonth = $this->modelDAO->getRecordById($modelOfTheMonthInfoRecord['modelId']);
			$ids = array($modelOfTheMonthInfoRecord['modelId']);
			if(empty($this->freeModelOfTheMonth['id']) || !empty($this->freeModelOfTheMonth['id']) && $this->freeModelOfTheMonth['modelState'] == 'hidden') {
				$this->freeModelOfTheMonth = null;
			} else {
				$this->freeModelOfTheMonthImagesList = $this->fileDAO->getFirstImageListByRecordList(
					'model',
					$ids,
					'gallery'
				);
			}
		}
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		$display->assign('record', $this->record);
		$display->assign('images', $this->images);
		$display->assign('featuredItemsList', $this->featuredItemsList);
		$display->assign('featuredItemsImagesList', $this->featuredItemsImagesList);
		$display->assign('freeModelOfTheMonth', $this->freeModelOfTheMonth);
		$display->assign('freeModelOfTheMonthImagesList', $this->freeModelOfTheMonthImagesList);
	}
}
?>