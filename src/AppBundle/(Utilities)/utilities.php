<?php
    function capitalizeWords($string) {
        return implode(' ', array_map('ucfirst', explode(' ', strtolower($string))));
    }
?>
