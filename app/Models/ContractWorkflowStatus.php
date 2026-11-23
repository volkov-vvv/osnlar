<?php

namespace App\Models;

final class ContractWorkflowStatus
{
    public const DRAFT = 'draft';
    public const PENDING_SIGNATURE = 'pending_signature';
    public const SIGNED = 'signed';
    public const ORIGINAL = 'original';

    public const LABELS = [
        self::DRAFT => 'Проект',
        self::PENDING_SIGNATURE => 'На подписании',
        self::SIGNED => 'Подписан',
        self::ORIGINAL => 'Оригинал',
    ];

    public static function keys(): array
    {
        return array_keys(self::LABELS);
    }

    public static function label(?string $status): string
    {
        if ($status === null || $status === '') {
            return self::LABELS[self::DRAFT];
        }

        return self::LABELS[$status] ?? $status;
    }

    public static function badgeClass(?string $status): string
    {
        return match ($status) {
            self::PENDING_SIGNATURE => 'badge badge-warning',
            self::SIGNED => 'badge badge-signed',
            self::ORIGINAL => 'badge badge-original',
            default => 'badge badge-secondary',
        };
    }

    public static function iconClass(?string $status): string
    {
        return match ($status) {
            self::PENDING_SIGNATURE => 'fas fa-pen-fancy',
            self::SIGNED => 'fas fa-file-signature',
            self::ORIGINAL => 'fas fa-stamp',
            default => 'fas fa-file-alt',
        };
    }
}
