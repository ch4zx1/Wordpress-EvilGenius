<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_get_hierarchical_2' ) ) {
	/**
	 * @param $settings
	 *
	 * @return false|string
	 */
	function foxiz_get_hierarchical_2( $settings ) {

		$settings = wp_parse_args( $settings, array(
			'uuid' => '',
			'name' => 'hierarchical_2'
		) );

		$settings = foxiz_detect_dynamic_query( $settings );

		$settings['classes'] = 'block-small block-hrc hrc-2';

		if ( empty( $settings['pagination'] ) ) {
			$settings['no_found_rows'] = true;
		} else {
			$settings['classes'] .= ' short-pagination';
		}

		$min_posts = 2;
		$_query    = foxiz_query( $settings );

		ob_start();

		foxiz_block_open_tag( $settings, $_query );
		if ( ! $_query->have_posts() || $_query->post_count < $min_posts ) {
			foxiz_error_posts( $_query, $min_posts );
		} else {
			foxiz_block_inner_open_tag( $settings );
			foxiz_loop_hierarchical_2( $settings, $_query );
			foxiz_block_inner_close_tag( $settings );
			foxiz_render_pagination( $settings, $_query );
			wp_reset_postdata();
		}
		foxiz_block_close_tag();

		return ob_get_clean();
	}
}

if ( ! function_exists( 'foxiz_loop_hierarchical_2' ) ) {
	/**
	 * @param  $settings
	 * @param $_query
	 */
	function foxiz_loop_hierarchical_2( $settings, $_query ) {

		$flag     = true;
		$settings = foxiz_get_design_builder_block( $settings );

		if ( empty( $settings['title_tag'] ) ) {
			$settings['title_tag'] = 'h3';
		}

		if ( empty( $settings['sub_title_tag'] ) ) {
			$settings['sub_title_tag'] = 'h5';
		}

		while ( $_query->have_posts() ) :
			$_query->the_post();
			if ( $flag ) {
				foxiz_list_small_1( $settings );
				$settings['title_tag'] = $settings['sub_title_tag'];
				$flag                  = false;
			} else {
				foxiz_list_inline( $settings );
			}
		endwhile;
	}
}
