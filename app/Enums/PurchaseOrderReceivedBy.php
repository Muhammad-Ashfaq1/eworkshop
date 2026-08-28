<?php

namespace App\Enums;

enum PurchaseOrderReceivedBy: string
{
    case StoreKeeper = 'store_keeper';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::StoreKeeper => 'Store Keeper',
            self::Other => 'Other',
        };
    }
}
