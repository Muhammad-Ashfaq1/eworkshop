@if (!empty($fleetManagers))
    @foreach ($fleetManagers as $fleetManager)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $fleetManager->name }}</td>
            <td>{{ ucfirst($fleetManager->type) }}</td>
            <td>
                @if ($fleetManager->is_active)
                    <span class="status-badge active with-icon">
                        <i class="ri-check-line"></i>Active
                    </span>
                @else
                    <span class="status-badge inactive with-icon">
                        <i class="ri-close-line"></i>Inactive
                    </span>
                @endif
            </td>
            <td>{{ formatCreatedAt($fleetManager->created_at) }}</td>
            <td>{{ formatUpdatedAt($fleetManager->updated_at) }}</td>
            <td>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ri-more-fill align-middle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">

                        @if (auth()->user()->can('update_fleet_manager'))
                            <li><a href="{{ route('admin.fleet-manager.edit', $fleetManager->id) }}"
                                    class="dropdown-item edit-fleet-manager-btn"><i
                                        class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>

                            </li>
                        @endif


                        @if (auth()->user()->can('delete_fleet_manager'))
                            <li>
                                <a href="{{ route('admin.fleet-manager.destroy', $fleetManager->id) }}"
                                    class="dropdown-item remove-item-btn" id="js-fleet-manager-delete-btn">
                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                </a>
                            </li>
                        @endif
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
