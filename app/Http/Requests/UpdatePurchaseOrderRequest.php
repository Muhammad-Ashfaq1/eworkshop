<?php

namespace App\Http\Requests;

use App\Enums\PurchaseOrderReceivedBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePurchaseOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $purchaseOrder = $this->route('purchaseOrder');
        $ignoreId = $purchaseOrder instanceof \App\Models\PurchaseOrder ? $purchaseOrder->id : $purchaseOrder;

        return [
            'defect_report_id' => 'required|exists:defect_reports,id',
            'po_no' => [
                'required',
                'string',
                'max:255',
                Rule::unique('purchase_orders', 'po_no')
                    ->ignore($ignoreId)
                    ->where(function ($query) use ($purchaseOrder, $ignoreId) {
                        $issueDate = $this->input('issue_date');
                        if (! $issueDate && $purchaseOrder instanceof \App\Models\PurchaseOrder) {
                            $issueDate = $purchaseOrder->issue_date;
                        } elseif (! $issueDate && is_numeric($ignoreId)) {
                            $issueDate = \App\Models\PurchaseOrder::where('id', $ignoreId)->value('issue_date');
                        }

                        if ($issueDate) {
                            try {
                                $fy = \App\Models\PurchaseOrder::getFiscalYearRange($issueDate);
                                $query->whereBetween('issue_date', [$fy['start_date'], $fy['end_date']]);
                            } catch (\Exception $e) {
                                // Invalid date will be caught by issue_date validation
                            }
                        }

                        return $query->whereNull('deleted_at');
                    }),
            ],
            'issue_date' => 'required|date|after_or_equal:2026-07-01|before_or_equal:2027-06-30',
            'received_by' => ['required', Rule::enum(PurchaseOrderReceivedBy::class)],
            'received_by_other' => ['nullable', 'string', 'max:255', 'required_if:received_by,'.PurchaseOrderReceivedBy::Other->value],
            'acc_amount' => 'required|numeric|min:0',
            'attachment_url' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'parts' => 'required|array|min:1',
            'parts.*.vehicle_part_id' => 'required|exists:vehicle_parts,id',
            'parts.*.quantity' => 'required|integer|min:1',
            'parts.*.details' => 'nullable|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'defect_report_id.required' => 'Please select a defect report reference.',
            'defect_report_id.exists' => 'The selected defect report reference is invalid.',
            'po_no.required' => 'Please enter the purchase order number.',
            'po_no.unique' => 'This purchase order number already exists for this financial year (July to June).',
            'issue_date.required' => 'Please select the issue date.',
            'issue_date.after_or_equal' => 'The purchase order issue date must be on or after 1st July 2026.',
            'issue_date.before_or_equal' => 'The purchase order issue date must be on or before 30th June 2027.',
            'received_by.required' => 'Please select who received the order.',
            'received_by_other.required_if' => 'Please enter who received the order.',
            'acc_amount.required' => 'Please enter the account amount.',
            'acc_amount.numeric' => 'Account amount must be a number.',
            'acc_amount.min' => 'Account amount must be greater than or equal to 0.',
            'attachment_url.file' => 'Please upload a valid file.',
            'attachment_url.mimes' => 'Please upload a file in PDF, DOC, DOCX, JPG, JPEG, or PNG format.',
            'attachment_url.max' => 'File size must not exceed 2MB.',
            'parts.required' => 'Please add at least one part.',
            'parts.min' => 'Please add at least one part.',
            'parts.*.vehicle_part_id.required' => 'Please select a vehicle part.',
            'parts.*.vehicle_part_id.exists' => 'The selected vehicle part is invalid.',
            'parts.*.quantity.required' => 'Please enter the quantity.',
            'parts.*.quantity.integer' => 'Quantity must be a whole number.',
            'parts.*.quantity.min' => 'Quantity must be at least 1.',
        ];
    }
}
