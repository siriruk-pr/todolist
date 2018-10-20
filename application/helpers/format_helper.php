<?php

if ( ! function_exists('print_arr'))
{
    function print_arr($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
}
