<div id="chat-widget">
    <button id="chat-toggle" class="chat-toggle-btn" title="Chat hỗ trợ AI">
        <i class="fa-solid fa-robot"></i>
        <span class="chat-badge">AI</span>
    </button>

    <div id="chat-box" class="chat-box d-none">
        <div class="chat-header">
            <div>
                <strong><i class="fa-solid fa-robot me-1"></i> Trợ lý AI {{ config('store.name') }}</strong>
                <small class="d-block text-white-50">Hỗ trợ 24/7 - Phản hồi tức thì</small>
            </div>
            <button id="chat-close" class="btn-close btn-close-white btn-sm"></button>
        </div>
        <div id="chat-messages" class="chat-messages">
            <div class="chat-msg bot">
                <div class="bubble">Xin chào! Tôi là trợ lý AI của {{ config('store.name') }} 👋<br>Hỏi tôi về đơn hàng, mã giảm giá, đổi trả, giao hàng nhé!</div>
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="chat-input" class="form-control form-control-sm" placeholder="Nhập câu hỏi..." maxlength="1000">
            <button id="chat-send" class="btn btn-dark btn-sm"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>
