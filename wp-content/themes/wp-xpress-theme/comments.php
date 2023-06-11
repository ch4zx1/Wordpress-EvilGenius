<?php if (post_password_required()) {
    return;
} ?>

<?php echo \XPress::xlink()->ad('thxpress_above_native_comments'); ?>

<div id="comments" class="block block--messages">
    <div class="block-outer">
        <?php
        $escape = null;
        echo \XPress::xlink()->templater()->fnWPPageNav(null, $escape, [
            'page' => get_query_var('cpage') ?: 1,
            'perPage' => get_option('comments_per_page'),
            'pages' => get_comment_pages_count(),
            'variantClass' => 'block-outer-main',
            'link' => get_permalink(),
            'pageBit' => 'comment-page-',
            'anchor' => 'comments'
        ]);
        ?>

        <div class="block-outer-opposite">
            <?php
            /* To Forum Discussion Button START */
            $postId = get_post()->ID;
            $threadId = get_post_meta($postId, '_xPressThreadID', true);


            $tags = wp_get_post_terms($post->ID);
            $redirectToWP = false;
            foreach($tags as $tag) {
                if($tag->slug == 'redirect-wp') {
                    $redirectToWP = true;
                    break;
                }
            }

            if ($threadId && !$redirectToWP) {
                /** @var \XF\Entity\Thread $thread */
                $thread = \XPress::xlink()->em()->find('XF:Thread', $threadId);

                if ($thread && $thread->canView()) {
                    $replyCount = $thread ? $thread->reply_count : 0;
                    $threadLink = \XPress::xlink()->buildLink('threads',
                        ['thread_id' => $threadId]);
                    $threadButton = \XPress::xlink()->templater()->button($replyCount ? \XPress::xlink()->phrase('thxpress_to_forum_discussion_x_posts',
                        [
                            'count' => $replyCount
                        ]) : \XPress::xlink()->phrase('thxpress_to_forum_discussion'),
                        ['href' => $threadLink, 'class' => ""]);
                    echo $threadButton;
                }
            }
            /* To Forum Discussion END */
            ?>
        </div>
    </div>

    <?php if (have_comments()): ?>
        <div class="block-body">
            <div class="comment-list js-CommentList">
                <?php wp_list_comments('type=comment&callback=thxpress_format_comment'); ?>
            </div>
        </div>
        <div class="block-outer block-outer--after">
            <?php
            $escape = null;
            echo \XPress::xlink()->templater()->fnWPPageNav(null, $escape, [
                'page' => get_query_var('cpage') ?: 1,
                'perPage' => get_option('comments_per_page'),
                'pages' => get_comment_pages_count(),
                'variantClass' => 'block-outer-main',
                'link' => get_permalink(),
                'pageBit' => 'comment-page-',
                'anchor' => 'comments'
            ]);
            ?>
        </div>
    <?php else: ?>
        <div class="block-body">
            <div class="comment-list js-CommentList">

            </div>
        </div>
    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments"><?php \XPress::xlink()->phrase('thxpress_comments_closed') ?></p>
    <?php endif; ?>
</div>

<?php echo \XPress::xlink()->ad('thxpress_above_native_quick_reply'); ?>

<div class="block message js-QuickReply block-container message message--simple message--quickReply block-topRadiusContent block-bottomRadiusContent"
     id="comment_form_container">
    <div class="message-inner">
        <div class="message-cell message-cell--user">
            <header class="messages-user">
                <div class="message-avatar">
                    <div class="message-avatar-wrapper">
                            <span class="avatar avatar--s">
                                <?php echo get_avatar(get_current_user_id(), 48); ?>
                            </span>
                    </div>
                </div>
            </header>
        </div>
        <div class="message-cell message-cell--main">
            <div class="message-editorWrapper">
                <?php
                $req = get_option('require_name_email');
                $required = $req ? 'required="required"' : '';
                $commenter = wp_get_current_commenter();
                $aria_req = ($req ? " aria-required='true'" : '');
                $author = esc_attr($commenter['comment_author']);
                $email = esc_attr($commenter['comment_author_email']);

                $cancelReplyUrl = esc_html(remove_query_arg('replytocom')) . '#respond';
                $cancelReplyLink = isset($_GET['replytocom']) ? '
                                <a href="' . $cancelReplyUrl . '" class="button button--icon button--icon--cancel">
                                    <span class="button-text">
                                        ' . \XPress::xlink()->phrase('thxpress_cancel_reply') . '
                                    </span>
                                </a>' : '';

                wp_enqueue_script('comment-reply');
                
                comment_form([
                    'title_reply' => '',
                    'title_reply_to' => '',
                    'format' => 'xhtml',
                    'comment_notes_before' => '',
                    'submit_button' => '<button name="%1$s" id="%2$s" class="button--primary button button--icon button--icon--reply %3$s" value="%4$s">
                                                        <span class="button-text">' . \XPress::xlink()->phrase('post_comment') . '</span>
                                                   </button>' . $cancelReplyLink,
                    'cancel_reply_before' => '<div style="display:none">',
                    'cancel_reply_after' => '</div>',
                    'fields' => [
                        'author' => "<p class='comment-form-author'>
                                            <label for='author'>" . \XPress::xlink()->phrase('name:') . "</label>
                                            <input type='text' class='input' {$required} {$aria_req} id='author' name='author' value='{$author}' />
                                         </p>",
                        'email' => "<p class='comment-form-author'>
                                            <label for='author'>" . \XPress::xlink()->phrase('email:') . "</label>
                                            <input type='email' class='input' {$required} {$aria_req} id='email' name='email' value='{$email}' />
                                        </p>"
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<?php echo \XPress::xlink()->ad('thxpress_below_native_comments');