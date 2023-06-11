=== Direct Logout ===
Contributors: Finalmarco
Donate link: https://www.finalmarco.com/donations/donation/
Tags: woocommerce, logout
Requires at least: 4.7
Tested up to: 5.6
Stable tag: 1.1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin let your users logout from woocommerce without Confirmation.

== Description ==

This plugin remove the “Are you sure you want to log out?” confirmation page once an user logout.

After the logout the plugin lets you choose from redirect:

* Same page
* Login page
* Homepage
* Custom page


== Frequently Asked Questions ==

= I don't want to install a plugin for this simple function, do you have a hook?   =

Of course, you can put the following code inside your function.php page, it will redirect ot the login page
`<?php 

function finalmarco_logout() {

    global $wp;
    if ( isset( $wp->query_vars['customer-logout'] ) ) {

		wp_redirect( str_replace( '&amp;', '&', wp_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) );
		header("Refresh:0");
        exit;

    }

}
add_action( 'template_redirect', 'finalmarco_logout' );


 ?>`


== Screenshots ==

1. Control Panel

== Changelog ==

None

== Upgrade Notice ==

None


