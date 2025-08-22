AI Chat (Gemini) Setup

This project includes a lightweight chat popup (AI assistant) that proxies messages server-side to a generative model (Gemini/Text-Bison). To enable it, add the following to your `.env` file:

- `GEMINI_API_KEY` — your Google Generative API key (keep this secret).
- `AI_PROVIDER` — optional (defaults to `gemini`).

Example:

GEMINI_API_KEY=sk-....
AI_PROVIDER=gemini

Then rebuild assets (if you use Vite) and ensure your web server is running. The chat popup is included site-wide in the layout; it posts to `/chat/message` which proxies the request to Gemini.

Notes:
- The server-side proxy prevents exposing your API key to the browser.
- The endpoint is rate-limited by middleware; consider further throttling or auth for production.
