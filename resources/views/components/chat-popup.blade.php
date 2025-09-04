<!-- GSAP Library for animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>

<!-- Custom Floating Contact Button -->
<div id="floating-contact" style="position:fixed;right:24px;bottom:24px;z-index:9999;">
  <style>
    #floating-contact .contact-btn {
      width:56px;
      height:56px;
      border-radius:50%;
      background:#59ad4b;
      color:#fff;
      border:none;
      cursor:pointer;
      box-shadow:0 4px 12px rgba(0,0,0,0.15);
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:20px;
      transition:all 0.3s ease;
    }
    #floating-contact .contact-btn:hover {
      transform:scale(1.1);
      box-shadow:0 6px 20px rgba(0,0,0,0.2);
    }
    #floating-contact .contact-menu {
      position:absolute;
      bottom:70px;
      right:0;
      background:#fff;
      border-radius:12px;
      box-shadow:0 8px 32px rgba(0,0,0,0.15);
      padding:8px;
      display:none;
      min-width:200px;
    }
    #floating-contact .contact-item {
      display:flex;
      align-items:center;
      padding:12px 16px;
      border-radius:8px;
      cursor:pointer;
      transition:background 0.2s ease;
      text-decoration:none;
      color:#333;
    }
    #floating-contact .contact-item:hover {
      background:#f8f9fa;
    }
    #floating-contact .contact-item i {
      margin-right:12px;
      width:20px;
      text-align:center;
    }
    #floating-contact .contact-item.ai-assistant {
      border-bottom:1px solid #eee;
      margin-bottom:4px;
      padding-bottom:16px;
    }
  </style>

  <!-- Contact Menu -->
  <div id="contact-menu" class="contact-menu">
    <div id="ai-assistant-btn" class="contact-item ai-assistant">
      <i class="fas fa-robot"></i>
      <span>AI Assistant</span>
    </div>
    <a href="https://wa.me/212628453375" target="_blank" class="contact-item">
      <i class="fab fa-whatsapp"></i>
      <span>WhatsApp</span>
    </a>
  </div>

  <!-- Main Button -->
  <button id="contact-toggle" class="contact-btn" title="Contact Us">
    <i class="fas fa-comments"></i>
  </button>
</div>

<!-- Chat window (hidden initially) -->
<div id="chat-window" style="position:fixed;right:24px;bottom:100px;z-index:10000;width:400px;max-width:calc(100vw - 48px);height:480px;border-radius:12px;box-shadow:0 12px 40px rgba(0,0,0,0.12);overflow:visible;background:#fff;display:none;font-family:Inter,Segoe UI,Roboto,Helvetica,Arial,sans-serif;">
  <style>
    #chat-window .chat-header{padding:12px 14px;background:#f3f4f6;display:flex;align-items:center;justify-content:space-between}
    #chat-window .chat-messages{padding:12px;height:360px;overflow:auto;background:#ffffff; max-width: 368px;}
    #chat-window .chat-input-wrap{padding:10px;background:#fbfbfb;border-top:1px solid #eee;display:flex;gap:8px}
    #chat-window .chat-input{flex:1;padding:8px 10px;border-radius:8px;border:1px solid #ddd}
    #chat-window .msg{margin-bottom:10px}
    #chat-window .msg .bubble{display:inline-block;padding:8px 12px;border-radius:12px;max-width: 384px;}
    #chat-window .msg.user .bubble{background:#f3f4f6;color:#111;margin-left:auto}
    #chat-window .msg.assistant .bubble{background:#f0fdf4;color:#064e3b}

    /* Neumorphism Send Button Styles */
    #chat-window .send {
      position: relative;
      display: flex;
      align-items: center;
      color: #353535;
      background: transparent;
      border: none;
      font-family: inherit;
      padding: 0;
      border-radius: 2.5rem;
      font-size: 1rem;
      font-variation-settings: 'wght' 500;
      cursor: pointer;
      transition: transform 0.4s ease-out;
      box-shadow: 6px 6px 12px 0px #a3b1c6, -6px -6px 12px 0px rgba(255, 255, 255, 0.6);
    }

    #chat-window .send:hover .icon svg {
      transform: translate(-5px, 5px);
    }

    #chat-window .send .text {
      height: 2.5rem;
      padding: 0.4rem 0.4rem 0.4rem 0.8rem;
      font-size: 0.9rem;
    }

    #chat-window .send .icon {
      display: block;
      padding: 0.4rem;
      box-shadow: 6px 6px 12px 0px #a3b1c6, -6px -6px 12px 0px rgba(255, 255, 255, 0.6);
      border-radius: 50%;
      width: 2.5rem;
      height: 2.5rem;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    #chat-window .send svg {
      fill: currentColor;
      width: 1.2rem;
      height: 1.2rem;
      transition: transform 0.4s ease-out;
    }
  </style>
  <div class="chat-header">
    <div style="font-weight:600">AI Assistant</div>
    <button id="chat-close" style="background:none;border:none;color:#6b7280;cursor:pointer;font-size:16px">✕</button>
  </div>
  <div id="chat-messages" class="chat-messages"></div>
  <div class="chat-input-wrap">
    <input id="chat-input" class="chat-input" placeholder="Ask me anything..." autocomplete="off" />
    <button class="send" id="chat-send">
      <span class="text">Send</span>
      <span class="icon">
        <svg viewBox="0 0 512.005 512.005">
          <path d="M511.658 51.675c2.496-11.619-8.895-21.416-20.007-17.176l-482 184a15 15 0 00-.054 28.006L145 298.8v164.713a15 15 0 0028.396 6.75l56.001-111.128 136.664 101.423c8.313 6.17 20.262 2.246 23.287-7.669C516.947 34.532 511.431 52.726 511.658 51.675zm-118.981 52.718L157.874 271.612 56.846 232.594zM175 296.245l204.668-145.757c-176.114 185.79-166.916 176.011-167.684 177.045-1.141 1.535 1.985-4.448-36.984 72.882zm191.858 127.546l-120.296-89.276 217.511-229.462z"/>
        </svg>
      </span>
    </button>
  </div>
