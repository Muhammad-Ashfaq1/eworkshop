<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\DeoStatsRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeoStatsController extends Controller
{
    public function __construct(
        private readonly DeoStatsRepositoryInterface $deoStatsRepository
    ) {}

    public function index()
    {
        $this->authorize('access_admin_panel');

        return view('admin.deo-stats.index');
    }

    public function listing(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');

        return $this->deoStatsRepository->getListing($request->all());
    }

    public function cards(Request $request): JsonResponse
    {
        $this->authorize('access_admin_panel');

        return $this->deoStatsRepository->getCards($request->only(['date_from', 'date_to']));
    }
}
