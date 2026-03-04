<?php
require_once __DIR__ . "/data.php";

function h($str) {
  return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8");
}

$ids = $_GET["ids"] ?? [];
if (!is_array($ids)) {
  $ids = [$ids];
}

$ids = array_values(array_unique(array_filter(array_map(function($v){
  return strtolower(trim((string)$v));
}, $ids))));

$selected = [];
foreach ($ids as $id) {
  if (isset($universities[$id])) {
    $selected[$id] = $universities[$id];
  }
}

// Ограничим сравнение 4 ВУЗами
if (count($selected) > 4) {
  $selected = array_slice($selected, 0, 4, true);
}
$selectedCount = count($selected);

function getField($u, $key) {
  $v = $u[$key] ?? "";
  if (is_string($v)) $v = trim($v);
  return $v !== "" ? $v : null;
}

function renderField($u, $key) {
  $v = getField($u, $key);
  if ($v === null) {
    return '<span class="muted">Нет данных</span>';
  }
  return h($v);
}

function renderPrograms($u) {
  $arr = $u["programs"] ?? [];
  if (!is_array($arr) || empty($arr)) {
    return '<span class="muted">Нет данных</span>';
  }
  $safe = array_map("h", $arr);
  return implode(", ", $safe);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Сравнение ВУЗов — GetInUniversity</title>
  <link rel="stylesheet" href="styles.css" />
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
        <div class="brand__sub">Сравнение университетов</div>
      </div>
    </a>
  </div>
</header>

<main class="container">
  <section class="section-head" style="margin-top:26px;">
    <h2>Сравнение ВУЗов</h2>
    <p class="muted">Выберите 2–4 университета на главной странице.</p>
  </section>

  <?php if ($selectedCount < 2): ?>
    <section class="notfound">
      <h1>Недостаточно ВУЗов для сравнения</h1>
      <p class="muted">
        Сейчас выбрано: <?php echo (int)$selectedCount; ?>.
        Вернись на главную и отметь минимум два университета.
      </p>
      <a class="btn" href="index.php">Вернуться к выбору</a>
    </section>
  <?php else: ?>

    <div class="compare-wrap">
      <table class="compare-table">
        <thead>
          <tr>
            <th>Параметр</th>
            <?php foreach ($selected as $id => $u): ?>
              <th>
                <div class="compare-head">
                  <div class="compare-head__logo"><?php echo h($u["logo"] ?? ""); ?></div>
                  <div class="compare-head__short"><?php echo h($u["short"] ?? ""); ?></div>
                  <div class="compare-head__city"><?php echo h($u["city"] ?? ""); ?></div>
                </div>
              </th>
            <?php endforeach; ?>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td class="row-title">Полное название</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo h($u["name"] ?? ""); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Город</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo h($u["city"] ?? ""); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Позиционирование</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo h($u["tagline"] ?? ""); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Направления (пример)</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo renderPrograms($u); ?></td>
            <?php endforeach; ?>
          </tr>

          <!-- НОВЫЕ ПОЛЯ -->
          <tr>
            <td class="row-title">Сроки приёма / приёмная комиссия</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo renderField($u, "admission_period"); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Пороговый балл</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo renderField($u, "threshold"); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Стоимость обучения</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo renderField($u, "tuition"); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Гранты / скидки</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo renderField($u, "grants"); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Общежитие</td>
            <?php foreach ($selected as $u): ?>
              <td><?php echo renderField($u, "dorm"); ?></td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Официальный сайт</td>
            <?php foreach ($selected as $u): ?>
              <td>
                <?php if (!empty($u["website"])): ?>
                  <a class="link" href="<?php echo h($u["website"]); ?>" target="_blank" rel="noreferrer">
                    Открыть сайт
                  </a>
                <?php else: ?>
                  <span class="muted">Не добавлен</span>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>

          <tr>
            <td class="row-title">Страница ВУЗа на платформе</td>
            <?php foreach ($selected as $id => $u): ?>
              <td>
                <a class="btn btn--ghost" href="university.php?id=<?php echo urlencode($id); ?>">
                  Открыть
                </a>
              </td>
            <?php endforeach; ?>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="backline">
      <a class="btn btn--ghost" href="index.php">← Изменить выбор</a>
    </div>

  <?php endif; ?>
</main>

<footer class="footer">
  <div class="container footer__inner">
    <div>© <?php echo date("Y"); ?> GetItUniversity (demo)</div>
  </div>
</footer>

</body>
</html>
