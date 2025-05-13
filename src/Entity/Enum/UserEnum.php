<?php

namespace App\Entity\Enum;

enum UserEnum
{
    case AbdelouahabMohammed;
    case ChakirAbdelkhalek;
    case ElhilaliAbdelmounaim;
    case BensaidAymane;
    case SouidiAbdellah;

    public function username(): string
    {
        return match ($this) {
            self::AbdelouahabMohammed => 'm.abdelouahab',
            self::ChakirAbdelkhalek => 'a.chakir',
            self::ElhilaliAbdelmounaim => 'm.elhilali',
            self::BensaidAymane => 'm.bensaid',
            self::SouidiAbdellah => 'm.souidi',
        };
    }

    public function firstName(): string
    {
        return match ($this) {
            self::AbdelouahabMohammed => 'Mohammmed',
            self::ChakirAbdelkhalek => 'Chakir',
            self::ElhilaliAbdelmounaim => 'Elhilali',
            self::BensaidAymane => 'Bensaid',
            self::SouidiAbdellah => 'Souidi',
        };
    }

    public function lastName(): string
    {
        return match ($this) {
            self::AbdelouahabMohammed => 'ABDELOUAHAB',
            self::ChakirAbdelkhalek => 'CHAKIR',
            self::ElhilaliAbdelmounaim => 'ELHILALI',
            self::BensaidAymane => 'BENSAID',
            self::SouidiAbdellah => 'SOUIDI',
        };
    }

    public function role(): RoleEnum
    {
        return match ($this) {
            self::AbdelouahabMohammed,
            self::ChakirAbdelkhalek,
            self::ElhilaliAbdelmounaim,
            self::BensaidAymane,
            self::SouidiAbdellah => RoleEnum::User,
        };
    }
}
