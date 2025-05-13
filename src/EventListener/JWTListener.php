<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Enum\OperationEnum;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JWTListener
{
    public function __construct(protected NormalizerInterface $normalizer)
    {
    }

    public function onJWTInvalid(JWTInvalidEvent $event): void
    {
        $response = new JWTAuthenticationFailureResponse(JWTEnum::Invalid->value);

        $event->setResponse($response);
    }

    public function onJWTExpired(JWTExpiredEvent $event): void
    {
        /** @var JWTAuthenticationFailureResponse $response */
        $response = $event->getResponse();

        $response->setMessage(JWTEnum::EXPIRED->value);
    }

    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $response = new JWTAuthenticationFailureResponse(JWTEnum::Failure->value);

        $event->setResponse($response);
    }

    /**
     * @throws ExceptionInterface
     */
    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        $user = $event->getUser();

        $data['user'] = $this->normalizer->normalize($user, 'jsonld', ['groups' => [OperationEnum::USER_AUTH]]);

        $event->setData($data);
    }
}
