<?php

namespace XPress\Action;

/**
 * Class SavePost
 * @package XPress\Action
 */
class SavePost
{
    /**
     *
     */
    public static function register()
    {
        add_action('save_post', [self::class, 'actionSavePost'], 10, 3);
        add_action('publish_future_post', [self::class, 'actionSavePost'], 10, 3);
    }

    /**
     * @param $postId
     * @param $post
     * @param $update
     * @throws \XF\PrintableException
     */
    public static function actionSavePost($postId, $post = null, $update = false) {
        if(!$post) {
            $post = get_post($postId);
        }

        if (array_key_exists('_xPressSectionContext', $_POST)) {
            $sectionContext = $_POST['_xPressSectionContext'];
            update_post_meta($postId, '_xPressSectionContext', $sectionContext);
        }

        if (!in_array(get_post_type($postId),
                \XPress::getCompatiblePostTypes()) || $post->post_status != 'publish') {
            return;
        }

        $threadLink = \XPress::getThreadLink($post);

        if (array_key_exists('_xpressThreadID', $_POST) && $threadLink) {
            $threadId = $_POST['_xpressThreadID'];

            if ($threadId) {
                $threadLink->content_id = $threadId;
                $threadLink->saveIfChanged();
            } else {
                $threadLink->delete(false);
            }
        }

        if ($update) {
            if (array_key_exists('_xpressThreadID', $_POST) && !$threadLink) {
                $threadId = $_POST['_xpressThreadID'];

                if ($threadId) {
                    $author = @\XPress::getXFUser(get_user_by('ID', $post->post_author)) ?: \XF::visitor();

                    $threadLink = \XPress::xlink()->em()->create('ThemeHouse\XLink:EntityLink');
                    $threadLink->bulkSet([
                        'platform_id' => \XPress::xlink()->platformLink()->platform_id,
                        'content_type' => 'XF:Thread',
                        'content_id' => $threadId,
                        'user_id' => $author->user_id,
                        'remote_entity_type' => 'post',
                        'remote_entity_id' => $postId,
                        'created_on' => 'remote'
                    ]);
                    $threadLink->saveIfChanged();

                    return;
                }
            }
        }

        $options = \XPress::xlink()->app()->options();

        if (array_key_exists('_xpressAuthorComment', $_POST)) {
            $authorComment = $_POST['_xpressAuthorComment'];
            update_post_meta($postId, '_xpressAuthorComment', $authorComment);
        }
        if (array_key_exists('_xpressIncludeNoExcerpt', $_POST)) {
            update_post_meta($postId, '_xpressIncludeNoExcerpt', 1);
        } else {
            update_post_meta($postId, '_xpressIncludeNoExcerpt', 0);
        }

        $postTitle = get_the_title($post);
        $postUrl = get_permalink($post);
        $postContent = $post->post_content;
        $postContent = apply_filters('the_content', $postContent);

        $categories = wp_get_post_categories($postId);

        $forumId = -1;
        if (array_key_exists('_xpressForumID', $_POST)) {
            $forumId = $_POST['_xpressForumID'];
        }
        if ($forumId == -1) {
            $forumId = 0;
            foreach ($categories as $category) {
                $categoryData = get_option("taxonomy_$category");
                $forumId = empty($categoryData['_xpressForumID']) ? 0 : $categoryData['_xpressForumID'];
                if ($forumId) {
                    break;
                }
            }
        }

        $prefixId = 0;
        if (array_key_exists('_xpressPrefixID', $_POST)) {
            $prefixId = $_POST['_xpressPrefixID'];
        }

        $user = null;
        if (array_key_exists('_xpressAuthor', $_POST)) {
            /** @var \XF\Repository\User $userRepo */
            $userRepo = \XPress::xlink()->repository('XF:User');

            $name = $_POST['_xpressAuthor'];
            if (ctype_digit($name)) {
                $user = \XPress::xlink()->em()->find('XF:User', $name);
            } else {
                $user = $userRepo->getUserByNameOrEmail($_POST['_xpressAuthor']);
            }
        }
        if (!$user) {
            $user = \XPress::getXFUser(wp_get_current_user());
        }

        $postData = [
            'title' => html_entity_decode($postTitle, ENT_COMPAT, 'utf-8'),
            'url' => $postUrl,
            'message' => html_entity_decode($postContent, ENT_COMPAT, 'utf-8'),
            'comment' => get_post_meta($postId, '_xpressAuthorComment', true),
            'noExcerpt' => get_post_meta($postId, '_xpressIncludeNoExcerpt', true),
            'user_id' => $user ? $user->user_id : 0,
            'forum_id' => $forumId,
            'prefix_id' => $prefixId
        ];

        /** @var \ThemeHouse\XLink\Entity\PlatformLink $platformLink */
        /** @noinspection PhpUndefinedMethodInspection */
        $platformLink = \XPress::xlink()->platformLink();

        $thread = false;
        if ($threadLink) {
            $thread = $threadLink->XFEntity;
        }

        if (!$thread) {
            if (isset($threadId) && $threadId) {
                $thread = \XPress::xlink()->em()->find('XF:Thread', $threadId);
            }

            if (!$thread) {
                /** @var \ThemeHouse\XPress\Service\WordPress\CreateThread $service */
                $service = \XPress::xlink()->service('ThemeHouse\XPress:WordPress\CreateThread', $postData,
                    $options);
                /** @var \XF\Entity\Thread $thread */
                $thread = $service->save();
            } else {
                /** @var \ThemeHouse\XPress\Service\WordPress\PostThread $service */
                $service = \XPress::xlink()->service('ThemeHouse\XPress:WordPress\PostThread', $thread, $postData,
                    $options);
                /** @var \XF\Entity\Thread $thread */
                $thread = $service->save();

                /** @noinspection PhpUndefinedFieldInspection */
                $threadLink = $thread->XLinkEntityLink;
            }

            if ($thread) {
                if (empty($threadLink)) {
                    /** @var \ThemeHouse\XLink\Entity\EntityLink $threadLink */
                    $threadLink = \XPress::xlink()->em()->create('ThemeHouse\XLink:EntityLink');
                    $threadLink->created_on = 'remote';
                }

                $threadLink->bulkSet([
                    'platform_id' => $platformLink->platform_id,
                    'content_type' => 'XF:Thread',
                    'content_id' => $thread->thread_id,
                    'user_id' => \XF::visitor()->user_id,
                    'remote_entity_type' => 'post',
                    'remote_entity_id' => $postId
                ]);
                $threadLink->saveIfChanged();
            }
        } else {
            if ($thread) {
                if (!get_post_meta($postId, '_xPressNoFirstPostSync',
                        true) && !($threadLink && $threadLink->created_on === 'xf')) {
                    /** @var \ThemeHouse\XPress\Service\WordPress\UpdateThread $service */
                    $service = \XPress::xlink()->service('ThemeHouse\XPress:WordPress\UpdateThread', $thread,
                        $postData,
                        $options);
                    $service->save();
                }
            }
        }
    }
}