    @if(!@empty($vehicles))
        @foreach($vehicles as $vehicle)
            <tr>
        <div class="form-check">
            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
        </div>
    </th>
    <td>{{ $vehicle->id }}</td>
    <td>{{ $vehicle->location->name }}</td>
    <td>slug</td>
     <td>{{ $vehicle->category->name }}</td>

    <td>
        {{ $vehicle->is_active ? 'Active' : 'Inactive' }}
    </td>
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
    @endif

