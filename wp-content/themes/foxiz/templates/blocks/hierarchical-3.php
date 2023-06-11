<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_get_hierarchical_3' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false|string
	 */
	function foxiz_get_hierarchical_3( $settings = array(), $_query = null ) {

		$settings = wp_parse_args( $settings, array(
			'uuid' => '',
			'name' => 'hierarchical_3'
		) );

		$settings['classes'] = 'hrc-3';

		$settings                  = foxiz_detect_dynamic_query( $settings );
		$settings['no_found_rows'] = true;
		$min_posts                 = 2;

		if ( ! $_query ) {
			$_query = foxiz_query( $settings );
		}

		ob_start();
		foxiz_block_open_tag( $settings, $_query );
		if ( ! $_query->have_posts() || $_query->post_count < $min_posts ) {
			foxiz_error_posts( $_query, $min_posts );
		} else {
			foxiz_loop_hierarchical_3( $settings, $_query );
			wp_reset_postdata();
		}
		foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_loop_hierarchical_3' ) ) {
	/**
	 * @param  $settings
	 * @param $_query
	 */
	function foxiz_loop_hierarchical_3( $settings, $_query ) {

		$flag     = true;
		$settings = foxiz_get_design_builder_block( $settings );

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h2';
		}
		$settings['featured_classes'] = 'ratio-v1';

		$list_settings = array(
			'title_tag'     => 'span',
			'title_classes' => 'h5'
		);
		if ( ! empty( $settings['sub_title_tag'] ) ) {
			$list_settings['title_classes'] = $settings['sub_title_tag'];
		}

		ob_start();
		while ( $_query->have_posts() ) {
			$_query->the_post();

			if ( $flag ) {
				$flag = false;
				continue;
			}
			foxiz_list_inline( $list_settings );
		}

		$buffer = ob_get_clean();

		$_query->rewind_posts();

		$settings['no_p_wrap']    = true;
		$settings['post_classes'] = 'p-highlight holder-wrap';
		if ( empty( $settings['crop_size'] ) ) {
			$settings['crop_size'] = 'foxiz_crop_g3';
		}
		while ( $_query->have_posts() ) :
			$_query->the_post(); ?>
			<div class="<?php foxiz_post_classes( $settings ); ?>">
				<?php foxiz_entry_featured( $settings ); ?>
				<div class="overlay-wrap overlay-text">
					<div class="overlay-inner p-gradient">
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
						<div class="sub-section"><?php
							if ( ! empty( $buffer ) ) {
								echo html_entity_decode( $buffer );
							} ?>
						</div>
					</div>
				</div>
			</div>
			<?php break;
		endwhile;
	}
}
