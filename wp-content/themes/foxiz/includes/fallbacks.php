<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_protocol' ) ) {
	/**
	 * get protocol
	 */
	function foxiz_protocol() {

		if ( is_ssl() ) {
			return 'https';
		}

		return 'http';
	}
}

if ( ! function_exists( 'rb_get_meta' ) ) {
	/**
	 * @param $id
	 * @param null $post_id
	 *
	 * @return false|mixed
	 * get meta
	 */
	function rb_get_meta( $id, $post_id = null ) {

		if ( empty( $post_id ) ) {
			$post_id = get_the_ID();
		}

		if ( empty( $post_id ) ) {
			return false;
		}

		$rb_meta = get_post_meta( $post_id, 'rb_global_meta', true );
		if ( ! empty( $rb_meta[ $id ] ) ) {

			if ( is_array( $rb_meta[ $id ] ) && isset( $rb_meta[ $id ]['placebo'] ) ) {
				unset( $rb_meta[ $id ]['placebo'] );
			}

			return $rb_meta[ $id ];
		}

		return false;
	}
}

/**
 * @param $text
 * @param string $domain
 *
 * @return mixed|string|void
 * foxiz html
 */
if ( ! function_exists( 'foxiz_html__' ) ) {
	function foxiz_html__( $text, $domain = 'foxiz-core' ) {

		$translated = esc_html( translate( $text, $domain ) );
		$id         = foxiz_convert_to_id( $text );
		$data       = get_option( 'rb_translated_data', array() );

		if ( ! empty( $data[ $id ] ) ) {
			$translated = $data[ $id ];
		}

		return $translated;
	}
}

if ( ! function_exists( 'foxiz_attr__' ) ) {
	/**
	 * @param $text
	 * @param string $domain
	 *
	 * @return mixed|string|void
	 * foxiz translate
	 */
	function foxiz_attr__( $text, $domain = 'foxiz-core' ) {

		$translated = esc_attr( translate( $text, $domain ) );
		$id         = foxiz_convert_to_id( $text );
		$data       = get_option( 'rb_translated_data', array() );

		if ( ! empty( $data[ $id ] ) ) {
			$translated = $data[ $id ];
		}

		return $translated;
	}
}

if ( ! function_exists( 'foxiz_html_e' ) ) {
	/**
	 * @param $text
	 * @param string $domain
	 * foxiz html e
	 */
	function foxiz_html_e( $text, $domain = 'foxiz-core' ) {

		echo foxiz_html__( $text, $domain );
	}
}

if ( ! function_exists( 'foxiz_attr_e' ) ) {
	/**
	 * @param $text
	 * @param string $domain
	 * foxiz attr e
	 */
	function foxiz_attr_e( $text, $domain = 'foxiz-core' ) {

		echo foxiz_attr__( $text, $domain );
	}
}

if ( ! function_exists( 'foxiz_get_option' ) ) {
	/**
	 * @param string $option_name
	 * @param false $default
	 *
	 * @return false|mixed|void
	 */
	function foxiz_get_option( $option_name = '', $default = false ) {

		$settings = get_option( FOXIZ_TOS_ID, [] );

		if ( empty( $option_name ) ) {
			return $settings;
		}

		if ( ! empty( $settings[ $option_name ] ) ) {
			return $settings[ $option_name ];
		}

		return $default;
	}
}

if ( ! function_exists( 'foxiz_is_amp' ) ) {
	/**
	 * @return bool
	 */
	function foxiz_is_amp() {

		return function_exists( 'amp_is_request' ) && amp_is_request();
	}
}

if ( ! function_exists( 'foxiz_pretty_number' ) ) {
	/**
	 * @param $number
	 *
	 * @return int|string
	 * pretty number
	 */
	function foxiz_pretty_number( $number ) {

		$number = intval( $number );
		if ( $number > 999999 ) {
			$number = str_replace( '.00', '', number_format( ( $number / 1000000 ), 2 ) ) . foxiz_attr__( 'M' );
		} elseif ( $number > 999 ) {
			$number = str_replace( '.0', '', number_format( ( $number / 1000 ), 1 ) ) . foxiz_attr__( 'k' );
		}

		return $number;
	}
}

if ( ! function_exists( 'foxiz_render_svg' ) ) {
	/**
	 * @param string $svg_name
	 * @param string $color
	 * @param string $ui
	 * render svg
	 */
	function foxiz_render_svg( $svg_name = '', $color = '', $ui = '' ) {

		echo foxiz_get_svg( $svg_name, $color, $ui );
	}
}

if ( ! function_exists( 'foxiz_get_svg' ) ) {
	/**
	 * @param string $svg_name
	 * @param string $color
	 * @param string $ui
	 *
	 * @return false
	 * get svg icon
	 */
	function foxiz_get_svg( $svg_name = '', $color = '', $ui = '' ) {

		return false;
	}
}

