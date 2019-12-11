<?php

// Only available for php >= 7.3
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr)
    {
        foreach ($arr as $key => $unused) {
            unset($unused);

            return $key;
        }

        return null;
    }
}
