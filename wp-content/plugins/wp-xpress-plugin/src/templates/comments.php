<?php

$post = get_post();
$thread = \XPress::getThread($post);

if ($thread) {
    /* Create proxy controller with mimicked request */
    /** @var \XF\Pub\Controller\Thread $controller */
    $controller = \XF::app()->controller('XF:Thread', new \XF\Http\Request(new \XF\InputFilterer()));
    $controller->setResponseType('raw');

    /* Create mimick parameterbag */
    $parameterBag = new \XF\Mvc\ParameterBag(['thread_id' => $thread->thread_id]);
    try {
        /* Get controller reply */
        $controllerReply = $controller->actionIndex($parameterBag);

        while ($controllerReply instanceof \XF\Mvc\Reply\Reroute) {
            $match = $controllerReply->getMatch();
            $controller = \XF::app()->controller($match->getController(),
                new \XF\Http\Request(new \XF\InputFilterer()));
            $controller->setResponseType('raw');
            $controllerReply = $controller->{"action" . ucfirst($match->getAction())}($match->getParameterBag());
        }

        /* Render thread_view template */
        $template = "public:" . $controllerReply->getTemplateName();
        $meta = get_post_meta($post->ID, '_xpressAuthorComment', true);
        $params = array_merge($controllerReply->getParams(),
            ['xpressView' => true, 'xpressNoFirstPost' => !$meta || !strlen($meta)]);
        \XF::app()->renderer('public')->renderViewObject($controllerReply->getViewClass(), $template, $params);

        \XF::app()->preRender($controllerReply, $controllerReply->getResponseType());
        $templater = \XF::app()->templater();
        $result = $templater->renderTemplate($template, $params);

        /* Reset breadcrumbs */
        $templater->breadcrumbs([]);
        $templater->pageParams = ['noTopic' => true, 'xpressView' => true];

        echo '<span id="comments"></span>';
        echo $result;
    }
    catch (\XF\Mvc\Reply\Exception $e) {

    }
    catch (\Exception $e) {
        \XF::app()->logException($e);
        echo '<div class="blockMessage">' . \XF::phrase('thxpress_an_error_occured') . '</div>';
    }
}