if ( ! function_exists( 'wp_body_open' ) ) {
	/** ensuring backward compatibility with versions of WordPress older than 5.2. */
	function wp_body_open() {

		do_action( 'wp_body_open' );
	}
}

if ( ! function_exists( 'foxiz_dark_mode' ) ) {
	/**
	 * @return false|mixed|void
	 */
	function foxiz_dark_mode() {

		return foxiz_get_option( 'dark_mode' );
	}
}

if ( ! function_exists( 'foxiz_get_breadcrumb' ) ) {
	/**
	 * @return false
	 */
	function foxiz_get_breadcrumb( $classes = '' ) {

		return false;
	}
}

if ( ! function_exists( 'foxiz_render_breadcrumb' ) ) {
	/**
	 * @param string $classes
	 */
	function foxiz_render_breadcrumb( $classes = '' ) {

		echo foxiz_get_breadcrumb( $classes );
	}
}

if ( ! function_exists( 'foxiz_is_svg' ) ) {
	/**
	 * @param string $attachment
	 *
	 * @return bool
	 */
	function foxiz_is_svg( $attachment = '' ) {
		if ( substr( $attachment, - 4, 4 ) === '.svg' ) {

			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'foxiz_navigation_fallback' ) ) {
	/**
	 * @param array $settings
	 *
	 * @return false
	 */
	function foxiz_navigation_fallback( $settings = array() ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		};

		$menu_name = '';
		if ( isset( $settings['fallback_name'] ) ) {
			$menu_name = $settings['fallback_name'];
		} ?>
		<div class="rb-error">
			<p><?php printf( esc_html__( 'Please assign a menu to the "%s" location under ', 'foxiz' ), $menu_name ) ?>
				<a href="<?php echo get_admin_url( get_current_blog_id(), 'nav-menus.php?action=locations' ); ?>"><?php esc_html_e( 'Manage Locations', 'foxiz' ); ?></a>
			</p>
		</div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_get_twitter_name' ) ) {
	/**
	 * @return string|void
	 */
	function foxiz_get_twitter_name() {
		$name        = get_bloginfo( 'name' );
		$twitter_url = foxiz_get_option( 'twitter' );
		if ( ! empty( $twitter_url ) ) {
			$name = parse_url( $twitter_url, PHP_URL_PATH );
			$name = str_replace( '/', '', $name );
		}

		return $name;
	}
}

if ( ! function_exists( 'foxiz_get_image_size' ) ) {
	/**
	 * @param $filename
	 *
	 * @return array|false
	 */
	function foxiz_get_image_size( $filename ) {
		if ( is_string( $filename ) ) {
			return @getimagesize( $filename );
		}

		return [];
	}
}

if ( ! function_exists( 'foxiz_calc_crop_sizes' ) ) {
	/**
	 * @return array[]
	 */
	function foxiz_calc_crop_sizes() {

		$settings = get_option( FOXIZ_TOS_ID );
		$crop     = true;
		if ( ! empty( $settings['crop_position'] ) && ( 'top' === $settings['crop_position'] ) ) {
			$crop = array( 'center', 'top' );
		}

		$sizes = array(
			'foxiz_crop_g1' => array( 330, 220, $crop ),
			'foxiz_crop_g2' => array( 420, 280, $crop ),
			'foxiz_crop_g3' => array( 615, 410, $crop ),
			'foxiz_crop_o1' => array( 860, 0, $crop ),
			'foxiz_crop_o2' => array( 1536, 0, $crop )
		);

		foreach ( $sizes as $crop_id => $size ) {
			if ( empty( $settings[ $crop_id ] ) ) {
				unset( $sizes[ $crop_id ] );
			}
		}

		if ( ! empty( $settings['featured_crop_sizes'] ) && is_array( $settings['featured_crop_sizes'] ) ) {
			foreach ( $settings['featured_crop_sizes'] as $custom_size ) {
				if ( ! empty( $custom_size ) ) {
					$custom_size = preg_replace( '/\s+/', '', $custom_size );;
					$hw = explode( 'x', $custom_size );
					if ( ! empty( $hw[0] ) && ! empty( $hw[1] ) ) {
						$crop_id           = 'foxiz_crop_' . $custom_size;
						$sizes[ $crop_id ] = array( absint( $hw[0] ), absint( $hw[1] ), $crop );
					}
				}
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'foxiz_get_adsense' ) ) {
	function foxiz_get_adsense() {
		return false;
	}
}

if ( ! function_exists( 'foxiz_get_ad_image' ) ) {
	function foxiz_get_ad_image() {
		return false;
	}
}

if ( ! function_exists( 'foxiz_get_theme_mode' ) ) {
	/**
	 * @return string
	 */
	function foxiz_get_theme_mode() {

		$mode = 'default';
		if ( foxiz_get_option( 'dark_mode_default' ) ) {
			$mode = 'dark';
		}

		return $mode;
	}
}