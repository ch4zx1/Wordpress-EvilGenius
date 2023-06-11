<?php

class XPress_WP_Widget_Recent_Posts extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'xpress_widget_recent_entries',
            'description' => \XPress::xlink()->phrase('thxpress_widget_recent_posts_desc'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('xpress-recent-posts', \XPress::xlink()->phrase('thxpress_widget_recent_posts'), $widget_ops);
        $this->alt_option_name = 'widget_recent_entries';
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title'])) ? $instance['title'] : \XPress::xlink()->phrase('thxpress_recent_posts');

        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;

        $r = new WP_Query(apply_filters('widget_posts_args', array(
            'posts_per_page' => $number,
            'no_found_rows' => true,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true,
        ), $instance));

        if (!$r->have_posts()) {
            return;
        }
        ?>
        <?php echo $args['before_widget']; ?>
        <?php
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        ?>
        <ul class="block-body">
            <?php foreach ($r->posts as $recent_post) : ?>
                <?php
                $post_title = get_the_title($recent_post->ID);
                $title = (!empty($post_title)) ? $post_title : \XPress::xlink()->phrase('(thxpress_no_title)');

                $user_id = $recent_post->post_author;
                $user = get_userdata($user_id);
                $username = is_object($user) ? $user->display_name : \XPress::xlink()->phrase('guest');
                $commentCount = thxpress_comment_count($recent_post);
                ?>
                <li class="block-row">
                    <div class="contentRow">
                        <div class="contentRow-figure">
							<span class="avatar avatar--xs">
								<?php echo get_avatar($user_id, 42); ?>
							</span>
                        </div>
                        <div class="contentRow-main contentRow-main--close">
                            <a href="<?php the_permalink($recent_post->ID); ?>"><?php echo $title; ?></a>
                            <div class="contentRow-minor contentRow-minor--hideLinks">
                                <ul class="listInline listInline--bullet">
                                    <li>
                                        <?php echo \XPress::xlink()->phrase('started_by_x', ['name' => $username]) ?>
                                    </li>
                                    <li>
                                        <?php if ($show_date) : ?>
                                            <span class="post-date"><?php echo get_the_date('',
                                                    $recent_post->ID); ?></span>
                                        <?php endif; ?>
                                    </li>
                                    <li>
                                        <?php echo \XPress::xlink()->phrase('comments:') ." $commentCount"; ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = (int)$new_instance['number'];
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool)$new_instance['show_date'] : false;
        return $instance;
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool)$instance['show_date'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>"
                   name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1"
                   value="<?php echo $number; ?>" size="3"/></p>

        <p><input class="checkbox" type="checkbox"<?php checked($show_date); ?>
                  id="<?php echo $this->get_field_id('show_date'); ?>"
                  name="<?php echo $this->get_field_name('show_date'); ?>"/>
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?'); ?></label></p>
        <?php
    }
}

add_action('widgets_init', function () {
    register_widget("XPress_WP_Widget_Recent_Posts");
});
