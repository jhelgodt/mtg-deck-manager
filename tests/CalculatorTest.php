<?php declare(strict_types=1);
require __DIR__ . "/../src/Calculator.php";
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase {
    public function testSumWithValidNumbers(): void {
        $num1 = 2;
        $num2 = 3;

        $sum = Calculator::sum($num1, $num2);

        $this->assertSame($sum, 5);
    }

    public function testSumWithInvalidNumbers(): void {
        $num1 = "two";
        $num2 = "three";

        $sum = Calculator::sum($num1, $num2);

        $this->assertNan($sum);
    }

    public function testSumWithNumericStrings(): void {
        $num1 = "2 hot dogs";
        $num2 = "3 hamburgers";

        $sum = Calculator::sum($num1, $num2);

        $this->assertSame($sum, 5);
    }
}
?>