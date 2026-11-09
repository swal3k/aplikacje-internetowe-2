<?php

namespace App\Controller;

use App\Entity\Location;
use App\Service\WeatherUtil;
use App\Repository\ForecastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;



final class WeatherController extends AbstractController
{
    #[Route('/weather/{country}/{city}', name: 'app_weather', requirements: ['country' => '[A-Z]{2}'])]
    public function city(
        #[MapEntity(expr: 'repository.findOneByCityAndCountry(city, country)')]
        Location $location, WeatherUtil $util): Response
    {
        $forecasts = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'forecasts' => $forecasts,
        ]);
    }
}