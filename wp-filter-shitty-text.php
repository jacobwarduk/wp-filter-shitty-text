<?php

    /*
        Plugin Name: WP Filter Shitty Text
        Description: WordPress plugin to filter 'shitty text' input and prevent cutting or breaking of posts.
        Author: Jacob Ward
        Version: 1.0.0
        Author URI: http://www.jacobward.co.uk
        Plugin URI: http://www.jacobward.co.uk

    */

    // Filtering out shitty text on post publish/save draft/update
    add_filter( 'wp_insert_post_data' , 'filter_shitty_text' , '99', 2 );

    function filter_shitty_text( $data , $postarr ) {

        // Converting post text to UTF-8
        $data['post_content'] = iconv( 'UTF-8', 'UTF-8//IGNORE', $data['post_content'] );

        // Replacing overly long 2 byte sequences and unicode characters above U+1000 with an empty string
        $data['post_content'] = preg_replace( '/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]' . '|[\x00-\x7F][\x80-\xBF]+' . '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*' . '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})' . '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S','', $data['post_content'] );

        // Replacing overly long 3 byte sequences and UTF-16 surrogates with an empty string
        $data['post_content'] = preg_replace( '/\xE0[\x80-\x9F][\x80-\xBF]' . '|\xED[\xA0-\xBF][\x80-\xBF]/S','', $data['post_content'] );

        return $data;

    }
