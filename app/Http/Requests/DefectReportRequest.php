<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefectReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'location_id' => 'nullable|exists:locations,id',
            'driver_name' => 'required|string|max:255',
            'fleet_manager_id' => 'nullable|exists:users,id',
            'mvi_id' => 'nullable|exists:users,id',
            'date' => 'required|date',
            'attach_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'type' => 'required|in:defect_report,purchase_order',
            'works' => 'required|array|min:1',
            'works.*.work' => 'nullable|string|max:300',
            'works.*.type' => 'required|in:defect,purchase_order',
            'works.*.quantity' => 'nullable|integer|min:1',
            'works.*.vehicle_part_id' => 'nullable|exists:vehicle_parts,id'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vehicle_id.exists' => 'The selected vehicle is invalid.',
            'location_id.exists' => 'The selected office/town is invalid.',
            'fleet_manager_id.exists' => 'The selected fleet manager is invalid.',
            'mvi_id.exists' => 'The selected MVI is invalid.',
            'works.required' => 'At least one work item is required.',
            'works.min' => 'At least one work item is required.',
            'works.*.work.max' => 'Work description cannot exceed 300 characters.',
            'works.*.type.required' => 'Work type is required.',
            'works.*.type.in' => 'Work type must be defect or purchase order.',
            'works.*.quantity.integer' => 'Quantity must be a whole number.',
            'works.*.quantity.min' => 'Quantity must be at least 1.',
            'works.*.vehicle_part_id.exists' => 'The selected vehicle part is invalid.'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $works = $this->input('works', []);
            
            foreach ($works as $index => $work) {
                // If work type is purchase_order, vehicle_part_id is required
                if ($work['type'] === 'purchase_order' && empty($work['vehicle_part_id'])) {
                    $validator->errors()->add("works.{$index}.vehicle_part_id", 'Vehicle part is required for purchase order works.');
                }
                
                // If work type is purchase_order, quantity is required
                if ($work['type'] === 'purchase_order' && empty($work['quantity'])) {
                    $validator->errors()->add("works.{$index}.quantity", 'Quantity is required for purchase order works.');
                }
                
                // If work type is defect, work description is required
                if ($work['type'] === 'defect' && empty($work['work'])) {
                    $validator->errors()->add("works.{$index}.work", 'Work description is required for defect works.');
                }
            }
        });
    }
}
