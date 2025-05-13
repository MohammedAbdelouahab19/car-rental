<?php

namespace App\EventListener;

enum JWTEnum: string
{
    case Invalid = 'JWT_INVALID';
    case NotFound = 'JWT_NOT_FOUND';
    case EXPIRED = 'JWT_EXPIRED';
    case Failure = 'AUTHENTICATION_FAILURE';
}
