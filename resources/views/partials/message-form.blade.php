<form action="{{ route('sendMessage') }}" method="POST" class="mt-4">
    @csrf
    <input type="hidden" name="room_id" value="{{ $roomId }}">
    <div class="input-group">
        <textarea class="form-control" name="message" placeholder="Type your message here..." rows="3" required id="messageInput"></textarea>
        <button class="btn btn-primary" type="submit">Send</button>
    </div>
</form>