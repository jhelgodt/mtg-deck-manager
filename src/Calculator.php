<?php declare(strict_types=1);
final class Calculator {
    public static function sum(int $num1, int $num2): int | float {
        if (!is_numeric($num1) || !is_numeric($num2)) {
            return NAN;
        }

        return $num1 + $num2;
    }
}
?>