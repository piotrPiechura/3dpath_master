<?php
abstract class WebsiteAbstractControllerStandardLayout extends WebsiteAbstractController {
	/**
	 * @var SubpageDAO
	 */
	protected $subpageDAO = null;
	/**
	 * @var BannerDAO
	 */
	protected $bannerDAO = null;
	/**
	 * @var BannerCampaignDAO
	 */
	protected $bannerCampaignDAO = null;
	/**
	 * @var FileDAO
	 */
	protected $fileDAO = null;
	protected $subpage = null;
	protected $menu = null;
	protected $bannerList = null;
	protected $bannerImageList = null;
	protected $bannerCampaignList = null;
	/**
	 * @var CoreForm
	 */
	protected $loginForm = null;
	protected $loginErrorMessageContainer = null;
	protected $filter = null;
	/**
	 * @var CoreForm
	 */
	protected $filterForm = null;
	protected $paginationForm = null;
	protected $backToLastSearchUrl = null;
	/**
	 * @var CoreForm
	 */
	protected $newsletterForm = null;
	protected $showCancelNewsletterSubscriptionLink = null;

	protected function getBaseTemplate() {
		return 'website';
	}

	public function prepareData() {
                 CoreUtils::redirect(CoreServices2::getUrl()->createAddress(
					'_m', 'Admin',
					'_o', 'Login'
				)
                        );
		$this->checkHTTPS();
		$this->initDAO();
		$this->initSubpage();
		$this->currentUser = CoreServices2::getAccess()->getCurrentUserData();
		if (!$this->isUsagePermitted()) {
			$this->redirectToPermissionDeniedPage();
		}
		$this->handleLoginForm();
		$this->initNewsletterForm();
		$this->initBannerList();
		$this->initFilterForm();
		$this->createBasicFilterFormFields();
		$this->initAdditionalData();
		$this->updateStats();
	}

	protected function initAdditionalData() {}

	protected function initDAO() {
		$this->subpageDAO = new SubpageDAO();
		$this->bannerDAO = new BannerDAO();
		$this->fileDAO = new FileDAO();
		$this->bannerCampaignDAO = new BannerCampaignDAO();
	}

	protected function initSubpage() {
		$this->subpage = $this->subpageDAO->getRecordByModuleAndMode(
			CoreServices2::getModules()->getControllerModule(),
			CoreServices2::getModules()->getControllerMode()
		);
		if (
			empty($this->subpage['id'])
			|| $this->subpage['subpageState'] != 'visible'
		) {
			$this->handleInvalidAddress();
		}
	}

	protected function getUserId() {
		return
			!empty($this->currentUser['id'])
			? $this->currentUser['id']
			: null;
	}

	protected function getMenuStruct() {
		$menu = array();
		$menuSideRecord = $this->subpageDAO->getRecordByModuleAndMode('Menu', 'Side');
		$menu['menuSide'] = $this->subpageDAO->getChildren($menuSideRecord, !$this->isUserLogged());
		$menuBottomRecord = $this->subpageDAO->getRecordByModuleAndMode('Menu', 'Bottom');
		$menu['menuBottom'] = $this->subpageDAO->getChildren($menuBottomRecord, !$this->isUserLogged());
		return $menu;
	}

	protected function isUsagePermitted() {
		return
			empty($this->subpage['subpageLoginRequired'])
			|| $this->isUserLogged();
	}

	protected function loginAndReload() {
		$success = CoreServices::get('access')->login(
			$this->loginForm->getField('userEmail')->getValue(),
			$this->loginForm->getField('userPassword')->getValue(),
			$this->loginErrorMessageContainer
		);
		if ($success) {
			/*
			// @TODO: na co to?
			$this->checkCredits();
			 */
			$this->redirectToPage(
				CoreServices2::getUrl()->getCurrentExactAddress(),
				'standard'
			);
		}
	}

	protected function initNewsletterFormForRegisteredUser() {
		$action = CoreServices2::getUrl()->createHTML(
			'_m', 'NewsletterSubscriber',
			'_o', 'WebsiteUserRegister'
		);
		$this->newsletterForm = new CoreForm('post', $action, 'newsletterForm');
	}

