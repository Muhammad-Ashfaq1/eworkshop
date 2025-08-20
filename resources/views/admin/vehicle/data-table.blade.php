@if(!empty($vehicles))
    @foreach($vehicles as $index => $vehicle)
    <tr>
    <th scope="row">
        <div class="form-check">
            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
        </div>
    </th>
    <td>{{ $index + 1 }}</td>
    <td>{{ $vehicle->vehicle_number }}</td>
    <td>{{ $vehicle->location->name ?? 'N/A' }}</td>
    <td>{{ $vehicle->category->name ?? 'N/A' }}</td>
    <td>{{ ucfirst($vehicle->condition) }}</td>
    <td>
        @if($vehicle->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-danger">Inactive</span>
        @endif
    </td>
    <td>{{ $vehicle->created_at ? $vehicle->created_at->format('Y-m-d') : 'N/A' }}</td>
    <td>{{ $vehicle->updated_at ? $vehicle->updated_at->format('Y-m-d') : 'N/A' }}</td>
    <td>
        <div class="dropdown d-inline-block">
            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ri-more-fill align-middle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                <li><a href="{{ route('admin.vehicle.edit', $vehicle->id)}}"  class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                <li>
                    <a href="{{ route('admin.vehicle.destroy', $vehicle->id) }}" class="dropdown-item remove-item-btn" id="vehicle-delete-btn" >
                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                    </a>
                </li>
            </ul>
        </div>
    </td>
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="10" class="text-center">No vehicles found</td>
    </tr>
@endif
