//File Upload vars
var file_frame;
var wp_media_post_id = (wp.media == undefined) ? null : wp.media.model.settings.post.id; // Store the old id
var set_to_post_id = 372; // Set this (from experminentation has to be a valid ID)
var jButton = null;

jQuery(document).ready(function(){
    jQuery('.upload_image_button').live('click', function( event ){
        event.preventDefault();
        jButton = jQuery(this);
        
        

        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            // Set the post ID to what we want
            file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
            // Open frame
            file_frame.open();
            return;
        } else {
            // Set the wp.media post id so the uploader grabs the ID we want when initialised
            wp.media.model.settings.post.id = set_to_post_id;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: jQuery( this ).data( 'uploader_title' ),
            button: {
                text: jQuery( this ).data( 'uploader_button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {
            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            // Do something with attachment.id and/or attachment.url here
            var data_apercu = jButton.attr('data-pic-to');
            var id_field = data_apercu.substr(7)
            console.log(id_field);

            

            var data = {
                'action': 'file_'+id_field,
                'attachment_id': attachment.id
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            jQuery.post(ajaxurl, data, function(response) {
                    
                    var object = JSON.parse(response);
                  jQuery('#'+data_apercu).html('<img src="'+object.url+'" />');
                  jQuery('input#value_'+id_field).val(attachment.id);
            });
            
            
            
            
            //jButton.prev('input[type="text"]').val(attachment.url);

            jButton = null;

            // Restore the main post ID
            wp.media.model.settings.post.id = wp_media_post_id;
        });

        // Finally, open the modal
        file_frame.open();
    });

    // Restore the main ID when the add media button is pressed
    jQuery('a.add_media').on('click', function() {
        wp.media.model.settings.post.id = wp_media_post_id;
    });
});