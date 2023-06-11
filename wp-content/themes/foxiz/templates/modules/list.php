<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_list_1' ) ) {
	/**
	 * @param $settings
	 */
	function foxiz_list_1( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}
		$settings['post_classes'] = 'p-list p-list-1';
		if ( ! empty( $settings['featured_position'] ) ) {
			$settings['post_classes'] .= ' featured-' . esc_attr( $settings['featured_position'] );
		}
		?><div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="list-holder">
                <div class="list-feat-holder">
                    <div class="feat-holder">
						<?php
						foxiz_entry_featured( $settings );
						foxiz_entry_top( $settings ); ?>
                    </div>
                </div>
                <div class="p-content">
					<?php
					foxiz_entry_title( $settings );
					foxiz_entry_review( $settings );
					foxiz_entry_excerpt( $settings );
					foxiz_entry_meta( $settings );
					foxiz_entry_readmore( $settings );
					foxiz_entry_bookmark_action( $settings );
					?>
                </div>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_2' ) ) {
	/**
	 * @param array $settings
	 */
	function foxiz_list_2( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}
		$settings['post_classes'] = 'p-big p-list p-list-2';
		if ( ! empty( $settings['featured_position'] ) ) {
			$settings['post_classes'] .= ' featured-' . esc_attr( $settings['featured_position'] );
		} ?>
        <div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="list-holder">
                <div class="list-feat-holder">
                    <div class="feat-holder">
						<?php foxiz_entry_featured( $settings ); ?>
                    </div>
                </div>
                <div class="p-content">
					<?php
					foxiz_entry_top( $settings );
					foxiz_entry_title( $settings );
					foxiz_entry_review( $settings );
					foxiz_entry_excerpt( $settings );
					foxiz_entry_meta( $settings );
					foxiz_entry_readmore( $settings );
					foxiz_entry_bookmark_action( $settings );
					?>
                </div>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_small_1' ) ) {
	/**
	 * @param array $settings
	 */
	function foxiz_list_small_1( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h5';
		}
		if ( empty( $settings['bottom_border'] ) ) {
			$settings['bottom_border'] = 'gray';
		}
		$settings['post_classes'] = 'p-small p-list-small-1';
		?>
        <div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="p-content">
				<?php
				foxiz_entry_counter( $settings );
				foxiz_entry_top( $settings );
				foxiz_entry_title( $settings );
				foxiz_entry_review( $settings );
				foxiz_entry_excerpt( $settings );
				foxiz_entry_meta( $settings );
				foxiz_entry_readmore( $settings );
				foxiz_entry_bookmark_action( $settings );
				?>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_small_2' ) ) {
	/**
	 * @param array $settings
	 */
	function foxiz_list_small_2( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h5';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'thumbnail';
		}
		if ( empty( $settings['featured_classes'] ) ) {
			$settings['featured_classes'] = 'ratio-v1';
		}
		$settings['post_classes'] = 'p-small p-list-small-2';
		if ( ! empty( $settings['featured_position'] ) && 'right' === $settings['featured_position'] ) {
			$settings['post_classes'] .= ' right-featured';
		}
		?>
        <div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="feat-holder">
				<?php foxiz_entry_featured( $settings ); ?>
            </div>
            <div class="p-content">
				<?php
				foxiz_entry_counter( $settings );
				foxiz_entry_top( $settings );
				foxiz_entry_title( $settings );
				foxiz_entry_review( $settings );
				foxiz_entry_excerpt( $settings );
				foxiz_entry_meta( $settings );
				foxiz_entry_readmore( $settings );
				foxiz_entry_bookmark_action( $settings );
				?>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_small_3' ) ) {
	/**
	 * @param array $settings
	 */
	function foxiz_list_small_3( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h5';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'thumbnail';
		}
		if ( empty( $settings['featured_classes'] ) ) {
			$settings['featured_classes'] = 'ratio-q';
		}
		$settings['post_classes'] = 'p-small p-list-small-3 p-list-small-2';
		if ( ! empty( $settings['featured_position'] ) && 'right' === $settings['featured_position'] ) {
			$settings['post_classes'] .= ' right-featured';
		}
		?>
        <div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="feat-holder">
				<?php foxiz_entry_featured( $settings ); ?>
            </div>
            <div class="p-content">
				<?php
				foxiz_entry_counter( $settings );
				foxiz_entry_top( $settings );
				foxiz_entry_title( $settings );
				foxiz_entry_review( $settings );
				foxiz_entry_excerpt( $settings );
				foxiz_entry_meta( $settings );
				foxiz_entry_readmore( $settings );
				foxiz_entry_bookmark_action( $settings );
				?>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_inline' ) ) {
	/**
	 * @param array $settings
	 * render list style inline
	 */
	function foxiz_list_inline( $settings = array() ) {

		$settings['post_classes'] = 'p-wrap p-list-inline';
		$settings['title_prefix'] = '<i class="rbi rbi-plus"></i>';
		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h6';
		} ?>
        <div class="<?php foxiz_post_classes( $settings ); ?>"><?php foxiz_entry_title( $settings ); ?></div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_box_1' ) ) {
	/**
	 * @param $settings
	 */
	function foxiz_list_box_1( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}
		if ( empty( $settings['box_style'] ) ) {
			$settings['box_style'] = 'bg';
		}
		$settings['post_classes'] = 'p-list p-list-1 p-list-box-1 box-' . $settings['box_style'];
		if ( ! empty( $settings['featured_position'] ) ) {
			$settings['post_classes'] .= ' featured-' . esc_attr( $settings['featured_position'] );
		} ?>
        <div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="list-box">
                <div class="list-holder">
                    <div class="list-feat-holder">
                        <div class="feat-holder">
							<?php
							foxiz_entry_featured( $settings );
							foxiz_entry_top( $settings ); ?>
                        </div>
                    </div>
                    <div class="p-content">
						<?php
						foxiz_entry_title( $settings );
						foxiz_entry_review( $settings );
						foxiz_entry_excerpt( $settings );
						foxiz_entry_meta( $settings );
						foxiz_entry_readmore( $settings );
						foxiz_entry_bookmark_action( $settings );
						?>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_list_box_2' ) ) {
	/**
	 * @param array $settings
	 */
	function foxiz_list_box_2( $settings = array() ) {

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g1';
		}
		if ( empty( $settings['box_style'] ) ) {
			$settings['box_style'] = 'bg';
		}

		$settings['post_classes'] = 'p-big p-list p-list-2 p-list-box-2 box-' . $settings['box_style'];
		if ( ! empty( $settings['featured_position'] ) ) {
			$settings['post_classes'] .= ' featured-' . esc_attr( $settings['featured_position'] );
		} ?>
        <div class="<?php foxiz_post_classes( $settings ); ?>">
            <div class="list-box">
                <div class="list-holder">
                    <div class="list-feat-holder">
                        <div class="feat-holder">
							<?php foxiz_entry_featured( $settings ); ?>
                        </div>
                    </div>
                    <div class="p-content">
						<?php
						foxiz_entry_top( $settings );
						foxiz_entry_title( $settings );
						foxiz_entry_review( $settings );
						foxiz_entry_excerpt( $settings );
						foxiz_entry_meta( $settings );
						foxiz_entry_readmore( $settings );
						foxiz_entry_bookmark_action( $settings );
						?>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}
