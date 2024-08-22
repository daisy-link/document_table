<?php

class Utils
{
    public static function setMessage($messages = [])
    {
        $_SESSION['messages'] = $messages;
    }

    public static function getMessage()
    {
        $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
        unset($_SESSION['messages']);
        return $messages;
    }

    public static function CSRF()
    {
        if (empty($_SESSION[CSRF_TOKEN_NAME])) {
            $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        echo '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $_SESSION[CSRF_TOKEN_NAME] . '">';
    }

    public static function validCSRF()
    {
        $result = false;
        if (isset($_REQUEST[CSRF_TOKEN_NAME]) && isset($_SESSION[CSRF_TOKEN_NAME])) {
            $result = ($_REQUEST[CSRF_TOKEN_NAME] === $_SESSION[CSRF_TOKEN_NAME] && !empty($_REQUEST[CSRF_TOKEN_NAME]) && !empty($_SESSION[CSRF_TOKEN_NAME]));
            if ($result === false) {
                unset($_SESSION[CSRF_TOKEN_NAME]);
            }
        }
        return $result;
    }
    public static function _esc($value)
    {
        $string = '';
        if (!empty($value)) {
            $string = htmlspecialchars($value, ENT_QUOTES, "UTF-8");
        }
        echo $string;
    }

    public static function _selected($value, $selected)
    {
        $string = 'selected';

        if ($value == $selected) {
            echo $string;
        }
    }



}
