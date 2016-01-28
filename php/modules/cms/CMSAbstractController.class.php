<?php
/**
 * V. 1.1
 */
abstract class CMSAbstractController extends CoreControllerAbstractPage {
	/**
	 * @var CMSLayoutAbstract
	 */
	protected $layout = null;
	/**
	 * @var CoreModelAbstractDAO
	 */
	protected $dao = null;
	protected $currentUser = null;
	protected $adminRoles = null;
        
        protected $company = null;
        protected $project = null;
        protected $site = null;
        protected $well = null;

	protected function checkHTTPS() {
		$httpsOn = CoreServices::get('url')->isHTTPSOn();
		$httpsRequired = CoreConfig::get('Environment', 'httpsForCMS');
		if ($httpsRequired && !$httpsOn) {
			CoreUtils::redirect(CoreServices::get('url')->getCurrentExactAddress('https'));
		}
		if (!$httpsRequired && $httpsOn) {
			CoreUtils::redirect(CoreServices::get('url')->getCurrentExactAddress('http'));
		}
	}

	public function prepareData() {
		$this->checkHTTPS();
		$this->adminRoles = array_flip(CoreConfig::get('Data', 'adminRoles'));
		$this->currentUser = CoreServices::get('access')->getCurrentUserData();
		if (!$this->isControllerUsagePermitted()) {
			CoreUtils::redirect($this->getNoPermissionsAddress());
		}
		$this->initDAO();
		$this->initLayout();
                
                $this->initCompany();
                $this->initProject();
	}

	protected function isControllerUsagePermitted() {
		return (!empty($this->currentUser['id']));
	}

	protected function initLayout() {
		$this->layout = new CMSLayoutStandard($this);
	}
        
        protected function initCompany(){
                $companyDAO = new CompanyDAO();
                $this->company = $companyDAO->getCompanyByUserId($this->currentUser['id']);
        }
        
        protected function initProject(){
                $projectDAO = new ProjectDAO();
                $siteDAO = new SiteDAO();
                $wellDAO = new WellDAO();
                foreach($this->company as $company){
                    $this->project[$company['id']] = $projectDAO->getProjectByCompanyIdAndUserId($company['id'],$this->currentUser['id']);  
                    foreach ($this->project[$company['id']] as $project){
                        $this->site[$project['id']] = $siteDAO->getSiteByProjectIdAndUserId($project['id'], $this->currentUser['id']); 
                        foreach ($this->site[$project['id']] as $site){
                            $this->well[$site['id']] = $wellDAO->getWellBySiteIdAndUserId($site['id'],$this->currentUser['id']);
                        }
                    }
                  
                }
                //print_r($this->project);
                //print_r($this->site);
        }
        
       
        

	public function sendHeaders() {
		parent::sendHeaders();
		header('Content-Language: ' . CoreServices::get('lang')->getLang('CMS'));
	}

	public function assignDisplayVariables() {
		parent::assignDisplayVariables();
		$display = CoreServices::get('display');
		
		$successMessageType = CoreServices::get('request')->getFromGet('_sm');
		// @TODO: fajnie by było sprawdzać poprawność tego komunikatu;
		// narazie strona się nie sypie, bo smarty gdy musi wyświetlić wartość
		// niezdefiniowanej zmiennej konfiguracyjnej, nie zgłasza błędu*;
		// nie da się w ten sposób "czytać" smarty configa, bo nazwa zmiennej smarty configa
		// to konkatenacja: 'successMessage' . $_sm
		// *) To zależy od poziomu komunikatów; to jest błąd na poziomie NOTICE.
		if (!empty($successMessageType)) {
			$display->assign('successMessageType', $successMessageType);
		}
                // Dane do menu 
                $display->assign('company', $this->company);
                $display->assign('project', $this->project);
                $display->assign('site', $this->site);
                $display->assign('well', $this->well);
                //
		// @TODO: to będzie pewnie kiedyś potrzebne, ale wcześniej będą potrzebne
		// języki contentu! I wszystko to będzie dopierow kolejnych projektach.
		$display->assign('interfaceLang', CoreServices::get('lang')->getLang('CMS'));
		$display->assign(
			'allInterfaceLangs',
			CoreServices::get('lang')->getLangs('CMS')
		);
		if (!empty($this->currentUser['id'])) {
			$display->assign('userRole', $this->currentUser['adminRole']);
			$display->assign('userName', $this->currentUser['adminName']);
                        $display->assign('userFirstName', $this->currentUser['adminFirstName']);
                        $display->assign('userSurname', $this->currentUser['adminSurname']);
                        $display->assign('userId', $this->currentUser['id']);
		}
		$this->layout->assignDisplayVariables();
	}

	public function getSessionName() {
		return 'CMSSession';
	}

	public function getMenuStruct() {
		if (!$this->currentUser) {
			return null;
		}
		$menuStruct = array();
		$fullMenuStruct = CoreConfig::get('Structure', 'cmsMenu');
		foreach ($fullMenuStruct[$this->currentUser['adminRole']] as $description => $urlParams) {
			$menuStruct[] = array(
				'description' => $description,
				'url' => CoreServices::get('url')->createHTML($urlParams)
			);
		}
		return $menuStruct;
	}

	protected function getDAOClass() {
		throw new CoreException('Method not implemented or it should not be invoked on an object of this class');
	}

	protected function initDAO() {
		$daoClass = $this->getDAOClass();
		$this->dao = new $daoClass();
                
                
	}

	protected function setDisplayBasics() {
		CoreServices2::getDisplay()->setRootTemplateType($this->layout->getBaseTemplate());
		CoreServices2::getDisplay()->setLang(CoreServices::get('lang')->getLang('CMS'));
	}

	protected function getNoPermissionsAddress() {
		return CoreServices::get('url')->createAddress(
			'_m',
			CoreConfig::get('Structure', 'cmsPermissionDeniedModule'),
			'_o',
			CoreConfig::get('Structure', 'cmsPermissionDeniedMode')
		);
	}
	
	abstract public function getMenuItemDescription();
}
?>