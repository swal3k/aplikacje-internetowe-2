<?php

namespace App\Command;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Wyświetla prognozę pogody dla miasta na podstawie kodu kraju i nazwy',  
)]
class WeatherCityCommand{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ){}
    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code')] string $country,
        #[Argument('City name')] string $city,
    ): int {
        $forecasts = $this->weatherUtil->getWeatherForCountryAndCity($country, $city);
        $io->writeln(sprintf('Location: %s, %s', $city, $country));

        if ([] === $forecasts){
            $io->warning('Brak prognoz dla tej lokalizacji lub lokalizacja nie istnieje');
            return Command::SUCCESS;
        }

        $io->table(
            ['Date', 'Temp [°C]', 'Humidity [%]', 'UV', 'Wind [km/h]', 'Precip. [mm]', 'Pressure [hPa]'],
            array_map(fn(Forecast $f)=> [
                $f->getDate()->format('Y-m-d'),
                $f->getTemperatureCelsius(),
                $f->getHumidity(),
                $f->getUvIndex(),
                $f->getWindSpeed(),
                $f->getPrecipitation(),
                $f->getPressure(),
            ], $forecasts)
        );
        
        return Command::SUCCESS;
    }
}
