<?php

function obsidian_add_rtl_to_arabic_paragraphs( $content ) {
    // Use DOMDocument to parse the content
    $dom = new DOMDocument();

    // Suppress errors due to malformed HTML fragments
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    libxml_clear_errors();

    // Get all <p> elements
    $paragraphs = $dom->getElementsByTagName('p');
    $heading3 = $dom->getElementsByTagName('h3');
    $unordered = $dom->getElementsByTagName('ul');

    // Arabic character pattern (unicode range)
    $arabic_pattern = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}]/u';

    foreach ($paragraphs as $p) {
        $text = $p->textContent;

        if (preg_match($arabic_pattern, $text)) {
            // Add RTL attributes
            $p->setAttribute('dir', 'rtl');
            $p->setAttribute('style', 'text-align: right; font-family: cairo');
        }
		else {
            // Add RTL attributes
            $p->setAttribute('dir', 'ltr !important');
            $p->setAttribute('style', 'text-align: left !important; direction: ltr !important');
		}
    }


    foreach ($heading3 as $h3) {
        $text = $p->textContent;

        if (preg_match($arabic_pattern, $text)) {
            // Add RTL attributes
            $h3->setAttribute('dir', 'rtl');
            $h3->setAttribute('style', 'text-align: right; font-family: cairo');
        }
		else {
            // Add RTL attributes
            $h3->setAttribute('dir', 'ltr !important');
            $h3->setAttribute('style', 'text-align: left !important; direction: ltr !important');
		}
    }

    foreach ($unordered as $ul) {
        $text = $ul->textContent;

        if (preg_match($arabic_pattern, $text)) {
            // Add RTL attributes
            $ul->setAttribute('dir', 'rtl');
            $ul->setAttribute('style', 'text-align: right; font-family: cairo');
        }
		else {
            // Add RTL attributes
            $ul->setAttribute('dir', 'ltr !important');
            $ul->setAttribute('style', 'text-align: left !important; direction: ltr !important');
		}
    }






    // Save the modified HTML, stripping the <html><body> wrappers
    $body = $dom->getElementsByTagName('body')->item(0);
    $innerHTML = '';
    foreach ($body->childNodes as $child) {
        $innerHTML .= $dom->saveHTML($child);
    }

    return $innerHTML;
}