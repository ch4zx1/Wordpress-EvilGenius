<?php

$excerptLimit = \XPress::getStyleProperty('thxpress_excerpt');


if (!function_exists('thxpress_author_name')) {
    function thxpress_author_name($user)
    {
        if (!($user instanceof WP_User)) {
            $user = get_user_by('ID', $user);
        }

        $settings = \XPress::app()->option('xpress');
        if ($settings['username_sync']) {
            if ($user instanceof WP_User) {
                $xfUser = \XPress::getXFUser($user);

                if ($xfUser) {
                    return $xfUser->username;
                }
            }
        }

        return $user ? $user->display_name : \XPress::xlink()->phrase('guest');
    }
}

if (!function_exists('thxpress_posted_on')) {
    function thxpress_posted_on()
    {
        return \XPress::xlink()->phrase('thxpress_posted_by_x_on', [
            'url' => get_author_posts_url(get_the_author_meta('ID')),
            'name' => thxpress_author_name(get_the_author_meta('ID')),
            'time' => thxpress_time_link()
        ]);
    }
}

if (!function_exists('thxpress_time_link')) {
    function thxpress_time_link()
    {
        $templater = \XPress::xlink()->templater();
        return \XPress::xlink()->phrase('thxpress_posted_on', [
            'time' => $templater->fnDateTime($templater, $escape, get_post_time('U', true)),
            'url' => get_permalink()
        ]);
    }
}

if (!function_exists('thxpress_edit_time_link')) {
    function thxpress_edit_time_link()
    {
        $templater = \XPress::xlink()->templater();
        if (get_the_time('U') !== get_the_modified_time('U')) {
            return \XPress::xlink()->phrase('thxpress_posted_on_update_on', [
                'post_time' => $templater->fnDateTime($templater, $escape, get_the_date('U')),
                'update_time' => $templater->fnDateTime($templater, $escape, get_the_modified_time('U')),
                'url' => get_permalink()
            ]);
        } else {
            return '';
        }
    }
}

if (!function_exists('xpress_entry_footer')) {
    function xpress_entry_footer()
    {
        $separate_meta = ', ';

        ob_start();
        thxpress_get_the_tag_list($separate_meta);
        $tags_list = ob_get_clean();

        if (($tags_list) || get_edit_post_link()) {
            echo '<footer class="entry-footer block-footer">';
            if ('post' === get_post_type()) {
                if ($tags_list) {
                    echo '<span class="cat-tags-links">';

                    if ($tags_list && !is_wp_error($tags_list)) {
                        $phrase = \XPress::xlink()->phrase('tags:');
                        echo "<span class='tags-links'><span>{$phrase}</span>{$tags_list}</span>";
                    }

                    echo '</span>';
                }
            }
            thxpress_edit_link();
            echo '</footer>';
        }
    }
}

if (!function_exists('thxpress_edit_link')) {
    function thxpress_edit_link()
    {
        edit_post_link(
            \XPress::xlink()->phrase('edit'),
            '<span class="edit-link">',
            '</span>'
        );
    }
}

if (!function_exists('thxpress_get_featured_excerpt')) {
    function thxpress_get_featured_excerpt()
    {
        $excerptLimit = \XPress::getStyleProperty('xpress_featuredExcerptLength');
        $excerpt = get_the_content();
        $excerpt = preg_replace(" ([.*?])", '', $excerpt);
        $excerpt = strip_shortcodes($excerpt);
        $excerpt = strip_tags($excerpt);
        $excerpt = mb_substr($excerpt, 0, $excerptLimit);
        $excerpt = mb_substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
        $excerpt = $excerpt . '...';
        return $excerpt;
    }
}

