<?php

namespace AppBundle\Utils;

class TextUtils {
    public static function capitalizeWords($string) {
        return implode(' ', array_map('ucfirst', explode(' ', strtolower($string))));
    }
}