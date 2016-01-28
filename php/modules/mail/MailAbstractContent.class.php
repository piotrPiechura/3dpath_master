<?php
abstract class MailAbstractContent {
	/**
	 * @var CoreDisplayAbstractEngine
	 */
	protected $display = null;
	protected $lang = null;
	protected $templateContent = null;
	protected $subject = null;
	protected $content = null;
	protected $params = null;
	protected $attachments = null;

	public function __construct(&$params = null) {
		// @TODO: nie całkiem fajnie - co jeśli będziemy chcieli zmienić template engine?
		$this->display = new CoreDisplaySmarty();
		$this->initLang();
		$this->setDisplayBasics();
		$this->setDisplayContentType();
		$this->initParams($params);
		$this->prepareData();
		$this->assignDisplayVariables();
		$this->splitContentOnSubjectAndBody();
		$this->initAttachments();
	}

	abstract protected function initLang();

	protected function initAttachments() {
		$this->attachments = array();
	}
	
	protected function initParams(&$params) {
		CoreUtils::checkConstraint(is_null($params) || is_array($params));
		if (!empty($params)) {
			$this->params = $params;
		}
		else {
			$this->params = array();
		}
	}

	protected function splitContentOnSubjectAndBody() {
		// fetch the email body
        $this->templateContent = $this->display->fetch();
        // the subject is on the first line, so parse that out
        $lines = explode("\n", $this->templateContent);
        $this->subject = trim(array_shift($lines));
        $this->content = join("\n", $lines);
	}

	protected function setDisplayBasics() {
		$this->initLayout();
		$this->display->setLang($this->lang);
	}

	protected function setDisplayContentType() {
		$className = get_class($this);
		$controllerName = str_replace('Content', '', $className);
		$this->display->setContentType($controllerName);
	}

	protected function initLayout() {
		$this->display->setRootTemplateType('email');
	}

	abstract protected function prepareData();

	protected function assignDisplayVariables() {
		$this->display->assign('globalCharset', strtolower(CoreConfig::get('CoreDisplay', 'globalCharset')));
		$this->display->assign('url', CoreServices2::getUrl());
	}

	public function getSubject() {
		return $this->subject;
	}

	public function getContent() {
		return $this->content;
	}

	public function getAttachments() {
		return $this->attachments;
	}
}
?>