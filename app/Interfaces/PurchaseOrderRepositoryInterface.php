<?php

namespace App\Interfaces;

interface PurchaseOrderRepositoryInterface
{
    public function getPurchaseOrderListing($data, $user);
    public function getPurchaseOrderById($id);
    public function createPurchaseOrder($data);
    public function updatePurchaseOrder($id, $data);
    public function deletePurchaseOrder($id);
    public function getPurchaseOrdersForUser($user, $perPage = 15);
    public function canViewPurchaseOrder($user, $purchaseOrder);
}
