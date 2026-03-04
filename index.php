<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>GetItUniversity — выбор университета в Казахстане</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="styles.css">
  <link rel="icon" href="icon.png">
</head>
<body>
    <video autoplay muted loop playsinline id="bgVideo">
  <source src="video.mp4" type="video/mp4">
</video>
<header class="topbar">
  <div class="container topbar__inner">
    <div class="brand">
      <div class="brand__logo brand__logo--img">
        <img src="icon.png" alt="GetItUniversity">
      </div>
      <div>
        <div class="brand__name">GetItUniversity</div>
        <div class="brand__sub">Платформа выбора ВУЗа в Казахстане</div>
      </div>
    </div>

    <button class="btn btn--ghost" id="themeToggle" type="button">
      🎨 Тема
    </button>
  </div>
</header>
<marquee behavior="scroll" direction="left" scrollamount="6" class="marq">
  🎓 Добро пожаловать в GetItUniversity — выбирай университет с умом!
</marquee>
<main class="container">

  <!-- HERO -->
  <section class="hero" style="text-align:center; margin-top:40px;">
    <h1>Выбери университет осознанно</h1>
    <p style="max-width:720px; margin:0 auto;">
      GetItUniversity помогает абитуриентам сравнивать университеты Казахстана,
      изучать направления обучения, смотреть кампусы и принимать взвешенное решение.
    </p>

    <!-- ГЛАВНЫЕ КНОПКИ -->
    <div style="margin-top:28px; display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
      <a href="catalog.php" class="btn" style="min-width:260px;">
        📚 Перейти в каталог университетов
      </a>

      <a href="assistant.php" class="btn btn--ghost" style="min-width:260px;">
        🤖 ИИ-консультант
      </a>
    </div>
  </section>

  <!-- ПРЕИМУЩЕСТВА -->
  <section style="margin-top:60px;">
    <div class="grid">

      <div class="box">
        <h3>📊 Сравнение ВУЗов</h3>
        <p class="muted">
          <u>Сравнивайте</u> 2–4 университета по направлениям, условиям поступления
          и другим важным критериям.
        </p>
      </div>

      <div class="box">
        <h3>🗺️ Кампусы 360°</h3>
        <p class="muted">
          Изучайте территорию университетов с помощью <b>Street View</b>
          и получайте представление о среде обучения.
        </p>
      </div>

      <div class="box">
        <h3>🤖 Умный помощник</h3>
        <p class="muted">
          Задавайте вопросы ИИ и <i>получайте рекомендации</i> по выбору
          университета и направлений.
        </p>
      </div>

    </div>
  </section>

  <!-- ДЛЯ КОГО -->
  <section style="margin-top:60px;">
    <div class="box">
      <h3>Кому полезен GetItUniversity?</h3>
      <ul>
        <li>Абитуриентам — для выбора подходящего ВУЗа</li>
        <li>Родителям — для понимания возможностей обучения</li>
        <li>Университетам — для привлечения мотивированных студентов</li>
      </ul>
    </div>
  </section>

</main>
<audio controls>
  <source src="hello.mp3">
</audio>
<footer class="footer">
  <div class="container footer__inner">
    <div>© <?php echo date("Y"); ?> GetItUniversity</div>
    <div class="muted">Учебный проект</div>
  </div>
</footer>

<!-- Переключатель темы -->
<script>
(function(){
  const root = document.documentElement;
  const btn = document.getElementById("themeToggle");
  const KEY = "giu-theme";

  function apply(theme){
    root.setAttribute("data-theme", theme);
    if (btn) btn.textContent = theme === "official" ? "🎓 Official" : "⚡ Neon";
  }

  const saved = localStorage.getItem(KEY);
  apply(saved || "neon");

  btn?.addEventListener("click", () => {
    const current = root.getAttribute("data-theme") || "neon";
    const next = current === "neon" ? "official" : "neon";
    localStorage.setItem(KEY, next);
    apply(next);
  });
})();
</script>
</body>
</html>
