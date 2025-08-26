@if(!empty($fleetManagers))
    @foreach ($fleetManagers as  $fleetManager)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $fleetManager->name }}</td>
            <td>{{$fleetManager->type}}</td>
            <td>
                @if ($fleetManager->is_active)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $fleetManager->created_at->format('Y-m-d H:i') }}</td>
            <td>{{ $fleetManager->updated_at->format('Y-m-d H:i') }}</td>
           <td>
        <div class="dropdown d-inline-block">
            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ri-more-fill align-middle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                @if(auth()->user()->can('edit fleet manager/mvi'))
                <li><a href="" class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                <li>
                    <a href="{{ route('admin.fleet-manager.destory',$fleetManager->id) }}" class="dropdown-item remove-item-btn" id="location-delete-btn" >
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
            <td colspan="7" class="text-center">No Fleet Managers/Mvi found.</td>
        </tr>
@endif
