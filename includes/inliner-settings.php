<?php
add_action( 'admin_init', 'inline_settings_init' );


function inline_settings_init(  ) { 
if(function_exists( 'wp_enqueue_media' )){
    wp_enqueue_media();
}else{
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
}
	register_setting( 'inlinepluginPage', 'inline_settings' );

	add_settings_section(
		'inline_pluginPage_section', 
		__( 'Get Inliner ready', 'inline' ), 
		'inline_settings_section_callback', 
		'inlinepluginPage'
	);

	add_settings_field( 
		'inline_text_token', 
		__( 'Telegram Bot Token', 'inline' ), 
		'inline_text_token_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_post_type', 
		__( 'Include Post Types', 'inline' ), 
		'inline_post_type_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_count', 
		__( 'Count Selected Results?', 'inline' ), 
		'inline_count_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	
	add_settings_field( 
		'inline_dimage', 
		__( 'Default Image', 'inline' ), 
		'inline_dimage_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_template', 
		__( 'Chosen Result Template', 'inline' ), 
		'inline_template_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_noresult', 
		__( 'No Result Title', 'inline' ), 
		'inline_noresult_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_noresultdes', 
		__( 'No Result Description', 'inline' ), 
		'inline_noresultdesc_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_noresultmessage', 
		__( 'No Result Message', 'inline' ), 
		'inline_noresultmessage_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);
	add_settings_field( 
		'inline_directanswer', 
		__( 'Answer to Direct Message', 'inline' ), 
		'inline_directanswer_render', 
		'inlinepluginPage', 
		'inline_pluginPage_section' 
	);



}

function inline_text_token_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<input  style='direction:ltr;' type='text' name='inline_settings[inline_text_token]' value='<?php echo $options['inline_text_token']; ?>'>
	<?php

}
function inline_post_type_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<input style='direction:ltr;' type='text' name='inline_settings[inline_post_type]' value='<?php echo $options['inline_post_type']; ?>'>
	<p><?php echo __('Separate post types with ",". leave emty to include posts only','inline'); ?></p>
	<p><?php echo __('ex: post,product,page','inline'); ?></p>
	<?php

}
function inline_template_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<textarea cols='70' rows='8' name='inline_settings[inline_template]' ><?php 
	if (!$options['inline_template'] == '') {
	echo $options['inline_template']; 
	} else {
		echo '%TITLE%
%CONTENT%
%SHORTLINK%';
	}
	?>
	</textarea>
	<?php
	echo '<h3>'.__('Instructions','inline').'</h3>';
	echo '<ul>';
	echo '<li>%TITLE% <span>'.__('Post Title','inline').'</span></li>';
	echo '<li>%Excerpt% <span>'.__('Post Excerpt','inline').'</span></li>';
	echo '<li>%LINK% <span>'.__('Post Url','inline').'</span></li>';
	echo '<li>%SHORTLINK% <span>'.__('Post Short Link','inline').'</span></li>';
	echo '<li>%CONTENT% <span>'.__('Post Content','inline').'</span></li>';
	echo '</ul>';

}
function inline_noresult_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<input type='text' name='inline_settings[inline_noresult]' value='<?php echo $options['inline_noresult']; ?>'>
	<?php

}
function inline_noresultdesc_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<textarea cols='70' rows='5' name='inline_settings[inline_noresultdesc]'><?php echo $options['inline_noresultdesc']; ?></textarea>
	<?php

}
function inline_noresultmessage_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<textarea cols='70' rows='5' name='inline_settings[inline_noresultmessage]'><?php echo $options['inline_noresultmessage']; ?></textarea>
	<?php

}
function inline_directanswer_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<textarea cols='70' rows='5' name='inline_settings[inline_directanswer]'><?php echo $options['inline_directanswer']; ?></textarea>
	<?php

}
function inline_dimage_render(  ) { 
	$options = get_option( 'inline_settings' );
	?>
	<input class="header_logo_url" type="text" name="inline_settings[dimage]" size="60" value="<?php echo $options['dimage']; ?>">
	<a href="#" class="header_logo_upload button button-primary"><?php _e('Upload','inline'); ?></a><br />
	<?php
	if (!$options['dimage'] == '') {
		echo '<img class="header_logo" src="'.$options['dimage'].'" width="100"/>';
		echo '<p>'.__('Will Be Shown on Search Results','inline').'</p>';
	}
}
function inline_settings_section_callback(  ) { 

	echo __( 'Before updating this settings please read the <a target="_blank" href="http://websima.com/telegram-inliner/">Inliner Documents</a> carefully', 'inline' );

}
function inline_settings_template_callback(  ) { 

	echo __( '', 'inline' );

}
function inline_count_render(  ) { 

	$options = get_option( 'inline_settings' );
	?>
	<select name='inline_settings[inline_count]'>
		<option value='yes' <?php selected( $options['inline_count'], 'yes' ); ?>><?php printf(__( 'Yes', 'inline' )); ?></option>
		<option value='no' <?php selected( $options['inline_count'], 'no' ); ?>><?php printf(__( 'No', 'inline' )); ?></option>
	</select>

<?php

}

function inliner_options_page(  ) { 

	?>
     
	<form action='options.php' method='post'>
		<h2><?php printf(__( 'Inliner Settings', 'inline' )); ?></h2>
		<?php
		settings_fields( 'inlinepluginPage' );
		do_settings_sections( 'inlinepluginPage' );

		submit_button();
		?>
		
	</form>
	
	<script>
    jQuery(document).ready(function($) {
        $('.header_logo_upload').click(function(e) {
            e.preventDefault();

            var custom_uploader = wp.media({
                multiple: false  // Set this to true to allow multiple files to be selected
            })
            .on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('.header_logo').attr('src', attachment.url);
                $('.header_logo_url').val(attachment.url);

            })
            .open();
        });
    });
</script>
	<?php

}

?>