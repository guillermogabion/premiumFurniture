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
                                    <label for="" class="card-title">Positions</label>
                                </div>
                                <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary add-item" data-bs-toggle="modal" data-bs-target="#addModal">Add Position</button>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <form method="GET" action="{{ route('users') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search positions ..." value="{{ request()->query('search') }}">
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
                                            @forelse ($positions as $position)
                                            <tr>
                                                <td>{{ $position->id }}</td>
                                                <td>{{ $position->name}}</td>
                                                <td>{{ $position->number}}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="badge 
                                                                    @if ($position->status == 'active') badge-success
                                                                    @else badge-danger
                                                                    @endif
                                                                    " type="button" id="dropdownMenuButton{{ $position->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            {{ $position->status == 'active' ? 'Active' : 'Disabled' }}
                                                        </button>
                                                        <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $position->id }}">
                                                            <form action="{{ route('positions.updateStatus', $position->id) }}" method="POST" id="statusForm{{ $position->id }}">
                                                                @csrf
                                                                <input type="hidden" name="status" id="statusInput{{ $position->id }}" value="">
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('active', {{ $position->id }});">Activate</a>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('disabled', {{ $position->id }});">Deactivate</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-rounded btn-icon edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $position->id }}" data-name="{{$position->name}}" data-number="{{ $position->number }}">
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
                                    {{ $positions->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
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
                        <label for="addName">Position Name:</label>
                        <input type="text" id="addName" name="positionName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addEmail">No. of Available :</label>
                        <input type="number" id="addNumber" name="positionNumber" class="form-control" required>
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
                <h5 class="modal-title">Update Item</h5>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="userId" name="id">
                    <div class="form-group">
                        <label for="userName">Position Name:</label>
                        <input type="text" id="positionName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="positionNumber">Number of Available:</label>
                        <input type="number" id="editNumber" name="number" class="form-control" required>
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
            let number = $(this).data('number');


            $('#positionName').val(name);
            $('#editNumber').val(number);

            $('#editModal').modal('show');
        });

        $('.add-btn').click(function(e) {
            e.preventDefault();
            let positionName = document.getElementById('addName').value
            let positionNumber = document.getElementById('addNumber').value

            $.post('/position_add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                name: positionName,
                number: positionNumber,
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


    })

    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection