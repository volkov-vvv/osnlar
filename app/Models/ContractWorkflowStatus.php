<?php

namespace App\Models;

final class ContractWorkflowStatus
{
    public const DRAFT = 'draft';
    public const PENDING_SIGNATURE = 'pending_signature';
    public const SIGNED = 'signed';
    public const ORIGINAL = 'original';
    public const AWAITING_PAYMENT = 'awaiting_payment';
    public const PAID = 'paid';

    public const LABELS = [
        self::DRAFT => 'Проект',
        self::PENDING_SIGNATURE => 'На подписании',
        self::SIGNED => 'Подписан',
        self::ORIGINAL => 'Оригинал',
    ];

    public const ACT_EXTRA_LABELS = [
        self::AWAITING_PAYMENT => 'Ожидаем оплату',
        self::PAID => 'Оплачено',
    ];

    public static function keys(): array
    {
        return array_keys(self::LABELS);
    }

    public static function actLabels(): array
    {
        return self::LABELS + self::ACT_EXTRA_LABELS;
    }

    public static function actKeys(): array
    {
        return array_keys(self::actLabels());
    }

    public static function allLabels(): array
    {
        return self::actLabels();
    }

    public static function label(?string $status): string
    {
        if ($status === null || $status === '') {
            return self::LABELS[self::DRAFT];
        }

        return self::allLabels()[$status] ?? $status;
    }

    public static function badgeClass(?string $status): string
    {
        return match ($status) {
            self::PENDING_SIGNATURE, self::AWAITING_PAYMENT => 'badge badge-warning',
            self::SIGNED => 'badge badge-signed',
            self::ORIGINAL => 'badge badge-original',
            self::PAID => 'badge badge-success',
            default => 'badge badge-secondary',
        };
    }

    public static function iconClass(?string $status): string
    {
        return match ($status) {
            self::PENDING_SIGNATURE => 'fas fa-pen-fancy',
            self::SIGNED => 'fas fa-file-signature',
            self::ORIGINAL => 'fas fa-stamp',
            self::AWAITING_PAYMENT => 'fas fa-clock',
            self::PAID => 'fas fa-ruble-sign',
            default => 'fas fa-file-alt',
        };
    }
}
