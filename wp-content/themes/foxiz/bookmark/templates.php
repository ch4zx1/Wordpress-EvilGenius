<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_follow_category' ) ) {
	/**
	 * @param $category_id
	 */
	function foxiz_follow_category( $category_id ) {

	}
}

if ( ! function_exists( 'foxiz_reading_list' ) ) {
	function foxiz_reading_list() {
		$settings = foxiz_get_archive_page_settings( 'saved_', array(
			'uuid'            => 'uid_saved',
			'bookmark_action' => true,
			'classes'         => 'saved-content',
		) );
		unset( $settings['pagination'] );
		$image_description      = foxiz_get_option( 'saved_image' );
		$image_description_dark = foxiz_get_option( 'saved_image_dark' );
		$heading_classes        = 'bookmark-section-header';
		if ( ! empty( $settings['pattern'] ) && '-1' !== (string) $settings['pattern'] ) {
			$heading_classes .= ' is-pattern pattern-' . esc_attr( $settings['pattern'] );
		} else {
			$heading_classes .= ' solid-bg';
		}

		$_query = Foxiz_Bookmark::get_instance()->get_query();
		?><div class="saved-section">
                <div class="<?php echo esc_attr($heading_classes); ?>">
                    <div class="rb-container edge-padding">
	                    <?php if ( ! empty( $image_description['url'] ) ) : ?>
                            <div class="bookmark-section-header-image">
			                    <?php if ( ! empty( $image_description_dark['url'] ) ) : ?>
                                    <img data-mode="default" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo esc_attr( $image_description['alt'] ); ?>" height="<?php echo esc_attr( $image_description['height'] ); ?>" width="<?php echo esc_attr( $image_description['width'] ); ?>">
                                    <img data-mode="dark" src="<?php echo esc_url( $image_description_dark['url'] ); ?>" alt="<?php echo esc_attr( $image_description_dark['alt'] ); ?>" height="<?php echo esc_attr( $image_description_dark['height'] ); ?>" width="<?php echo esc_attr( $image_description_dark['width'] ); ?>">
			                    <?php else : ?>
                                    <img src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo esc_attr( $image_description['alt'] ); ?>" height="<?php echo esc_attr( $image_description['height'] ); ?>" width="<?php echo esc_attr( $image_description['width'] ); ?>">
			                    <?php endif; ?>
                            </div>
	                    <?php endif; ?>
                        <div class="bookmark-section-header-inner">
                            <h2 class="bookmark-section-title"><?php echo esc_html( foxiz_get_option( 'saved_heading' ) ); ?></h2>
		                    <?php if ( foxiz_get_option( 'saved_description' ) ) : ?>
                                <p class="bookmark-section-decs is-meta"><?php echo esc_html( foxiz_get_option( 'saved_description' ) ); ?></p>
		                    <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php if ( ! empty( $_query ) && $_query->have_posts() ) :
                    foxiz_the_blog( $settings, $_query );
                else : ?>
                    <div class="empty-saved">
                        <div class="rb-container edge-padding">
                            <h4 class="empty-saved-title"><?php foxiz_html_e( 'You haven\'t saved anything yet.', 'foxiz' ); ?></h4>
                            <p class="empty-saved-desc"><?php printf( foxiz_html__( 'Start saving your interested articles by clicking the %s icon and you\'ll find them all here.', 'foxiz' ), '<i class="rbi rbi-bookmark"></i>' ); ?></p>
                        </div>
                    </div>
				<?php endif; ?>
        </div>
		<?php
	}
}

