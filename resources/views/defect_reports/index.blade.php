@extends('layout.main')

@section('title', 'Defect Reports')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-border">
                    <h4 class="mb-sm-0">Defect Reports</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ \App\Http\Controllers\DashboardController::getDashboardRoute() }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Defect Reports</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End page title -->

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4 class="card-title mb-0">Defect Reports List</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                @role('deo')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDefectReportModal">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Defect Report
                                </button>
                                @endrole
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Vehicle</th>
                                        <th>Office/Town</th>
                                        <th>Driver Name</th>
                                        <th>Manager Fleet</th>
                                        <th>MVI</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Works Count</th>
                                        <th>Created By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($defectReports as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->vehicle->vehicle_number ?? 'N/A' }}</td>
                                        <td>{{ $report->location->name ?? 'N/A' }}</td>
                                        <td>{{ $report->driver_name }}</td>
                                        <td>{{ $report->fleetManager->first_name ?? 'N/A' }} {{ $report->fleetManager->last_name ?? '' }}</td>
                                        <td>{{ $report->mvi->first_name ?? 'N/A' }} {{ $report->mvi->last_name ?? '' }}</td>
                                        <td>{{ $report->date->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $report->type === 'defect_report' ? 'warning' : 'info' }}">
                                                {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $report->works->count() }}</span>
                                        </td>
                                        <td>{{ $report->creator->first_name }} {{ $report->creator->last_name }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#viewDefectReportModal{{ $report->id }}">
                                                    <i class="ri-eye-line"></i>
                                                </button>
                                                
                                                @role('super_admin|admin')
                                                <button type="button" class="btn btn-sm btn-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editDefectReportModal{{ $report->id }}">
                                                    <i class="ri-edit-line"></i>
                                                </button>
                                                
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        onclick="deleteDefectReport({{ $report->id }})">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No defect reports found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($defectReports->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $defectReports->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Defect Report Modal -->
<div class="modal fade" id="addDefectReportModal" tabindex="-1" aria-labelledby="addDefectReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDefectReportModalLabel">Add Defect Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addDefectReportForm" action="{{ route('defect-reports.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="vehicle_id" class="form-label">Vehicle <x-req /></label>
                                <select class="form-select required" id="vehicle_id" name="vehicle_id" required>
                                    <option value="" selected disabled>Select Vehicle</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location_id" class="form-label">Office/Town <x-req /></label>
                                <select class="form-select required" id="location_id" name="location_id" required>
                                    <option value="" selected disabled>Select Office/Town</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="driver_name" class="form-label">Driver Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control required" id="driver_name" name="driver_name" 
                                       placeholder="Enter driver name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control required" id="date" name="date" 
                                       value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fleet_manager_id" class="form-label">Fleet Manager <x-req /></label>
                                <select class="form-select required" id="fleet_manager_id" name="fleet_manager_id" required>
                                    <option value="" selected disabled>Select Fleet Manager</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="mvi_id" class="form-label">MVI <x-req /></label>
                                <select class="form-select" id="mvi_id" name="mvi_id" required>
                                    <option value="" selected disabled>Select MVI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Report Type</label>
                                <input type="text" class="form-control" id="type" name="type" value="defect_report" readonly>
                                <input type="hidden" name="type" value="defect_report">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="attach_file" class="form-label">Attach File <x-req /></label>
                                <input type="file" class="form-control required" id="attach_file" name="attach_file" 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 2MB</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Work Items</h6>
                            <div id="works-container">
                                <div class="work-item row mb-3">
                                    <div class="col-md-10">
                                        <label class="form-label">Work Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control work-description required" name="works[0][work]" 
                                               placeholder="Enter work description" maxlength="300" required>
                                        <input type="hidden" name="works[0][type]" value="defect">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-work" style="display: none;">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-sm" id="add-work">
                                    <i class="ri-add-line"></i> Add Work Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Defect Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Defect Report Modals -->
@foreach($defectReports as $report)
<div class="modal fade" id="viewDefectReportModal{{ $report->id }}" tabindex="-1" aria-labelledby="viewDefectReportModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDefectReportModalLabel{{ $report->id }}">View Defect Report #{{ $report->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Basic Information</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Report Type:</td>
                                <td>
                                    <span class="badge bg-{{ $report->type === 'defect_report' ? 'warning' : 'info' }}">
                                        {{ ucfirst(str_replace('_', ' ', $report->type)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Vehicle:</td>
                                <td>{{ $report->vehicle->vehicle_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Office/Town:</td>
                                <td>{{ $report->location->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Driver Name:</td>
                                <td>{{ $report->driver_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Date:</td>
                                <td>{{ $report->date->format('d/m/Y') }}</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Assigned Personnel</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" style="width: 150px;">Fleet Manager:</td>
                                <td>{{ $report->fleetManager->first_name ?? 'N/A' }} {{ $report->fleetManager->last_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">MVI:</td>
                                <td>{{ $report->mvi->first_name ?? 'N/A' }} {{ $report->mvi->last_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Created By:</td>
                                <td>{{ $report->creator->first_name }} {{ $report->creator->last_name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Created On:</td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($report->attachment_url)
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Attached File</h6>
                        <div class="d-flex align-items-center">
                            <i class="ri-file-line me-2 fs-4"></i>
                            <a href="{{ $report->attachment_url }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                View Attachment
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <hr class="my-4">

                <div class="row">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Work Items ({{ $report->works->count() }})</h6>
                        
                        @if($report->works->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Work Description</th>
                                        <th style="width: 120px;">Type</th>
                                        <th style="width: 100px;">Quantity</th>
                                        <th style="width: 150px;">Vehicle Part</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($report->works as $index => $work)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $work->work ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $work->type === 'defect' ? 'danger' : 'success' }}">
                                                {{ ucfirst(str_replace('_', ' ', $work->type)) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($work->type === 'purchase_order' && $work->quantity)
                                                {{ $work->quantity }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($work->type === 'purchase_order' && $work->vehiclePart)
                                                {{ $work->vehiclePart->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <i class="ri-inbox-line fs-1"></i>
                            <p class="mt-2">No work items found for this report.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Edit Defect Report Modals -->
@foreach($defectReports as $report)
@role('super_admin|admin')
<div class="modal fade" id="editDefectReportModal{{ $report->id }}" tabindex="-1" aria-labelledby="editDefectReportModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDefectReportModalLabel{{ $report->id }}">Edit Defect Report #{{ $report->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('defect-reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_vehicle_id_{{ $report->id }}" class="form-label">Vehicle <x-req /></label>
                                <select class="form-select required" id="edit_vehicle_id_{{ $report->id }}" name="vehicle_id" required>
                                    <option value="" selected disabled>Select Vehicle</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_location_id_{{ $report->id }}" class="form-label">Office/Town <x-req /></label>
                                <select class="form-select required" id="edit_location_id_{{ $report->id }}" name="location_id" required>
                                    <option value="" selected disabled>Select Office/Town</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_driver_name_{{ $report->id }}" class="form-label">Driver Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_driver_name_{{ $report->id }}" name="driver_name" 
                                       value="{{ $report->driver_name }}" placeholder="Enter driver name" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_date_{{ $report->id }}" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="edit_date_{{ $report->id }}" name="date" 
                                       value="{{ $report->date->format('Y-m-d') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_fleet_manager_id_{{ $report->id }}" class="form-label">Fleet Manager <x-req /></label>
                                <select class="form-select required" id="edit_fleet_manager_id_{{ $report->id }}" name="fleet_manager_id" required>
                                    <option value="" selected disabled>Select Fleet Manager</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_mvi_id_{{ $report->id }}" class="form-label">MVI <x-req /></label>
                                <select class="form-select required" id="edit_mvi_id_{{ $report->id }}" name="mvi_id" required>
                                    <option value="" selected disabled>Select MVI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_type_{{ $report->id }}" class="form-label">Report Type</label>
                                <input type="text" class="form-control" id="edit_type_{{ $report->id }}" value="defect_report" readonly>
                                <input type="hidden" name="type" value="defect_report">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_attach_file_{{ $report->id }}" class="form-label">Attach File <x-req /></label>
                                <input type="file" class="form-control required" id="edit_attach_file_{{ $report->id }}" name="attach_file" 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                <div class="form-text">Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 2MB</div>
                                @if($report->attach_file)
                                    <div class="mt-2">
                                        <small class="form-text text-muted">Current file:</small>
                                        <a href="{{ asset('storage/' . $report->attach_file) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                                            View Current File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-3">Work Items</h6>
                            <div id="edit-works-container-{{ $report->id }}">
                                @foreach($report->works as $index => $work)
                                <div class="work-item row mb-3">
                                    <div class="col-md-10">
                                        <label class="form-label">Work Description <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control work-description required" name="works[{{ $index }}][work]" 
                                               value="{{ $work->work }}" placeholder="Enter work description" maxlength="300" required>
                                        <input type="hidden" name="works[{{ $index }}][type]" value="defect">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        @if($index > 0)
                                        <button type="button" class="btn btn-danger btn-sm remove-work">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="text-center">
                                <button type="button" class="btn btn-success btn-sm" onclick="addEditWorkItem({{ $report->id }})">
                                    <i class="ri-add-line"></i> Add Work Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Defect Report</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endrole
@endforeach

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let workIndex = 1;
    
    // Load dropdowns on page load
    loadAddModalDropdowns();
    
    // Add work item
    $('#add-work').click(function() {
        const workItem = `
            <div class="work-item row mb-3">
                <div class="col-md-10">
                    <label class="form-label">Work Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control work-description required" name="works[${workIndex}][work]" 
                           placeholder="Enter work description" maxlength="300" required>
                    <input type="hidden" name="works[${workIndex}][type]" value="defect">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-work">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        `;
        
        $('#works-container').append(workItem);
        workIndex++;
        
        // Show remove buttons for all items except the first one
        $('.remove-work').show();
    });
    
    // Remove work item
    $(document).on('click', '.remove-work', function() {
        $(this).closest('.work-item').remove();
        
        // Hide remove button if only one item remains
        if ($('.work-item').length === 1) {
            $('.remove-work').hide();
        }
    });
    
    // Initialize - hide remove button for first item
    $('.remove-work').hide();
    
    // Load dropdowns for add modal
    function loadAddModalDropdowns() {
        getDynamicDropdownData('/get-vehicles', '#vehicle_id');
        getDynamicDropdownData('/get-locations', '#location_id');
        getDynamicDropdownData('/get-fleet-managers', '#fleet_manager_id');
        getDynamicDropdownData('/get-mvis', '#mvi_id');
    }
    
    // Load dropdowns for edit modals when they open
    $('[id^="editDefectReportModal"]').on('show.bs.modal', function() {
        const reportId = $(this).attr('id').replace('editDefectReportModal', '');
        loadEditModalDropdowns(reportId);
    });
    
    // Load dropdowns for edit modal
    function loadEditModalDropdowns(reportId) {
        getDynamicDropdownData('/get-vehicles', `#edit_vehicle_id_${reportId}`);
        getDynamicDropdownData('/get-locations', `#edit_location_id_${reportId}`);
        getDynamicDropdownData('/get-fleet-managers', `#edit_fleet_manager_id_${reportId}`);
        getDynamicDropdownData('/get-mvis', `#edit_mvi_id_${reportId}`);
        
        // Set selected values after dropdowns are loaded
        setTimeout(() => {
            setEditModalValues(reportId);
        }, 500);
    }
    
    // Set selected values in edit modal
    function setEditModalValues(reportId) {
        // This will be populated from the server data
        // The values are already set in the HTML when the modal is rendered
    }
});

// Function to add work item in edit modals
function addEditWorkItem(reportId) {
    const container = document.getElementById(`edit-works-container-${reportId}`);
    const workItems = container.querySelectorAll('.work-item');
    const newIndex = workItems.length;
    
    const workItem = document.createElement('div');
    workItem.className = 'work-item row mb-3';
    workItem.innerHTML = `
        <div class="col-md-10">
            <label class="form-label">Work Description <span class="text-danger">*</span></label>
            <input type="text" class="form-control work-description required" name="works[${newIndex}][work]" 
                   placeholder="Enter work description" maxlength="300" required>
            <input type="hidden" name="works[${newIndex}][type]" value="defect">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-work">
                <i class="ri-delete-bin-line"></i>
            </button>
        </div>
    `;
    
    container.appendChild(workItem);
    
    // Show remove buttons for all items except the first one
    const removeButtons = container.querySelectorAll('.remove-work');
    removeButtons.forEach(btn => btn.style.display = 'block');
}

// Function to delete defect report
function deleteDefectReport(reportId) {
    if (confirm('Are you sure you want to delete this defect report?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/defect-reports/${reportId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

    // Handle form submission for add modal
    $('#addDefectReportForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous validation errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Basic validation
        let isValid = true;
        const requiredFields = ['vehicle_id', 'location_id', 'driver_name', 'date', 'fleet_manager_id', 'mvi_id', 'attach_file'];
        
        requiredFields.forEach(field => {
            const value = $(`[name="${field}"]`).val();
            if (!value || value === '') {
                $(`[name="${field}"]`).addClass('is-invalid');
                $(`[name="${field}"]`).after(`<div class="invalid-feedback">This field is required.</div>`);
                isValid = false;
            }
        });
        
        // Validate work items
        const workItems = $('.work-description');
        workItems.each(function(index) {
            const value = $(this).val().trim();
            if (!value) {
                $(this).addClass('is-invalid');
                $(this).after(`<div class="invalid-feedback">Work description is required.</div>`);
                isValid = false;
            }
        });
        
        if (!isValid) {
            toastr.error('Please fill in all required fields.');
            return;
        }
        
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message || 'Defect report created successfully!');
                    $('#addDefectReportModal').modal('hide');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Failed to create defect report.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(key => {
                        const field = $(`[name="${key}"]`);
                        if (field.length) {
                            field.addClass('is-invalid');
                            field.after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                        } else {
                            toastr.error(errors[key][0]);
                        }
                    });
                } else {
                    toastr.error('An error occurred while creating the defect report.');
                }
            }
        });
    });
    
    // Real-time validation
    $('input, select').on('blur', function() {
        const field = $(this);
        const value = field.val().trim();
        const fieldName = field.attr('name');
        
        // Remove previous validation
        field.removeClass('is-invalid');
        field.siblings('.invalid-feedback').remove();
        
        // Validate required fields
        if (field.hasClass('required') || field.prop('required')) {
            if (!value) {
                field.addClass('is-invalid');
                field.after(`<div class="invalid-feedback">This field is required.</div>`);
            }
        }
        
        // Validate specific fields
        if (fieldName === 'driver_name' && value && value.length < 2) {
            field.addClass('is-invalid');
            field.after(`<div class="invalid-feedback">Driver name must be at least 2 characters.</div>`);
        }
        
        if (fieldName === 'date' && value) {
            const selectedDate = new Date(value);
            const today = new Date();
            if (selectedDate > today) {
                field.addClass('is-invalid');
                field.after(`<div class="invalid-feedback">Date cannot be in the future.</div>`);
            }
        }
        
        // Validate dropdown fields
        if (['vehicle_id', 'location_id', 'fleet_manager_id', 'mvi_id'].includes(fieldName)) {
            if (!value || value === '') {
                field.addClass('is-invalid');
                field.after(`<div class="invalid-feedback">Please select a valid option.</div>`);
            }
        }
    });
    
    // Work description validation
    $(document).on('blur', '.work-description', function() {
        const field = $(this);
        const value = field.val().trim();
        
        field.removeClass('is-invalid');
        field.siblings('.invalid-feedback').remove();
        
        if (!value) {
            field.addClass('is-invalid');
            field.after(`<div class="invalid-feedback">Work description is required.</div>`);
        } else if (value.length < 3) {
            field.addClass('is-invalid');
            field.after(`<div class="invalid-feedback">Work description must be at least 3 characters.</div>`);
        }
    });
</script>
@endsection
