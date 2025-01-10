<div id="message-container" class="dropdown-user-scroll scrollbar-outer">


    @if(isset($items) && count($items) > 0)
    @foreach($items as $message)
    <div class="d-flex @if($profile->id === $message->sender_id) justify-content-end @else justify-content-start @endif mb-2">
        <div class="card @if($profile->id === $message->sender_id) bg-primary text-white @else bg-light @endif shadow-sm"
            style="max-width: 60%; border-radius: 12px;">
            <div class="card-body p-3">
                <p class="mb-2">{{ $message->message }}</p>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="text-center">
        <p class="text-muted">No messages in your inbox.</p>
    </div>
    @endif
</div>