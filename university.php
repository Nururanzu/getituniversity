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
  <link rel="icon" href="icon.png">
</head>
<body>

<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand brand--link" href="index.php">
      <div class="brand__logo brand__logo--img">
        <img src="icon.png" alt="GetInUniversity">
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
          <ol type="I">
  <li>Бакалавриат
    <ul style="list-style-type:square;">
      <?php foreach ($uni["programs"] as $prog): ?>
        <li><?php echo $prog; ?></li>
      <?php endforeach; ?>
    </ul>
  </li>
</ol>
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
   
    <div class="backline">
      <a class="btn btn--ghost" href="catalog.php">← Назад к университетам</a>
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
