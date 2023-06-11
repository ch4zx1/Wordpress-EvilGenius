<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'foxiz_register_options_block_classic_1' ) ) {
	/**
	 * @return array
	 */
	function foxiz_register_options_block_classic_1() {

		$prefix = 'classic_1_';

		return array(
			'id'         => 'foxiz_config_section_styling_classic_1',
			'title'      => esc_html__( 'Classic (Standard)', 'foxiz' ),
			'icon'       => 'el el-indent-right',
			'subsection' => true,
			'desc'       => esc_html__( 'These are settings for the Classic (standard) layout. It will be overridden by the "Block Design" (for Elementor pages) and "Blog Design" (for the Blog and Archive pages).', 'foxiz' ),
			'fields'     => array(
				array(
					'id'    => $prefix . 'info',
					'type'  => 'info',
					'class' => 'layout-info',
					'style' => 'success',
					'desc'  => html_entity_decode( '<img src="' . get_theme_file_uri( 'assets/images/' . str_replace( '_', '-', rtrim( $prefix, '_' ) ) . '.jpg' ) . '" alt="' . esc_attr__( 'classic', 'foxiz' ) . '">' ),
				),
				array(
					'id'     => 'section_start_' . $prefix . 'featured',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Featured Image', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'crop_size',
					'title'    => esc_html__( 'Featured Image Size', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a featured image size to optimize with the columns setting.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_crop_size(),
					'default'  => '0'
				),
				array(
					'id'     => 'section_end_' . $prefix . 'featured',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'category',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Category', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'entry_category',
					'title'    => esc_html__( 'Entry Category', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a layout for the entry category to display in the post listing.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_standard_entry_category(),
					'default'  => 'bg-1,big'
				),
				array(
					'id'       => $prefix . 'hide_category',
					'type'     => 'select',
					'title'    => esc_html__( 'Responsive - Hide Entry Category', 'foxiz' ),
					'subtitle' => esc_html__( 'Hide the entry category on the tablet and mobile devices.', 'foxiz' ),
					'options'  => foxiz_config_hide_dropdown(),
					'default'  => '0',
				),
				array(
					'id'     => 'section_end_' . $prefix . 'category',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'title',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Post Title', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'title_tag',
					'title'    => esc_html__( 'Title HTML Tag', 'foxiz' ),
					'subtitle' => esc_html__( 'Select a title HTML tag for the post title.', 'foxiz' ),
					'type'     => 'select',
					'options'  => foxiz_config_heading_tag(),
					'default'  => '0'
				),
				array(
					'id'     => 'section_end_' . $prefix . 'title',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'meta',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Entry Meta', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'entry_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Entry Meta Tags', 'foxiz' ),
					'subtitle' => esc_html__( 'Select settings for the entry meta bar.', 'foxiz' ),
					'desc'     => esc_html__( 'Organize how you want the entry meta to appear. Leave blank to set it as the default.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => array( 'avatar', 'author', 'date' ),
				),

				array(
					'id'       => $prefix . 'review',
					'type'     => 'select',
					'title'    => esc_html__( 'Review Meta', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable or select setting for entry review meta.', 'foxiz' ),
					'options'  => foxiz_config_entry_review(),
					'default'  => '1',
				),
				array(
					'id'       => $prefix . 'review_meta',
					'type'     => 'switch',
					'title'    => esc_html__( 'Review Meta Description', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the meta description at the end of the review bar.', 'foxiz' ),
					'default'  => true
				),
				array(
					'id'       => $prefix . 'sponsor_meta',
					'title'    => esc_html__( 'Sponsored Meta', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the "sponsored by" meta.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => true,
				),
				array(
					'id'       => $prefix . 'tablet_hide_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Hide Entry Meta on Tablet', 'foxiz' ),
					'subtitle' => esc_html__( 'Select entry meta tags you would like to hide on the tablet devices. In case long meta it would be useful.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => array(),
				),
				array(
					'id'       => $prefix . 'mobile_hide_meta',
					'type'     => 'select',
					'multi'    => true,
					'title'    => esc_html__( 'Hide Entry Meta on Mobile', 'foxiz' ),
					'subtitle' => esc_html__( 'Select entry meta tags you would like to hide on the mobile devices. In case long meta it would be useful.', 'foxiz' ),
					'options'  => foxiz_config_entry_meta_tags(),
					'default'  => array(),
				),
				array(
					'id'     => 'section_end_' . $prefix . 'meta',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'bookmark',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Bookmark', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'bookmark',
					'type'     => 'switch',
					'title'    => esc_html__( 'Bookmark Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the bookmark icon.', 'foxiz' ),
					'default'  => false
				),
				array(
					'id'     => 'section_end_' . $prefix . 'bookmark',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'format',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Post Format', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'entry_format',
					'type'     => 'select',
					'title'    => esc_html__( 'Post Format Icon', 'foxiz' ),
					'subtitle' => esc_html__( 'Disable or select setting for the post format.', 'foxiz' ),
					'options'  => foxiz_config_entry_format(),
					'default'  => 'bottom,big'
				),
				array(
					'id'     => 'section_end_' . $prefix . 'format',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'excerpt',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Excerpt', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'excerpt_length',
					'type'     => 'text',
					'class'    => 'small-text',
					'validate' => 'numeric',
					'title'    => esc_html__( 'Excerpt - Max Length', 'foxiz' ),
					'subtitle' => esc_html__( 'select max length of the post excerpt.', 'foxiz' ),
					'desc'     => esc_html__( 'Leave this option blank or set 0 to disable.', 'foxiz' ),
					'default'  => '30'
				),
				array(
					'id'          => $prefix . 'excerpt_source',
					'title'       => esc_html__( 'Excerpt - Source', 'foxiz' ),
					'subtitle'    => esc_html__( 'Where to get the post excerpt.', 'foxiz' ),
					'description' => esc_html__( 'When you select "use title tagline". if it is empty, it will fallback to the post excerpt or content.', 'foxiz' ),
					'type'        => 'select',
					'options'     => foxiz_config_excerpt_source(),
					'default'     => 'tagline'
				),
				array(
					'id'       => $prefix . 'hide_excerpt',
					'type'     => 'select',
					'title'    => esc_html__( 'Responsive - Hide Excerpt', 'foxiz' ),
					'subtitle' => esc_html__( 'Hide the post excerpt on the tablet and mobile devices.', 'foxiz' ),
					'options'  => foxiz_config_hide_dropdown(),
					'default'  => '0',
				),
				array(
					'id'     => 'section_end_' . $prefix . 'excerpt',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'readmore',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Read More', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'readmore',
					'title'    => esc_html__( 'Read More Button', 'foxiz' ),
					'subtitle' => esc_html__( 'Enable or disable the read more button.', 'foxiz' ),
					'type'     => 'switch',
					'default'  => false,
				),
				array(
					'id'     => 'section_end_' . $prefix . 'readmore',
					'type'   => 'section',
					'class'  => 'ruby-section-end',
					'indent' => false
				),
				array(
					'id'     => 'section_start_' . $prefix . 'centered',
					'type'   => 'section',
					'class'  => 'ruby-section-start',
					'title'  => esc_html__( 'Centering', 'foxiz' ),
					'indent' => true
				),
				array(
					'id'       => $prefix . 'center_mode',
					'type'     => 'switch',
					'title'    => esc_html__( 'Centering Content', 'foxiz' ),
					'subtitle' => esc_html__( 'Centering text and elements for the post listing.', 'foxiz' ),
					'default'  => false,
				),
				array(
					'id'     => 'section_end_' . $prefix . 'centered',
					'type'   => 'section',
					'class'  => 'ruby-section-end no-border',
					'indent' => false
				)
			)
		);
	}
}