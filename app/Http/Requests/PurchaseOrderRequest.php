<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'defect_report_id' => 'required|exists:defect_reports,id',
            'po_no' => 'required|string|max:255|unique:purchase_orders,po_no',
            'issue_date' => 'required|date',
            'received_by' => 'required|string|max:255',
            'acc_amount' => 'required|numeric|min:0',
            'attachment_url' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'parts' => 'required|array|min:1',
            'parts.*.vehicle_part_id' => 'required|exists:vehicle_parts,id',
            'parts.*.quantity' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'defect_report_id.required' => 'Please select a defect report reference.',
            'defect_report_id.exists' => 'The selected defect report reference is invalid.',
            'po_no.required' => 'Please enter the purchase order number.',
            'po_no.unique' => 'This purchase order number already exists.',
            'issue_date.required' => 'Please select the issue date.',
            'received_by.required' => 'Please enter who received the order.',
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
