<?php

require_once THXPRESS_PLUGIN_DIR . '/src/XLink.php';

if (!class_exists('\XPress')) {

    /** @noinspection PhpUndefinedClassInspection */
    class XPress
    {
        protected static $time, $rootDirectory, $sourceDirectory, $autoLoader;

        /**
         * @var array
         */
        protected static $threadData = [];

        /**
         * @var array
         */
        protected static $widgets = [];

        /**
         * @param int $code
         */
        public static function setResponseCode($code = 200)
        {
            self::xlink()->xfApp()->setResponseCode($code);
        }

        /**
         * @return array
         */
        public static function getCompatiblePostTypes()
        {
            if (defined('XPRESS_COMPATIBLE_POST_TYPES')) {
                if (is_array(constant('XPRESS_COMPATIBLE_POST_TYPES'))) {
                    return constant('XPRESS_COMPATIBLE_POST_TYPES');
                } else {
                    return @unserialize(constant('XPRESS_COMPATIBLE_POST_TYPES')) ?: ['post'];
                }
            }

            return ['post'];
        }

        /**
         * @var XLink
         */
        protected static $xlink;

        /**
         * @return XLink
         */
        public static function xlink()
        {
            return self::$xlink;
        }

        /**
         * @param $rootDirectory
         * @throws Exception
         */
        public static function start($rootDirectory)
        {
            self::$xlink = $xlink = new \XLink($rootDirectory, [
                'rootPath' => get_option('xpress_xfpath'),
                'baseUrl' => get_option('xpress_xfurl')
            ]);
            self::initializeRegistryValues();

            /* Thanks WordPress 5.0 for again not being able to handle actual debugging */
            // TODO: Continue to monitor until WordPress is fixed
            if (is_admin() && self::xlink()->app()->isXFInitialized()) {
                \XF::$debugMode = false;
            }

            self::registerGlobalHooks();
        }

        /**
         *
         * @throws Exception
         */
        protected static function registerGlobalHooks()
        {
            \XPress\Action\RestApiInit::register();

            if (!self::xlink()->app()->isXFInitialized()) {
                return;
            }

            $toRegister = [
                'Action' => [
                    'init',
                    'save_post',
                    'before_post_delete',
                    'post_updated',
                    'updated_post_meta',
                    'show_user_profile',
                    'wp_loaded',
                    'admin_enqueue_scripts',
                    'wp_logout',
                    'edit_comment',
                    'deleted_post',
                    'edit_term',
                    'edited_category',
                    'add_meta_boxes',
                    'category_edit_form_fields',
                    'admin_menu',
                    'delete_user',
                    'widgets_init'
                ],
                'Filter' => [
                    'logout_url',
                    'authenticate',
                    'the_content',
                    'single_template',
                    'get_the_tags',
                    'get_avatar',
                    'get_the_author_description',
                    'comments_template',
                    'comment_text',
                    'duplicate_comment_id'
                ],
                'Shortcode' => [
                    'XFAttach',
                    'XFSpoiler',
                    'XFCode',
                    'XFQuote',
                    'thxpress_comment_count',
                    'thxpress_comment_url'
                ]
            ];

            $toRegister = apply_filters('xpress_registered_hooks', $toRegister);

            foreach ($toRegister as $category => $items) {
                $category = ucfirst(strtolower($category));
                foreach ($items as $item) {
                    $item = str_replace('_', '', ucwords($item, '_'));
                    $class = '\\XPress\\' . $category . '\\' . $item;
                    $class = \XPress::xlink()->xfApp()->extendClass($class);

                    if (class_exists($class) && method_exists($class, 'register')) {
                        /**
                         * @var Object $class
                         */
                        $class::register();
                    }
                }
            }
        }

        /**
         * @return \XLink\App
         */
        public static function app()
        {
            return self::xlink()->app();
        }

        /**
         * @return \XLink\Registry
         */
        public static function registry()
        {
            return self::xlink()->registry();
        }

        /**
         *
         */
        public static function initializeRegistryValues()
        {
            $options = self::xlink()->options();
            $registry = self::xlink()->registry();

            $registry->bulkSet([
                'config' => [
                    'disk_path' => rtrim(ABSPATH, '/')
                ],
                'xpress' => $options
            ]);

            $linkOptions = self::xlink()->platformLink()->options;
            self::xlink()->app()->setOption('xlink', $linkOptions);
            $registry->set('xlink', $linkOptions);
        }

        /**
         *
         */
        public static function updateRegistryValues()
        {
            if (!self::xlink()->app()->isXFInitialized()) {
                return;
            }

            $registryValues = self::xlink()->registry()->get('xpress');

            if (empty($registryValues['title'])) {
                $registryValues['title'] = wp_title('', false);
            }

            self::xlink()->registry()->set('xpress', $registryValues);
        }

        /**
         * @param $id
         * @return bool
         */
        public static function hasDiscussionUrl($id)
        {
            $data = self::getThreadData($id);

            if (!$data || empty($data['comment_url'])) {
                return false;
            }

            return true;
        }

        /**
         * @param $id
         * @return int
         */
        public static function commentCount($id)
        {
            $data = self::getThreadData($id);

            if (!$data) {
                return 0;
            }

            return apply_filters('xpress_thread_reply_count', $data['reply_count'], $id);
        }

        /**
         * @param $id
         * @return string
         */
        public static function threadUrl($id)
        {
            $data = self::getThreadData($id);

            if (!$data) {
                return '#';
            }

            return apply_filters('xpress_thread_url', $data['comment_url'], $id);
        }

        /**
         * @param $post
         * @return null|\ThemeHouse\XLink\Entity\EntityLink
         * @throws \XF\PrintableException
         */
        public static function getLegacyThreadLink($post)
        {
            if ($post instanceof WP_Post) {
                $postId = $post->ID;
            } else {
                if (is_numeric($post)) {
                    $postId = (int)$post;
                } else {
                    return null;
                }
            }

            $threadId = get_post_meta($postId, '_xPressThreadID', true);
            if (!$threadId) {
                $threadId = get_post_meta($postId, 'smf_topic_id', true);
            }

            if (!$threadId) {
                return null;
            }

            /** @var \ThemeHouse\XLink\Entity\PlatformLink $platformLink */
            $platformLink = self::xlink()->platformLink();
            /** @var \ThemeHouse\XLink\Entity\EntityLink $threadLink */
            $threadLink = self::xlink()->em()->create('ThemeHouse\XLink:EntityLink');
            $threadLink->bulkSet([
                'platform_id' => $platformLink->platform_id,
                'content_type' => 'XF:Thread',
                'content_id' => $threadId,
                'user_id' => \XF::visitor()->user_id,
                'remote_entity_type' => 'post',
                'remote_entity_id' => $postId
            ]);
            /** @noinspection PhpUnhandledExceptionInspection */
            $threadLink->save();

            delete_post_meta($postId, 'smf_topic_id');
            delete_post_meta($postId, '_xPressThreadID');

            return $threadLink;
        }

        /**
         * @param $post
         * @return null|\ThemeHouse\XLink\Entity\EntityLink|\XF\Mvc\Entity\Entity
         */
        public static function getThreadLink($post)
        {
            if ($post instanceof WP_Post) {
                $postId = $post->ID;
            } else {
                if (is_numeric($post)) {
                    $postId = (int)$post;
                } else {
                    return apply_filters('xpress_thread_link', null, $post);
                }
            }

            if (!self::xlink()->app()->isXFInitialized()) {
                return apply_filters('xpress_thread_link', null, $post);
            }

            /** @noinspection PhpUndefinedMethodInspection */
            $platformLink = self::xlink()->platformLink();
            $threadLink = self::xlink()->em()->find('ThemeHouse\XLink:EntityLink', [
                $platformLink->platform_id,
                $postId,
                'post'
            ]);

            if (!$threadLink) {
                return apply_filters('xpress_thread_link', self::getLegacyThreadLink($postId), $post);
            }

            return apply_filters('xpress_thread_link', $threadLink, $post);
        }

        /**
         * @param $post
         * @return null|\XF\Entity\Thread
         */
        public static function getThread($post)
        {
            $threadLink = self::getThreadLink($post);

            if (!$threadLink) {
                return null;
            }

            /** @var \ThemeHouse\XLink\Entity\EntityLink $threadLink */
            /** @noinspection PhpIncompatibleReturnTypeInspection */
            return $threadLink->XFEntity;
        }

        /**
         * @param $comment
         * @return null|\XF\Mvc\Entity\Entity
         */
        public static function getPostLink($comment)
        {
            if ($comment instanceof WP_Comment) {
                /** @var WP_Comment $comment */
                $commentId = $comment->comment_ID;
            } else {
                if (is_numeric($comment)) {
                    $commentId = (int)$comment;
                } else {
                    return apply_filters('xpress_post_link', null, $comment);
                }
            }

            if (!self::app()->isXFInitialized()) {
                return apply_filters('xpress_post_link', null, $comment);
            }

            /** @noinspection PhpUndefinedMethodInspection */
            $platformLink = self::xlink()->platformLink();
            $commentLink = self::xlink()->em()->find('ThemeHouse\XLink:EntityLink', [
                $platformLink->platform_id,
                $commentId,
                'comment'
            ]);

            return apply_filters('xpress_post_link', $commentLink);
        }

        /**
         * @param $comment
         * @return null|\XF\Mvc\Entity\Entity
         */
        public static function getPost($comment)
        {
            /** @var \ThemeHouse\XLink\Entity\EntityLink $commentLink */
            $commentLink = self::getPostLink($comment);

            if (!$commentLink) {
                return null;
            }

            return $commentLink->XFEntity;
        }

        /**
         * @param $id
         * @return bool|mixed
         */
        protected static function getThreadData($id)
        {
            if ($smfLegacyThreadId = get_post_meta($id, 'smf_topic_id', true)) {
                if (!add_post_meta($id, '_xPressThreadID', $smfLegacyThreadId, true)) {
                    update_post_meta($id, '_xPressThreadID', $smfLegacyThreadId);
                }
                delete_post_meta($id, 'smf_topic_id');
            }

            $threadId = get_post_meta($id, '_xPressThreadID', true);
            if (!$threadId) {
                return false;
            }

            if (!isset(self::$threadData[$id])) {
                self::$threadData[$id] = get_post_meta($id, '_xPressThreadData', true);
                if (empty(self::$threadData[$id]) || self::$threadData[$id]['timestamp'] < time() - 60) {
                    self::$threadData[$id] = self::updateThreadData($id, $threadId);
                }
            }

            return self::$threadData[$id];
        }

        /**
         * @param $id
         * @param $threadId
         * @return array|bool
         */
        protected static function updateThreadData($id, $threadId)
        {
            /** @var \XF\Entity\Thread $thread */
            $thread = self::xlink()->em()->find('XF:Thread', $threadId);
            if (!$thread) {
                delete_post_meta($id, '_xPressThreadID');
                return false;
            }

            $data = [
                'timestamp' => time(),
                'reply_count' => $thread->reply_count,
                'comment_url' => self::xlink()->buildLink('threads', $thread)
            ];

            if (!add_post_meta($id, '_xPressThreadData', $data, true)) {
                update_post_meta($id, '_xPressThreadData', $data);
            }

            return $data;
        }

        /**
         * @param $categoryId
         * @return null|\XF\Mvc\Entity\Entity
         * @throws \XF\PrintableException
         */
        public static function getCategoryLink($categoryId)
        {
            if (!self::xlink()->platformLink()) {
                return apply_filters('xpress_category_link', null, $categoryId);
            }

            $categoryLink = self::xlink()->em()->find('ThemeHouse\XLink:EntityLink', [
                self::xlink()->platformLink()->platform_id,
                $categoryId,
                'category'
            ]);

            if (!$categoryLink) {
                $term_meta = get_option("taxonomy_{$categoryId}");
                if (!$categoryLink) {
                    $categoryLink = self::xlink()->em()->create('ThemeHouse\XLink:EntityLink');
                    $categoryLink->bulkSet([
                        'platform_id' => self::xlink()->platformLink()->platform_id,
                        'remote_entity_id' => $categoryId,
                        'remote_entity_type' => 'category',
                        'created_on' => 'remote'
                    ]);
                }

                $categoryLink->bulkSet([
                    'content_type' => 'XF:Node',
                    'content_id' => isset($term_meta['_xPressXFForumID']) ? $term_meta['_xPressXFForumID'] : 0,
                    'extra' => [
                        'category' => isset($term_meta['_xPressXFCategoryID']) ? $term_meta['_xPressXFCategoryID'] : 0,
                        'bg_color' => isset($term_meta['_xPressCatBackgroundColor']) ? $term_meta['_xPressCatBackgroundColor'] : '#bada55',
                        'text_color' => isset($term_meta['_xPressCatTextColor']) ? $term_meta['_xPressCatTextColor'] : '#ffffff'
                    ]
                ]);

                $categoryLink->save();
            }

            return apply_filters('xpress_category_link', $categoryLink, $categoryId);
        }

        /**
         * @var bool
         */
        public static $xpressUpdateCycle = false;

        public static function setWidgets(array $widgets)
        {
            self::$widgets = $widgets;
        }

        /**
         * @return array
         */
        public static function getWidgets()
        {
            return self::$widgets;
        }

        /**
         * @param $widgetId
         * @return mixed|null
         */
        public static function getWidget($widgetId)
        {
            if (isset(self::$widgets[$widgetId])) {
                return self::$widgets[$widgetId];
            }

            return null;
        }

        /**
         * @param $propertyId
         * @return mixed|string
         */
        public static function getStyleProperty($propertyId)
        {
            return apply_filters('xpress_style_property', self::xlink()->styleProperty($propertyId), $propertyId);
        }

        /**
         * @param $name
         * @param null $origName
         * @return string
         */
        public static function getEditorInstance($name, $origName = null)
        {
            return "<input type='hidden' name='{$origName}' value='x' />" . self::xlink()->templater()->formEditor(
                    [
                        'name' => $name,
                        'htmlName' => "{$name}_html"
                    ]);
        }

        /**
         * @param $bbCode
         * @return string
         */
        public static function renderBbCode($bbCode)
        {
            return apply_filters('xpress_render_bb_code', self::xlink()->bbCode()->render($bbCode, 'html', null, null),
                $bbCode);
        }

        /**
         * @param $html
         * @param int $htmlMaxLength
         * @return mixed
         */
        public static function convertToBbCode($html, $htmlMaxLength = -1)
        {
            if ($htmlMaxLength < 0) {
                $htmlMaxLength = 4 * self::xlink()->xfApp()->options()->messageMaxLength;
                // quadruple the limit as HTML can be a lot more verbose
            }

            if ($htmlMaxLength && utf8_strlen($html) > $htmlMaxLength) {
                throw self::xlink()->phrasedException('submitted_message_is_too_long_to_be_processed');
            }

            $bbCode = \XF\Html\Renderer\BbCode::renderFromHtml($html);
            return apply_filters('xpress_html_to_bb_code', \XF::cleanString($bbCode), $html, $htmlMaxLength);
        }

        /**
         * @param $name
         * @return mixed
         * @throws Exception
         */
        public static function getEditorContent($name)
        {
            $request = self::xlink()->request();
            $content = $request->filter("{$name}_html", 'str') ?: $request->filter($name, 'str');

            return apply_filters('xpress_editor_content', self::convertToBbCode($content), $content);
        }

        /**
         * @return bool|false|null|WP_User
         */
        public static function authenticateFromXF()
        {
            if (self::xlink()->app()->hasLoginLevel('sso') && self::xlink()->visitor()->user_id) {
                $user = self::getOrCreateWPUserForXFUser(self::xlink()->visitor());

                if ($user) {
                    wp_set_auth_cookie($user->ID);
                    return $user;
                }
            }

            return false;
        }

        /**
         * @param $xfUser
         * @param null $password
         * @return false|null|WP_User
         * @throws \XF\PrintableException
         */
        public static function getOrCreateWPUserForXFUser($xfUser, $password = null)
        {
            /** @var \XF\Entity\User $xfUser */
            if ($xfUser->user_state !== 'valid') {
                return null;
            }

            /** --- Linked User --- */

            /** @var \ThemeHouse\XLink\Entity\AccountLink $wpUser */
            $accountLink = self::xlink()->accountLink(null, $xfUser->user_id);

            if ($accountLink) {
                $user = get_user_by('ID', $accountLink->remote_user_id);

                if (!$user) {
                    $accountLink->delete(false);
                } else {
                    return $user;
                }
            }

            /** --- Link User by Email --- */

            $user = get_user_by('email', $xfUser->email);
            if ($user) {
                $accountLink = self::xlink()->em()->create('ThemeHouse\XLink:AccountLink');
                $accountLink->bulkSet([
                    'platform_id' => self::xlink()->platformLink()->platform_id,
                    'user_id' => $xfUser->user_id,
                    'remote_user_id' => $user->ID
                ]);
                $accountLink->save();
                return $user;
            }

            /** --- Generate new User --- */

            $platformLink = self::xlink()->platformLink();
            if ($platformLink && $platformLink->create_remote_accounts) {
                /** @var \XF\Entity\User $xfUser */
                $userData = [
                    'user_email' => $xfUser->email,
                    'user_login' => $xfUser->email,
                    'user_pass' => $password ?: '',
                    'user_nicename' => $xfUser->username,
                    'display_name' => $xfUser->username,
                ];
                $newUserId = wp_insert_user($userData);

                if ($newUserId instanceof WP_Error) {
                    if (!empty($newUserId->errors['existing_user_login'])) {
                        /** @var \XF\Entity\User $xfUser */
                        $userData = [
                            'user_email' => $xfUser->email,
                            'user_login' => $xfUser->email . '-' . $xfUser->user_id,
                            'user_pass' => $password ?: '',
                            'user_nicename' => $xfUser->username . '-' . $xfUser->user_id,
                            'display_name' => $xfUser->username . '-' . $xfUser->user_id,
                        ];
                        $newUserId = wp_insert_user($userData);
                    }

                    if ($newUserId instanceof WP_Error) {
                        return null;
                    }
                }

                $accountLink = self::xlink()->em()->getFinder('ThemeHouse\XLink:AccountLink')
                    ->where('platform_id', '=', self::xlink()->platformLink()->platform_id)
                    ->where('remote_user_id', '=', $newUserId)
                    ->fetchOne();

                if($accountLink) {
                    $accountLink->user_id = $xfUser->user_id;
                }
                else {
                    $accountLink = self::xlink()->em()->create('ThemeHouse\XLink:AccountLink');
                    $accountLink->bulkSet([
                        'platform_id' => self::xlink()->platformLink()->platform_id,
                        'user_id' => $xfUser->user_id,
                        'remote_user_id' => $newUserId
                    ]);
                    $accountLink->save();
                }

                $user = new WP_User($newUserId);

                return $user;
            }

            return null;
        }

        /**
         * @var array
         */
        protected static $xfUserCache = [];

        /**
         * @param null $user
         * @return mixed|null
         */
        public static function getXFUser($user = null)
        {
            if ($user === null) {
                $user = wp_get_current_user();
            }

            if (!($user instanceof WP_User)) {
                return null;
            }

            if (!isset(self::$xfUserCache[$user->ID])) {
                /** @var \ThemeHouse\XLink\Entity\AccountLink $accountLink */
                $accountLink = self::xlink()->accountLink($user->ID);
                self::$xfUserCache[$user->ID] = apply_filters('xpress_get_xf_user',
                    $accountLink ? $accountLink->User : null, $user);
            }

            return self::$xfUserCache[$user->ID];
        }

        /**
         * @var array
         */
        protected static $wpUserCache = [];

        /**
         * @param \XF\Entity\User $user
         * @return mixed
         */
        public static function getWPUser(\XF\Entity\User $user)
        {
            if (!isset(self::$xfUserCache[$user->user_id])) {
                self::$xfUserCache[$user->user_id] = apply_filters('xpress_get_wp_user',
                    self::xlink()->em()->getFinder('ThemeHouse\XLink:AccountLink')
                        ->where('user_id', '=', $user->user_id)
                        ->where('platform_id', '=', self::xlink()->platformLink()->platform_id)
                        ->with('User', true)
                        ->fetchOne()->User, $user);
            }

            return self::$xfUserCache[$user->user_id];
        }
    }
}