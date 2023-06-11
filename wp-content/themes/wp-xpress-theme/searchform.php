<?php $unique_id = esc_attr(uniqid('search-form-')); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <input class="input" type="search" id="<?php echo $unique_id; ?>" class="search-field"
           placeholder="<?php echo esc_attr(\XPress::xlink()->phrase('thxpress_search')); ?>"
           value="<?php echo get_search_query(); ?>" name="s"/>
    <button type="submit" class="button--cta button button--icon button--icon--search"><span
                class="button-text"><?php echo \XPress::xlink()->phrase('search') ?></span><span
                class="screen-reader-text"><?php echo \XPress::xlink()->phrase('search') ?></span>
    </button>
</form>
