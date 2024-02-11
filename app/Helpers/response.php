<?php

use Symfony\Component\HttpFoundation\Response;

if ( ! function_exists('isValidHttpResponseCode')) {
    function isValidHttpResponseCode(int $code): bool
    {
        return array_key_exists($code, Response::$statusTexts);
    }
}

if ( ! function_exists('returnValidHttpResponseCode')) {
    function returnValidHttpResponseCode(int $providedCode, int $fallbackCode): int
    {
        return isValidHttpResponseCode($providedCode) ? $providedCode : $fallbackCode;
    }
}
