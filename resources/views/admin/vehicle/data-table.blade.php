
    <tr>
    <th scope="row">
        <div class="form-check">
            <input class="form-check-input fs-15" type="checkbox" name="checkAll" value="option1">
        </div>
    </th>
    <td>1</td>
    <td>locatiuon</td>
    <td>slug</td>
     <td>type</td>

    <td>
        status
    </td>

    <td>2023-10-01</td>
    <td>2023-10-01</td>
    <td>
        <div class="dropdown d-inline-block">
            <button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ri-more-fill align-middle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a href="#!" class="dropdown-item"><i class="ri-eye-fill align-bottom me-2 text-muted"></i> View</a></li>
                <li><a href="{{ route('admin.location.edit', 2)}}"  class="dropdown-item edit-item-btn"><i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit</a></li>
                <li>
                    <a href="{{ route('admin.location.destroy', 1) }}" class="dropdown-item remove-item-btn" id="location-delete-btn" >
                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                    </a>
                </li>
            </ul>
        </div>
    </td>
    </tr>

