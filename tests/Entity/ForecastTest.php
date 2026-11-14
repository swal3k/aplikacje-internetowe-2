<?php

namespace App\Tests\Entity;

use App\Entity\Forecast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


class ForecastTest extends TestCase
{
    public static function dataGetFahrenheit(): array
    {
        return[
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-40', '-40'],
            ['37', '98.6'],
            ['36.6', '97.88'],
            ['-17.5', '0.5'],
            ['25.5', '77.9'],
            ['10.1', '50.18'],
        ];
    }

    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $forecast = new Forecast();
        $forecast->setTemperatureCelsius($celsius);
        $this->assertEquals((float) $expectedFahrenheit, $forecast->getFahrenheit());
    }
}
