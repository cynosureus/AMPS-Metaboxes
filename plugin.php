<?php

/*
Plugin Name: AMPS Metaboxes
Plugin URI: http://www.cynosure.com
Description: Adds metaboxes and metabox functionality to admin
Version: 1.0
Author: Daniel Miller
*/

add_action('admin_enqueue_scripts', 'add_scripts');

function add_scripts() 
{
    wp_enqueue_script('test', plugin_dir_url( __FILE__ ) . '/assets/js/amps_metaboxes.js' );
}

function amps_meta_box_add()
{
    add_meta_box( 'content-meta-box', 'Add Content Items', 'amps_meta_box_cb', 'cynosure-material', 'normal', 'high' );
}

function amps_meta_box_cb()
{
    
     $values = get_post_custom( $post->ID );
	 $text = isset( $values['my_meta_box_text'] ) ? esc_attr( $values['my_meta_box_text'][0] ) : ”;
	 
	  wp_nonce_field( 'content_meta_box_nonce', 'meta_box_nonce' );

      $content = get_post_meta(get_the_ID(), 'amps_content_items', true);

    ?>

    <div id = "content-items-container">

    <?php 

   $content = get_post_meta(get_the_ID(), 'amps_content_items', true);

   if ($content) {
        foreach ($content as $key => $value) {
        
            echo amps_content_textbox_html($key);
        
        }
   } else {


        echo amps_content_textbox_html(0);

   }

   
    ?>

    </div>

    <p>
        <div class = "add-button" id = "add-button"><a href = "#" id = "add-button-link">Add Item</a></div>
    </p>


   
    <?php    
}

function amps_meta_box_save( $post_id )
{


   
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
 
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'content_meta_box_nonce' ) ) return;
         
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;
     
    // now we can actually save the data
    $allowed = array( 
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );
     
    // Make sure your data is set before trying to save it
    
    $content = array();
    
    $urls = $_POST['content-url'];
    $captions = $_POST['content-caption'];
    $content_size = count($urls);

    for($i = 0; $i < $content_size; $i++) {
        if (isset( $captions[$i]) )
            $caption  = $captions[$i];
        if (isset( $urls[$i]))
            $url = $urls[$i];

        if($caption && $url) {
            $content_item = array('caption' => $caption, 'url' => $url);
            array_push($content, $content_item);
        }        

    }

 

    update_post_meta($post_id, 'amps_content_items', $content);


}


function amps_content_textbox_html($item_num) {

    ob_start();

    $content = get_post_meta(get_the_ID(), 'amps_content_items', true);
    ?>

    <p class = "content-item-boxes" id = "content-item-boxes-<?=$item_num ?>">
        <label for="content-url-1">URL:</label>
        <input type="text" name="content-url[<?= $item_num ?>]" value = "<?= $content[$item_num]['url'] ? $content[$item_num]['url'] : '' ?>"/>

         <label for="content-caption-1">Caption:</label>
        <input type="text" name="content-caption[<?= $item_num ?>]" value = "<?= $content[$item_num]['caption'] ? $content[$item_num]['caption'] : '' ?>"/>
    
        <span class = "remove-material"><a href = "#" class = "remove-material-link" id = "remove-material-link-<?=$item_num ?>">Remove</a></span>

    </p>

    <?php 

    $html = ob_get_contents();

    ob_end_clean();

    return $html;

}

add_action( 'add_meta_boxes', 'amps_meta_box_add' );
add_action( 'save_post', 'amps_meta_box_save' );