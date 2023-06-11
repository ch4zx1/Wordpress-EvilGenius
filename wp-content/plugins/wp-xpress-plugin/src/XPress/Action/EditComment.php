<?php

namespace XPress\Action;

use ThemeHouse\XPress\XF\Entity\Post;

/**
 * Class EditComment
 * @package XPress\Action
 */
class EditComment
{
    /**
     *
     */
    public static function register()
    {
        add_action('edit_comment', [self::class, 'actionEditComment']);
        add_action('comment_post', [self::class, 'actionEditComment']);

        add_action('comment_unapproved_to_approved', [self::class, 'actionEditComment']);
        add_action('comment_unapproved_to_trash', [self::class, 'actionEditComment']);
        add_action('comment_unapproved_to_spam', [self::class, 'actionEditComment']);

        add_action('comment_spam_to_approved', [self::class, 'actionEditComment']);
        add_action('comment_spam_to_unapproved', [self::class, 'actionEditComment']);
        add_action('comment_spam_to_trash', [self::class, 'actionEditComment']);

        add_action('comment_approved_to_unapproved', [self::class, 'actionEditComment']);
        add_action('comment_approved_to_spam', [self::class, 'actionEditComment']);
        add_action('comment_approved_to_trash', [self::class, 'actionEditComment']);

        add_action('comment_trash_to_approved', [self::class, 'actionEditComment']);
        add_action('comment_trash_to_unapproved', [self::class, 'actionEditComment']);
        add_action('comment_trash_to_spam', [self::class, 'actionEditComment']);
    }

    /**
     *
     * @param $comment
     * @throws \XF\PrintableException
     */
    public static function actionEditComment($comment)
    {
        $platformLink = \XPress::xlink()->platformLink();

        if (\XPress::$xpressUpdateCycle) {
            return;
        }
        \XPress::$xpressUpdateCycle = true;

        if (!is_object($comment)) {
            $comment = get_comment($comment);
            $parentId = $comment->comment_parent ? $comment->comment_parent : 0;
        }

        $thread = \XPress::getThread($comment->comment_post_ID);
        if (!$thread) {
            return;
        }

        /** @var \ThemeHouse\XLink\Entity\EntityLink $commentLink */
        $commentLink = \XPress::getPostLink($comment);

        if (!$commentLink) {
            $author = get_user_by('id', $comment->user_id);
            $user = \XPress::getXFUser($author);
            if (!$user) {
                /** @var \XF\Repository\User $userRepo */
                $userRepo = \XPress::xlink()->repository('XF:User');
                $user = $userRepo->getUserByNameOrEmail($comment->comment_author_email);
            }

            /** @var \XF\Entity\Post $post */
            $post = \XPress::xlink()->em()->create('XF:Post');
            Post::$xpressUpdateCycle = true;
            $post->thread_id = $thread->thread_id;

            if ($user) {
                $post->user_id = $user->user_id;
                $post->username = $user->username;
            } else {
                $post->user_id = 0;
                $post->username = $comment->comment_author;
            }
            $post->position = $thread->reply_count;

            $commentLink = \XPress::xlink()->em()->create('ThemeHouse\XLink:EntityLink');
            $commentLink->created_on = 'remote';
        } else {
            $post = $commentLink->XFEntity;
        }

        if (!$post) {
            return;
        }

        $post->message = $comment->comment_content;

        switch ($comment->comment_approved) {
            case '0':
            case 'hold':
            case 'spam':
                $post->message_state = 'moderated';
                break;

            case 'trash':
                $post->softDelete();
                return;

            case '1':
            case 'approve':
            default:
                $post->message_state = 'visible';
                break;
        }

        $addonCache = \XPress::xlink()->xfApp()->container('addon.cache');
        if (isset($addonCache['ThemeHouse/PostComments']) && isset($parentId) && $parentId) {
            $parent = \XPress::getPost($parentId);

            /** @var \ThemeHouse\PostComments\XF\Entity\Post $post */
            /** @var \ThemeHouse\PostComments\XF\Entity\Post $parent */
            $post->parent_post_id = $parent->post_id;
            $post->root_post_id = $parent->root_post_id ? $parent->root_post_id : $parent->post_id;
        }

        $post->save();

        $commentLink->bulkSet([
            'platform_id' => $platformLink->platform_id,
            'remote_entity_id' => $comment->comment_ID,
            'remote_entity_type' => 'comment',
            'content_type' => 'XF:Post',
            'content_id' => $post->post_id
        ]);
        $commentLink->saveIfChanged();
    }
}