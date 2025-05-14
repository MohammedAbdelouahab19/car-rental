<?php

declare(strict_types=1);

namespace App\Entity\Enum;

enum OperationEnum
{
    case UserListing;
    case UserCreate;
    case UserDetail;
    case UserUpdate;
    case UserDelete;

    case CarListing;
    case CarCreate;
    case CarDetail;
    case CarUpdate;
    case CarDelete;

    case ReservationListing;
    case UserReservationListing;
    case ReservationCreate;
    case ReservationDetail;
    case ReservationUpdate;
    case ReservationDelete;

    public const string USER_AUTH = 'User.AUTH';
}
