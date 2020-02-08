<?php
declare(strict_types=1);

namespace App\Domain;

class Role
{
    const ROLE_USER = ['ROLE_USER'];
    const ROLE_TRADE = ['ROLE_TRADE'];

    /** @var array */
    private $role;

    public function __construct(array $role)
    {
        $this->role = $role;
    }

    public function getRole(): array
    {
        return $this->role;
    }
}
