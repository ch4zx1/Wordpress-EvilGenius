<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
For the configuration page I used the RationalOptionPages class 
Infor here https://github.com/jeremyHixon/RationalOptionPages
*/

//
$indirizzo = get_site_url();

$pages = array(
	'logout-settings'	=> array(
		'page_title'	=> __( 'Logout settings', 'logout-settings' ),
		'sections'		=> array(
			'section-one'	=> array(
				'title'			=> __( 'Enable / Disable Logout Plugin', 'logout-settings' ),
				'fields'		=> array(			
					
					'activation'			=> array(
						'title'			=> __( 'Direct logout', 'sample-domain' ),
						'type'			=> 'radio',
						'id'			=> 'logout-attivo',
						'value'			=> 'enabled',
						'choices'		=> array(
							'enabled'	=> __( 'Enabled', 'sample-domain' ),
							'disabled'	=> __( 'Disabled', 'sample-domain' ),
						),
					),
										
			
				),
			),
			
			'section-two'	=> array(
				'title'			=> __( 'Redirect', 'logout-settings' ),
				'fields'		=> array(
					
			
					
				    'redirect'			=> array(
						'title'			=> __( 'Redirect after logout', 'logout-settings' ),
						'type'			=> 'radio',
						'id'			=> 'logout-redirect',
						'value'			=> 'same',
						'choices'		=> array(
							'same'	=> __( 'Same page', 'logout-settings' ),
							'login'	=> __( 'Login page', 'logout-settings' ),
							'homepage'	=> __( 'Homepage', 'logout-settings' ),
							'custom'	=> __( 'Custom page', 'logout-settings' ),
						),
					),
					
						'custom_page'		=> array(
						'id'			=> 'custom-redirect',
						'title'			=> __( $indirizzo.'/', 'logout-settings' ),
					),
					
			
				),
			),
			
			
			'thanks'	=> array(
				'title'			=> __( 'Thank You', 'logout-settings' ),
				'text'			=> '<p>
				
<div id="wcgiftwrapper-description"><div style="background:#fff;border:1px solid #e0c33f;padding-bottom:1em;padding-left:1em;padding-right:1em">
<img src="https://www.gravatar.com/avatar/7117dad5f79cb61cf5f3b95cd50a82ce" alt="little package gravatar" style="float:right;height:180px;margin-bottom:1em;margin-left:1em;margin-top:1em;width:180px" /></p>
<p style="font-size:1.125em">Hello. I&#8217;m Marco. <BR> I develop simple things for free.</p>
<p style="font-size:1.125em">If you like this plugin please consider  <a href="https://www.finalmarco.com/donations/donation/" target="_blank" rel="noopener">making a small donation</a> it will help me with the bills</p>
<p></p>
<p>Looking for hiring Worpress/Woocommerce programmers? <a href="mailto:finalmarco@icloud.com">contact me</a><br>I&#8217;m on <a href="https://twitter.com/Finalmarco">Twitter</a> - <a href="https://www.linkedin.com/in/finalmarco/" target="_blank">Linkedin</a> - <a href="https://www.facebook.com/Finalmarco" target="_blank">Facebook</a> too.</p>
<p>If you al looking for the "save" button for the configuration is down here </p>
</div>
</div>
				
				</p>',

			),
		
			
		),
	),
);

$option_page = new RationalOptionPages( $pages );





