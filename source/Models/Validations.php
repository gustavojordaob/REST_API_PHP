<?php

namespace source\Models;

final class Validations{
    public static function validationString(string $string){
        return strlen($string)>= 3 && !is_numeric($string);
    }

    public static function validationEmail(string $email){
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validationInt(int $int){
        return filter_var($int, FILTER_VALIDATE_INT) && $int > 0;
    }

    public static function validationPassword(string $string){
        return strlen($string)>= 3 && is_string($string);
    }
}