</div>

  <script>
    // Floating contact button functionality
    (function(){
      const contactToggle = document.getElementById('contact-toggle');
      const contactMenu = document.getElementById('contact-menu');
      const aiAssistantBtn = document.getElementById('ai-assistant-btn');
      const chatWindow = document.getElementById('chat-window');
      const chatInput = document.getElementById('chat-input');
  
      // Toggle contact menu
      contactToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        const isVisible = contactMenu.style.display === 'block';
        contactMenu.style.display = isVisible ? 'none' : 'block';
      });
  
      // Close menu when clicking outside
      document.addEventListener('click', function(e) {
        if (!contactToggle.contains(e.target) && !contactMenu.contains(e.target)) {
          contactMenu.style.display = 'none';
        }
      });
  
      // AI Assistant button
      aiAssistantBtn.addEventListener('click', function() {
        contactMenu.style.display = 'none';
        chatWindow.style.display = 'block';
        if (chatInput) chatInput.focus();
      });
    })();
  
    // Chat functionality
    (function(){
      const chatWindow = document.getElementById('chat-window');
      const chatClose = document.getElementById('chat-close');
      const chatFormInput = document.getElementById('chat-input');
      const chatSendBtn = document.getElementById('chat-send');
      const chatMessages = document.getElementById('chat-messages');
  
      function closeChat(){ chatWindow.style.display = 'none'; }
  
      chatClose.addEventListener('click', closeChat);
  
      function escapeHtml(unsafe){
        return unsafe.replaceAll('&','&amp;').replaceAll('<','<').replaceAll('>','>').replaceAll('"','"').replaceAll("'","&#039;");
      }
  
      function appendMessage(role, text){
        const d = document.createElement('div'); d.className = 'msg '+role;
        const bubble = document.createElement('div'); bubble.className = 'bubble';
  
        if (role === 'user') {
          bubble.innerHTML = escapeHtml(text);
        } else {
          // For assistant messages, use the HTML-formatted response directly
          bubble.innerHTML = text;
        }
  
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
          if (json.error){
            loadingIndex.querySelector('.bubble').innerHTML = '<em>'+escapeHtml(json.error)+'</em>';
          }
          else {
            // Use the HTML-formatted response directly for assistant messages
            loadingIndex.querySelector('.bubble').innerHTML = json.message || JSON.stringify(json);
          }
        }catch(e){ loadingIndex.querySelector('.bubble').innerHTML = '<em>Network error</em>'; }
      }
  
      chatSendBtn.addEventListener('click', function(){
        const t = chatFormInput.value.trim();
        if (!t) return;

        // GSAP Animation
        gsap.timeline()
          .to('.send .icon svg', 0.4, {
            x: -8,
            y: 8
          })
          .to('.send .icon svg', 0.4, {
            x: '100vw',
            y: '-100vh',
          })
          .set('.send .icon svg', {
            x: '-100vw',
            y: '100vh'
          })
          .to('.send .icon svg', 0.3, {
            x: 0,
            y: 0,
            clearProps: 'transform'
          });

        chatFormInput.value = '';
        sendMessage(t);
      });
      chatFormInput.addEventListener('keydown', function(e){ if (e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); chatSendBtn.click(); } });
    })();
  </script>
