<?php

function pp($arr, $die = "true")
{
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    if ($die == 'true') {
        die();
    }
}