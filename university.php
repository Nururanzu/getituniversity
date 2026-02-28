<?php
require_once __DIR__ . "/data.php";
function h($str) {
  return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8");
}
?>
<?php
$id = isset($_GET["id"]) ? $_GET["id"] : "";
$id = strtolower(trim($id));
$uni = $universities[$id] ?? null;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?php echo $uni ? h($uni["short"]) . " — GetInUniversity" : "ВУЗ не найден"; ?></title>
  <link rel="stylesheet" href="styles.css" />
  <link rel="icon" type="image/svg+xml" href="assets/giu-icon.svg">
</head>
<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand brand--link" href="index.php">
      <div class="brand__logo brand__logo--img">
        <img src="assets/giu-icon.svg" alt="GetInUniversity">
      </div>
      <div class="brand__text">
        <div class="brand__name">GetInUniversity</div>
        <div class="brand__sub">Вернуться к каталогу</div>
      </div>
    </a>
  </div>
</header>

<main class="container">
  <?php if (!$uni): ?>
    <section class="notfound">
      <h1>ВУЗ не найден</h1>
      <p class="muted">Проверь ссылку или вернись в каталог.</p>
      <a class="btn" href="index.php">На главную</a>
    </section>
  <?php else: ?>
    <section class="uni-hero">
      <div class="uni-hero__logo"><?php echo h($uni["logo"]); ?></div>
      <div class="uni-hero__info">
        <div class="uni-hero__short"><?php echo h($uni["short"]); ?></div>
        <h1 class="uni-hero__name"><?php echo h($uni["name"]); ?></h1>
        <div class="uni-hero__meta">
          <span class="pill"><?php echo h($uni["city"]); ?></span>
          <span class="pill pill--soft"><?php echo h($uni["tagline"]); ?></span>
        </div>
        <?php if (!empty($uni["website"])): ?>
          <a class="link" href="<?php echo h($uni["website"]); ?>" target="_blank" rel="noreferrer">
            Официальный сайт
          </a>
        <?php else: ?>
          <span class="muted">Официальный сайт: добавь в data.php</span>
        <?php endif; ?>
      </div>
    </section>

    <section class="two-col">
      <div class="box">
        <h3>Описание</h3>
        <p><?php echo h($uni["description"]); ?></p>
      </div>

      <div class="box">
        <h3>Популярные направления </h3>
        <div class="chips">
          <?php foreach (($uni["programs"] ?? []) as $p): ?>
            <span class="mini"><?php echo h($p); ?></span>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="box">
         <h3>Поступление и стоимость</h3>

         <?php
           $hasInfo =
             !empty($uni['admission_period']) ||
             !empty($uni['threshold']) ||
             !empty($uni['tuition']) ||
             !empty($uni['grants']) ||
             !empty($uni['dorm']);
         ?>

         <?php if ($hasInfo): ?>
           <ul>
             <?php if (!empty($uni['admission_period'])): ?>
               <li><strong>Приёмная комиссия:</strong> <?php echo htmlspecialchars($uni['admission_period']); ?></li>
             <?php endif; ?>

             <?php if (!empty($uni['threshold'])): ?>
               <li><strong>Пороговый балл:</strong> <?php echo htmlspecialchars($uni['threshold']); ?></li>
             <?php endif; ?>

             <?php if (!empty($uni['tuition'])): ?>
               <li><strong>Стоимость обучения:</strong> <?php echo htmlspecialchars($uni['tuition']); ?></li>
             <?php endif; ?>

             <?php if (!empty($uni['grants'])): ?>
               <li><strong>Гранты/скидки:</strong> <?php echo htmlspecialchars($uni['grants']); ?></li>
             <?php endif; ?>

             <?php if (!empty($uni['dorm'])): ?>
               <li><strong>Общежитие:</strong> <?php echo htmlspecialchars($uni['dorm']); ?></li>
             <?php endif; ?>
           </ul>
         <?php else: ?>
           <p class="muted">Пока нет заполненных данных. Добавьте их в data.php.</p>
         <?php endif; ?>
       </div>

      <div class="box">
        <h3>Как поступить </h3>
        <ul>
          <?php foreach (($uni["admission_steps"] ?? []) as $step): ?>
            <li><?php echo h($step); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>

      <?php if (!empty($uni["streetview_src"])): ?>
        <div class="box">
          <h3>Кампус в 360°</h3>
          <iframe
            src="<?php echo h($uni['streetview_src']); ?>"
            width="100%"
            height="360"
            style="border:0; border-radius: 14px;"
            allowfullscreen=""
            loading="lazy">
          </iframe>
          <p class="muted" style="margin-top:10px;">
            Если панорама не отображается, попробуйте выбрать другую точку Street View рядом с входом.
          </p>
        </div>
      <?php else: ?>
        <div class="box">
          <h3>Кампус в 360°</h3>
          <p class="muted">Панорама не добавлена. Вставь src в поле streetview_src в data.php.</p>
        </div>
      <?php endif; ?>

      <div class="box">
        <h3>Что можешь посмотреть сам через ИИ помощник</h3>
        <ul>
          <li>Точные сроки приёма документов</li>
          <li>Конкретные цены и грантовые квоты</li>
          <li>Отзывы студентов и выпускников</li>
          <li>Фильтры по направлениям и городам</li>
        </ul>
      </div>
    </section>
    <section class="box">
      <h3>ИИ по этому ВУЗу </h3>
      <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <input id="aiInputUni"
               placeholder="Спроси про поступление, гранты, направления..."
               style="flex:1; min-width:240px; padding:10px; border-radius:10px;">
        <select id="aiModelUni" style="padding:10px; border-radius:10px;">
          <option value="llama3.2">llama3.1</option>
          <option value="llama3.2">llama3.2</option>
        </select>
        <button class="btn" id="aiSendUni" type="button">Спросить</button>
      </div>
      <div id="aiOutUni" class="muted" style="margin-top:12px; white-space: pre-wrap;"></div>
    </section>

<script>
const UNI_ID = "<?php echo h($id); ?>";

document.getElementById("aiSendUni").addEventListener("click", async () => {
  const inp = document.getElementById("aiInputUni");
  const out = document.getElementById("aiOutUni");
  const model = document.getElementById("aiModelUni").value;

  const text = inp.value.trim();
  if (!text) return;

  out.textContent = "Думаю...";

  const r = await fetch("ollama_chat.php", {
    method: "POST",
    headers: {"Content-Type":"application/json"},
    body: JSON.stringify({ message: text, model, uni_id: UNI_ID })
  });

  const data = await r.json();
  out.textContent = data?.message?.content ?? data?.error ?? JSON.stringify(data);
});
</script>

    <div class="backline">
      <a class="btn btn--ghost" href="index.php">← Назад к университетам</a>
      <a class="btn btn--ghost" href="compare.php?ids[]=<?php echo urlencode($id); ?>">
        Открыть сравнение
      </a>
    </div>
  <?php endif; ?>
</main>

<footer class="footer">
  <div class="container footer__inner">
    <div>© <?php echo date("Y"); ?> GetItUniversity </div>
  </div>
</footer>

</body>
</html>
