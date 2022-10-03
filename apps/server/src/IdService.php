<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\Id;
use Ramsey\Uuid\Uuid;

final class IdService
{
    public static function generate(): Id {
        return new Id(Uuid::uuid4()->toString());
    }
}
