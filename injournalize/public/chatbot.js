let msgCounter = 0;
let conversationHistory = [];
let crudHistory = [];
let currentMode = 'ask';
let pendingOperation = null;

function setMode(mode) {
    currentMode = mode;

    const btnAsk = document.getElementById('btn-ask');
    const btnManage = document.getElementById('btn-manage');
    const label = document.getElementById('mode-label');

    const activeStyle = 'linear-gradient(90deg, #6c63ff, #4facfe)';
    const inactiveStyle = 'transparent';

    if (mode === 'ask') {
        btnAsk.style.background = activeStyle;
        btnAsk.style.color = 'white';
        btnManage.style.background = inactiveStyle;
        btnManage.style.color = '#6b7280';
        label.textContent = 'Read-only inquiry mode';
        document.getElementById('chat-input').placeholder = 'Ask about your journal...';
    } else {
        btnManage.style.background = activeStyle;
        btnManage.style.color = 'white';
        btnAsk.style.background = inactiveStyle;
        btnAsk.style.color = '#6b7280';
        label.textContent = 'Manage mode — create, update, delete entries';
        document.getElementById('chat-input').placeholder = 'e.g. create, update, or delete an entry...';
    }

    // Clear pending operation when switching modes
    pendingOperation = null;
}

async function sendMessage() {
    const input = document.getElementById("chat-input");
    const message = input.value.trim();
    if (!message) return;

    addMessage("You", message);
    input.value = "";

    const loadingId = addMessage("AI", "Thinking...");

    const history = currentMode === 'ask' ? conversationHistory : crudHistory;
    history.push({ role: "user", content: message });
    if (history.length > 10) history.splice(0, history.length - 10);

    try {
        const lowerMsg = message.toLowerCase();
        let bodyPayload = { message, history };

        // Handle confirmation/cancellation of pending operation
        if (pendingOperation && currentMode === 'manage') {
            if (lowerMsg.includes('yes') || lowerMsg.includes('confirm') || lowerMsg.includes('go ahead') || lowerMsg.includes('do it')) {
                bodyPayload.pending_operation = pendingOperation;
                pendingOperation = null;
            } else if (lowerMsg.includes('no') || lowerMsg.includes('cancel') || lowerMsg.includes('stop')) {
                pendingOperation = null;
                const el = document.getElementById(loadingId);
                if (el) el.querySelector(".bubble-text").innerText = "❌ Operation cancelled.";
                history.push({ role: "assistant", content: "Operation cancelled." });
                return;
            }
        }

        const endpoint = currentMode === 'ask' ? '/chat' : '/chat/crud';

        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(bodyPayload)
        });

        if (!response.ok) throw new Error(`Server error: ${response.status}`);

        const data = await response.json();

        history.push({ role: "assistant", content: data.reply });
        if (history.length > 10) history.splice(0, history.length - 10);

        const el = document.getElementById(loadingId);
        if (el) el.querySelector(".bubble-text").innerText = data.reply;

        if (data.pending_operation) {
            pendingOperation = data.pending_operation;
        }

        if (data.reload) {
            setTimeout(() => location.reload(), 1500);
        }

    } catch (err) {
        const el = document.getElementById(loadingId);
        if (el) el.querySelector(".bubble-text").innerText = "Error: " + err.message;
    }
}

function addMessage(sender, text) {
    const id = "msg-" + (++msgCounter);
    const div = document.createElement("div");
    div.id = id;
    div.style.marginBottom = "8px";
    div.innerHTML = `<strong>${sender}:</strong> <span class="bubble-text">${escapeHtml(text)}</span>`;
    const container = document.getElementById("messages");
    container.appendChild(div);
    container.scrollTop = container.scrollHeight;
    return id;
}

function escapeHtml(text) {
    const d = document.createElement("div");
    d.appendChild(document.createTextNode(text));
    return d.innerHTML;
}

document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("chat-input");
    if (input) {
        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter" && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }
});