<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date in the standard format: Aug 28, 2025
     */
    public static function formatDate($date, $includeTime = false): string
    {
        if (!$date) {
            return 'N/A';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        if ($includeTime) {
            return $date->format('M d, Y g:i A');
        }

        return $date->format('M d, Y');
    }

    /** Convert the table's displayed/input date formats to an ISO date for searching. */
    public static function parseSearchDate(string $value): ?string
    {
        foreach (['M d, Y', 'Y-m-d', 'd/m/Y', 'm/d/Y', 'd-m-Y', 'm-d-Y', 'd m Y'] as $format) {
            try {
                $date = Carbon::createFromFormat('!' . $format, $value);
            } catch (\Throwable) {
                continue;
            }

            if ($date && $date->format($format) === $value) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    /**
     * Format created_at date
     */
    public static function formatCreatedAt($date): string
    {
        return self::formatDate($date);
    }

    /**
     * Format updated_at date
     */
    public static function formatUpdatedAt($date): string
    {
        return self::formatDate($date);
    }

    /**
     * Format date for JavaScript (moment.js) - returns ISO string
     */
    public static function formatForJs($date): string
    {
        if (!$date) {
            return '';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->toISOString();
    }

    /**
     * Get relative time (e.g., "2 hours ago")
     */
    public static function getRelativeTime($date): string
    {
        if (!$date) {
            return 'N/A';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->diffForHumans();
    }
}
