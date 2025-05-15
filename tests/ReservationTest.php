<?php

declare(strict_types=1);

namespace App\Tests;

use App\Factory\CarFactory;
use App\Factory\ReservationFactory;
use App\Factory\UserFactory;
use App\Service\Validator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ReservationTest extends AbstractTest
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetReservationCollection(): void
    {
        ReservationFactory::createMany(10);

        $response = $this->createClientWithCredentials()->request('GET', '/api/reservations');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Reservation',
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
    public function testCreateReservation(): void
    {
        $car = CarFactory::createOne();
        $tenant = UserFactory::createOne();

        $response = $this->createClientWithCredentials()->request('POST', '/api/reservations', ['json' => [
            'startDate' => (new \DateTime('+1 day'))->format('Y-m-d'),
            'endDate' => (new \DateTime('+3 days'))->format('Y-m-d'),
            'car' => '/api/cars/' . $car->getId(),
            'tenant' => '/api/users/' . $tenant->getId(),
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonContains([
            '@context' => '/api/contexts/Reservation',
            '@type' => 'Reservation',
            'car' => [
                '@id' => '/api/cars/' . $car->getId(),
            ],
            'tenant' => [
                '@id' => '/api/users/' . $car->getId(),
            ],
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testStartDateGreaterThanToday(): void
    {
        $car = CarFactory::createOne();
        $tenant = UserFactory::createOne();

        $response = $this->createClientWithCredentials()->request('POST', '/api/reservations', ['json' => [
            'startDate' => (new \DateTime('-1 day'))->format('Y-m-d'),
            'endDate' => (new \DateTime('+1 day'))->format('Y-m-d'),
            'car' => '/api/cars/' . $car->getId(),
            'tenant' => '/api/users/' . $tenant->getId(),
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolation',
            'violations' => [
                ['propertyPath' => 'startDate', 'message' => Validator::STARTING_DATE_GREATER_OR_EQ_THAN_TODAY],
            ],
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testEndDateBeforeStartDate(): void
    {
        $car = CarFactory::createOne();
        $tenant = UserFactory::createOne();

        $response = $this->createClientWithCredentials()->request('POST', '/api/reservations', ['json' => [
            'startDate' => (new \DateTime('+1 day'))->format('Y-m-d'),
            'endDate' => (new \DateTime('-1 day'))->format('Y-m-d'),
            'car' => '/api/cars/' . $car->getId(),
            'tenant' => '/api/users/' . $tenant->getId(),
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolation',
            'violations' => [
                ['propertyPath' => 'endDate', 'message' => Validator::END_DATE_GREATER_OR_EQ_THAN_START_DATE],
            ],
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCarNotAvailableInPeriod(): void
    {
        $car = CarFactory::createOne();
        $tenant = UserFactory::createOne();

        $response = $this->createClientWithCredentials()->request('POST', '/api/reservations', ['json' => [
            'startDate' => (new \DateTime('+1 day'))->format('Y-m-d'),
            'endDate' => (new \DateTime('+3 days'))->format('Y-m-d'),
            'car' => '/api/cars/' . $car->getId(),
            'tenant' => '/api/users/' . $tenant->getId(),
            'reference' => 'ref',
        ]]);

        $response = $this->createClientWithCredentials()->request('POST', '/api/reservations', ['json' => [
            'startDate' => (new \DateTime('+1 day'))->format('Y-m-d'),
            'endDate' => (new \DateTime('+3 days'))->format('Y-m-d'),
            'car' => '/api/cars/' . $car->getId(),
            'tenant' => '/api/users/' . $tenant->getId(),
            'reference' => 'ref',
        ]]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolation',
            'violations' => [
                ['propertyPath' => 'car', 'message' => Validator::CAR_NOT_AVAILABLE_IN_THIS_PERIOD],
            ],
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUpdateReservation(): void
    {
        $reservation = ReservationFactory::createOne();
        $car = CarFactory::createOne();

        $response = $this->createClientWithCredentials()->request('PUT', sprintf('/api/reservations/%d', $reservation->getId()), ['json' => [
            'startDate' => (new \DateTime('+1 days'))->format('Y-m-d'),
            'endDate' => (new \DateTime('+5 days'))->format('Y-m-d'),
            'car' => '/api/cars/' . $car->getId(),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => sprintf('/api/reservations/%d', $reservation->getId()),
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDeleteReservation(): void
    {
        $reservation = ReservationFactory::createOne();

        $client = $this->createClientWithCredentials();
        $client->request('DELETE', sprintf('/api/reservations/%s', $reservation->getId()));

        $this->assertResponseStatusCodeSame(204);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetReservationItem(): void
    {
        $reservation = ReservationFactory::createOne();

        $response = $this->createClientWithCredentials()->request('GET', sprintf('/api/reservations/%s', $reservation->getId()));

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/reservations/' . $reservation->getId(),
            '@type' => 'Reservation',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetReservationCollectionOfOtherUserNotFound(): void
    {
        $currentUser = UserFactory::createOne();

        $client = $this->createClientWithCredentials();

        $client->request('GET', sprintf('/api/users/%d/reservations', $currentUser->getId()));

        $this->assertResponseStatusCodeSame(404);
    }
}