	protected function initNewsletterFormForGuest() {
		$action = CoreServices2::getUrl()->createHTML(
			'_m', 'NewsletterSubscriber',
			'_o', 'WebsiteGuestRegister'
		);
		$this->newsletterForm = new CoreForm('post', $action, 'newsletterForm');
		$this->newsletterForm->addField(new CoreFormFieldText('newsletterSubscriberEmail'));
	}

	protected function initNewsletterForm() {
		if (!empty($this->currentUser['id'])) {
			$newsletterSubscriberDAO = new NewsletterSubscriberDAO();
			$newsletterSubscriber = $newsletterSubscriberDAO->getByEmail(
				$this->currentUser['userEmail']
			);
			if ($newsletterSubscriber['newsletterSubscriberState'] == 'active') {
				$this->showCancelNewsletterSubscriptionLink = 1;
			}
			else {
				$this->initNewsletterFormForRegisteredUser();
			}
		}
		else {
			$this->initNewsletterFormForGuest();
		}
	}

	protected function handleLoginForm() {
		if (!empty($this->currentUser['id'])) {
			if (CoreServices2::getRequest()->getFromGet('logout') == 1) {
				CoreServices2::getAccess()->logout();
				$this->redirectToHomePage();
			}
		}
		else {
			$currentPage = CoreServices2::getUrl()->getCurrentExactAddress();
			$this->loginForm = new CoreForm(
				'post',
				CoreServices2::getUrl()->getCurrentExactAddress(),
				'loginForm'
			);
			$this->createLoginFormFields();
			if ($this->loginForm->isSubmitted()) {
				//$this->addLoginFormValidators();
				CoreServices2::getAccess()->logout();
				$this->loginForm->setFieldValuesFromRequest();
				$this->loginErrorMessageContainer = $this->loginForm->getValidationResults();
				if (!$this->loginErrorMessageContainer->isAnyErrorMessage()) {
					$this->loginAndReload();
				}
			}
		}
	}

	protected function createLoginFormFields() {
		$this->loginForm->addField(new CoreFormFieldText('userEmail'));
		$this->loginForm->addField(new CoreFormFieldPassword('userPassword'));
		// @TODO: sprawdzić czy to potrzebne, bo raczej nie
		$this->loginForm->addField(new CoreFormFieldSubmit('_login'));
	}

	protected function addLoginFormValidators() {
		$this->loginForm->addValidator(new CoreFormValidatorNotEmpty('userEmail'));
		$this->loginForm->addValidator(new CoreFormValidatorMaxTextLength('userEmail', 40));
		$this->loginForm->addValidator(new CoreFormValidatorNotEmpty('userPassword'));
		$this->loginForm->addValidator(new CoreFormValidatorMaxTextLength('userPassword', 40));
	}

	protected function getModelCategoryOptions() {
		$modelCategoryDAO = new ModelCategoryDAO();
		$categories = $modelCategoryDAO->getSimpleVisibleList();
		$options = $modelCategoryDAO->modifyListForSelectBySpecificColumn(
			$categories,
			'id',
			'<modelCategoryName>',
			'<all>'
		);
		return $options;
	}

	protected function getModelTypeOptions() {
		$modelTypeDAO = new ModelTypeDAO();
		$types = $modelTypeDAO->getSimpleVisibleList();
		$options = $modelTypeDAO->modifyListForSelectBySpecificColumn(
			$types,
			'id',
			'<modelTypeName>',
			'<all>'
		);
		return $options;
	}

	protected function getFilterTypes() {
		$categoryOptions = $this->getModelCategoryOptions();
		$typeOptions = $this->getModelTypeOptions();
		return array(
			'modelCategoryId' => new CoreFilterSelect(false, $categoryOptions),
			'modelTypeId' => new CoreFilterSelect(false, $typeOptions),
			'phrase' => new CoreFilterMultipleLikeWithOr(array(
				'modelName',
				'modelDescription',
				'authorName',
				'modelPublishDate',
				'modelTag'
			))
		);
	}

