<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Foxiz_Bookmark', false ) ) {

	/**
	 * Class Foxiz_Bookmark
	 * Bookmark system
	 */
	class Foxiz_Bookmark {

		private static $instance;
		public $settings = array();
		public $defaults = array();
		private $meta_ID = 'rb_bookmark_data';
		private $meta_category_ID = 'rb_user_category_data';

		public static function get_instance() {

			if ( self::$instance === null ) {
				return new self();
			}

			return self::$instance;
		}

		/**
		 * Foxiz_Bookmark constructor.
		 */
		public function __construct() {
			self::$instance = $this;

			$this->get_settings();
			add_action( 'wp_ajax_nopriv_rb_bookmark', array( $this, 'add_bookmark' ) );
			add_action( 'wp_ajax_rb_bookmark', array( $this, 'add_bookmark' ) );
			add_action( 'wp_ajax_nopriv_rb_follow', array( $this, 'follow_toggle' ) );
			add_action( 'wp_ajax_rb_follow', array( $this, 'follow_toggle' ) );
			add_action( 'wp_ajax_nopriv_sync_bookmarks', array( $this, 'sync_bookmarks' ) );
			add_action( 'wp_ajax_sync_bookmarks', array( $this, 'sync_bookmarks' ) );
			add_filter( 'body_class', array( $this, 'add_classes' ), 99 );
			add_action( 'wp_footer', array( $this, 'bookmark_info_template' ) );
			add_action( 'wp_footer', array( $this, 'bookmark_remove_info' ) );
			add_action( 'transition_post_status', array( $this, 'push_notification' ), 10, 3 );
		}

		function bookmark_remove_info() {
			$settings = $this->settings;
			if ( empty( $settings['bookmark'] ) ) {
				return;
			} ?>
            <aside id="bookmark-remove-info" class="bookmark-info edge-padding">
                <div class="bookmark-remove-holder bookmark-holder">
                    <p><?php echo esc_html__( 'Removed from reading list', 'foxiz' ); ?></p>
                    <a href="#" id="bookmark-undo" class="bookmark-undo h4"><?php foxiz_html_e( 'Undo', 'foxiz' ); ?></a>
                </div>
            </aside>
		<?php }

		function bookmark_info_template() {
			$settings = $this->settings;
			if ( empty( $settings['bookmark'] ) || empty( $settings['notification'] ) ) {
				return;
			} ?>
            <aside id="bookmark-toggle-info" class="bookmark-info edge-padding">
                <div class="bookmark-holder">
                    <div class="bookmark-featured"></div>
                    <div class="bookmark-inner">
                        <span class="bookmark-title h5"></span>
                        <span class="bookmark-desc"></span>
                    </div>
                </div>
            </aside>
			<?php
		}

		/**
		 * @param $classes
		 *
		 * @return mixed
		 * body classes
		 */
		function add_classes( $classes ) {
			if ( ! empty( $this->settings['bookmark'] ) ) {
				$classes[] = 'sync-bookmarks';
			}

			return $classes;
		}

		/**
		 * @param $name
		 *
		 * @return false|mixed
		 * get setting by name
		 */
		public function get_setting( $name ) {
			if ( function_exists( 'foxiz_get_option' ) ) {
				return foxiz_get_option( $name );
			}

			return false;
		}

		/**
		 * get settings
		 */
		public function get_settings() {

			$settings = array(
				'bookmark'        => $this->get_setting( 'bookmark_system' ),
				'enable_when'     => $this->get_setting( 'bookmark_enable_when' ),
				'logged_redirect' => $this->get_setting( 'bookmark_logged_redirect' ),
				'expiration'      => intval( $this->get_setting( 'bookmark_expiration' ) ) * 86400,
				'notification'    => $this->get_setting( 'bookmark_notification' )
			);

			$this->settings = wp_parse_args( $settings, array(
				'bookmark'        => '',
				'enable_when'     => '',
				'logged_redirect' => '',
				'expiration'      => '5076000'
			) );
		}

		/**
		 * @return string|string[]|null
		 * get user IP
		 */
		public function get_ip() {
			if ( function_exists( 'foxiz_get_user_ip' ) ) {
				$ip = foxiz_get_user_ip();

				return foxiz_convert_to_id( $ip );
			}

			return '127_0_0_1';
		}

		/**
		 * @param $post_id
		 *
		 * @return bool
		 * check bookmark
		 */
		public function is_bookmarked( $post_id ) {

			if ( is_user_logged_in() ) {
				$data = get_user_meta( get_current_user_id(), $this->meta_ID, true );
			} else {
				$data = get_transient( 'rb_bookmark_' . $this->get_ip() );
			}
			if ( empty( $data ) || ! is_array( $data ) ) {
				return false;
			} else {
				return in_array( $post_id, $data );
			}
		}

		/**
		 * add bookmark
		 */
		public function add_bookmark() {

			if ( empty( $_POST['pid'] ) ) {
				wp_send_json( '', null );
			}

			$post_id  = intval( $_POST['pid'] );
			$response = array(
				'action'      => 'added',
				'description' => foxiz_html__( 'This article has been added to reading list', 'foxiz' )
			);

			if ( is_user_logged_in() ) {
					$user_id    = get_current_user_id();
					$bookmarked = get_user_meta( $user_id, $this->meta_ID, true );
				if ( empty( $bookmarked ) || ! is_array( $bookmarked ) ) {
					$bookmarked = array();
				}

				$key = array_search( $post_id, $bookmarked );
				if ( false === $key ) {
					array_push( $bookmarked, $post_id );
				} else {
					unset( $bookmarked[ $key ] );
					$response['action']      = 'removed';
					$response['description'] = foxiz_html__( 'This article was removed from reading list', 'foxiz' );
				}

				update_user_meta( $user_id, $this->meta_ID, array_unique( $bookmarked ) );
			} else {

				$transient_ID = 'rb_bookmark_' . $this->get_ip();
				$bookmarked   = get_transient( $transient_ID );

				if ( empty( $bookmarked ) || ! is_array( $bookmarked ) ) {
					$bookmarked = array();
				}

				$key = array_search( $post_id, $bookmarked );
				if ( false === $key ) {
					array_push( $bookmarked, $post_id );
				} else {
					unset( $bookmarked[ $key ] );
					$response['action']      = 'removed';
					$response['description'] = foxiz_html__( 'This article was removed from your bookmark', 'foxiz' );
				}
				set_transient( $transient_ID, array_unique( $bookmarked ), $this->settings['expiration'] );
			}

			if ( ! empty( $this->settings['notification'] ) ) {
				$response['title'] = get_the_title( $post_id );
				$response['image'] = '<img alt="' . esc_attr( $response['title'] ) . '" src="' . get_the_post_thumbnail_url( $post_id, 'thumbnail' ) . '">';
			}

			wp_send_json( $response, null );
		}

		/**
		 * get bookmarks
		 */
		public function get_bookmarks() {

			if ( is_user_logged_in() ) {
				$bookmarked = get_user_meta( get_current_user_id(), $this->meta_ID, true );
			} else {
				$bookmarked = get_transient( 'rb_bookmark_' . $this->get_ip() );
			}

			return $bookmarked;
		}

		public function sync_bookmarks() {
			wp_send_json( $this->get_bookmarks(), null );
		}


		/**
		 * @return false|WP_Query
		 */
		public function get_query() {

			$data = $this->get_bookmarks();
			if ( is_array( $data ) && count( $data ) ) {
				return new WP_Query( array(
					'post_type'           => 'post',
					'post__in'            => $data,
					'ignore_sticky_posts' => 1,
					'duplicate_allowed'   => 1
				) );
			} else {
				return false;
			}
		}

		/**
		 * @param $new_status
		 * @param $old_status
		 * @param $post
		 */
		public function push_notification( $new_status, $old_status, $post ) {
			if ( ( 'publish' === $new_status && 'publish' !== $old_status ) && 'post' === $post->post_type ) {
				update_option( 'rb_push_notification', $post->ID );
			}
		}

		/**
		 * @return array|mixed
		 */
		public function get_user_categories() {

			if ( is_user_logged_in() ) {
				$ids = get_user_meta( get_current_user_id(), $this->meta_category_ID, true );
				if ( ! empty( $ids ) && is_array( $ids ) && count( $ids ) ) {
					return $ids;
				}
			}

			$data       = array();
			$counter    = 1;
			$categories = get_categories( array(
				'orderby' => 'count',
				'order'   => 'DESC'
			) );

			foreach ( $categories as $category ) {
				array_push( $data, $category->term_id );
				if ( $counter >= 4 ) {
					break;
				}
				$counter ++;
			}

			return $data;
		}

		/** follow toggle */
		public function follow_toggle() {

			if ( empty( $_POST['cid'] ) && ! is_user_logged_in() ) {
				wp_send_json( '', null );
			}

			$category_id = intval( $_POST['cid'] );
			$response    = array(
				'action' => 'added',
			);

			if ( is_user_logged_in() ) {
				$user_id  = get_current_user_id();
				$followed = get_user_meta( $user_id, $this->meta_category_ID, true );
				if ( empty( $followed ) || ! is_array( $followed ) ) {
					$followed = array();
				}
				$key = array_search( $category_id, $followed );
				if ( false === $key ) {
					array_push( $followed, $category_id );
				} else {
					unset( $followed[ $key ] );
					$response['action'] = 'removed';
				}

				update_user_meta( $user_id, $this->meta_category_ID, array_unique( $followed ) );
			}

			wp_send_json( $response, null );
		}

		/**
		 * @param $category_id
		 *
		 * @return bool
		 */
		public function is_followed( $category_id ) {

			if ( ! is_user_logged_in() ) {
				return false;
			}
			$data = get_user_meta( get_current_user_id(), $this->meta_category_ID, true );

			if ( empty( $data ) || ! is_array( $data ) ) {
				return false;
			} else {
				return in_array( $category_id, $data );
			}
		}

	}
}
