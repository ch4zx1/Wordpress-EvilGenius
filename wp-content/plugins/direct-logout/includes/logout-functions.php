<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/*
Call the classe (start _constructor)
*/

$procedi = new FinalmarcoLogout();


/*
FinalmarcoLogout is a simple class
remove the “Are you sure you want to log out?” confirmation page once an user logout.
After the logout the plugin lets you choose from redirect:
* Same page
* Login page
* Homepage
* Custom page
*/

class FinalmarcoLogout {
	


	/**
	 * NOTE:
	 Directly calling a constructor or a destructor is not a perfect programming practice
	 If you have a better solution feel free to edit
	 */
	public function __construct() {

		
		add_action( 'template_redirect', [ $this, 'logout' ], 20 );

	}
	

	
	public function logout( ) {
		

		global $wp;
		 
		if ( isset( $wp->query_vars['customer-logout'] ) ) {
			
			//Get information from setting
			$this->options = get_option( 'logout-settings', array() );
			
			//check status
			$stato = (! empty($this->options))?$this->options['logout-attivo']:'enabled';
			
			
			if($stato  == "enabled"){
				
			$this->_dove_reindirizzo();

			header("Refresh:0");
			exit;
            }

		}

	}
	
	private function _dove_reindirizzo(){
	

		$setting = (! empty($this->options))?$this->options['logout-redirect']:'login';
		
			
			switch ($setting) {
				case "same":
					
					 if ( wp_get_referer() ) {
					wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wp_get_referer() ) ) );
					 } else {
					wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
					 }
					break;
		
				
				case "homepage":
					wp_redirect( str_replace( '&amp;', '&', wp_logout_url( home_url() ) ) );
					break;
				case "login":
				
				
					wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
					break;
				case "custom":
					$custom = $this->options['custom-redirect'];
					wp_redirect( str_replace( '&amp;', '&', get_permalink( get_page_by_path( $custom ) ) ) );
					
					break;	
				
				default:
				   
				   wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount'  ) ) ) );
		}
	
	}

	
}














