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
                <!-- Action buttons (Edit, Delete) can be added here -->
                <button class="btn btn-sm btn-primary edit-btn">Edit</button>
                <!-- Add delete button if needed -->
            </td>
        </tr>
    @endforeach

    @else
        <tr>
            <td colspan="7" class="text-center">No Fleet Managers/Mvi found.</td>
        </tr>
@endif