if ( ! function_exists( 'foxiz_user_interests' ) ) {
	function foxiz_user_interests() {

		$heading_classes = 'bookmark-section-header';
		$pattern         = foxiz_get_option( 'interest_pattern' );
		if ( ! empty( $pattern ) && ( '-1' !== (string) $pattern ) ) {
			$heading_classes .= ' is-pattern pattern-' . esc_attr( $pattern );
		} else {
			$heading_classes .= ' solid-bg';
		}
		$image_description      = foxiz_get_option( 'interest_image' );
		$image_description_dark = foxiz_get_option( 'interest_image_dark' );

		$settings = array(
			'layout'      => foxiz_get_option( 'interest_layout' ),
			'url'         => foxiz_get_option( 'interest_url' ),
			'follow'      => true,
			'title_tag'   => 'h4',
			'count_posts' => true,
		); ?>
        <div class="interest-section">
            <div class="<?php echo esc_attr( $heading_classes ); ?>">
                <div class="rb-container edge-padding">
                <?php if ( ! empty( $image_description['url'] ) ) : ?>
                    <div class="bookmark-section-header-image">
                        <?php if ( ! empty( $image_description_dark['url'] ) ) : ?>
                            <img data-mode="default" src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo esc_attr( $image_description['alt'] ); ?>" height="<?php echo esc_attr( $image_description['height'] ); ?>" width="<?php echo esc_attr( $image_description['width'] ); ?>">
                            <img data-mode="dark" src="<?php echo esc_url( $image_description_dark['url'] ); ?>" alt="<?php echo esc_attr( $image_description_dark['alt'] ); ?>" height="<?php echo esc_attr( $image_description_dark['height'] ); ?>" width="<?php echo esc_attr( $image_description_dark['width'] ); ?>">
                        <?php else : ?>
                            <img src="<?php echo esc_url( $image_description['url'] ); ?>" alt="<?php echo esc_attr( $image_description['alt'] ); ?>" height="<?php echo esc_attr( $image_description['height'] ); ?>" width="<?php echo esc_attr( $image_description['width'] ); ?>">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                    <div class="bookmark-section-header-inner">
                        <h2 class="bookmark-section-title"><?php echo esc_html( foxiz_get_option( 'interest_heading' ) ); ?></h2>
						<?php if ( foxiz_get_option( 'saved_description' ) ) : ?>
                            <p class="bookmark-section-decs is-meta"><?php echo esc_html( foxiz_get_option( 'interest_description' ) ); ?></p>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="interest-content">
                <div class="rb-container edge-padding">
					<?php foxiz_follow_categories_list( $settings ); ?>
                </div>
            </div>
        </div>
        </div>
	<?php }
}

if ( ! function_exists( 'foxiz_recommended_section' ) ) {
	/**
	 * foxiz_recommended_section
	 */
	function foxiz_recommended_section() {
		$settings = array(
			'uuid' => 'uid_rec',
		);
		$settings = foxiz_get_archive_page_settings( 'recommended_', $settings );
		if ( is_user_logged_in() ) {
			$settings['categories'] = Foxiz_Bookmark::get_instance()->get_user_categories();
			if ( ! is_array( $settings['categories'] ) || ! count( $settings['categories'] ) ) {
				$settings['order'] = 'popular_m';
			}
		} elseif ( function_exists( 'pvc_get_post_views' ) ) {
			$settings['order'] = 'popular';
		} else {
			$settings['order'] = 'comment_count';
		}

		$_query = foxiz_query( $settings );
		if ( ! $_query->have_posts() ) {
			return false;
		} ?>
        <div class="rec-section light-scheme">
	        <?php foxiz_the_blog( $settings, $_query ) ?>
        </div>
	<?php }
}

if ( ! function_exists( 'foxiz_follow_categories_list' ) ) {
	/**
	 * @param array $settings
	 */
	function foxiz_follow_categories_list( $settings = array() ) {

		$categories_ids = Foxiz_Bookmark::get_instance()->get_user_categories();

		$settings['classes'] = 'block-follow';

		if ( empty( $settings['columns'] ) ) {
			$settings['columns'] = 5;
		}
		if ( empty( $settings['column_gap'] ) ) {
			$settings['column_gap'] = 10;
		}
		foxiz_block_open_tag( $settings ); ?>
        <div class="block-inner">
			<?php
			if ( ! empty( $categories_ids ) ) {
				foreach ( $categories_ids as $id ) {
					$settings['cid'] = $id;
					foxiz_category_item_1( $settings );
				}
			}
			foxiz_category_item_follow( $settings );
			?>
        </div>
		<?php foxiz_block_close_tag();
	}
}




