<?php

namespace App\Repositories;

use App\Constants\UserRoles;
use App\Interfaces\DeoStatsRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class DeoStatsRepository implements DeoStatsRepositoryInterface
{
    public function getListing(array $data): JsonResponse
    {
        try {
            $data = array_merge([
                'start' => 0,
                'length' => 10,
                'draw' => 1,
                'search' => ['value' => ''],
                'order' => [],
                'columns' => [],
            ], $data);

            [$dateFrom, $dateTo] = $this->resolveDateRange($data);
            $searchValue = trim($data['search']['value'] ?? '');
            $pageLength = (int) $data['length'];
            $skip = (int) $data['start'];

            $query = $this->baseQuery($dateFrom, $dateTo);

            if ($searchValue !== '') {
                $query->where(function (Builder $q) use ($searchValue) {
                    $q->where('first_name', 'like', "%{$searchValue}%")
                        ->orWhere('last_name', 'like', "%{$searchValue}%")
                        ->orWhere('email', 'like', "%{$searchValue}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$searchValue}%"]);
                });
            }

            $recordsFiltered = (clone $query)->count();
            $recordsTotal = User::role(UserRoles::DEO)->count();

            $this->applyOrdering($query, $data);

            $rows = $query->skip($skip)->take($pageLength)->get()->map(function (User $user, int $index) use ($skip) {
                return [
                    'sr_no' => $skip + $index + 1,
                    'id' => $user->id,
                    'name' => $user->full_name,
                    'email' => $user->email,
                    'status' => $user->is_active
                        ? '<span class="badge bg-success-subtle text-success"><i class="ri-check-line me-1"></i>Active</span>'
                        : '<span class="badge bg-danger-subtle text-danger"><i class="ri-close-line me-1"></i>Inactive</span>',
                    'defect_reports_count' => (int) $user->defect_reports_count,
                    'purchase_orders_count' => (int) $user->purchase_orders_count,
                ];
            });

            return response()->json([
                'draw' => (int) $data['draw'],
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $rows,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load DEO stats: '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCards(array $filters): JsonResponse
    {
        try {
            [$dateFrom, $dateTo] = $this->resolveDateRange($filters);

            $deos = $this->baseQuery($dateFrom, $dateTo)
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get()
                ->map(function (User $user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->full_name,
                        'email' => $user->email,
                        'image_url' => $user->image_url,
                        'is_active' => (bool) $user->is_active,
                        'defect_reports_count' => (int) $user->defect_reports_count,
                        'purchase_orders_count' => (int) $user->purchase_orders_count,
                        'total_count' => (int) $user->defect_reports_count + (int) $user->purchase_orders_count,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $deos,
                'totals' => [
                    'defect_reports' => $deos->sum('defect_reports_count'),
                    'purchase_orders' => $deos->sum('purchase_orders_count'),
                    'deos' => $deos->count(),
                ],
                'date_from' => $dateFrom?->toDateString(),
                'date_to' => $dateTo?->toDateString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load DEO cards: '.$e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function baseQuery($dateFrom, $dateTo): Builder
    {
        return User::role(UserRoles::DEO)
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.image_url', 'users.is_active')
            ->withCount([
                'defectReports as defect_reports_count' => fn (Builder $q) => $this->applyCreatedAtRange($q, $dateFrom, $dateTo),
                'purchaseOrders as purchase_orders_count' => fn (Builder $q) => $this->applyCreatedAtRange($q, $dateFrom, $dateTo),
            ]);
    }

    /**
     * @return array{0:?string,1:?string}
     */
    private function resolveDateRange(array $data): array
    {
        $dateFrom = ! empty($data['date_from']) ? Carbon::parse($data['date_from'])->startOfDay() : null;
        $dateTo = ! empty($data['date_to']) ? Carbon::parse($data['date_to'])->endOfDay() : null;

        return [$dateFrom, $dateTo];
    }

    private function applyCreatedAtRange(Builder $query, $dateFrom, $dateTo): void
    {
        if ($dateFrom) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('created_at', '<=', $dateTo);
        }
    }

    private function applyOrdering(Builder $query, array $data): void
    {
        $orderable = [
            'name' => 'first_name',
            'email' => 'email',
            'defect_reports_count' => 'defect_reports_count',
            'purchase_orders_count' => 'purchase_orders_count',
            'status' => 'is_active',
        ];

        if (! empty($data['order'][0])) {
            $columnIndex = (int) ($data['order'][0]['column'] ?? 0);
            $direction = ($data['order'][0]['dir'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
            $columnName = $data['columns'][$columnIndex]['name'] ?? 'name';

            if (isset($orderable[$columnName])) {
                $query->orderBy($orderable[$columnName], $direction);

                if ($columnName === 'name') {
                    $query->orderBy('last_name', $direction);
                }

                return;
            }
        }

        $query->orderByDesc('defect_reports_count')->orderBy('first_name');
    }
}
