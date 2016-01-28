<?php
abstract class WebsiteAbstractControllerThickboxLayout extends WebsiteAbstractController {
	protected function getBaseTemplate() {
		return 'websitethickbox';
	}

	protected function initAdditionalData() {}

	protected function redirectToPage($url, $layoutType) {
		switch ($layoutType) {
			case 'standard':
				CoreUtils::redirect(CoreServices2::getUrl()->createAddress(
					'_m', 'Helper',
					'_o', 'WebsiteThickboxParentRedirect',
					'url', $url
				));
			case 'thickbox':
				CoreUtils::redirect($url);
			default:
				throw new CoreException('Invalid layout type ' . $layoutType);
		}
	}
}
?>