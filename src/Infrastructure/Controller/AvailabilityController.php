<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Application\Service\AvailabilityService;

class AvailabilityController extends AbstractController {
    public function getAvailability(Request $request, AvailabilityService $availabilityService): JsonResponse {
        $origin = $request->query->get('origin');
        $destination = $request->query->get('destination');
        $date = $request->query->get('date');
        
        $flights = $availabilityService->getAvailability($origin, $destination, $date);

        return new JsonResponse($flights);
    }
}
