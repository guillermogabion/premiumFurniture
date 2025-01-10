@extends('layouts.app')

@section('content')

@include('partials.header')

<div class="container my-4">
    <div class="m-2 d-flex align-items-center">
        @if($profile->role === 'client')
        <img src="{{ asset('profile/' . ($chat->user->profile ?? 'default.png')) }}" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
        @else
        <img src="{{ asset('profile/' . ($chat->user_customer->profile ?? 'default.png')) }}" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
        @endif
    </div>

    <!-- Messages Section -->
    <div class="card shadow-sm" style="height: 400px; border-radius: 12px;">
        <div id="message-container" class="dropdown-user-scroll scrollbar-outer" style="height: 100%; overflow-y: auto; padding: 15px;">
            @include('partials.messages', ['roomId' => $roomId])
        </div>
    </div>

    <!-- Message Form -->
    @include('partials.message-form', ['roomId' => $roomId])
</div>

<script>
    setInterval(function() {
        fetchMessages();
    }, 1000);

    function fetchMessages() {
        fetch('{{ route("fetchMessages", ["room_id" => $roomId]) }}')
            .then(response => response.text())
            .then(html => {
                document.getElementById('message-container').innerHTML = html;
                const messageContainer = document.getElementById('message-container');
            })
            .catch(error => console.error('Error fetching messages:', error));
    }

    // Scroll to the latest message on page load
</script>

@endsection