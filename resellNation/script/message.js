// Urban Nation chat: talks to php/chat.php and php/sendMesssage.php and
// renders a thread. All user-supplied text goes through textContent.

const UNChat = (function () {
    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function post(url, payload) {
        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken()
            },
            body: JSON.stringify(payload)
        }).then(res => res.json().catch(() => ({ success: false, message: 'Unexpected server response.' })));
    }

    function el(tag, className, text) {
        const node = document.createElement(tag);
        if (className) node.className = className;
        if (text !== undefined && text !== null) node.textContent = String(text);
        return node;
    }

    function productCard(product) {
        const card = el('div', 'd-flex rounded p-2 align-self-center mb-2 bg-white');
        card.style.minWidth = '18rem';
        card.style.border = '1px solid #e0dcdc';

        const img = el('img', 'card-img rounded');
        img.alt = '';
        img.src = product.image ? './upload/' + encodeURIComponent(product.image) : './images/Thrift store.jpg';
        img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; object-position: center;';

        const body = el('div', 'card-body p-2');
        body.appendChild(el('h5', 'card-title', product.name));
        body.appendChild(el('p', 'card-text', '₹' + Number(product.price || 0).toLocaleString('en-IN')));

        card.appendChild(img);
        card.appendChild(body);
        return card;
    }

    function emptyNote(parent, text) {
        const wrap = el('div', 'text-center text-muted py-4');
        wrap.appendChild(el('p', '', text));
        parent.appendChild(wrap);
    }

    // Renders a thread payload ({me, product, messages}) into parentElement.
    function render(thread, parentElement) {
        parentElement.replaceChildren();

        const chatContainer = el('div', 'chat-container');
        if (thread && thread.product) {
            chatContainer.appendChild(productCard(thread.product));
        }

        const messages = (thread && Array.isArray(thread.messages)) ? thread.messages : [];
        if (messages.length === 0) {
            emptyNote(chatContainer, 'No messages yet. Send a message to start the conversation!');
        }

        messages.forEach(message => {
            const isMine = Number(message.sender_id) === Number(thread.me);
            const messageContainer = el('div', 'message-container ' + (isMine ? 'sent' : 'received'));
            messageContainer.appendChild(el('div', 'message-content', message.body));
            messageContainer.appendChild(el('div', 'timestamp', message.created_at));
            chatContainer.appendChild(messageContainer);
        });

        parentElement.appendChild(chatContainer);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function showError(parentElement, message) {
        parentElement.replaceChildren();
        emptyNote(parentElement, message || 'Something went wrong. Please try again.');
    }

    function loadThread(productId, otherId) {
        return post('./php/chat.php', { action: 'thread', product_id: productId, other_id: otherId || 0 });
    }

    function send(productId, receiverId, body) {
        return post('./php/sendMesssage.php', { product_id: productId, receiver_id: receiverId || 0, body: body });
    }

    // Wires an input + send button to the thread currently held in state.
    function bindComposer(input, button, parentElement, state) {
        if (!input || !button) return;
        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                e.preventDefault();
                button.click();
            }
        });
        button.addEventListener('click', () => {
            const text = input.value.trim();
            if (!text || !state.productId || button.disabled) return;
            button.disabled = true;
            send(state.productId, state.otherId, text)
                .then(data => {
                    if (data.success) {
                        input.value = '';
                        render(data, parentElement);
                    } else {
                        alert(data.message || 'Could not send the message.');
                    }
                })
                .catch(() => alert('Could not send the message.'))
                .finally(() => { button.disabled = false; });
        });
    }

    return { render, showError, loadThread, send, bindComposer };
})();
