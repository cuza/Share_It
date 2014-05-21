<?php

namespace BrightSoft\UserBundle\Lib;

class Utilities
{
    static public function getSlug($text, $separator = '-')
    {
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        $slug = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $slug);
        $slug = strtolower(trim($slug, $separator));
        $slug = preg_replace("/[\/_|+ -]+/", $separator, $slug);
        return $slug;
    }
}
