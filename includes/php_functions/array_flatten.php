<?php
// https://gist.github.com/SeanCannon/6585889
/**
 * Convert a multi-dimensional array into a single-dimensional array.
 * @author Sean Cannon, LitmusBox.com | seanc@litmusbox.com
 * @param  array $array The multi-dimensional array.
 * @return array
 */
function array_flatten($array)
{
    if (!is_array($array)) {
        return false;
    }
    $result = [];
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, array_flatten($value));
        } else {
            $result = array_merge($result, [$key => $value]);
        }
    }
    return $result;
}
