@if (!empty($vehicle_categories))
    @foreach ($vehicle_categories as $vehicle_category)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $vehicle_category->name }}</td>
            <td>
                @if ($vehicle_category->is_active)
                    <span class="status-badge active with-icon">
                        <i class="ri-check-line"></i>Active
                    </span>
                @else
                    <span class="status-badge inactive with-icon">
                        <i class="ri-close-line"></i>Inactive
                    </span>
                @endif
            </td>
            <td>{{ formatCreatedAt($vehicle_category->created_at) }}</td>
            <td>{{ formatUpdatedAt($vehicle_category->updated_at) }}</td>
            <td>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="ri-more-fill align-middle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @can('update_vehicle_categories')


                            <li><a href="{{ route('admin.vehicle-categories.edit',$vehicle_category->id) }}"
                                    class="dropdown-item edit-vehicle-categories-btn"><i
                                        class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a>

                            </li>
                            @endcan

                            @can('delete_vehicle_categories')

                            <li>
                                <a href="#"
                                    class="dropdown-item js-vehicle-categories-delete-btn" data-id="{{ $vehicle_category->id }}">
                                    <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                                </a>
                            </li>
                            @endcan

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
