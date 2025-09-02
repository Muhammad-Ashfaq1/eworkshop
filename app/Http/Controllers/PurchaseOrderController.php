<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseOrderRequest;
use App\Http\Requests\UpdatePurchaseOrderRequest;
use App\Interfaces\PurchaseOrderRepositoryInterface;
use App\Models\PurchaseOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    private $purchaseOrderRepository;

    public function __construct(PurchaseOrderRepositoryInterface $purchaseOrderRepository)
    {
        $this->purchaseOrderRepository = $purchaseOrderRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('read_purchase_orders');
        
        $user = Auth::user();

        // Get purchase orders based on user role
        $purchaseOrders = $this->purchaseOrderRepository->getPurchaseOrdersForUser($user, 15);

        return view('purchase_orders.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create_purchase_orders');
        
        // Redirect to index page since we use modals
        return redirect()->route('purchase-orders.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('read_purchase_orders');
        
        $user = Auth::user();

        // Check if user can view this specific purchase order
        if (!$this->canViewPurchaseOrder($user, $purchaseOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this purchase order.'
            ], 403);
        }

        // Load the purchase order with relationships
        $purchaseOrder = $this->purchaseOrderRepository->getPurchaseOrderById($purchaseOrder->id);

        // Ensure we have the works relationship loaded
        if (!$purchaseOrder->relationLoaded('works')) {
            $purchaseOrder->load('works.vehiclePart');
        }

        return response()->json([
            'success' => true,
            'purchaseOrder' => $purchaseOrder,
        ]);
    }

    /**
     * Get purchase orders listing for datatable
     * @param Request $request
     * @return JsonResponse
     */
    public function getPurchaseOrderListing(Request $request): JsonResponse
    {
        $this->authorize('read_purchase_orders');
        
        $user = Auth::user();
        return $this->purchaseOrderRepository->getPurchaseOrderListing($request->all(), $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PurchaseOrderRequest $request)
    {
        $this->authorize('create_purchase_orders');

        return $this->purchaseOrderRepository->createPurchaseOrder($request->all());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $this->authorize('read_purchase_orders');
        
        $purchaseOrder = $this->purchaseOrderRepository->getPurchaseOrderById($id);

        if (!$purchaseOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }

        // Check if user can view this specific purchase order
        $user = Auth::user();
        if (!$this->canViewPurchaseOrder($user, $purchaseOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to view this purchase order.'
            ], 403);
        }

        // Ensure we have the works relationship loaded
        if (!$purchaseOrder->relationLoaded('works')) {
            $purchaseOrder->load('works.vehiclePart');
        }

        return response()->json([
            'success' => true,
            'purchaseOrder' => $purchaseOrder,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseOrderRequest $request, PurchaseOrder $purchaseOrder)
    {
        $this->authorize('update_purchase_orders');

        $user = Auth::user();

        // Check if user can update this specific purchase order
        if (!$this->canViewPurchaseOrder($user, $purchaseOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this purchase order.'
            ], 403);
        }

        return $this->purchaseOrderRepository->updatePurchaseOrder($purchaseOrder->id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        $this->authorize('delete_purchase_orders');

        $user = Auth::user();

        // Check if user can delete this specific purchase order
        if (!$this->canViewPurchaseOrder($user, $purchaseOrder)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this purchase order.'
            ], 403);
        }

        return $this->purchaseOrderRepository->deletePurchaseOrder($purchaseOrder->id);
    }

    /**
     * Show archived purchase orders
     */
    public function archived()
    {
        $this->authorize('read_purchase_orders');
        $archivedPurchaseOrders = PurchaseOrder::with(['creator', 'defectReport.vehicle', 'defectReport.location'])->onlyTrashed()->get();
        return view('purchase_orders.archived', compact('archivedPurchaseOrders'));
    }

    /**
     * Restore archived purchase order
     */
    public function restoreArchived($id)
    {
        $this->authorize('restore_purchase_orders');
        $purchaseOrder = PurchaseOrder::withTrashed()->find($id);
        
        if (!$purchaseOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase order not found'
            ], 404);
        }
        
        $purchaseOrder->restore();
        
        return response()->json([
            'success' => true,
            'message' => 'Purchase order restored successfully'
        ]);
    }

    /**
     * Check if user can view the purchase order
     */
    private function canViewPurchaseOrder($user, $purchaseOrder)
    {
        // Super admin and admin can view all purchase orders
        if ($user->can('read_purchase_orders')) {
            return true;
        }
        
        // DEO can only view their own purchase orders
        if ($user->hasRole('deo')) {
            return $purchaseOrder->created_by == $user->id;
        }

        return false;
    }
}
