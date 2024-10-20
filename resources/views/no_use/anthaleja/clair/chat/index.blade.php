@extends('layouts.main')

@section('content')
    <div class="chat-container">
        <div id="chat-box" class="chat-box">
            <div id="chat-messages">
                @foreach ($interactions as $interaction)
                    <div class="message">
                        <div class="user-message">
                            <strong>Tu:</strong> {{ $interaction->message }} <span
                                class="time">{{ $interaction->created_at->format('H:i') }}</span>
                        </div>
                        <div class="ai-response">
                            <strong>AI:</strong> {{ $interaction->response }} <span
                                class="time">{{ $interaction->updated_at->format('H:i') }}</span>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>

        <div id="typing-indicator" class="typing-indicator" style="display: none;">
            <strong>AI sta scrivendo...</strong>
        </div>

        <div class="chat-input">
            <form action="{{ route('chat.send') }}" id="chat-form" method="POST">
                @csrf
                <div class="input-group chat-input-group">
                    <label for="message">Scrivi un messaggio</label>
                    <textarea name="message" id="message" class="form-control chat-form-control" maxlength="1000"
                        placeholder="Scrivi il tuo messaggio..." rows="1" required></textarea>
                    <button type="submit" class="btn btn-primary chat-btn">Invia</button>
                </div>
                <div class="form-group">
                    <label for="tone">Seleziona il tono della risposta</label>
                    <select name="tone" class="form-control">
                        <option value="formale">Formale</option>
                        <option value="informale">Informale</option>
                        <option value="amichevole">Amichevole</option>
                        <option value="professionale">Professionale</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Invia</button>
            </form>
        </div>
    </div>
    <script>
        // Auto-resize the textarea based on its content
        const chatForm = document.getElementById('chat-form');
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');

        // Auto-resize the textarea based on its content
        const textarea = document.getElementById('message');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Function to append message and AI response to chat box
        function appendMessage(message, response, time) {
            const messageHTML = `
            <div class="message">
                <div class="user-message">
                    <strong>Tu:</strong> ${message} <span class="time">${time}</span>
                </div>
                <div class="ai-response">
                    <strong>AI:</strong> ${response} <span class="time">${time}</span>
                </div>
            </div>
            <hr>
        `;
            chatMessages.insertAdjacentHTML('afterbegin', messageHTML);
        }

        // Handle form submission via AJAX
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent form from submitting normally

            // Show typing indicator
            typingIndicator.style.display = 'block';

            const formData = new FormData(chatForm);
            fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // Hide typing indicator
                    typingIndicator.style.display = 'none';

                    // Append the message and response to the chat box
                    appendMessage(data.message, data.response, data.created_at);

                    // Clear the textarea
                    textarea.value = '';
                    textarea.style.height = 'auto'; // Reset the height
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    </script>
@endsection
