<?php

namespace App\Controller;

use App\Form\ExternalAPIType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;


final class ExternalAPIController extends AbstractController
{
    #[Route('/externalapi', name: 'app_external_api')]
    public function index(Request $request, HttpClientInterface $httpClient): Response
    {
        $form = $this->createForm(ExternalAPIType::class, [
            'latitude' => 53.429,
            'longitude' => 14.553,
        ]);
        $weatherData = null;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $latitude = $data['latitude'];
            $longitude = $data['longitude'];

            $apiUrl = sprintf(
                'https://api.open-meteo.com/v1/forecast?latitude=%s&longitude=%s&daily=temperature_2m_max,temperature_2m_min,precipitation_sum&timezone=UTC',
                $latitude,
                $longitude
            );

            $response = $httpClient->request('GET', $apiUrl);
            $weatherData = $response->toArray();
        }

        return $this->render('external_api/index.html.twig', [
            'form' => $form,
            'weather_data' => $weatherData,
        ]);
    }
}
