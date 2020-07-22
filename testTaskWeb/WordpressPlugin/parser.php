<?php
/**
 * @package parser
 * @version 1.0
 */
/*
Plugin Name: Parser Yahoo
Plugin URI: http://wordpress.org/plugins/123/
Description: Just a plugin
Author: Dima Bu
Version: 1.0
Author URI: -
*/
function createCategories(){
    $categories = get_categories();

    if( $categories ){
        $slug = [];
        foreach( $categories as $cat ){
            $slug[] = $cat->slug;
        }

        if (!in_array('news', $slug)) {
            $news = array(
                'cat_name' => 'news',
                'category_description' => 'Новости',
                'category_nicename' => 'news'
            );
            wp_insert_category( $news );
        }

        if (!in_array('entertainment', $slug)) {
            $entertainment = array(
                'cat_name' => 'entertainment',
                'category_description' => 'entertainment',
                'category_nicename' => 'entertainment'
            );
            wp_insert_category( $entertainment );
        }
    }
}

// This just echoes the chosen line, we'll position it later.
function createPost() {
    global $wpdb;
    $posts = $wpdb->get_results( $wpdb->prepare("SELECT * FROM yahoo_posts WHERE published = 0") );

    foreach ($posts as $post) {
        $source = array(
            'post_title' => $post->title,
            'post_name' => $post->guid,
            'post_content' => $post->description,
            'post_status' => 'publish',
            'post_author' => 1,
            'post_type' => 'post',
            'post_category' => array( get_cat_ID($post->category) ),
            'tags_input' => 'Добавление постов, WordPress',
            'comment_status' => 'closed'
        );

        $post_id = wp_insert_post($source);
        if (!is_null($post->media_content)) {
            $attach_id = downloadImage($post->media_content);
            set_post_thumbnail($post_id, $attach_id);
        }

        if ($post_id !== 0) {
            $wpdb->update('yahoo_posts', ["published" => 1], ['id' => $post->id]);
        }
    }
}

function downloadImage($img_adress){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents( $img_adress );
    $filename = basename( $img_adress ) . '.jpg';

    if ( wp_mkdir_p( $upload_dir['path'] ) ) {
        $file = $upload_dir['path'] . '/' . $filename;
    }
    else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents( $file, $image_data );

    $wp_filetype = wp_check_filetype( $filename, null );

    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name( $filename ),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $file );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    return $attach_id;
}
// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'createCategories' );
add_action( 'admin_notices', 'createPost' );
