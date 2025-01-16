@extends('layouts.app')

@section('content')

@include('partials.header')

<div class="container my-4" style="position: relative; min-height: 80vh;">
    <!-- Sticky Profile Image -->
    <div class="m-2 d-flex align-items-center" style="position: sticky; top: 0; z-index: 10; background-color: white;">
        @if($profile->role === 'client')
        <img src="{{ asset('profile/' . ($chat->user->profile ?? 'default.png')) }}" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
        @else
        <img src="{{ asset('profile/' . ($chat->user_customer->profile ?? 'default.png')) }}" alt="Profile Image" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
        @endif
    </div>

    <!-- Messages Section -->
    <div class="card shadow-sm" style="border-radius: 12px; padding-bottom: 80px; max-height: 500px; overflow-y: auto;" id="message-container">
        @include('partials.messages', ['roomId' => $roomId])
    </div>

    <!-- Sticky Message Form -->
    <div style="position: sticky; bottom: 0; z-index: 10; background-color: white; padding: 10px;">
        @include('partials.message-form', ['roomId' => $roomId])
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messageContainer = document.getElementById('message-container');

        let isUserScrolling = false;
        let scrollTimeout;
        let autoRefreshEnabled = true;

        // Function to fetch and update messages
        function fetchMessages() {
            fetch('{{ route("fetchMessages", ["room_id" => $roomId]) }}')
                .then(response => response.text())
                .then(html => {
                    const wasScrolledToBottom = messageContainer.scrollHeight - messageContainer.scrollTop === messageContainer.clientHeight;

                    // Replace the current messages with the new ones
                    messageContainer.innerHTML = html;

                    // If the user was already at the bottom before, scroll to the bottom after fetching new messages
                    if (wasScrolledToBottom && autoRefreshEnabled) {
                        messageContainer.scrollTop = messageContainer.scrollHeight;
                    }
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Start the auto-refresh process
        function startAutoRefresh() {
            setInterval(() => {
                if (!isUserScrolling && autoRefreshEnabled) {
                    fetchMessages();
                }
            }, 5000); // Check every 5 seconds, you can adjust this value
        }

        // Handle scroll events
        messageContainer.addEventListener('scroll', function() {
            const scrollTop = messageContainer.scrollTop;
            const scrollHeight = messageContainer.scrollHeight;
            const clientHeight = messageContainer.clientHeight;

            // Detect if the user is scrolling up or down
            if (scrollTop < scrollHeight - clientHeight - 10) {
                isUserScrolling = true; // User is scrolling up
                autoRefreshEnabled = false; // Disable auto-refresh
                clearTimeout(scrollTimeout); // Reset timeout
            } else {
                isUserScrolling = false; // User is at the bottom
            }

            // Start the timeout when the user stops scrolling
            scrollTimeout = setTimeout(() => {
                // Resume auto-refresh after 3 seconds of inactivity
                autoRefreshEnabled = true;
            }, 3000); // 3 seconds delay
        });

        // Initialize auto-refresh on page load
        startAutoRefresh();

        // Observe changes in the message container (for new messages)
        const observer = new MutationObserver(() => {
            if (!isUserScrolling && autoRefreshEnabled) {
                messageContainer.scrollTop = messageContainer.scrollHeight;
            }
        });

        observer.observe(messageContainer, {
            childList: true,
            subtree: true
        });
    });
</script>

@endsection