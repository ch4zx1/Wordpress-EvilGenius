<?php
/** Don't load directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$foxiz_file_paths = array(

	'templates/template-helpers.php',
	'templates/parts.php',
	'templates/entry.php',
	'templates/popup.php',
	'templates/blog.php',
	'templates/page.php',
	'templates/ajax.php',

	'templates/header/templates.php',
	'templates/header/layouts.php',
	'templates/header/transparent.php',
	'templates/footer.php',

	/** single */
	'templates/single/templates.php',
	'templates/single/reviews.php',
	'templates/single/layouts.php',
	'templates/single/footer.php',
	'templates/single/related.php',
	'templates/single/custom-post-type.php',
	'templates/single/standard-1.php',
	'templates/single/standard-2.php',
	'templates/single/standard-3.php',
	'templates/single/standard-4.php',
	'templates/single/standard-5.php',
	'templates/single/standard-6.php',
	'templates/single/standard-7.php',
	'templates/single/standard-8.php',
	'templates/single/standard-9.php',
	'templates/single/video-1.php',
	'templates/single/video-2.php',
	'templates/single/video-3.php',
	'templates/single/video-4.php',
	'templates/single/audio-1.php',
	'templates/single/audio-2.php',
	'templates/single/audio-3.php',
	'templates/single/audio-4.php',
	'templates/single/gallery-1.php',
	'templates/single/gallery-2.php',
	'templates/single/gallery-3.php',

	/** module */
	'templates/modules/classic.php',
	'templates/modules/grid.php',
	'templates/modules/list.php',
	'templates/modules/overlay.php',
	'templates/modules/category.php',

	/** blocks */
	'templates/blocks/heading.php',
	'templates/blocks/classic-1.php',
	'templates/blocks/grid-1.php',
	'templates/blocks/grid-2.php',
	'templates/blocks/grid-small-1.php',
	'templates/blocks/grid-box-1.php',
	'templates/blocks/grid-box-2.php',
	'templates/blocks/grid-flex-1.php',
	'templates/blocks/grid-flex-2.php',
	'templates/blocks/list-1.php',
	'templates/blocks/list-2.php',
	'templates/blocks/list-box-1.php',
	'templates/blocks/list-box-2.php',
	'templates/blocks/list-small-1.php',
	'templates/blocks/list-small-2.php',
	'templates/blocks/list-small-3.php',
	'templates/blocks/hierarchical-1.php',
	'templates/blocks/hierarchical-2.php',
	'templates/blocks/hierarchical-3.php',
	'templates/blocks/overlay-1.php',
	'templates/blocks/overlay-2.php',
	'templates/blocks/playlist.php',
	'templates/blocks/quick-links.php',
	'templates/blocks/breaking-news.php',
	'templates/blocks/categories.php',
	'templates/blocks/newsletter.php'
);

/**
 * load file templates
 */
foreach ( $foxiz_file_paths as $foxiz_path ) {
	$foxiz_file = get_theme_file_path( $foxiz_path );
	if ( file_exists( $foxiz_file ) ) {
		include_once $foxiz_file;
	}
}
