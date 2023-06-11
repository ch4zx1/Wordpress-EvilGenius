<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_register_options_login' ) ) {
	function foxiz_register_options_login() {

		return array(
			'id'     => 'foxiz_config_section_login_screen',
			'title'  => esc_html__( 'Login Screen', 'foxiz' ),
			'desc'   => esc_html__( 'Select option for the login screen page.', 'foxiz' ),
			'icon'   => 'el el-wordpress',
			'fields' => array(
				array(
					'id'     => 'section_start_login_screen',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Layout Settings', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => 'login_screen_style',
					'title'    => esc_html__( 'Custom Login Screen', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable wp-login.php page style.', 'foxiz' ),
					'type'     => 'select',
					'options'  => array(
						'0' => esc_html__( '- Disable -', 'foxiz' ),
						'1' => esc_html__( 'Enable', 'foxiz' ),
					),
					'default'  => '0',
				),
				array(
					'id'       => 'login_form_position',
					'title'    => esc_html__( 'Login Form Position', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a position for the login form.', 'foxiz' ),
					'type'     => 'select',
					'options'  => array(
						'0' => esc_html__( '- Left -', 'foxiz' ),
						'1' => esc_html__( 'Center', 'foxiz' ),
						'2' => esc_html__( 'Right', 'foxiz' ),
					),
					'default'  => '0',
				),
				array(
					'id'       => 'login_screen_logo',
					'type'     => 'media',
					'url'      => true,
					'preview'  => true,
					'title'    => esc_html__( 'Login Logo', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your logo for the logo screen. This option will override on wordpress logo.', 'foxiz' ),
				),
				array(
					'id'          => 'logo_redirect',
					'type'        => 'text',
					'title'       => esc_html__( 'Logo - Destination URL', 'foxiz' ),
					'subtitle'    => esc_html__( 'Redirect to this link when clicking on the logo.', 'foxiz' ),
					'description' => esc_html__( 'Leave this option blank to redirect to the homepage.', 'foxiz' ),
					'default'     => ''
				),
				array(
					'id'       => 'login_screen_bg',
					'type'     => 'background',
					'preview'  => true,
					'title'    => esc_html__( 'Login Screen Background', 'foxiz' ),
					'subtitle' => esc_html__( 'Upload your background for the logo screen.', 'foxiz' ),
					'default'  => array(
						'background-size'       => 'cover',
						'background-attachment' => 'fixed',
						'background-repeat'     => 'no-repeat',
						'background-position'   => 'center center'
					)
				),
				array(
					'id'          => 'login_color',
					'type'        => 'color',
					'transparent' => false,
					'title'       => esc_html__( 'Login Screen Color', 'foxiz' ),
					'subtitle'    => esc_html__( 'Select a color for the login screen.', 'foxiz' ),
					'default'     => ''
				),
				array(
					'id'     => 'section_end_login_screen',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false
				),
				array(
					'id'       => 'section_start_login_redirect',
					'type'     => 'section',
					'class'    => 'ruby-section-start',
					'title'    => esc_html__( 'Redirect Settings', 'foxiz' ),
					'subtitle' => array(
						esc_html__( 'Redirect links should be inner links.', 'foxiz' ),
						esc_html__( 'Navigate to "Header > Popup Sign In" to set for the popup login form.', 'foxiz' ),
					),
					'indent'   => true
				),
				array(
					'id'          => 'login_redirect',
					'type'        => 'text',
					'title'       => esc_html__( 'Login Redirect URL', 'foxiz' ),
					'subtitle'    => esc_html__( 'Redirect to this link after logged in.', 'foxiz' ),
					'description' => esc_html__( 'Leave this option blank to set the default value.', 'foxiz' ),
					'default'     => ''
				),
				array(
					'id'          => 'logout_redirect',
					'type'        => 'text',
					'title'       => esc_html__( 'Logout Redirect URL', 'foxiz' ),
					'subtitle'    => esc_html__( 'Redirect to this link after successful logout.', 'foxiz' ),
					'description' => esc_html__( 'Leave this option blank to set the default value.', 'foxiz' ),
					'default'     => ''
				),
				array(
					'id'       => 'remove_admin_bar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Disable Admin Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable admin bar for all users except for Administrators', 'foxiz' ),
					'default'  => true
				),
				array(
					'id'       => 'remove_lang_bar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Disable Language Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the language bar in the login screen.', 'foxiz' ),
					'default'  => false
				),
				array(
					'id'     => 'section_end_login_screen',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_login_admin_bar',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Admin Bar', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => 'remove_admin_bar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Disable Admin Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable admin bar for all users except for Administrators', 'foxiz' ),
					'default'  => true
				),
				array(
					'id'       => 'remove_lang_bar',
					'type'     => 'switch',
					'title'    => esc_html__( 'Disable Language Bar', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable the language bar in the login screen.', 'foxiz' ),
					'default'  => false
				),
				array(
					'id'     => 'section_end_login_admin_bar',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false
				),
			)
		);
	}
}
