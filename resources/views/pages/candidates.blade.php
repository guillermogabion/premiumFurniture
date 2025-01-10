@extends('layouts.app')

@section('content')

<div class="page-inner">
    <div class="content-wrapper">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label for="" class="card-title">Candidates</label>
                                </div>
                                <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary add-item" data-bs-toggle="modal" data-bs-target="#addModal">Add Candidate</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <form method="GET" action="{{ route('candidates') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search candidate..." value="{{ request()->query('search') }}">
                                            <span class="input-group-append">
                                                <button class="btn btn-outline-secondary d-none" type="submit">Search</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="basic-datatables" class="display table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                @foreach($headers as $header)
                                                <th>{{ $header }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($candidates as $candidate)
                                            <tr>
                                                <td>{{ $candidate->id }}</td>
                                                <td>{{ $candidate->user_candidates->name ?? "NULL"}}</td>
                                                <td>{{ $candidate->candidate_position->name }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="badge 
                                                                    @if ($candidate->status == 'active') badge-success
                                                                    @elseif ($candidate->status == 'unavailable') badge-warning
                                                                    @else badge-danger
                                                                    @endif
                                                                    " type="button" id="dropdownMenuButton{{ $candidate->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            {{ $candidate->status == 'active' 
                                                                        ? 'Active' 
                                                                        : ($candidate->status == 'unavailable' 
                                                                            ? 'Not Available' 
                                                                            : 'Withdrawn') 
                                                                    }}

                                                        </button>
                                                        <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $candidate->id }}">
                                                            <form action="{{ route('candidates.updateStatus', $candidate->id) }}" method="POST" id="statusForm{{ $candidate->id }}">
                                                                @csrf
                                                                <input type="hidden" name="status" id="statusInput{{ $candidate->id }}" value="">
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus(');">Activate</a>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('unavailable', {{ $candidate->id }});">Deactivate</a>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('withdrawn', {{ $candidate->id }});">Withdraw</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-rounded btn-icon edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $candidate->id }}" data-name="{{ $candidate->user_id }}" data-position="{{$candidate->position_id}}">
                                                        <i class="fa fa-edit text-primary"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="9" class="alert alert-info">No Items</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end">
                                    {{ $candidates->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Add User</h5>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="form-group">
                        <label for="addUserId" class="form-label">Event</label>
                        <select id="addUserId" name="userId" class="form-select" required onchange="fetchChartData()">
                            <option value="">Select an Student</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="addPositionId" class="form-label">Event</label>
                        <select id="addPositionId" name="positionId" class="form-select" required onchange="fetchChartData()">
                            <option value="">Select Position</option>
                            @foreach ($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 add-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-center">
                <h5 class="modal-title">Update Candidate</h5>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="editId" name="id">
                    <div class="form-group">
                        <label for="editUserId" class="form-label">Event</label>
                        <select id="editUserId" name="userId" class="form-select" required onchange="fetchChartData()">
                            <option value="">Select an Student</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editPositionId" class="form-label">Event</label>
                        <select id="editPositionId" name="positionId" class="form-select" required onchange="fetchChartData()">
                            <option value="">Select Position</option>
                            @foreach ($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4 edit-submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/jquery.cookie.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.add-item').click(function() {
            $('#addModal').modal('show');
        });

        $('.edit-btn').click(function() {
            let name = $(this).data('name');
            let positionId = $(this).data('position');
            let id = $(this).data('id');

            $('#editId').val(id);
            $('#editUserId').val(name);
            $('#editPositionId').val(positionId);

            $('#editModal').modal('show');
        });

        $('.add-btn').click(function(e) {
            e.preventDefault();
            let userId = document.getElementById('addUserId').value
            let position = document.getElementById('addPositionId').value


            $.post('/candidate_add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: userId,
                position_id: position,

            }).done(function(res) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Saving Success',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.reload();
                    }
                });
            }).fail(function(err) {
                if (err.status === 422) {
                    let errors = err.responseJSON.errors;
                    for (let key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            console.error(key + ": " + errors[key]);
                        }
                    }
                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        text: "Please check the input data.",
                        confirmButtonText: 'OK'
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message || "An error occurred",
                        confirmButtonText: 'OK'
                    });
                    console.error(err);
                }
            })
        })
        $('.edit-submit-btn').click(function(event) {
            event.preventDefault();

            let id = $('#editId').val();
            let userName = $('#editUserId').val();
            let editPosition = $('#editPositionId').val();

            $.post('/candidate_update', {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id: id,
                    user_id: userName,
                    position_id: editPosition,
                })
                .done(function(res) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Update Success',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                })
                .fail(function(err) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: err.responseJSON.message || "An error occurred",
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });
                    console.error(err);
                });
        });


    })

    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection