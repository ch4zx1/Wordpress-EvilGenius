<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_get_bookmark' ) ) {
	/**
	 * @param string $post_id
	 *
	 * @return false|string
	 * get bookmark
	 */
	function foxiz_get_bookmark( $post_id = '' ) {

		if ( foxiz_is_amp() ) {
			return false;
		}

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		$output = '<span class="rb-bookmark bookmark-trigger"';
		if ( is_rtl() ) {
			$output .= ' dir="rtl"';
		}
		$output .= ' data-pid="' . $post_id . '">';
		$output .= '<i data-title="' . foxiz_html__( 'Save it', 'foxiz' ) . '" class="rbi rbi-bookmark"></i>';
		$output .= '<i data-title="' . foxiz_html__( 'Remove', 'foxiz' ) . '" class="bookmarked-icon rbi rbi-bookmark-fill"></i>';
		$output .= '</span>';

		return $output;
	}
}

if ( ! function_exists( 'foxiz_get_wologin_bookmark' ) ) {
	/**
	 * @param string $post_id
	 * @param string $redirect
	 *
	 * @return false|string
	 * without login bookmark
	 */
	function foxiz_get_wologin_bookmark( $post_id = '', $redirect = '' ) {

		if ( function_exists( 'foxiz_is_amp' ) && foxiz_is_amp() ) {
			return false;
		}

		if ( empty( $redirect ) ) {
			$redirect = home_url( '/' );
		}

		$output = '<span class="rb-bookmark"';
		if ( is_rtl() ) {
			$output .= ' dir="rtl"';
		}
		$output .= ' data-title="' . foxiz_html__( 'Sign In to Save', 'foxiz' ) . '">';
		$output .= '<a href="' . wp_login_url( $redirect ) . '">';
		$output .= '<i class="rbi rbi-bookmark"></i>';
		$output .= '</a>';
		$output .= '</span>';

		return $output;
	}
}

if ( ! function_exists( 'foxiz_bookmark_trigger' ) ) {
	/**
	 * @param string $post_id
	 */
	function foxiz_bookmark_trigger( $post_id = '' ) {
		echo foxiz_get_bookmark_trigger( $post_id );
	}
}

if ( ! function_exists( 'foxiz_get_bookmark_trigger' ) ) {
	/**
	 * @param string $post_id
	 *
	 * @return false|string
	 */
	function foxiz_get_bookmark_trigger( $post_id = '' ) {

		if ( foxiz_is_amp() || ! class_exists( 'Foxiz_Bookmark' ) ) {
			return false;
		}

		$settings = Foxiz_Bookmark::get_instance()->settings;

		if ( empty( $settings['bookmark'] ) ) {
			return false;
		}

		if ( ! isset( $settings['logged_redirect'] ) ) {
			$redirect = $settings['logged_redirect'];
		} else {
			$redirect = get_home_url();
		}

		if ( empty( $settings['enable_when'] ) || is_user_logged_in() ) {
			return foxiz_get_bookmark( $post_id );
		}

		if ( 'ask_login' === $settings['enable_when'] && ! is_user_logged_in() ) {
			return foxiz_get_wologin_bookmark( $post_id, esc_url( $redirect ) );
		}
	}
}
