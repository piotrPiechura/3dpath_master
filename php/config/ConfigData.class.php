<?php
class ConfigData extends CoreConfigAbstractConfig {
	protected function init() {

		$this->values['adminRoles'] = array(
			'10' => 'adminRoleDataAdmin',
			'30' => 'adminRoleSuperadmin'
		);
		
		$this->values['bannerLocations'] = array(
			'top' => array(
				'width' => 300,
				'height' => 100
			),
			// Ta lokacja nie jest uwzględniona w projekcie graficznym
			//'bottom' => array(
			//	'width' => 400,
			//	'height' => 150
			//),
			'right' => array(
				'width' => 100,
				'height' => 240
			)
		);

		$this->values['subpageBannerLocationsAndAmounts'] = array(
			'default' => array(
				'top' => 1,
				//'bottom' => 1,
				'right' => 1
			),
			'ArticleWebsite' => array(
				'top' => 1,
				//'bottom' => 1,
				'right' => 2
			)
		);

		// @TODO: to się chyba powinno znaleźć w ConfigDisplay
		$this->values['recordsPerPage'] = array(
			'5' => 'records5',
			'10' => 'records10',
			'20' => 'records20',
			'50' => 'records50',
			'100' => 'records100'
		);

		// @TODO: to się chyba powinno znaleźć w ConfigDisplay
		$this->values['thumbsSizes'] = array(
			'small' => 'thumbsSmall',
			'medium' => 'thumbsMedium',
			'big' => 'thumbsLarge'
		);

		// @TODO: to się chyba powinno znaleźć w ConfigDisplay
		$this->values['thumbsSizesValues'] = array(
			'small' => 80,
			'medium' => 110,
			'big' => 140
		);

		/**
		 * Podobieństwo poniższych kodów z platnosci.pl jest czysto przypadkowe.
		 * Każda implementacja płatności powinna tłumaczyć stany dostawcy na poniższe.
		 */
		$this->values['paymentStates'] = array(
			'new'  => 'paymentStateNew',
			'initialized' => 'paymentStateInitialized',
			'cancelled' => 'paymentStateCancelled',
			'confirmed' => 'paymentStateAccomplished'
			// => 'paymentStateAborted'
			// => 'paymentStatePaidBack'
		);
		$this->values['paymentPositiveStates'] = array('confirmed');
		$this->values['paymentNegativeStates'] = array('cancelled');
		$this->values['paymentNeutralStates'] = array('new', 'initialized');
                
                $this->values['wellType'] = array(
                        '2d' => '2D',
                        '3d' => '3D'
                );
                
                $this->values['well3DTrajectory'] = array(
                        'POCZ'   => 'From starting point',
                        'KONC'   => 'From ending point',
                        'KONCB'   => 'From ending point - build turn'
                );  
                
                $this->values['wellTrajectory'] = array(
                        'J1'   => 'J1',
                        'J2'   => 'J2',
                        'J3'   => 'J3',
                        'S1'   => 'S1',
                        'S2'   => 'S2',
                        'S3'   => 'S3',
                        'S4'   => 'S4',
                        'catenary' => 'Catenary'
                );     
	}
}
?>