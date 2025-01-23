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
                                    <label for="" class="card-title">Seller</label>
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
                                            <tr data-documents="{{ json_encode($item->document ? json_decode($item->document->documents) : []) }}" data-gcash="{{$item->gcash->gcash_qr_code ?? ''}}">
                                                <td>{{ $item->fullname }}</td>
                                                <td>{{ $item->address }}</td>
                                                <td>{{ $item->contact }}</td>
                                                <td>{{ $item->email }}</td>
                                                <td>{{ $item->shop_name }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="badge 
                @if ($item->status == 'active') badge-success
                @else badge-danger
                @endif
                "
                                                            type="button" id="dropdownMenuButton{{ $item->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="event.stopPropagation();">
                                                            @if ($item->status == 'active')
                                                            Active
                                                            @else
                                                            Inactive
                                                            @endif
                                                        </button>
                                                        <div class=" dropdown-menu custom-dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}" onclick="event.stopPropagation();">
                                                            <form action="{{ route('vendor.updateStatus', $item->id) }}" method="POST" id="statusForm{{ $item->id }}">
                                                                @csrf
                                                                <input type="hidden" name="status" id="statusInput{{ $item->id }}" value="">
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('active', {{ $item->id }});">Activate/Verified</a>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('disabled', {{ $item->id }});">Disabled</a>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); setStatus('rejected', {{ $item->id }});">Rejected</a>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-rounded btn-icon edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $item->id }}" data-userid="{{ $item->userId }}" data-name="{{$item->fullname}}" data-email="{{ $item->email }}" data-role="{{ $item->role }}">
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

<!-- Image Modal -->
<div id="imageModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="imageModalLabel">Documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="gcashSection" class="text-center mb-4"></div>

                <p class="text-center w-100 text-muted" id="noDocumentsMessage">No documents available.</p>

                <div class="row g-2" id="imagesRow"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>






<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script>
    $(document).ready(function() {
        $('tr').click(function() {

            var documents = $(this).data('documents');
            var gcashQrCode = $(this).data('gcash');
            var imagesRow = $('#imagesRow');
            var noDocumentsMessage = $('#noDocumentsMessage');
            var gcashSection = $('#gcashSection');

            imagesRow.empty();
            noDocumentsMessage.show();
            gcashSection.empty();

            if (gcashQrCode) {
                var gcashContent = `
                <h6 class="text-muted">GCash QR Code</h6>
                <div class="gcash-container text-center">
                    <a href="{{ asset('qrcode') }}/${gcashQrCode}" data-lightbox="gcash" data-title="GCash QR Code">
                        <img src="{{ asset('qrcode') }}/${gcashQrCode}" alt="GCash QR Code" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: contain;">
                    </a>
                </div>
`;
                gcashSection.html(gcashContent);
                gcashSection.show();
            } else {
                gcashSection.hide();
            }

            if (documents && Array.isArray(documents) && documents.length > 0) {
                noDocumentsMessage.hide(); // Hide "No documents" message
                documents.forEach(function(document, index) {
                    var imgUrl = "{{ url('document') }}/" + document;
                    var lightboxGroup = 'documents'; // Group name for navigation
                    var col = `
                    <div class="col-md-12 mb-3 text-center">
                        <a href="${imgUrl}" data-lightbox="${lightboxGroup}" data-title="Document ${index + 1}">
                            <img src="${imgUrl}" class="img-fluid rounded shadow-sm" style="max-height: 300px; object-fit: contain;" alt="Document">
                        </a>
                    </div>`;
                    imagesRow.append(col);
                });
            }

            // Show the modal manually
            $('#imageModal').modal('show');
        });
    });







    function setStatus(eventStatus, itemId) {
        document.getElementById('statusInput' + itemId).value = eventStatus;
        document.getElementById('statusForm' + itemId).submit();
    }
</script>

@endsection