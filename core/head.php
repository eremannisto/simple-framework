<?php

// Dependancies:
if(!class_exists('Config'))  require_once(__DIR__ . '/config.php');
if(!class_exists('Request')) require_once(__DIR__ . '/request.php');

class Head {

    /**
     * Render the head tags and their contents. You can also
     * pass a key => value array to the render method to override
     * the default meta data.
     * 
     * @param array|null $meta
     * An array containing the meta data to override the default meta data.
     * 
     * @return string|null
     * The rendered head tags and their contents, or null if the file could not be read or decoded.
     * 
     * @example Head::render();
     * @example Head::render(['title' => 'My title']);
     * 
     */
    public static function render(?array $meta = null): ?string {

        // Start output buffer:
        $output = '';

        // Get the default meta data:
        $defaults = [
            'title'         => htmlspecialchars(Meta::get('title')),
            'description'   => htmlspecialchars(Meta::get('description')),
            'keywords'      => htmlspecialchars(Meta::get('keywords')),
            'type'          => htmlspecialchars(Meta::get('type')),
            'language'      => htmlspecialchars(Meta::get('language')),
            'image'         => htmlspecialchars(Meta::get('image')),
            'url'           => Request::page()
        ];

        // Merge the defaults with the given meta data:
        $meta = array_merge($defaults, $meta ?: []);

        // Sanitize the meta data variables:
        $title       = htmlspecialchars($meta['title']);
        $description = htmlspecialchars($meta['description']);
        $keywords    = htmlspecialchars($meta['keywords']);
        $type        = htmlspecialchars($meta['type']);
        $language    = htmlspecialchars($meta['language']);
        $image       = htmlspecialchars($meta['image']);
        $url         = htmlspecialchars($meta['url']);

        $output .= '<!DOCTYPE html>';
        $output .= sprintf('<html lang="%s">', $language);
        $output .= '<head>';

        ?><!-- General meta data: --><?php
        $output .= '<meta charset="UTF-8">';
        $output .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
        $output .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';

        ?><!-- Title, description and keywords:  --><?php
        $output .= !empty($title)       ? sprintf('<title>%s</title>',                      $title)       : '';
        $output .= !empty($description) ? sprintf('<meta name="description" content="%s">', $description) : '';
        $output .= !empty($keywords)    ? sprintf('<meta name="keywords"    content="%s">', $keywords)    : '';
    
        ?><!-- Open graph meta data: --><?php
        $output .= !empty($title)       ? sprintf('<meta property="og:title"       content="%s">', $title)       : '';
        $output .= !empty($description) ? sprintf('<meta property="og:description" content="%s">', $description) : '';
        $output .= !empty($type)        ? sprintf('<meta property="og:type"        content="%s">', $type)        : '';
        $output .= !empty($image)       ? sprintf('<meta property="og:image"       content="%s">', $image)       : '';
        $output .= !empty($url)         ? sprintf('<meta property="og:url"         content="%s">', $url)         : '';

        ?><!-- Twitter meta data : --><?php
        $output .= !empty($title)       ? sprintf('<meta name="twitter:title"       content="%s">', $title)       : '';
        $output .= !empty($description) ? sprintf('<meta name="twitter:description" content="%s">', $description) : '';
        $output .= !empty($image)       ? sprintf('<meta name="twitter:image"       content="%s">', $image)       : '';
        $output .= !empty($url)         ? sprintf('<meta name="twitter:url"         content="%s">', $url)         : '';
        if(!empty($title || $description || $image)) {
            $output .= '<meta name="twitter:card"    content="summary_large_image">';
            $output .= '<meta name="twitter:site"    content="@twitter">';
            $output .= '<meta name="twitter:creator" content="@twitter">';
        }

        ?><!-- Favicons: --><?php
        $output .= Favicon::generate("public/favicons");


    }
} 

  