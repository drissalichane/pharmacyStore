(function(){
  // Simple chat popup client
  const toggleBtn = document.getElementById('chat-toggle');
  const chatWindow = document.getElementById('chat-window');
  const chatClose = document.getElementById('chat-close');
  const chatForm = document.getElementById('chat-form');
  const chatMessages = document.getElementById('chat-messages');
  const chatInput = document.getElementById('chat-input');

  if (!toggleBtn) return;

  function openChat(){
    chatWindow.classList.remove('hidden');
    chatWindow.classList.add('block');
    chatInput.focus();
  }
  function closeChat(){
    chatWindow.classList.add('hidden');
    chatWindow.classList.remove('block');
  }

  toggleBtn.addEventListener('click', function(){
    if (chatWindow.classList.contains('hidden')) openChat(); else closeChat();
  });
  chatClose.addEventListener('click', closeChat);

  function appendMessage(role, text){
    const wrapper = document.createElement('div');
    wrapper.className = 'mb-3';
    if (role === 'user') {
      wrapper.innerHTML = `<div class="text-sm text-right"><div class="inline-block bg-gray-100 text-gray-900 px-3 py-2 rounded-lg">${escapeHtml(text)}</div></div>`;
    } else {
      wrapper.innerHTML = `<div class="text-sm text-left"><div class="inline-block bg-green-50 text-gray-900 px-3 py-2 rounded-lg">${escapeHtml(text)}</div></div>`;
    }
    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function escapeHtml(unsafe) {
    return unsafe
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  async function sendMessage(text){
    appendMessage('user', text);
    appendMessage('assistant', '...');
    const loadingNode = chatMessages.lastChild;

    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const res = await fetch('/chat/message', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ message: text })
      });

      const json = await res.json();
      if (json.error) {
        loadingNode.querySelector('div').innerHTML = '<em>Service unavailable</em>';
      } else {
        // For assistant messages, use the HTML-formatted response directly
        loadingNode.querySelector('div').innerHTML = json.message || json;
      }
    } catch (e) {
      loadingNode.querySelector('div').innerHTML = '<em>Network error</em>';
    }
  }

  chatForm?.addEventListener('submit', function(e){
    e.preventDefault();
    const text = chatInput.value.trim();
    if (!text) return;
    chatInput.value = '';
    sendMessage(text);
  });

})();
