<?php

function obsidian_add_rtl_to_arabic_content($content) {
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    $arabic_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}]/u';

    $body = $dom->getElementsByTagName('body')->item(0);

    // List of tag names to exclude
    $excluded_tags = ['code', 'pre'];

    // Recursive function to process all elements
    $process_element = function($node) use (&$process_element, $arabic_pattern, $excluded_tags) {
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $tag_name = strtolower($node->nodeName);

            // Skip excluded tags
            if (in_array($tag_name, $excluded_tags)) {
                return;
            }

            $text = $node->textContent;

            if (preg_match($arabic_pattern, $text)) {
                $node->setAttribute('dir', 'rtl');
                $node->setAttribute('style', 'text-align: right; font-family: cairo; font-weight: 400');
            } else {
                $node->setAttribute('dir', 'ltr');
                $node->setAttribute('style', 'text-align: left; direction: ltr');
            }
        }

        // Recurse into children
        foreach ($node->childNodes as $child) {
            $process_element($child);
        }
    };

    // Start processing from body
    $process_element($body);

    // Extract the modified HTML
    $innerHTML = '';
    foreach ($body->childNodes as $child) {
        $innerHTML .= $dom->saveHTML($child);
    }

    return $innerHTML;
}
