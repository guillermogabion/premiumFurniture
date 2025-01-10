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
                                    <label for="" class="card-title">Organizations</label>
                                </div>
                                <!-- <div class="col-lg-6 col-md-6 d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary add-item" data-bs-toggle="modal" data-bs-target="#addModal">Add User</button>
                                </div> -->
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <form method="GET" action="{{ route('users') }}">
                                        <div class="input-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request()->query('search') }}">
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
                                            @forelse ($items as $item)
                                            <tr>
                                                <td>{{ $item->orgId }}</td>
                                                <td>{{ $item->orgName ?? "NULL"}}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ $item->contact }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="badge 
                                                                    @if ($item->status == 'active') badge-success
                                                                    @else badge-danger
                                                                    @endif
                                                                    " type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            {{ $item->status == 'active' ? 'Confirmed' : 'Paused' }}
                                                        </button>
                                                        <div class="dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                                                            <form action="{{ route('organizations.updateStatus', $item->id) }}" method="POST" id="statusForm{{ $item->id }}">
                                                                @csrf
                                                                <input type="hidden" name="status" id="statusInput{{ $item->id }}" value="">
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('active', {{ $item->id }});">Confirm</a>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('inactive', {{ $item->id }});">Pause</a>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                                <!-- <td>
                                                    <button type="button" class="btn btn-rounded btn-icon edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $item->id }}" data-userid="{{ $item->userId }}" data-name="{{$item->name}}" data-email="{{ $item->email }}" data-role="{{ $item->role }}">
                                                        <i class="fa fa-edit text-primary"></i>
                                                    </button>
                                                </td> -->
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
                                    {{ $items->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
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
                        <label for="addUserId">User ID:</label>
                        <input type="text" id="addUserId" name="userId" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addName">Full Name:</label>
                        <input type="text" id="addName" name="userName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addEmail">Email:</label>
                        <input type="email" id="addEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="addRole">Select a Role:</label>
                        <select class="form-control" id="addRole" name="role" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="instructor">Teacher</option>
                            <option value="executive">Executive</option>
                            <option value="student">Student</option>
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
                <h5 class="modal-title">Update Item</h5>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="userId" name="id">
                    <div class="form-group">
                        <label for="userUserId">User ID:</label>
                        <input type="text" id="userUserId" name="userId" class="form-control" disabled>
                    </div>
                    <div class="form-group">
                        <label for="userName">Full Name:</label>
                        <input type="text" id="userName" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">Email:</label>
                        <input type="text" id="userEmail" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userRole">Select a Role:</label>
                        <select class="form-control" id="userRole" name="role" required>
                            <option value="" disabled>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="instructor">Teacher</option>
                            <option value="executive">Executive</option>
                            <option value="student">Student</option>
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
            let email = $(this).data('email');
            let role = $(this).data('role');
            let userId = $(this).data('userid');

            $('#userName').val(name);
            $('#userEmail').val(email);
            $('#userRole').val(role);
            $('#userUserId').val(userId);

            $('#editModal').modal('show');
        });

        $('.add-btn').click(function(e) {
            e.preventDefault();
            let userId = document.getElementById('addUserId').value
            let userEmail = document.getElementById('addEmail').value
            let userName = document.getElementById('addName').value
            let userRole = document.getElementById('addRole').value

            $.post('/user_add', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                userId: userId,
                name: userName,
                email: userEmail,
                role: userRole
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