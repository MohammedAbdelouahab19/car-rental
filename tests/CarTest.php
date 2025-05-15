<?php

declare(strict_types=1);

namespace App\Tests;

use App\Factory\CarFactory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CarTest extends AbstractTest
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCarCollection(): void
    {
        CarFactory::createMany(10);

        $response = $this->createClientWithCredentials()->request('GET', '/api/cars');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Car',
            '@type' => 'Collection',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCarItem(): void
    {
        $car = CarFactory::createOne();

        $response = $this->createClientWithCredentials()->request('GET', '/api/cars/' . $car->getId());

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/cars/' . $car->getId(),
            '@type' => 'Car',
        ]);
    }
}
