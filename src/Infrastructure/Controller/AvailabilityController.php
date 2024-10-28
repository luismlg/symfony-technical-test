<?php

namespace App\Infrastructure\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Application\Service\AvailabilityService;
use Symfony\Component\Routing\Annotation\Route;


class AvailabilityController extends AbstractController {

    #[Route('/api/avail')]
    public function getAvailability(Request $request, AvailabilityService $availabilityService): JsonResponse {
        $origin = $request->query->get('origin');
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');
        
        $flights = $availabilityService->getAvailability($origin, $destination, $date);
        $response = [];
        
        foreach($flights as $flight) {
            $response[] = $flight->toArrayWithKeys();
        }

        return new JsonResponse($response);
    }
}
