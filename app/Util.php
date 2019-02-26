<?php

namespace App;

class Util
{

    public static function jsonEncodeWithoutBrackets($array)
    {
        return substr(json_encode($array), 1, strlen(json_encode($array)) - 2);
    }
}