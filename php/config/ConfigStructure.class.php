<?php
class ConfigStructure extends CoreConfigAbstractConfig {
	protected function init() {
		$this->values['websiteSubpageMaxChildren'] = 15;
	
		$this->values['defaultModule'] = 'Home';
		$this->values['defaultMode'] = 'Website';

		$this->values['websitePermissionDeniedModule'] = 'User';
		$this->values['websitePermissionDeniedMode'] = 'Login';

		$this->values['websiteMenus'] = array(
			'websiteMenuSide' => 'websiteMenuSide',
			'websiteMenuBottom' => 'websiteMenuBottom',
			'websiteMenuHidden' => 'websiteMenuHidden'
		);

		$this->values['websiteSubpageMaxLevel'] = array(
			'websiteMenuSide' => 1,
			'websiteMenuBottom' => 1,
			'websiteMenuHidden' => 1
		);

		$this->values['cmsPermissionDeniedModule'] = 'Admin';
		$this->values['cmsPermissionDeniedMode'] = 'Login';

		$this->values['cmsMenu'] = array();

		$this->values['cmsMenu'][10] = array(
                        'Home' => array('_m', 'Home', '_o', 'CMSEdit'),
			'Model' => array('_m', 'Model', '_o', 'CMSList'),
			'ModelOfTheMonth' => array('_m', 'ModelOfTheMonth', '_o', 'CMSList'),
			'Download' => array('_m', 'Download', '_o', 'CMSList'),
			//'ModelType' => array('_m', 'ModelType', '_o', 'CMSList'),
			//'ModelCategory' => array('_m', 'ModelCategory', '_o', 'CMSList'),
			//'PriceGroup' => array('_m', 'PriceGroup', '_o', 'CMSList'),
			//'CreditsPackageType' => array('_m', 'CreditsPackageType', '_o', 'CMSList'),
			'Forum' => array('_m', 'ForumTopic', '_o', 'CMSList'),
			'ForumPostForModeration' => array('_m', 'ForumPostForModeration', '_o', 'CMSList'),
			'Article' => array('_m', 'Article', '_o', 'CMSList'),
			'Faq' => array('_m', 'Faq', '_o', 'CMSList'),
			'Announcement' => array('_m', 'Announcement', '_o', 'CMSList'),
			'SupportCategory' => array('_m', 'SupportCategory', '_o', 'CMSList'),
			'Advertiser' => array('_m', 'Advertiser', '_o', 'CMSList'),
			'BannerCampaign' => array('_m', 'BannerCampaign', '_o', 'CMSList'),
			'Author' => array('_m', 'Author', '_o', 'CMSList'),
			'NewsletterSubscriber' => array('_m', 'NewsletterSubscriber', '_o', 'CMSList'),
			'User' => array('_m', 'User', '_o', 'CMSList'),
			// 'Settings' => array('_m', 'Settings', '_o', 'CMSList'),
			'Admin' => array('_m', 'Admin', '_o', 'CMSList'),
			'Stats' => array('_m', 'Stats', '_o', 'CMSList'),
			'Logs' => array('_m', 'Log', '_o', 'CMSList')
			/*'StatsCMSAccounts' => array('_m', 'Stats', '_o', 'CMSAccounts'),
			'StatsCMSCategories' => array('_m', 'Stats', '_o', 'CMSCategories'),
			'StatsCMSCategoriesSearch' => array('_m', 'Stats', '_o', 'CMSCategoriesSearch')*/
		);

		$this->values['cmsMenu'][30] = array(
                        'Home' => array('_m', 'Home', '_o', 'CMSEdit'),
			'Model' => array('_m', 'Model', '_o', 'CMSList'),
			'ModelOfTheMonth' => array('_m', 'ModelOfTheMonth', '_o', 'CMSList'),
			'ModelType' => array('_m', 'ModelType', '_o', 'CMSList'),
			'ModelCategory' => array('_m', 'ModelCategory', '_o', 'CMSList'),
			'ModelFileType' => array('_m', 'ModelFileType', '_o', 'CMSList'),
			'Download' => array('_m', 'Download', '_o', 'CMSList'),
			'PriceGroup' => array('_m', 'PriceGroup', '_o', 'CMSList'),
			'CreditsPackageType' => array('_m', 'CreditsPackageType', '_o', 'CMSList'),
			'Forum' => array('_m', 'ForumTopic', '_o', 'CMSList'),
			'ForumPostForModeration' => array('_m', 'ForumPostForModeration', '_o', 'CMSList'),
			'Article' => array('_m', 'Article', '_o', 'CMSList'),
			'Faq' => array('_m', 'FaqItem', '_o', 'CMSList'),
			'Announcement' => array('_m', 'Announcement', '_o', 'CMSList'),
			'SupportCategory' => array('_m', 'SupportCategory', '_o', 'CMSList'),
			'Advertiser' => array('_m', 'Advertiser', '_o', 'CMSList'),
			'BannerCampaign' => array('_m', 'BannerCampaign', '_o', 'CMSList'),
			'Author' => array('_m', 'Author', '_o', 'CMSList'),
			'Subpage' => array('_m', 'Subpage', '_o', 'CMSList'),
			'Newsletter' => array('_m', 'Newsletter', '_o', 'CMSList'),
			'NewsletterSubscriber' => array('_m', 'NewsletterSubscriber', '_o', 'CMSList'),
			'User' => array('_m', 'User', '_o', 'CMSList'),
			//'UserDeletion' => array('_m', 'User', '_o', 'CMSListForDeletion'),
			'Settings' => array('_m', 'Settings', '_o', 'CMSList'),
			'Admin' => array('_m', 'Admin', '_o', 'CMSList'),
			'Stats' => array('_m', 'Stats', '_o', 'CMSList'),
			'Logs' => array('_m', 'Log', '_o', 'CMSList')
			/*'StatsCMSAccounts' => array('_m', 'Stats', '_o', 'CMSAccounts'),
			'StatsCMSCategories' => array('_m', 'Stats', '_o', 'CMSCategories'),
			'StatsCMSCategoriesSearch' => array('_m', 'Stats', '_o', 'CMSCategoriesSearch')*/
		);

		// this must cover ALL possible controller modules
		$this->values['moduleNumbers'] = array(
			'Home' => 1,
			'Subpage' => 2,
			'Forum' => 3,
			'ForumTopic' => 4,
			'ForumThread' => 5,
			'ForumPost' => 6,
			'Article' => 7,
			'FaqItem' => 8,
			'SupportCategory' => 9,
			'ModelType' => 10,
			'ModelCategory' => 11,
			'Banner' => 12,
			'BannerCampaign' => 13,
			'Announcement' => 14,
			'Author' => 15,
			'Advertiser' => 16,
			'CreditsPackageType' => 17,
			'PriceGroup' => 18,
			'SupportCategory' => 19,
			'Download' => 20,
			'User' => 31,
			'Admin' => 32,
			'Model' => 33,
			'Stats' => 34,
			'ModelFileType' => 35,
			'Invoice' => 36,
			// 'PlatnosciPl' => 501,
			'PayPal_EC' => 502,
			'Optima' => 503,
			'File' => 999,
			'SessionRefresh' => 9999
		);
		// this must cover ALL possible controller modes
		$this->values['modeNumbers'] = array(
			'Website' => 1,
			'WebsiteEdit' => 2,
			'WebsiteList' => 3,
			'WebsiteDelete' => 4,
			// 'WebsiteDayList' => 4,
			'WebsiteView' => 5,
			'WebsiteRedirect' => 6,
			'WebsiteSearch' => 101,
			'WebsiteRegister' => 102,
			'WebsiteRegister2' => 103,
			'WebsiteDownload' => 104,
			'WebsiteDownloadHistory' => 105,
			'WebsitePasswordRecovery1' => 106,
			'WebsitePasswordRecovery2' => 107,
			'WebsiteError' => 108,
			'Login' => 201,
			'CMS' => 1001,
			'CMSEdit' => 1002,
			'CMSList' => 1003,
			'CMSCategories' => 1004,
			'CMSCategoriesSearch' => 1005,
			'CMSTypesSearch' => 1006,
			'CMSListForDeletion' => 1007,
			// 'UpdatePage' => 2001,
			'_ReturnPage' => 2002,
			'_OrderListRequest' => 2003,
			'Static' => 999,
			// 'CronSMS' => 998,
			// 'AjaxSelect' => 801,
			'AjaxAutoSuggest' => 802,
			'AjaxUpload' => 803,
			'AjaxDelete' => 804
		);
	}
}
?>