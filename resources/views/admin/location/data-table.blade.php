@if(!empty($locations))
    @foreach($locations as $location)
    <tr>
    <th scope="row">
        <div class="form-check">
            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
        </div>
    </th>
    <td>{{ $location->id }}</td>
    <td>{{  $location->name }}</td>
    <td>{{ $location->slug }}</td>
    <td>
        @if($location->is_active)
            <span class="badge bg-success">Active</span>
        @else
            <span class="badge bg-danger">Inactive</span>
        @endif
    </td>
    <td>{{ $location->created_at->format('d-M-Y') }}</td>
    <td>{{ $location->updated_at->format('d-M-Y') }}</td>

    <td>
        <div class="dropdown d-inline-block">
            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ri-more-fill align-middle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                <li><a href="" class="dropdown-item edit-item-btn" data-id={{ $location->id }}><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                <li>
                    <a href="{{ route('admin.location.destroy', $location->id) }}" class="dropdown-item remove-item-btn" id="location-delete-btn" data-id={{ $location->id}}>
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
        <td colspan="8" class="text-center">No locations found.</td>
    </tr>
@endif
