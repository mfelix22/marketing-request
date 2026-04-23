<?php

namespace App\Enums;

enum Role: string
{
    case Admin    = 'admin';
    case Marcom   = 'marcom';
    case Manager  = 'manager';
    case Staff    = 'staff';
    case Gm       = 'gm';
    case Director = 'director';

    /**
     * Human-readable label for display.
     */
    public function label(): string
    {
        return match ($this) {
            Role::Admin    => 'Admin',
            Role::Marcom   => 'Admin Marketing',
            Role::Manager  => 'Manager',
            Role::Staff    => 'Staff',
            Role::Gm       => 'General Manager',
            Role::Director => 'Director',
        };
    }

    /**
     * Whether this role can participate in the approval chain.
     * marcom (Admin Marketing) and admin are NOT approvers.
     */
    public function canApprove(): bool
    {
        return in_array($this, [
            self::Manager,
            self::Gm,
            self::Director,
        ]);
    }
}
