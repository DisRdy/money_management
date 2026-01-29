<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Transaction;

class TransactionObserver
{
    /**
     * Fields to exclude from change tracking (noise reduction)
     */
    protected array $excludedFields = [
        'created_at',
        'updated_at',
    ];

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        $this->logAction('created', $transaction, null, $this->cleanAttributes($transaction->getAttributes()));
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        $changes = $this->getCleanChanges($transaction);

        // Only log if there are meaningful changes
        if (!empty($changes['new'])) {
            $this->logAction('updated', $transaction, $changes['old'], $changes['new']);
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        $this->logAction('deleted', $transaction, $this->cleanAttributes($transaction->getOriginal()), null);
    }

    /**
     * Log the action to audit_logs table
     */
    private function logAction(string $action, Transaction $transaction, ?array $oldValues, ?array $newValues): void
    {
        // Skip if no authenticated user (e.g., during seeding or testing)
        if (!auth()->check()) {
            return;
        }

        AuditLog::create([
            'tenant_id' => $transaction->tenant_id,
            'user_id' => auth()->id(),
            'target_user_id' => $transaction->user_id,
            'action' => $action,
            'model_type' => Transaction::class,
            'model_id' => $transaction->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get cleaned changes (only fields that actually changed, excluding noise)
     */
    private function getCleanChanges(Transaction $transaction): array
    {
        $old = [];
        $new = [];

        foreach ($transaction->getChanges() as $key => $value) {
            if (!in_array($key, $this->excludedFields)) {
                $old[$key] = $transaction->getOriginal($key);
                $new[$key] = $value;
            }
        }

        return ['old' => $old, 'new' => $new];
    }

    /**
     * Remove excluded fields from attributes
     */
    private function cleanAttributes(array $attributes): array
    {
        return array_diff_key($attributes, array_flip($this->excludedFields));
    }
}
