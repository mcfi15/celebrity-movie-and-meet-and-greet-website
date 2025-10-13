<?php

namespace App\Services;

use App\Mail\ActivityNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * NotificationService
 * 
 * Handles all system notifications and activity tracking for the inventory management system.
 * Provides email notifications and logging for various activities including CRUD operations,
 * user authentication, product requests workflow, and inventory transactions.
 * 
 * Product Request Workflow Notifications:
 * - requestCreated()        : When a new product request is submitted
 * - requestStatusChanged()  : When a request status changes (generic status change tracking)
 * - requestApproved()       : When a request is approved by an administrator
 * - requestRejected()       : When a request is rejected by an administrator  
 * - requestIssued()         : When products are actually issued and stock is deducted
 * - requestCompleted()      : When a request is fully completed (end-to-end tracking)
 * - requestDeleted()        : When a request is deleted
 * 
 * Inventory Management Notifications:
 * - lowStockAlert()    : Alerts when product stock falls below minimum levels
 * - stockDeducted()    : Tracks when inventory is reduced
 * - stockAdded()       : Tracks when inventory is increased
 * - transactionCreated(): Logs all inventory transaction records
 * 
 * CRUD Operations Notifications:
 * - {resource}Created(), {resource}Updated(), {resource}Deleted() for Products, Categories, Suppliers, Users
 * 
 * Authentication Notifications:
 * - userLogin(), userLogout() : Track user authentication events
 * 
 * Usage Examples:
 * NotificationService::requestApproved($productRequest);
 * NotificationService::requestStatusChanged($productRequest, 'pending', 'approved');
 * NotificationService::requestCompleted($productRequest);
 * NotificationService::lowStockAlert($product);
 * NotificationService::stockDeducted($product, 5, 'Product Request Fulfilled');
 */

class NotificationService
{
    /**
     * Send activity notification email
     *
     * @param string $action The action performed (Created, Updated, Deleted, etc.)
     * @param string $resource The resource type (Product, Category, Supplier, etc.)
     * @param array $data Additional data about the activity
     * @param mixed $model The model instance (optional)
     * @return void
     */
    public static function sendActivityNotification($action, $resource, $data = [], $model = null)
    {
        try {
            $user = Auth::user();

            $activityData = [
                'action' => $action,
                'resource' => $resource,
                'user_name' => $user ? $user->name : 'System',
                'user_email' => $user ? $user->email : 'system@inventory.com',
                'user_role' => $user && $user->role ? $user->role : 'Unknown',
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'data' => $data,
                'model' => $model,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];

            // Get notification email from config
            $notificationEmail = config('notifications.email', env('NOTIFICATION_EMAIL'));

            if ($notificationEmail) {
                Mail::to($notificationEmail)->send(new ActivityNotification($activityData));
            }

            // Log the activity for backup
            Log::info('Inventory Activity', $activityData);

        } catch (\Exception $e) {
            // Log the error but don't break the application
            Log::error('Failed to send activity notification: ' . $e->getMessage(), [
                'action' => $action,
                'resource' => $resource,
                'data' => $data
            ]);
        }
    }

    /**
     * Send specific notifications for different actions
     */
    public static function productCreated($product)
    {
        self::sendActivityNotification('Created', 'Product', [
            'name' => $product->name,
            'sku' => $product->sku,
            'quantity' => $product->quantity,
            'price' => $product->price,
            'category' => $product->category ? $product->category->name : 'N/A',
            'supplier' => $product->supplier ? $product->supplier->name : 'N/A',
        ], $product);
    }

    public static function productUpdated($product, $oldData = [])
    {
        self::sendActivityNotification('Updated', 'Product', [
            'name' => $product->name,
            'sku' => $product->sku,
            'quantity' => $product->quantity,
            'price' => $product->price,
            'category' => $product->category ? $product->category->name : 'N/A',
            'supplier' => $product->supplier ? $product->supplier->name : 'N/A',
            'old_data' => $oldData,
        ], $product);
    }

    public static function productDeleted($product)
    {
        self::sendActivityNotification('Deleted', 'Product', [
            'name' => $product->name,
            'sku' => $product->sku,
        ], $product);
    }

    public static function categoryCreated($category)
    {
        self::sendActivityNotification('Created', 'Category', [
            'name' => $category->name,
            'description' => $category->description,
        ], $category);
    }

