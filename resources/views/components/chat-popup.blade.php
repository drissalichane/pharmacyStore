<div id="chat-popup" style="position:fixed;right:24px;bottom:24px;z-index:9999;font-family:Inter,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
  <style>
    /* Minimal, self-contained styles so the widget appears without compiled CSS */
    #chat-popup .chat-btn{width:56px;height:56px;border-radius:9999px;box-shadow:0 6px 18px rgba(0,0,0,0.12);display:flex;align-items:center;justify-content:center;background:#59ad4b;color:#fff;border:none;cursor:pointer}
    #chat-popup .chat-window{width:320px;max-width:calc(100vw - 48px);border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,0.12);overflow:hidden;background:#fff;margin-bottom:8px;display:none}
    #chat-popup .chat-header{padding:12px 14px;background:#f3f4f6;display:flex;align-items:center;justify-content:space-between}
    #chat-popup .chat-messages{padding:12px;height:260px;overflow:auto;background:#ffffff}
    #chat-popup .chat-input-wrap{padding:10px;background:#fbfbfb;border-top:1px solid #eee;display:flex;gap:8px}
    #chat-popup .chat-input{flex:1;padding:8px 10px;border-radius:8px;border:1px solid #ddd}
    #chat-popup .chat-send{background:#59ad4b;color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer}
    #chat-popup .msg{margin-bottom:10px}
    #chat-popup .msg .bubble{display:inline-block;padding:8px 12px;border-radius:12px;max-width:85%}
    #chat-popup .msg.user .bubble{background:#f3f4f6;color:#111;margin-left:auto}
    #chat-popup .msg.assistant .bubble{background:#f0fdf4;color:#064e3b}
  </style>

  <!-- Floating icon -->
  <div>
    <button id="chat-toggle" class="chat-btn" aria-label="Open chat">💬</button>
  </div>

  <!-- Chat window -->
  <div id="chat-window" class="chat-window" role="dialog" aria-label="AI chat window">
    <div class="chat-header">
      <div style="font-weight:600">Assistant</div>
      <button id="chat-close" style="background:none;border:none;color:#6b7280;cursor:pointer;font-size:16px">✕</button>
    </div>
    <div id="chat-messages" class="chat-messages"></div>
    <div class="chat-input-wrap">
      <input id="chat-input" class="chat-input" placeholder="Ask me anything..." autocomplete="off" />
      <button id="chat-send" class="chat-send">Send</button>
    </div>
  </div>

  <script>
    (function(){
      const toggleBtn = document.getElementById('chat-toggle');
      const chatWindow = document.getElementById('chat-window');
      const chatClose = document.getElementById('chat-close');
      const chatFormInput = document.getElementById('chat-input');
      const chatSendBtn = document.getElementById('chat-send');
      const chatMessages = document.getElementById('chat-messages');

      function openChat(){ chatWindow.style.display = 'block'; chatFormInput.focus(); }
      function closeChat(){ chatWindow.style.display = 'none'; }

      toggleBtn.addEventListener('click', function(){
        if (chatWindow.style.display === 'block') closeChat(); else openChat();
      });
      chatClose.addEventListener('click', closeChat);

      function escapeHtml(unsafe){
        return unsafe.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'","&#039;");
      }

      function appendMessage(role, text){
        const d = document.createElement('div'); d.className = 'msg '+role;
        const bubble = document.createElement('div'); bubble.className = 'bubble'; bubble.innerHTML = escapeHtml(text);
        d.appendChild(bubble);
        chatMessages.appendChild(d);
        chatMessages.scrollTop = chatMessages.scrollHeight;
      }

      async function sendMessage(text){
        appendMessage('user', text);
        const loadingIndex = document.createElement('div'); loadingIndex.className = 'msg assistant'; loadingIndex.innerHTML = '<div class="bubble">...</div>'; chatMessages.appendChild(loadingIndex); chatMessages.scrollTop = chatMessages.scrollHeight;

        try{
          const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
          const res = await fetch('/chat/message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
            body: JSON.stringify({ message: text })
          });

          const json = await res.json();
          if (json.error){ loadingIndex.querySelector('.bubble').innerHTML = '<em>'+escapeHtml(json.error)+'</em>'; }
          else { loadingIndex.querySelector('.bubble').innerHTML = escapeHtml(json.message || JSON.stringify(json)); }
        }catch(e){ loadingIndex.querySelector('.bubble').innerHTML = '<em>Network error</em>'; }
      }

      chatSendBtn.addEventListener('click', function(){ const t = chatFormInput.value.trim(); if (!t) return; chatFormInput.value=''; sendMessage(t); });
      chatFormInput.addEventListener('keydown', function(e){ if (e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); chatSendBtn.click(); } });
    })();
  </script>

</div>
