<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Reservation;
use App\Factory\CarFactory;
use App\Factory\ReservationFactory;
use App\Factory\UserFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ReservationTest extends AbstractTest
{
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
                ['propertyPath' => 'startDate', 'message' => 'STARTING_DATE_GREATER_OR_EQ_THAN_TODAY'],
            ],
        ]);
    }

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
                ['propertyPath' => 'endDate', 'message' => 'END_DATE_GREATER_OR_EQ_THAN_START_DATE'],
            ],
        ]);
    }

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

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonContains([
            '@context' => '/api/contexts/ConstraintViolation',
            'violations' => [
                ['propertyPath' => 'car', 'message' => 'CAR_NOT_AVAILABLE_IN_THIS_PERIOD'],
            ],
        ]);
    }


    public function testUpdateReservation(): void
    {
        $reservation = ReservationFactory::createOne();

        $response = $this->createClientWithCredentials()->request('PUT', '/api/reservations/' . $reservation->getId(), ['json' => [
            'endDate' => (new \DateTime('+5 days'))->format('Y-m-d'),
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/reservations/' . $reservation->getId(),
        ]);
    }

    public function testDeleteReservation(): void
    {
        $reservation = ReservationFactory::createOne();

        $client = $this->createClientWithCredentials();
        $client->request('DELETE', '/api/reservations/' . $reservation->getId());

        $this->assertResponseStatusCodeSame(204);
    }
}
