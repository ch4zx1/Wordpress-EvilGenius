<div class="wrap">
    <h1>XPress Settings</h1>

	<?php if (\XPress::app()->isXFConnectionBroken()): ?>
		<div class="notice notice-error">It appears your connection with XenForo is broken. Please verify the settings below</div>
	<?php elseif (!\XPress::app()->isXFInitialized()): ?>
		<div class="notice notice-error">More settings will become available once XenForo has been initialized.</div>
	<?php endif; ?>

    <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields( 'xpress' );
        do_settings_sections( 'xpress' );

        submit_button();
        ?>
    </form>
</div>