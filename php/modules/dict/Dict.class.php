<?php
class Dict {
	protected static $values = array(
		'en' => array(
			'pageTitle' => 'HushViz',

			'newsletterSenderName' => 'HushViz',

			// Newsletter]
			'clickHereToCancelNewsletterSubscription' => 'Click the link below to cancel newsletter subscription:',

			// NewsletterConfirmationMail]
			'newsletterSubscriberConfirmationMailSubject' => 'Confirmation of newsletter subscription',

			'newsletterSubscriberConfirmationMailTitle' => 'Newsletter',
			'newsletterSubscriberConfirmationMailPart1HTML' => '
				<p>Thank you for subscribing to our Newsletter.</p>',
			'clickHereToActivateNewsletterSubscription' => 'Please click on the link below to confirm your subscription:',
			'newsletterSubscriberConfirmationMailPart2HTML' => '
				<p>You can also copy this address and paste it in the address bar of your web browser.</p>
				<p>If you have not subscribed to our Newsletter, someone else has added your address on your behalf. If this is the case, all you need to do is not click on the link above.</p>
				<p>Best wishes,<br>
				HushViz Team</p>',

			'readMore' => 'Read on',

			'contactBoxContent' => '@TODO: stopka'
		)
	);

	public static function get($lang, $text) {
		return self::$values[$lang][$text];
	}
}
?>