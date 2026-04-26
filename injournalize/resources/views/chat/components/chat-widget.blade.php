<!-- Toggle Button -->
<button onclick="toggleChat()" style="
    position: fixed; bottom: 20px; right: 20px;
    width: 50px; height: 50px; border-radius: 50%;
    background: linear-gradient(90deg, #6c63ff, #4facfe);
    color: white; border: none; font-size: 22px;
    cursor: pointer; z-index: 10000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
">💬</button>

<!-- Chat Box -->
<div id="chat-widget" style="
    display: none;
    position: fixed; bottom: 80px; right: 20px;
    width: 340px; height: 500px;
    background: white; border-radius: 16px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    flex-direction: column;
    z-index: 9999; overflow: hidden;
">
    <!-- Header -->
    <div style="
        background: linear-gradient(90deg, #6c63ff, #4facfe);
        padding: 14px 16px; color: white;
        font-weight: 600; font-size: 15px;
        display: flex; justify-content: space-between; align-items: center;
    ">
        <span>📓 Journal AI</span>
        <span onclick="toggleChat()" style="cursor:pointer; font-size:18px;">✕</span>
    </div>

    <!-- Mode Toggle -->
    <div style="
        display: flex; border-bottom: 1px solid #e5e7eb;
        background: #f9fafb;
    ">
        <button id="btn-ask" onclick="setMode('ask')" style="
            flex: 1; padding: 10px; border: none;
            background: linear-gradient(90deg, #6c63ff, #4facfe);
            color: white; font-weight: 600; cursor: pointer;
            font-size: 13px;
        ">💬 Ask</button>
        <button id="btn-manage" onclick="setMode('manage')" style="
            flex: 1; padding: 10px; border: none;
            background: transparent; color: #6b7280;
            font-weight: 600; cursor: pointer; font-size: 13px;
        ">✏️ Manage</button>
    </div>

    <!-- Mode Label -->
    <div id="mode-label" style="
        text-align: center; font-size: 11px;
        color: #9ca3af; padding: 6px;
        background: #f9fafb; border-bottom: 1px solid #e5e7eb;
    ">Read-only inquiry mode</div>

    <!-- Messages -->
    <div id="messages" style="
        flex: 1; overflow-y: auto;
        padding: 12px; display: flex;
        flex-direction: column; gap: 8px;
        background: #f9fafb;
    "></div>

    <!-- Input Row -->
    <div style="
        display: flex; gap: 8px;
        padding: 10px; border-top: 1px solid #e5e7eb;
        background: white;
    ">
        <input id="chat-input" placeholder="Ask about your journal..."
            style="
                flex: 1; border: 1px solid #d1d5db;
                border-radius: 10px; padding: 8px 12px;
                font-size: 13px; outline: none;
            "
        />
        <button onclick="sendMessage()" style="
            background: linear-gradient(90deg, #6c63ff, #4facfe);
            color: white; border: none;
            border-radius: 10px; padding: 8px 14px;
            cursor: pointer; font-size: 13px;
        ">Send</button>
    </div>
</div>

<script>
function toggleChat() {
    const widget = document.getElementById('chat-widget');
    const isOpen = widget.style.display === 'flex';
    widget.style.display = isOpen ? 'none' : 'flex';
}
</script>