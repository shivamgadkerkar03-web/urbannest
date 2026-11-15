

function loadWhatsAppChat(data, parentElement) {
    parentElement.innerHTML = "";
    
    if (!data || !Array.isArray(data) || data.length === 0) {
        parentElement.innerHTML = '<div class="text-center text-muted py-4"><p>No messages yet.</p></div>';
        return;
    }
    
    const chatContainer = document.createElement('div');
    chatContainer.classList.add('chat-container');

    data.forEach(message => {
        const messageContainer = document.createElement('div');
        messageContainer.classList.add('message-container');

        const senderClass = message.sender === 'sell' ? 'sent' : 'received';
        messageContainer.classList.add(senderClass);

        const messageContent = document.createElement('div');
        messageContent.classList.add('message-content');
        if (message.content == "_send_object_to_user_") {
            messageContent.innerHTML = `<div class="d-flex rounded p-2" style="min-width: 18rem; border: 1px solid #e0dcdc;">
            <img src="./upload/${message.pimage}" class="card-img rounded" style="width: 100px; height: 100px; object-fit:cover; object-position: center;">
            <div class="card-body p-2
            ">
              <h5 class="card-title">${message.pname}</h5>
              <p class="card-text">${message.pprice}Rs</p>
            </div>
          </div>`;
        } else {
            messageContent.textContent = message.content;
        }

        const timestamp = document.createElement('div');
        timestamp.classList.add('timestamp');
        timestamp.textContent = message.timestamp;

        messageContainer.appendChild(messageContent);
        messageContainer.appendChild(timestamp);

        chatContainer.appendChild(messageContainer);
    });

    parentElement.appendChild(chatContainer);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function loadWhatsAppChat2(data, parentElement) {
    parentElement.innerHTML = "";
    
    if (!data || !Array.isArray(data) || data.length === 0) {
        parentElement.innerHTML = '<div class="text-center text-muted py-4"><p>No messages yet.</p></div>';
        return;
    }
    
    const chatContainer = document.createElement('div');
    chatContainer.classList.add('chat-container');

    data.forEach(message => {
        const messageContainer = document.createElement('div');
        messageContainer.classList.add('message-container');

        const senderClass = message.sender === 'sell' ? 'received' : 'sent';
        messageContainer.classList.add(senderClass);

        const messageContent = document.createElement('div');
        messageContent.classList.add('message-content');
        if (message.content == "_send_object_to_user_") {
            messageContent.innerHTML = `<div class="d-flex rounded p-2" style="min-width: 18rem; border: 1px solid #e0dcdc;">
            <img src="./upload/${message.pimage}" class="card-img rounded" style="width: 100px; height: 100px; object-fit:cover; object-position: center;">
            <div class="card-body p-2
            ">
              <h5 class="card-title">${message.pname}</h5>
              <p class="card-text">${message.pprice}Rs</p>
            </div>
          </div>`;
        } else {
            messageContent.textContent = message.content;
        }

        const timestamp = document.createElement('div');
        timestamp.classList.add('timestamp');
        timestamp.textContent = message.timestamp;

        messageContainer.appendChild(messageContent);
        messageContainer.appendChild(timestamp);

        chatContainer.appendChild(messageContainer);
    });

    parentElement.appendChild(chatContainer);
    chatContainer.scrollTop = chatContainer.scrollHeight;
}