<?php

class XPress_WP_Widget_Recent_Comments extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'xpress_widget_recent_comments',
            'description' => \XPress::xlink()->phrase('thxpress_widget_recent_comments_desc'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('xpress-recent-comments', \XPress::xlink()->phrase('thxpress_widget_recent_comments'), $widget_ops);
        $this->alt_option_name = 'widget_recent_comments';

        if (is_active_widget(false, false, $this->id_base) || is_customize_preview()) {
            add_action('wp_head', array($this, 'recent_comments_style'));
        }
    }


    public function recent_comments_style()
    {
        if (!current_theme_supports('widgets') // Temp hack #14876
            || !apply_filters('show_recent_comments_widget_style', true, $this->id_base)) {
            return;
        }
        ?>
        <style type="text/css">.recentcomments a {
                display: inline !important;
                padding: 0 !important;
                margin: 0 !important;
            }</style>
        <?php
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $output = '';

        $title = (!empty($instance['title'])) ? $instance['title'] : \XPress::xlink()->phrase('thxpress_recent_comments');

        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }

        $comments = get_comments(apply_filters('widget_comments_args', array(
            'number' => $number,
            'status' => 'approve',
            'post_status' => 'publish'
        ), $instance));

        $output .= $args['before_widget'];
        if ($title) {
            $output .= $args['before_title'] . $title . $args['after_title'];
        }

        $output .= '<ul id="block-body">';
        if (is_array($comments) && $comments) {
            $post_ids = array_unique(wp_list_pluck($comments, 'comment_post_ID'));
            _prime_post_caches($post_ids, strpos(get_option('permalink_structure'), '%category%'), false);

            foreach ((array)$comments as $comment) {
                $user = get_user_by('email', $comment->comment_author_email);

                if ($user) {
                    $output .= '<li class="block-row"><div class="contentRow">';
                    $output .= '<div class="contentRow-figure">
						<span class="avatar avatar--xs">' . get_avatar($user->ID, 42) . '</span>
					</div><div class="contentRow-main contentRow-main--close">';
                    $output .= '<a href="' . esc_url(get_comment_link($comment)) . '">' . get_the_title($comment->comment_post_ID) . '</a>';
                    $output .= '<div class="contentRow-minor contentRow-minor--hideLinks"><span class="comment-author-link">' . \XPress::xlink()->phrase('thxpress_comment_by') . ' ' . get_comment_author_link($comment) . '</span></div>';
                    $output .= '</div></div></li>';
                }
            }
        }
        $output .= '</ul>';
        $output .= $args['after_widget'];

        echo $output;
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = absint($new_instance['number']);
        return $instance;
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of comments to show:'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1"
                   value="<?php echo $number; ?>" size="3"/></p>
        <?php
    }

    public function flush_widget_cache()
    {
        _deprecated_function(__METHOD__, '4.4.0');
    }
}

add_action('widgets_init', function () {
    register_widget("XPress_WP_Widget_Recent_Comments");
});