	protected function initFilterForm() {
		$this->filterForm = new CoreForm(
			'get',
			CoreServices2::getUrl()->createHTML('_m', 'Model', '_o', 'SearchWebsiteList'),
			'filterForm'
		);
	}

	protected function createBasicFilterFormFields() {
		foreach ($this->getFilterTypes() as $fieldName => $filter) {
			$field = $filter->createField($fieldName);
			if(!empty($field)) {
				$this->filterForm->addField($field);
			}
		}
	}

	// @TODO: na większości podstron zbędne!
	protected function initPaginationForm() {
		if(!empty($this->pagination)) {
			$this->paginationForm = new CoreForm(
				'post',
				CoreServices2::getUrl()->getCurrentPageFullUrlHTML(),
				99
			);
			$this->paginationForm->addField(new CoreFormFieldText('pageNumber', $this->pagination->getCurrentPage() + 1));
			$this->paginationForm->addValidator(new CoreFormValidatorNotEmpty('pageNumber'));
			$this->paginationForm->addValidator(new CoreFormValidatorInteger('pageNumber', -9999999, 9999999));
			$this->handlePaginationForm();
		}
	}

	// @TODO: na większości podstron zbędne!
	protected function handlePaginationForm() {
		if($this->paginationForm->isSubmitted()) {
			$this->paginationForm->setFieldValuesFromRequest();
			$errorMessageContainer = $this->paginationForm->getValidationResults();
			if (!$errorMessageContainer->isAnyErrorMessage()) {
				$pageNumberField = $this->paginationForm->getField('pageNumber');
				$urlSrv = CoreServices2::getUrl();
				$fullUrl = $urlSrv->getCurrentPageFullUrl();
				$url = $urlSrv->stripParams($fullUrl, array('page' => null));
				$pageNumber = $pageNumberField->getValue() - 1;
				$firstPage = 0;
				$lastPage = $this->pagination->getPageCount() - 1;
				if($pageNumber > $lastPage) {
					$pageNumber = $lastPage;
				} else if ($pageNumber < $firstPage) {
					$pageNumber = $firstPage;
				}
				$url = $urlSrv->appendArguments($url, array('page', $pageNumber));
				CoreUtils::redirect($url);
			}
		}
	}

	protected function getBannerIds() {
		$bannerIds = array();
		foreach($this->bannerList as $position) {
			foreach($position as $banner) {
				$bannerIds[] = $banner['id'];
			}
		}
		return $bannerIds;
	}

	protected function getBannerCampaignIds() {
		$bannerCampaignIds = array();
		foreach($this->bannerList as $position) {
			foreach($position as $banner) {
				if(empty($bannerCampaignIds[$banner['bannerCampaignId']])) {
					$bannerCampaignIds[$banner['bannerCampaignId']] = 1;
				}
				else {
					$bannerCampaignIds[$banner['bannerCampaignId']]++;
				}
			}
		}
		return $bannerCampaignIds;
	}

	protected function initBannerList() {
		// tworzymy nazwę modułu według której w configu będziemy odszukiwać ilość banerów do wyświetlenia
		$modules = CoreServices2::getModules();
		$subpageModuleAndMode = $modules->getControllerModule() . $modules->getControllerMode();

		$subpageBannerLocationsAndAmounts = CoreConfig::get('Data', 'subpageBannerLocationsAndAmounts');
		if(!empty($subpageBannerLocationsAndAmounts[$subpageModuleAndMode])) {
			// jeśli odnajdziemy w configu ustawienia specyficzne dla danej podstrony serwisu, to odczytujemy je
			$bannerAmount = $subpageBannerLocationsAndAmounts[$subpageModuleAndMode];
		}
		else {
			// w przeciwnym wypadku pobieramy ustawienia domyślne
			$bannerAmount = $subpageBannerLocationsAndAmounts['default'];
		}

		// Można tu zacząć tranzakcję b.d. ale jak baner się wyświetli raz za dużo
		// to nic się strasznego nie stanie - wydajność ważniejsza

		// pobieramy losową listę banerów pochodzących z aktywnych kampanii, indeksowanych według ich położeń
		$this->bannerList = $this->bannerDAO->getBannerListIndexedByLocation();
		$bannerList = array();
		// skracamy listę wylosowanych banerów do ilości potrzebnej w widoku (UWAGA, na niektórych pozycjach może nie być banerów)
		foreach($bannerAmount as $bannerLocation => $bannerAmount) {
			if(!empty($this->bannerList[$bannerLocation])) {
				$bannerList[$bannerLocation] = array_slice($this->bannerList[$bannerLocation], 0, $bannerAmount);
			}
			else {
				$bannerList[$bannerLocation] = array();
			}
		}
		$this->bannerList = $bannerList;

		// pobieramy tablicę ID banerów
		$bannerIds = $this->getBannerIds();
		// pobieramy listę plików banerów
		$bannerImageList = $this->fileDAO->getListByRecordList('banner', $bannerIds, null, 'banner');
		$this->bannerImageList = array();
		foreach ($bannerImageList as $bannerId => $files) {
			$fileIds = array_keys($files);
			if (!empty($files[$fileIds[0]])) {
				$this->bannerImageList[$bannerId] = $files[$fileIds[0]];
			}
		}
		$this->updateBannerCampaigns();
	}