    public static function categoryUpdated($category, $oldData = [])
    {
        self::sendActivityNotification('Updated', 'Category', [
            'name' => $category->name,
            'description' => $category->description,
            'old_data' => $oldData,
        ], $category);
    }

    public static function categoryDeleted($category)
    {
        self::sendActivityNotification('Deleted', 'Category', [
            'name' => $category->name,
        ], $category);
    }

    public static function supplierCreated($supplier)
    {
        self::sendActivityNotification('Created', 'Supplier', [
            'name' => $supplier->name,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
        ], $supplier);
    }

    public static function supplierUpdated($supplier, $oldData = [])
    {
        self::sendActivityNotification('Updated', 'Supplier', [
            'name' => $supplier->name,
            'email' => $supplier->email,
            'phone' => $supplier->phone,
            'old_data' => $oldData,
        ], $supplier);
    }

    public static function supplierDeleted($supplier)
    {
        self::sendActivityNotification('Deleted', 'Supplier', [
            'name' => $supplier->name,
        ], $supplier);
    }

    public static function requestCreated($request)
    {
        self::sendActivityNotification('Created', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'priority' => $request->priority ?? 'Normal',
            'notes' => $request->notes ?? 'N/A',
        ], $request);
    }

    public static function requestUpdated($request, $oldData = [])
    {
        self::sendActivityNotification('Updated', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'priority' => $request->priority ?? 'Normal',
            'notes' => $request->notes ?? 'N/A',
            'old_data' => $oldData,
        ], $request);
    }

    public static function requestApproved($request)
    {
        self::sendActivityNotification('Approved', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'quantity_approved' => $request->quantity_approved,
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'approved_by' => $request->approver ? $request->approver->name : 'N/A',
            'approved_by_email' => $request->approver ? $request->approver->email : 'N/A',
            'approved_at' => $request->approved_at ? $request->approved_at->format('Y-m-d H:i:s') : 'N/A',
            'approval_notes' => $request->approval_notes ?? 'N/A',
            'priority' => $request->priority ?? 'Normal',
        ], $request);
    }

    public static function requestRejected($request)
    {
        self::sendActivityNotification('Rejected', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'rejected_by' => $request->approver ? $request->approver->name : 'N/A',
            'rejected_by_email' => $request->approver ? $request->approver->email : 'N/A',
            'rejected_at' => $request->approved_at ? $request->approved_at->format('Y-m-d H:i:s') : 'N/A',
            'rejection_reason' => $request->approval_notes ?? 'N/A',
            'priority' => $request->priority ?? 'Normal',
        ], $request);
    }

    public static function requestIssued($request, $quantityIssued = null)
    {
        self::sendActivityNotification('Issued', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'quantity_approved' => $request->quantity_approved,
            'quantity_issued' => $quantityIssued ?? $request->quantity_issued,
            'remaining_stock' => $request->product ? $request->product->quantity : 'N/A',
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'approved_by' => $request->approver ? $request->approver->name : 'N/A',
            'issued_by' => Auth::user() ? Auth::user()->name : 'System',
            'issued_by_email' => Auth::user() ? Auth::user()->email : 'system@inventory.com',
            'issued_at' => now()->format('Y-m-d H:i:s'),
            'priority' => $request->priority ?? 'Normal',
        ], $request);
    }

    public static function requestStatusChanged($request, $oldStatus, $newStatus)
    {
        self::sendActivityNotification('Status Changed', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'changed_by' => Auth::user() ? Auth::user()->name : 'System',
            'changed_by_email' => Auth::user() ? Auth::user()->email : 'system@inventory.com',
            'priority' => $request->priority ?? 'Normal',
            'notes' => $request->notes ?? 'N/A',
        ], $request);
    }

    public static function requestCompleted($request)
    {
        self::sendActivityNotification('Completed', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'quantity_approved' => $request->quantity_approved,
            'quantity_issued' => $request->quantity_issued,
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
            'requested_by_email' => $request->user ? $request->user->email : 'N/A',
            'approved_by' => $request->approver ? $request->approver->name : 'N/A',
            'approved_by_email' => $request->approver ? $request->approver->email : 'N/A',
            'completed_by' => Auth::user() ? Auth::user()->name : 'System',
            'completed_by_email' => Auth::user() ? Auth::user()->email : 'system@inventory.com',
            'requested_at' => $request->created_at ? $request->created_at->format('Y-m-d H:i:s') : 'N/A',
            'approved_at' => $request->approved_at ? $request->approved_at->format('Y-m-d H:i:s') : 'N/A',
            'completed_at' => now()->format('Y-m-d H:i:s'),
            'total_processing_time' => $request->created_at ? $request->created_at->diffForHumans(now(), true) : 'N/A',
            'priority' => $request->priority ?? 'Normal',
            'final_notes' => $request->approval_notes ?? 'N/A',
        ], $request);
    }

    public static function requestDeleted($request)
    {
        self::sendActivityNotification('Deleted', 'Product Request', [
            'product' => $request->product ? $request->product->name : 'N/A',
            'sku' => $request->product ? $request->product->sku : 'N/A',
            'requested_quantity' => $request->requested_quantity,
            'status' => $request->status,
            'requested_by' => $request->user ? $request->user->name : 'N/A',
        ], $request);
    }

    public static function lowStockAlert($product)
    {
        self::sendActivityNotification('Low Stock Alert', 'Product Inventory', [
            'product' => $product->name,
            'sku' => $product->sku,
            'current_quantity' => $product->quantity,
            'minimum_quantity' => $product->minimum_quantity ?? 0,
            'category' => $product->category ? $product->category->name : 'N/A',
            'supplier' => $product->supplier ? $product->supplier->name : 'N/A',
            'supplier_email' => $product->supplier ? $product->supplier->email : 'N/A',
            'supplier_phone' => $product->supplier ? $product->supplier->phone : 'N/A',
            'price' => $product->price,
        ], $product);
    }

    public static function stockDeducted($product, $quantityDeducted, $reason = 'Product Request')
    {
        self::sendActivityNotification('Stock Deducted', 'Product Inventory', [
            'product' => $product->name,
            'sku' => $product->sku,
            'quantity_deducted' => $quantityDeducted,
            'remaining_quantity' => $product->quantity,
            'reason' => $reason,
            'category' => $product->category ? $product->category->name : 'N/A',
            'supplier' => $product->supplier ? $product->supplier->name : 'N/A',
            'price' => $product->price,
        ], $product);
    }

    public static function stockAdded($product, $quantityAdded, $reason = 'Stock Replenishment')
    {
        self::sendActivityNotification('Stock Added', 'Product Inventory', [
            'product' => $product->name,
            'sku' => $product->sku,
            'quantity_added' => $quantityAdded,
            'new_quantity' => $product->quantity,
            'reason' => $reason,
            'category' => $product->category ? $product->category->name : 'N/A',
            'supplier' => $product->supplier ? $product->supplier->name : 'N/A',
            'price' => $product->price,
        ], $product);
    }

    public static function transactionCreated($transaction)
    {
        self::sendActivityNotification('Transaction Created', 'Inventory Transaction', [
            'product' => $transaction->product ? $transaction->product->name : 'N/A',
            'sku' => $transaction->product ? $transaction->product->sku : 'N/A',
            'transaction_type' => $transaction->type,
            'quantity' => $transaction->quantity,
            'reference_type' => $transaction->reference_type ?? 'N/A',
            'reference_id' => $transaction->reference_id ?? 'N/A',
            'notes' => $transaction->notes ?? 'N/A',
            'performed_by' => $transaction->user ? $transaction->user->name : 'System',
            'performed_by_email' => $transaction->user ? $transaction->user->email : 'system@inventory.com',
        ], $transaction);
    }

    public static function userCreated($user)
    {
        self::sendActivityNotification('Created', 'User', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role : 'N/A',
        ], $user);
    }

    public static function userUpdated($user, $oldData = [])
    {
        self::sendActivityNotification('Updated', 'User', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role : 'N/A',
            'old_data' => $oldData,
        ], $user);
    }

    public static function userDeleted($user)
    {
        self::sendActivityNotification('Deleted', 'User', [
            'name' => $user->name,
            'email' => $user->email,
        ], $user);
    }

    public static function userLogin($user)
    {
        self::sendActivityNotification('Login', 'User Authentication', [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role ? $user->role : 'N/A',
        ], $user);
    }

    public static function userLogout($user)
    {
        self::sendActivityNotification('Logout', 'User Authentication', [
            'name' => $user->name,
            'email' => $user->email,
        ], $user);
    }
}