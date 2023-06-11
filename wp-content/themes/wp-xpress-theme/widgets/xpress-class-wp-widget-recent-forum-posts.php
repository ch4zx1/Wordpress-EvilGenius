<?php

class XPress_WP_Widget_Recent_Forum_Posts extends WP_Widget
{

    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'xpress_widget_recent_forum_posts',
            'description' => \XPress::xlink()->phrase('thxpress_widget_recent_forum_posts_desc'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('xpress-recent-forum-posts', \XPress::xlink()->phrase('thxpress_widget_recent_forum_posts'),
            $widget_ops);
        $this->alt_option_name = 'widget_recent_forum_entries';
    }

    public function widget($args, $instance)
    {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title'])) ? $instance['title'] : \XPress::xlink()->phrase('thxpress_recent_forum_posts');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number'])) ? absint($instance['number']) : 5;
        if (!$number) {
            $number = 5;
        }

        $categories = get_the_category();

        if (empty($categories) || count($categories) > 1) {
            return;
        }

        $category = $categories[0];
        $categoryData = get_option("taxonomy_{$category->term_id}");

        if (!isset($categoryData['_xPressXFForumID']) && !isset($categoryData['_xPressXFCategoryID'])) {
            return;
        }

        if (isset($categoryData['_xPressXFCategoryID'])) {
            $forums = \XF::finder('XF:Node')->where('parent_node_id',
                $categoryData['_xPressXFCategoryID'])->fetch()->keys();
        } else {
            $forum = \XF::em()->find('XF:Forum', $categoryData['_xPressXFForumID']);
            if ($forum) {
                $forums = [$forum->node_id];
            } else {
                $forums = [];
            }
        }

        $forums = array_filter($forums);

        if (empty($forums)) {
            return;
        }

        $latestThreads = \XF::finder('XF:Thread')
            ->where('node_id', '=', $forums)
            ->with('User')
            ->with('Forum')
            ->order('post_date', 'DESC')
            ->limit($number)
            ->fetch();

        echo \XPress::xlink()->templater()->renderTemplate('public:widget_new_threads', [
            'title' => $title,
            'threads' => $latestThreads
        ]);
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
        <?php
    }
}

add_action('widgets_init', function () {
    register_widget("XPress_WP_Widget_Recent_Forum_Posts");
});