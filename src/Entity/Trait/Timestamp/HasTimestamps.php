<?php

declare(strict_types=1);

namespace App\Entity\Trait\Timestamp;

trait HasTimestamps
{
    use HasCreatedAt;
    use HasUpdatedAt;
}