	protected function updateBannerCampaigns() {
		// pobieramy tablicę ID kampanii banerowych wraz z ilością banerów z danej kampanii które zostaną przypisane do widoku
		$bannerCampaignIds = $this->getBannerCampaignIds();
		// pobieramy listę tych kampanii banerowych
		$this->bannerCampaignList = $this->bannerCampaignDAO->getBannerCampaignListByIds(array_keys($bannerCampaignIds));

		// w pętli updateujemy kampanie banerowe zwiększając licznik wyświetleń o ilość banerów wyświetlonych w danej kampanii
		// @TODO: żeby poprawić wydajność i jednocześnie uniknąć problemu z niespójnością
		//        danych (bo nie chcemy tego robić w transakcji b.d.!), trzeba raczej zrobić
		//        funkcję BannerCampaignDAO, która przyjmuje listę identyfikatorów i w jednym zapytaniu
		//        zwiększa im wszystkim licznik o 1.
		foreach($bannerCampaignIds as $bannerCampaignId => $bannerCampaignCount) {
			$bannerCampaignRecord = $this->bannerCampaignList[$bannerCampaignId];
			if(empty($bannerCampaignRecord['bannerCampaignDisplayCounter'])) {
				$bannerCampaignRecord['bannerCampaignDisplayCounter'] = 0;
			}
			$bannerCampaignRecord['bannerCampaignDisplayCounter'] += $bannerCampaignCount;
			$this->bannerCampaignDAO->save($bannerCampaignRecord);
		}
	}

	protected function redirectToPage($url, $layoutType) {
		switch ($layoutType) {
			case 'standard':
				CoreUtils::redirect($url);
			case 'thickbox':
				throw new CoreException('Can\'t redirect from standard layout to thickbox.');
			default:
				throw new CoreException('Unknown layout type ' . $layoutType);
		}
	}

	protected function handleInvalidAddress() {
		// @TODO: mógłby też być header 404 - może powinien być?
		$this->redirectToHomePage();
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices2::getDisplay();
		$display->assign('fileUrl', CoreServices2::getAttachmentLocationManager());
		$display->assign('subpageUrl', CoreServices::get('websiteMenuManager'));
		$display->assign('subpage', $this->subpage);
		$menuStruct = $this->getMenuStruct();
		if (!empty($menuStruct)) {
		 	$display->assign('menu', $menuStruct);
		}
		if (!empty($this->showCancelNewsletterSubscriptionLink)) {
			$display->assign('showCancelNewsletterSubscriptionLink', 1);
		}
		$display->assign('newsletterForm', $this->newsletterForm);
		$display->assign('bannerList', $this->bannerList);
		$display->assign('bannerImageList', $this->bannerImageList);
		$display->assign('loginForm', $this->loginForm);
		$display->assign('loginErrorMessages', $this->loginErrorMessageContainer);
		$display->assign('filterForm', $this->filterForm);
		$display->assign('fullUrl', CoreServices2::getUrl()->getCurrentExactAddress());
		$display->assign('paginationForm', $this->paginationForm);
	}
}
?>