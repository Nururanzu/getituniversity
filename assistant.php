<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>ИИ-консультант — GetItUniversity</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css">
<header class="topbar">
  <div class="container topbar__inner">
    <a href="index.php" class="brand brand--link">
      <div class="brand__logo brand__logo--img">
        <img src="assets/giu-icon.svg" alt="GetItUniversity">
      </div>
      <div>
        <div class="brand__name">GetItUniversity</div>
        <div class="brand__sub">Каталог университетов</div>
      </div>
    </a>

    <div style="display:flex; gap:10px; flex-wrap:wrap;">
      <button class="btn btn--ghost" id="themeToggle">🎨 Тема</button>
    </div>
  </div>
</header>

<style>
.chat-wrapper{
  max-width: 800px;
  margin: 40px auto;
}
.chat-box{
  background: #111827;
  padding: 20px;
  border-radius: 16px;
  min-height: 300px;
  max-height: 500px;
  overflow-y: auto;
}
.msg{
  margin-bottom: 15px;
  padding: 12px;
  border-radius: 12px;
}
.user{
  background: #2563eb;
  color: white;
}
.ai{
  background: #1f2937;
  color: #e5e7eb;
}
.controls{
  display: flex;
  gap: 10px;
  margin-top: 15px;
}
.controls input{
  flex: 1;
  padding: 10px;
  border-radius: 8px;
  border: none;
}
.controls button{
  padding: 10px 16px;
  border-radius: 8px;
  border: none;
  background: #22c55e;
  color: white;
  cursor: pointer;
}
</style>
</head>

<body>

<div class="chat-wrapper">
  <h1>ИИ-консультант GetItUniversity</h1>

  <div id="chatBox" class="chat-box">
    <div class="msg ai">
      👋 Спроси, например:
      <br>— Дай подробную информацию про KBTU
      <br>— Лучшие университеты для IT в Казахстане
    </div>
  </div>

  <div class="controls">
    <input id="userInput" type="text" placeholder="Введите вопрос...">
    <button onclick="sendMessage()">Спросить</button>
  </div>
</div>

<script>
async function sendMessage(){
  const input = document.getElementById("userInput");
  const chat = document.getElementById("chatBox");
  const text = input.value.trim();
  if(!text) return;

  chat.innerHTML += `<div class="msg user">${text}</div>`;
  input.value = "";

  chat.innerHTML += `<div class="msg ai" id="loading">ИИ думает...</div>`;
  chat.scrollTop = chat.scrollHeight;

  const response = await fetch("gemini_chat.php", {
    method: "POST",
    headers: {"Content-Type": "application/json"},
    body: JSON.stringify({ message: text })
  });

  const data = await response.json();
  document.getElementById("loading").remove();

  const answer =
    data?.candidates?.[0]?.content?.parts?.[0]?.text ||
    data?.error ||
    "Ошибка ответа ИИ";

  chat.innerHTML += `<div class="msg ai">${answer.replace(/\n/g, "<br>")}</div>`;
  chat.scrollTop = chat.scrollHeight;
}
</script>

</body>
</html>