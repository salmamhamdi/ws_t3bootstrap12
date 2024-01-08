<?php
namespace WapplerSystems\WsT3bootstrap\Utility;

class ArrayUtility {



    public static function implodeRecursive(string $separator, array $array): string
    {
        $string = '';
        foreach ($array as $i => $a) {
            if (is_array($a)) {
                $string .= self::implodeRecursive($separator, $a);
            } else {
                $string .= $a;
                if ($i < count($array) - 1) {
                    $string .= $separator;
                }
            }
        }
        return $string;
    }

}
