<?php
use PHPUnit\Framework\TestCase;
error_reporting(E_ALL);
ini_set('display_errors', 1);

class CalculatorTest extends TestCase {
    public function testAddition() {
        $calculator = new Calculator();
        $result = $calculator->add(2, 3);
        $this->assertEquals(5, $result);
    }

    public function testSubtraction() {
        $calculator = new Calculator();
        $result = $calculator->sub(5, 2);
        $this->assertEquals(3, $result);
    }
}
