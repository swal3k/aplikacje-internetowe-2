<?php

namespace App\Controller;

use App\Entity\Forecast;
use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp'=> '/^(json|csv)$/'])]
        string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $forecasts = $util->getWeatherForCountryAndCity($country, $city);
        
        if ($twig){
            $params = [
                'city'=>$city,
                'country'=>$country,
                'measurements'=>$forecasts,
            ];

            if('csv' === $format){
                return $this->render('weather_api/index.csv.twig', $params,
                    new Response('', 200, ['Content-Type'=> 'text/plain; charset=utf-8']));
            }

            return $this->render('weather_api/index.json.twig', $params,
                new Response('', 200, ['Content-Type' => 'application/json'])); 
        }

        if('csv' === $format){
            $lines = ['city,country,date,celsius,fahrenheit'];
            foreach($forecasts as $f){
                $lines[] = implode(',', [
                    $city,
                    $country,
                    $f->getDate()->format('Y-m-d'),
                    $f->getTemperatureCelsius(),
                    $f->getFahrenheit(),
                ]);
            }

            return new Response(implode("\n", $lines), 200, [
                'Content-Type'=> 'text/plain; charset=utf-8'
            ]);
        }
        
        return $this->json([
            'city' => $city,
            'country' => $country,
            'measurements' => array_map(fn(Forecast $f) => [
                'date' => $f->getDate()->format('Y-m-d'),
                'celsius' => $f-> getTemperatureCelsius(),
                'fahrenheit' => $f->getFahrenheit(),
            ], $forecasts),
        ]);
    }
}
