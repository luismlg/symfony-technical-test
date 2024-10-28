<?php

namespace App\Tests\Application\Service;

use App\Application\Service\AvailabilityService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Segment;

class AvailabilityServiceTest extends TestCase
{
    private AvailabilityService $availabilityService;
    private HttpClientInterface $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->availabilityService = new AvailabilityService($this->httpClient);
    }

    public function testGetAvailabilityReturnsParsedSegments(): void
    {
        $xmlContent = file_get_contents(__DIR__ . '/../../FakeData/MAD_BIO_OW_1PAX_RS_NO_SOAP.xml');

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(200);
        $response->method('getContent')->willReturn($xmlContent);

        $this->httpClient->method('request')->willReturn($response);

        $segments = $this->availabilityService->getAvailability('MAD', 'BIO', '2022-06-01');

        $this->assertNotEmpty($segments);
        $this->assertInstanceOf(Segment::class, $segments[0]);
        $this->assertEquals('MAD', $segments[0]->getOriginCode());
        $this->assertEquals('BIO', $segments[0]->getDestinationCode());
    }

    public function testGetAvailabilityThrowsExceptionOnError(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')->willReturn(500);
        
        $this->httpClient->method('request')->willReturn($response);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Error al realizar la petición al proveedor.');

        $this->availabilityService->getAvailability('MAD', 'BIO', '2022-06-01');
        
    }
}
