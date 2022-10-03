<?php

declare(strict_types=1);

namespace merms\anno\server;

use merms\anno\server\model\MicrotimeDate;

final class TimeService
{
    public static function getMicrotime(): MicrotimeDate {
        return new MicrotimeDate((string) microtime(true));
    }
}
