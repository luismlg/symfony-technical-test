<?php

namespace App\Application\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Domain\Entity\Segment;

class AvailabilityService
{
    private HttpClientInterface $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getAvailability(string $origin, string $destination, string $date): array
    {
        $url = sprintf(
            'https://testapi.lleego.com/prueba-tecnica/availability-price?origin=%s&destination=%s&date=%s',
            $origin,
            $destination,
            $date
        );

        $response = $this->httpClient->request('GET', $url);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Error al realizar la petición al proveedor.');
        }

        $xmlContent = $response->getContent();

        return $this->parseXml($xmlContent);
    }

    private function parseXml(string $xmlContent): array
    {
        $segments = [];

        $xml = new \SimpleXMLElement($xmlContent);
        $xml->registerXPathNamespace('ns', 'http://www.iata.org/IATA/EDIST/2017.2'); 
        $flightSegments = $xml->xpath('//ns:AirShoppingRS/ns:DataLists/ns:FlightSegmentList/ns:FlightSegment');

        foreach ($flightSegments as $segmentData) {
            $segment = new Segment();
            $segment->setOriginCode((string) $segmentData->Departure->AirportCode);
            $segment->setOriginName((string) $segmentData->Departure->AirportName);
            $segment->setDestinationCode((string) $segmentData->Arrival->AirportCode);
            $segment->setDestinationName((string) $segmentData->Arrival->AirportName);
            $segment->setStart(new \DateTime((string) $segmentData->Departure->DateTime));
            $segment->setEnd(new \DateTime((string) $segmentData->Arrival->DateTime));
            $segment->setTransportNumber((string) $segmentData->MarketingCarrier->FlightNumber);
            $segment->setCompanyCode((string) $segmentData->MarketingCarrier->AirlineID);
            $segment->setCompanyName((string) $segmentData->MarketingCarrier->Name);

            $segments[] = $segment;
        }

        return $segments;
    }
}
