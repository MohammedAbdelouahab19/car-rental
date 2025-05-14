<?php

declare(strict_types=1);

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Enum\UserEnum;
use App\Story\TestStory;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use function Zenstruck\Foundry\Persistence\flush_after;

abstract class AbstractTest extends ApiTestCase
{
    use ResetDatabase, Factories;

    private ?string $token = null;

    public function setUp(): void
    {
        parent::setUp();
        flush_after(function () {
            TestStory::load();
        });
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function createClientWithCredentials($token = null): Client
    {
        $token = $token ?: $this->getToken();

        return static::createClient(defaultOptions: ['headers' => ['authorization' => "Bearer $token"]]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function getToken(): string
    {
        if ($this->token) return $this->token;

        $credentials = ['username' => UserEnum::AbdelouahabMohammed->username(), 'password' => 'azerty'];

        $response = static::createClient()->request('POST', '/api/auth/login', ['json' => $credentials]);
        $this->assertResponseIsSuccessful();

        $data = $response->toArray();
        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('refreshToken', $data);
        $this->token = $data['token'];

        return $data['token'];
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function getTokenForUser($user): string
    {
        $credentials = ['username' => $user->getUsername(), 'password' => 'azerty'];

        $response = static::createClient()->request('POST', '/api/auth/login', ['json' => $credentials]);
        $this->assertResponseIsSuccessful();

        $data = $response->toArray();
        return $data['token'];
    }
}
