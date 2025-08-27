<?php

use Carbon\Carbon;

if (!function_exists('formatDate')) {
    /**
     * Format date in the standard format: Aug 28, 2025
     */
    function formatDate($date, $includeTime = false): string
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
}

if (!function_exists('formatCreatedAt')) {
    /**
     * Format created_at date
     */
    function formatCreatedAt($date): string
    {
        return formatDate($date);
    }
}

if (!function_exists('formatUpdatedAt')) {
    /**
     * Format updated_at date
     */
    function formatUpdatedAt($date): string
    {
        return formatDate($date);
    }
}

if (!function_exists('formatForJs')) {
    /**
     * Format date for JavaScript (moment.js) - returns ISO string
     */
    function formatForJs($date): string
    {
        if (!$date) {
            return '';
        }

        if (is_string($date)) {
            $date = Carbon::parse($date);
        }

        return $date->toISOString();
    }
}

if (!function_exists('getRelativeTime')) {
    /**
     * Get relative time (e.g., "2 hours ago")
     */
    function getRelativeTime($date): string
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
