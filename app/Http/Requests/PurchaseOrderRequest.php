<?php

namespace App\Http\Requests;

use App\Enums\PurchaseOrderReceivedBy;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseOrderRequest extends FormRequest
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
        return [
            'defect_report_id' => [
                'required',
                'exists:defect_reports,id',
                function ($attribute, $value, $fail) {
                    // Check if defect report already has a purchase order
                    $existingPO = \App\Models\PurchaseOrder::where('defect_report_id', $value)->exists();
                    if ($existingPO) {
                        $fail('This defect report already has a purchase order.');
                    }
                },
            ],
            'po_no' => 'required|string|max:255|unique:purchase_orders,po_no',
            'issue_date' => 'required|date|after_or_equal:2026-07-01|before_or_equal:2027-06-30',
            'received_by' => ['required', Rule::enum(PurchaseOrderReceivedBy::class)],
            'received_by_other' => ['nullable', 'string', 'max:255', 'required_if:received_by,'.PurchaseOrderReceivedBy::Other->value],
            'acc_amount' => 'required|numeric|min:0',
            'attachment_url' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'parts' => 'required|array|min:1',
            'parts.*.vehicle_part_id' => 'required|exists:vehicle_parts,id',
            'parts.*.quantity' => 'required|integer|min:1',
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
            'po_no.unique' => 'This purchase order number already exists.',
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
