<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface DeoStatsRepositoryInterface
{
    /**
     * Get DEO performance stats for DataTables.
     */
    public function getListing(array $data): JsonResponse;

    /**
     * Get DEO performance cards for dashboard widgets.
     */
    public function getCards(array $filters): JsonResponse;
}
