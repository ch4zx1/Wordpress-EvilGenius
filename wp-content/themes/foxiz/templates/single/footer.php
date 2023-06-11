<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_single_footer' ) ) {
	/**
	 * @return false
	 */
	function foxiz_single_footer() {

		global $wp_query;
		if ( isset( $wp_query->query_vars['rbsnp'] ) ) {
			return false;
		}

		$post_id           = get_the_ID();
		$popular_shortcode = foxiz_get_option( 'single_post_popular_shortcode' );
		$related_section   = foxiz_get_option( 'single_post_related' );

		if ( $related_section ) {
			$related_classes = 'single-related sfoter-sec';

			$settings = array(
				'uuid'             => 'uuid_r' . $post_id,
				'posts_per_page'   => foxiz_get_option( 'single_post_related_total' ),
				'post_not_in'      => $post_id,
				'is_related_query' => 1,
				'where'            => foxiz_get_option( 'single_post_related_where' ),
				'layout'           => foxiz_get_option( 'single_post_related_layout' ),
				'pagination'       => foxiz_get_option( 'single_post_related_pagination' ),
			);

			/** $_query */
			$_query = foxiz_query_related( $settings );
			if ( ! $_query->have_posts() ) {
				return false;
			} ?>
            <aside class="<?php echo esc_attr( $related_classes ); ?>">
				<?php $related_title = foxiz_get_option( 'single_post_related_blog_heading' );
				if ( empty( $related_title ) ) {
					$related_title = foxiz_html__( 'You Might Also Like', 'foxiz' );
				}
				if ( '-1' !== (string) $related_title ) {
					echo foxiz_get_heading( array(
						'title'    => $related_title,
						'layout'   => foxiz_get_option( 'single_post_related_blog_heading_layout' ),
						'html_tag' => foxiz_get_option( 'single_post_related_blog_heading_tag' ),
					) );
				}
				echo foxiz_get_single_footer_listing( $settings, $_query ); ?>
            </aside>
			<?php
		}
		if ( ! empty( $popular_shortcode ) ) : ?>
            <aside class="sfoter-sec single-popular">
				<?php echo do_shortcode( $popular_shortcode ); ?>
            </aside>
		<?php endif;
	}
}

if ( ! function_exists( 'foxiz_get_single_footer_listing' ) ) {
	/**
	 * @param array $settings
	 * @param null $_query
	 *
	 * @return false
	 */
	function foxiz_get_single_footer_listing( $settings = array(), $_query = null ) {

		if ( empty( $settings['layout'] ) ) {
			return false;
		}

		$settings['excerpt_length'] = 0;
		$settings['excerpt']        = 1;

		switch ( $settings['layout'] ) {
			case 'grid_1' :
				$settings['columns'] = 3;

				return foxiz_get_grid_1( $settings, $_query );
			case 'grid_2' :
				$settings['columns'] = 3;

				return foxiz_get_grid_2( $settings, $_query );
			case 'grid_box_1' :
				$settings['columns'] = 3;

				return foxiz_get_grid_box_1( $settings, $_query );
			case 'grid_box_2' :
				$settings['columns'] = 3;

				return foxiz_get_grid_box_2( $settings, $_query );
			default:
				$settings['columns'] = 4;

				return foxiz_get_grid_small_1( $settings, $_query );
		}
	}
}