if (!function_exists('thxpress_get_author_block')) {
    function thxpress_get_author_block($authorID, $link = '', $class = false)
    {
        $user = get_userdata($authorID);
        if (!$user) {
            return;
        }
        $displayName = thxpress_author_name(get_the_author_meta('ID'));
        $description = get_the_author_meta('description');
        $id = $user->ID;
        $website = $user->user_url;
        $user_post_count = count_user_posts($id);
        ?>

        <div class="<?php echo $class ? 'thxpress_authorBlock' : null; ?>">
            <div class="contentRow contentRow--alignMiddle">
            <span class="contentRow-figure">
                <?php echo get_avatar($id); ?>
            </span>
                <div class="contentRow-main">
                    <div class="p-title">
                        <?php echo ($link ? '<a href="' . $link . '" class="p-title-value">' : '') . $displayName . ($link ? '</a>' : ''); ?>
                    </div>
                    <div class="p-description">
                        <?php
                        $strippedDescription = strip_tags($description);
                        echo substr($strippedDescription, 0, 150) . (strlen($strippedDescription) > 150 ? '...' : '')
                        ?>
                        <ul class="listInline listInline--bullet">
                            <?php if ($link == '') : ?>
                                <li>
                                    <span><?php echo \XPress::xlink()->phrase('thxpress_articles:'); ?></span>
                                    <span><?php echo $user_post_count ?></span>
                                </li>
                            <?php else : ?>
                                <li>
                                    <?php
                                    echo \XPress::xlink()->phrase('thxpress_view_all_x_articles', [
                                        'link' => $link,
                                        'count' => $user_post_count
                                    ]);
                                    ?>
                                </li>
                            <?php
                            endif;
                            if ($website): ?>
                                <li>
                                    <a href="<?php echo $website ?>"
                                       target="_blank"><?php echo \XPress::xlink()->phrase('website') ?></a>
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}

if (!function_exists('thxpress_comment_count')) {
    /**
     * @param WP_Post $post
     * @return string
     * @throws Exception
     */
    function thxpress_comment_count(WP_Post $post)
    {
        $settings = \XPress::app()->option('xpress');
        $commentSync = $settings['comment_sync'];

        if (!$commentSync) {
            return $post->comment_count;
        }

        $thread = \XPress::getThread($post);

        if (!$thread) {
            return $post->comment_count;
        }

        return $thread->reply_count;
    }
}

if (!function_exists('thxpress_get_categories')) {
    function thxpress_get_categories()
    {
        $categories = get_categories(array(
            'orderby' => 'name',
            'order' => 'ASC'
        ));

        $sortedCategories = array();
        foreach ($categories as $category) {
            $sortedCategories[$category->term_id] = array(
                'name' => $category->name,
                'description' => $category->description,
                'link' => get_category_link($category->term_id),
            );
        }

        return $sortedCategories;
    }
}

if (!function_exists('thxpress_get_all_categories')) {
    function thxpress_get_all_categories()
    {
        $currentCatID = 0;
        $currentCat = get_category(get_query_var('cat'));

        if (isset($currentCat->cat_ID)) {
            $currentCatID = $currentCat->cat_ID;
        }

        $categories = thxpress_get_categories();

        foreach ($categories as $category) {
            if (isset($category->cat_ID)) {
                $currentCatClass = '';
                $categoryHref = get_category_link($category->term_id);

                if ($currentCatID == $category->cat_ID) {
                    $currentCatClass = 'current-cat';
                };

                echo "<a href='$categoryHref' class='category-tag $currentCatClass'>
			        <span class='category__name'>$category->name</span>
			        <span class='category__count'>($category->category_count)</span>
		        </a>";
            }
        }
    }
}

if (!function_exists('thxpress_get_the_category_list')) {
    function thxpress_get_the_category_list($classname = '', $_postID = 0, $return = false)
    {
        if ($_postID > 0) {
            $postID = $_postID;
        } else {
            $postID = get_the_ID();
        }
        $categories = get_the_category($postID);
        if (!$categories) {
            echo '';
            return;
        }
        $ret = "<div class='entry-categories'>";

        foreach ($categories as $category) {
            if (isset($category->cat_ID)) {
                $categoryLink = \XPress::getCategoryLink($category->term_id);

                $category_link = get_category_link($category->term_id);
                $ret .= "<a style='--catColor: {$categoryLink->extra['bg_color']}; border-color: {$categoryLink->extra['bg_color']}; background-color: {$categoryLink->extra['bg_color']}; color: {$categoryLink->extra['text_color']};' href='$category_link' class='category-tag {$category->slug} $classname'>{$category->name}</a>";
            }
        }

        $ret .= "</div>";

        if ($return) {
            return $ret;
        } else {
            echo $ret;
        }
    }
}

if (!function_exists('thxpress_get_the_tag_list')) {
    function thxpress_get_the_tag_list($classname = '', $_postID = 0, $return = false)
    {
        if ($_postID > 0) {
            $postID = $_postID;
        } else {
            $postID = get_the_ID();
        }
        $tags = get_the_tags($postID);
        if (!$tags) {
            echo '';
            return;
        }
        $ret = "<div class='entry-tags'>";

        foreach ($tags as $tag) {
            $tag_slug = $tag->slug;
            $tag_name = $tag->name;
            $tag_link = get_tag_link($tag->term_id);
            $ret .= "<a href='$tag_link' class='category-tag $tag_slug $classname'>$tag_name</a>";
        }

        $ret .= "</div>";

        if ($return) {
            return $ret;
        } else {
            echo $ret;
        }
    }
}

if (!function_exists('thxpress_format_comment')) {
    function thxpress_format_comment($comment, $args, $depth)
    {

        $userId = $comment->user_id;
        $xfUser = false;
        if ($userId) {
            $xfUser = \XPress::getXFUser(get_user_by('ID', $userId));
            if ($xfUser) {
                $templater = \XPress::xlink()->templater();
            }
        }

        ?>
        <div class="message message--simple js-Comment" id="comment-<?php echo $comment->comment_ID ?>"
             data-comment-id="<?php echo $comment->comment_ID ?>">
            <div class="message-inner">
                <div class="message-cell message-cell--user">
                    <header class="messages-user">
                        <div class="message-avatar">
                            <div class="message-avatar-wrapper">
                                <?php
                                if ($xfUser) {
                                    echo $templater->fnAvatar($templater, $escape, $xfUser, 's');
                                } else {
                                    ?><span class="avatar avatar--s"><?php echo get_avatar($comment, 48); ?></span><?php
                                }
                                ?>
                            </div>
                        </div>
                    </header>
                </div>
                <div class="message-cell message-cell--main">
                    <div class="message-main">
                        <div class="message-content">
                            <header class="message-attribution message-attribution--plain">
                                <ul class="listInline listInline--bullet">
                                    <li class="message-attribution-user">
                                        <?php
                                        if ($xfUser) {
                                            echo $templater->fnAvatar($templater, $escape, $xfUser, 'xxs');
                                        } else {
                                            ?>
                                            <span class="avatar avatar--xxs"><?php echo get_avatar($comment); ?></span><?php
                                        }
                                        ?>
                                        <div class="attribution">
                                            <?php
                                            if ($xfUser) {
                                                echo $templater->fnUsernameLink($templater, $escape, $xfUser);
                                            } else {
                                                echo get_comment_author_link();
                                            }
                                            ?>
                                        </div>
                                    </li>
                                    <li class="u-concealed"><?php echo get_comment_date() ?></li>
                                </ul>
                            </header>
                            <div class="message-body">
                                <?php echo \XPress::renderBbCode(get_comment_text()); ?>
                            </div>
                            <footer class="message-footer">
                                <div class="message-actionBar actionBar">
                                    <div class="actionBar-set actionBar-set--external">
                                        <?php comment_reply_link(array_merge($args, array(
                                            'add_below' => 'comment',
                                            'respond_id' => 'comment_form_container',
                                            'depth' => $depth,
                                            'max_depth' => $args['max_depth'],
                                            'before' => '<span class="actionBar-action">',
                                            'after' => '</span>'
                                        ))); ?>
                                    </div>
                                </div>
                            </footer>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}