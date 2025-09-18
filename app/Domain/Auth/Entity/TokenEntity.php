<?php
/**
 * @author Serhii Makarov <oreymgt@gmail.com>
 * @git https://github.com/OreyM
 */

namespace App\Domain\Auth\Entity;

use Carbon\Carbon;

final readonly class TokenEntity
{
    public string $type;
    public string $value;
    public Carbon $expiresAt;

    public function __construct(string $type, string $value, Carbon $expiresAt)
    {
        $this->type = $type;
        $this->value = $value;
        $this->expiresAt = $expiresAt;
    }
}
