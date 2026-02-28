<?php
require_once __DIR__ . "/data.php";

function h($str) {
  return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8");
}

function lower_txt($str) {
  if ($str === null) return "";
  $str = (string)$str;
  return function_exists("mb_strtolower")
    ? mb_strtolower($str, "UTF-8")
    : strtolower($str);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Каталог университетов — GetItUniversity</title>

  <link rel="stylesheet" href="styles.css" />
  <link rel="icon" type="image/svg+xml" href="assets/giu-icon.svg">
  <!-- маленькая доп-стилизация для select в ИИ-блоке -->

  <style>
    .ai-select{
      padding: 10px 12px;
      border-radius: 12px;
      border: 1px solid var(--border, rgba(255,255,255,0.1));
      background: var(--surface, rgba(255,255,255,0.06));
      color: var(--text, #fff);
      min-width: 120px;
    }
  </style>
</head>
<body>

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
      <a href="assistant.php" class="btn btn--ghost">🤖 ИИ</a>
      <button class="btn btn--ghost" id="themeToggle">🎨 Тема</button>
    </div>
  </div>
</header>


<!-- Вариант 2: боковые изображения -->
<div class="page-layout">
  <aside class="side-img side-img--left" aria-hidden="true"></aside>

  <div class="page-center">

    <main class="container">

      <!-- ПОИСК -->
      <section class="box" style="margin: 18px 0;">
        <h3>Поиск университета</h3>
        <div class="searchbar">
          <input id="uniSearch" type="text"
                 placeholder="Например: KazNU, IT, Алматы, медицина..."
                 autocomplete="off">
          <button class="btn btn--ghost" id="uniSearchClear" type="button">Сброс</button>
        </div>
        <div id="uniSearchInfo" class="muted" style="margin-top:8px;"></div>
      </section>

      <section class="section-head">
        <h2>Университеты</h2>
        <p>Поставь галочки у 2–4 ВУЗов и нажми «Сравнить выбранные».</p>
      </section>

      <form class="compare-form" action="compare.php" method="get">
        <div class="compare-bar">
          <button class="btn" type="submit">Сравнить выбранные</button>
          <span class="muted">Рекомендация: 2–4 ВУЗа для удобного сравнения</span>
        </div>

        <section class="grid">
          <?php foreach ($universities as $id => $u): ?>
            <?php
              $programsText = "";
              if (!empty($u["programs"]) && is_array($u["programs"])) {
                $programsText = implode(" ", $u["programs"]);
              }

              $searchText = lower_txt(
                ($u["short"] ?? "") . " " .
                ($u["name"] ?? "") . " " .
                ($u["city"] ?? "") . " " .
                ($u["tagline"] ?? "") . " " .
                ($u["description"] ?? "") . " " .
                $programsText
              );
            ?>

            <div class="card card--selectable uni-card"
                 data-search="<?php echo h($searchText); ?>">

              <div class="card__top">
                <div class="card__logo"><?php echo h($u["logo"]); ?></div>

                <div class="card__titles">
                  <div class="card__short"><?php echo h($u["short"]); ?></div>
                  <div class="card__name"><?php echo h($u["name"]); ?></div>
                </div>

                <div class="card__right">
                  <label class="compare-inline">
                    <input
                      class="compare-check"
                      type="checkbox"
                      name="ids[]"
                      value="<?php echo h($id); ?>"
                    />
                    <span>Сравнить</span>
                  </label>

                  <span class="pill"><?php echo h($u["city"]); ?></span>
                </div>
              </div>

              <div class="card__tagline">
                <?php echo h($u["tagline"]); ?>
              </div>

              <div class="card__programs">
                <?php foreach (array_slice($u["programs"] ?? [], 0, 3) as $p): ?>
                  <span class="mini"><?php echo h($p); ?></span>
                <?php endforeach; ?>
              </div>

              <a class="card__cta-link" href="university.php?id=<?php echo urlencode($id); ?>">
                Смотреть ВУЗ →
              </a>
            </div>
          <?php endforeach; ?>
        </section>
      </form>

    </main>

    <footer class="footer">
      <div class="container footer__inner">
        <div>© <?php echo date("Y"); ?> GetItUniversity (demo)</div>
        <div class="muted">Школьный демо-проект</div>
      </div>
    </footer>

    <!-- 1) Поиск по карточкам -->
    <script>
    (function () {
      var input = document.getElementById("uniSearch");
      var clearBtn = document.getElementById("uniSearchClear");
      var info = document.getElementById("uniSearchInfo");
      var cards = Array.prototype.slice.call(document.querySelectorAll(".uni-card"));

      if (!input || cards.length === 0) return;

      function applyFilter() {
        var q = (input.value || "").trim().toLowerCase();
        var visible = 0;

        cards.forEach(function(card){
          var hay = (card.getAttribute("data-search") || "").toLowerCase();
          var ok = (q === "") || (hay.indexOf(q) !== -1);
          card.style.display = ok ? "" : "none";
          if (ok) visible++;
        });

        if (info) {
          info.textContent = q ? ("Найдено: " + visible + " из " + cards.length) : "";
        }
      }

      input.addEventListener("input", applyFilter);

      if (clearBtn) {
        clearBtn.addEventListener("click", function(){
          input.value = "";
          applyFilter();
          input.focus();
        });
      }

      applyFilter();
    })();
    </script>

    <!-- 2) ИИ на главной -->
    <script>
    (function(){
      var btn = document.getElementById("aiSend");
      var inp = document.getElementById("aiInput");
      var out = document.getElementById("aiOut");
      var modelSel = document.getElementById("aiModel");

      if (!btn || !inp || !out || !modelSel) return;

      btn.addEventListener("click", async function () {
        var text = (inp.value || "").trim();
        if (!text) return;

        out.textContent = "Думаю...";

        try {
          var r = await fetch("ollama_chat.php", {
            method: "POST",
            headers: {"Content-Type":"application/json"},
            body: JSON.stringify({
              message: text,
              model: modelSel.value,
              uni_id: ""
            })
          });

          var data = await r.json();
          var answer = (data && data.message && data.message.content) ? data.message.content
                       : (data && data.error) ? data.error
                       : JSON.stringify(data);

          out.textContent = answer;
        } catch (e) {
          out.textContent = "Ошибка: " + e.message;
        }
      });
    })();
    </script>

    <!-- 3) Переключатель темы -->
    <script>
    (function(){
      var root = document.documentElement;
      var btn = document.getElementById("themeToggle");
      var KEY = "giu-theme";

      function apply(theme){
        root.setAttribute("data-theme", theme);
        if (btn) btn.textContent = (theme === "official") ? "🎓 Official" : "⚡ Neon";
      }

      var saved = null;
      try { saved = localStorage.getItem(KEY); } catch(e){}

      apply(saved || "neon");

      if (btn) {
        btn.addEventListener("click", function(){
          var current = root.getAttribute("data-theme") || "neon";
          var next = (current === "neon") ? "official" : "neon";

          try { localStorage.setItem(KEY, next); } catch(e){}

          apply(next);
        });
      }
    })();
    </script>

  </div>

  <aside class="side-img side-img--right" aria-hidden="true"></aside>
</div>

</body>
</html>
