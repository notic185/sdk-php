<?php

namespace Notic185\PHP_SDK\Utilities;

class Simplifier
{
    public static function pileDown(array $from, array &$to, string $toPrefix = ''): void
    {
        foreach ($from as $key => $value) {
            // -
            $prefixedKey = $toPrefix === '' ? $key : "$toPrefix.$key";
            // -
            if (is_array($value) || is_object($value)) {
                Simplifier::pileDown($value, $to, $prefixedKey);
            } else {
                $to[$prefixedKey] = $value;
            }
        }
    }
}
