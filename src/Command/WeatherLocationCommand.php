<?php

namespace App\Command;

use App\Entity\Forecast;
use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Shows a weather forecast for a given location ID',
)]
class WeatherLocationCommand
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
    ){
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Location ID')] int $id,
    ): int {
        $location = $this->locationRepository->find($id);
        if (null === $location) {
            $io->error(sprintf('Location ID not found  %d', $id));
            return Command::FAILURE;
        }
        $forecasts = $this->weatherUtil->getWeatherForLocation($location);
        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));

        $io->table(
            ['Date', 'Temp [°C]', 'Humidity [%]', 'UV', 'Wind [km/h]', 'Precip. [mm]', 'Pressure [hPa]'],
            array_map(fn (Forecast $f) => [
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
