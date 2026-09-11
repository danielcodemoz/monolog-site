<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=UTF-8');
$ALL_DATA = json_decode((string)file_get_contents(__DIR__ . '/content.json'), true);
if (!is_array($ALL_DATA)) {
    http_response_code(500);
    echo 'content.json ilegível';
    exit;
}

$lang = isset($_COOKIE['monolog_lang']) && $_COOKIE['monolog_lang'] === 'en' ? 'en' : 'pt';
$DATA = $lang === 'en' && isset($ALL_DATA['en']) ? $ALL_DATA['en'] : $ALL_DATA;

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function t(array $c, string $path): string {
    $v = $c;
    foreach (explode('.', $path) as $k) {
        if (!is_array($v) || !array_key_exists($k, $v)) {
            return '';
        }
        $v = $v[$k];
    }
    return is_string($v) ? $v : '';
}
function p(array $c, string $path): string {
    return nl2br(e(t($c, $path)), false);
}
$dl = t($DATA, 'hero.cta_href') ?: '/download/Monolog-1.0.0-setup.exe';
$dl2 = t($DATA, 'download.cta_href') ?: $dl;
$canon = 'https://monolog.danielpro.dev/';
$features = ['home','entries','tasks','habits','calendar','starred','archive','quotes','import','folders'];
$proofs = ['offline','aes','windows','account'];
?><!DOCTYPE html>
<html lang="<?= $lang ?>" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e(t($DATA,'meta.title')) ?></title>
<meta name="description" content="<?= e(t($DATA,'meta.description')) ?>">
<link rel="canonical" href="<?= e($canon) ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= e($canon) ?>">
<meta property="og:title" content="<?= e(t($DATA,'meta.og_title')) ?>">
<meta property="og:description" content="<?= e(t($DATA,'meta.og_description')) ?>">
<meta property="og:image" content="<?= e($canon) ?>assets/hero-3d.webp?v=2">
<meta name="theme-color" content="#F4EDE2">
<link rel="icon" href="/assets/favicon-32.png" type="image/png" sizes="32x32">
<link rel="icon" href="/favicon.ico" sizes="any">

<link rel="icon" href="/assets/favicon-48.png" type="image/png" sizes="48x48">
<link rel="apple-touch-icon" href="/assets/apple-touch.png">
<link rel="preload" href="/assets/fonts/fraunces-700.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/hero-3d.webp?v=2" as="image" fetchpriority="high">
<link rel="stylesheet" href="/css/site.css?v=2">
</head>
<body>
<div class="grain" aria-hidden="true"></div>
<a class="skip" href="#conteudo"><?= e(t($DATA,'nav.skip')) ?></a>

<header class="nav">
  <div class="wrap nav-in">
    <a class="brand" href="/">
      <img src="/assets/icon.webp?v=2" width="256" height="256" alt="<?= e(t($DATA,'alts.icon')) ?>">
      <span>
        <strong><?= e(t($DATA,'nav.brand')) ?></strong>
        <small><?= e(t($DATA,'nav.brand_sub')) ?></small>
      </span>
    </a>
    <nav class="nav-links" aria-label="<?= e(t($DATA,'nav.brand')) ?>">
      <a href="#programa"><?= e(t($DATA,'nav.link_shots')) ?></a>
      <a href="#funcionalidades"><?= e(t($DATA,'nav.link_features')) ?></a>
      <a href="#porque"><?= e(t($DATA,'nav.link_why')) ?></a>
      <a href="#sobre"><?= e(t($DATA,'nav.link_about')) ?></a>
      <a class="btn" href="#descarregar"><?= e(t($DATA,'nav.cta')) ?></a>
    </nav>
    <div class="toggles">
      <button class="toggle-theme" id="themeToggle" type="button" aria-label="Toggle theme">
        <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
        <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      </button>
      <button class="toggle-lang" id="langToggle" type="button" aria-label="Toggle language">
        <span class="lang-pt">PT</span>
        <span class="lang-en">EN</span>
      </button>
    </div>
  </div>
</header>

<main id="conteudo">
  <section class="hero">
    <img class="hero-img" src="/assets/hero-3d.webp?v=2" width="1536" height="1024" alt="<?= e(t($DATA,'alts.hero')) ?>" fetchpriority="high" decoding="async" sizes="100vw">
    <div class="hero-veil" aria-hidden="true"></div>
    <div class="wrap hero-copy">
      <p class="hero-kicker"><?= e(t($DATA,'hero.kicker')) ?></p>
      <h1><?= e(t($DATA,'hero.title')) ?><?php if (t($DATA,'hero.title_em') !== ''): ?> <em><?= e(t($DATA,'hero.title_em')) ?></em><?php endif; ?></h1>
      <p class="lead"><?= p($DATA,'hero.lead') ?></p>
      <div class="hero-actions">
        <a class="btn btn-lg" href="<?= e($dl) ?>"><?= e(t($DATA,'hero.cta')) ?></a>
        <a class="scroll" href="#programa"><?= e(t($DATA,'hero.scroll')) ?></a>
      </div>
      <p class="cta-note"><?= e(t($DATA,'hero.cta_note')) ?></p>
    </div>
  </section>

  <section class="proof" aria-label="<?= e(t($DATA,'proof.aria')) ?>">
    <div class="wrap proof-in">
<?php foreach ($proofs as $k): ?>
    <article>
      <h3><?= e(t($DATA,"proof.{$k}_label")) ?></h3>
      <p><?= e(t($DATA,"proof.{$k}_text")) ?></p>
    </article>
<?php endforeach; ?>
    </div>
  </section>

  <section class="section" id="programa">
    <div class="wrap">
      <p class="kicker"><?= e(t($DATA,'shots.kicker')) ?></p>
      <h2><?= e(t($DATA,'shots.title')) ?></h2>
      <p class="lead"><?= p($DATA,'shots.lead') ?></p>
      <div class="shots-grid">
        <figure class="shot featured">
          <div class="chrome">
            <div class="chrome-b" aria-hidden="true"><i></i><i></i><i></i> <?= e(t($DATA,'nav.brand')) ?></div>
            <img src="/assets/pub-home.webp?v=2" width="1279" height="1088" alt="<?= e(t($DATA,'alts.home')) ?>" loading="lazy" decoding="async">
          </div>
          <figcaption><?= e(t($DATA,'shots.home_caption')) ?></figcaption>
        </figure>
        <figure class="shot">
          <div class="chrome">
            <div class="chrome-b" aria-hidden="true"><i></i><i></i><i></i> <?= e(t($DATA,'nav.brand')) ?></div>
            <img src="/assets/pub-calendar.webp?v=2" width="1600" height="953" alt="<?= e(t($DATA,'alts.calendar')) ?>" loading="lazy" decoding="async">
          </div>
          <figcaption><?= e(t($DATA,'shots.calendar_caption')) ?></figcaption>
        </figure>
        <figure class="shot">
          <div class="chrome">
            <div class="chrome-b" aria-hidden="true"><i></i><i></i><i></i> <?= e(t($DATA,'nav.brand')) ?></div>
            <img src="/assets/pub-quotes.webp?v=2" width="1600" height="953" alt="<?= e(t($DATA,'alts.quotes')) ?>" loading="lazy" decoding="async">
          </div>
          <figcaption><?= e(t($DATA,'shots.quotes_caption')) ?></figcaption>
        </figure>
        <figure class="shot">
          <div class="chrome">
            <div class="chrome-b" aria-hidden="true"><i></i><i></i><i></i> <?= e(t($DATA,'nav.brand')) ?></div>
            <img src="/assets/pub-tasks.webp?v=2" width="1600" height="1230" alt="<?= e(t($DATA,'alts.tasks')) ?>" loading="lazy" decoding="async">
          </div>
          <figcaption><?= e(t($DATA,'shots.tasks_caption')) ?></figcaption>
        </figure>
        <figure class="shot">
          <div class="chrome">
            <div class="chrome-b" aria-hidden="true"><i></i><i></i><i></i> <?= e(t($DATA,'nav.brand')) ?></div>
            <img src="/assets/pub-settings.webp?v=2" width="927" height="640" alt="<?= e(t($DATA,'alts.settings')) ?>" loading="lazy" decoding="async">
          </div>
          <figcaption><?= e(t($DATA,'shots.settings_caption')) ?></figcaption>
        </figure>
        <figure class="shot">
          <div class="chrome">
            <div class="chrome-b" aria-hidden="true"><i></i><i></i><i></i> <?= e(t($DATA,'nav.brand')) ?></div>
            <img src="/assets/pub-vault.webp?v=3" width="1400" height="834" alt="<?= e(t($DATA,'alts.vault')) ?>" loading="lazy" decoding="async">
          </div>
          <figcaption><?= e(t($DATA,'shots.vault_caption')) ?></figcaption>
        </figure>
      </div>
    </div>
  </section>

  <section class="section features" id="funcionalidades">
    <div class="wrap">
      <p class="kicker"><?= e(t($DATA,'features.kicker')) ?></p>
      <h2><?= e(t($DATA,'features.title')) ?></h2>
      <p class="lead"><?= p($DATA,'features.lead') ?></p>
      <div class="feat-grid">
<?php foreach ($features as $k): ?>
        <article class="feat">
          <h3><?= e(t($DATA,"features.{$k}_title")) ?></h3>
          <p><?= e(t($DATA,"features.{$k}_text")) ?></p>
        </article>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section" id="porque">
    <div class="wrap">
      <p class="kicker"><?= e(t($DATA,'why.kicker')) ?></p>
      <h2><?= e(t($DATA,'why.title')) ?></h2>
      <div class="why-grid">
        <div class="why-copy">
          <p><?= p($DATA,'why.p1') ?></p>
          <p><?= p($DATA,'why.p2') ?></p>
          <p><?= p($DATA,'why.p3') ?></p>
        </div>
        <div class="why-cards">
          <article class="why-card">
            <img src="/assets/vault-3d.webp?v=2" width="1400" height="933" alt="<?= e(t($DATA,'alts.vault3d')) ?>" loading="lazy" decoding="async">
            <div>
              <h3><?= e(t($DATA,'why.vault_title')) ?></h3>
              <p><?= e(t($DATA,'why.vault_text')) ?></p>
            </div>
          </article>
          <article class="why-card">
            <img src="/assets/offline-3d.webp?v=2" width="1400" height="933" alt="<?= e(t($DATA,'alts.offline3d')) ?>" loading="lazy" decoding="async">
            <div>
              <h3><?= e(t($DATA,'why.offline_title')) ?></h3>
              <p><?= e(t($DATA,'why.offline_text')) ?></p>
            </div>
          </article>
        </div>
      </div>
    </div>
  </section>


  <section class="section about" id="sobre">
    <div class="wrap">
      <p class="kicker"><?= e(t($DATA,'about.kicker')) ?></p>
      <h2><?= e(t($DATA,'about.title')) ?></h2>
      <div class="about-grid">
        <aside class="about-card">
          <img src="/assets/icon.webp?v=2" width="256" height="256" alt="<?= e(t($DATA,'alts.icon')) ?>">
          <strong><?= e(t($DATA,'about.name')) ?></strong>
          <span><?= e(t($DATA,'about.role')) ?></span>
        </aside>
        <div class="about-copy">
          <p><?= p($DATA,'about.p1') ?></p>
          <p><?= p($DATA,'about.p2') ?></p>
          <p><?= p($DATA,'about.p3') ?></p>
          <p><a class="btn" href="<?= e(t($DATA,'about.cta_href')) ?>"><?= e(t($DATA,'about.cta')) ?></a></p>
        </div>
      </div>
    </div>
  </section>

  <section class="download" id="descarregar">
    <div class="wrap">
      <p class="kicker"><?= e(t($DATA,'download.kicker')) ?></p>
      <h2><?= e(t($DATA,'download.title')) ?></h2>
      <p class="lead"><?= p($DATA,'download.lead') ?></p>
      <p><a class="btn btn-lg btn-ghost" href="<?= e($dl2) ?>"><?= e(t($DATA,'download.cta')) ?></a></p>
      <p class="file"><?= e(t($DATA,'download.file')) ?></p>
      <p class="note"><?= e(t($DATA,'download.note')) ?></p>
      <p class="note"><?= e(t($DATA,'download.reqs')) ?></p>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <div>
      <strong><?= e(t($DATA,'footer.brand')) ?></strong>
      <span class="tag"><?= e(t($DATA,'footer.tag')) ?></span>
    </div>
    <p class="foot-line"><?= e(t($DATA,'footer.line')) ?></p>
    <p class="foot-meta"><?= e(t($DATA,'footer.credit')) ?></p>
    <p class="foot-meta"><?= e(t($DATA,'footer.url')) ?></p>
  </div>
</footer>
<script>
(function() {
  const html = document.documentElement;
  const themeToggle = document.getElementById('themeToggle');
  const langToggle = document.getElementById('langToggle');
  
  const savedTheme = localStorage.getItem('monolog_theme') || 'light';
  const savedLang = localStorage.getItem('monolog_lang') || 'pt';
  
  html.setAttribute('data-theme', savedTheme);
  html.setAttribute('lang', savedLang);
  
  themeToggle.addEventListener('click', function() {
    const current = html.getAttribute('data-theme');
    const next = current === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', next);
    localStorage.setItem('monolog_theme', next);
  });
  
  langToggle.addEventListener('click', function() {
    const current = html.getAttribute('lang');
    const next = current === 'pt' ? 'en' : 'pt';
    document.cookie = 'monolog_lang=' + next + '; path=/; max-age=31536000; SameSite=Lax';
    location.reload();
  });
})();
</script>
</body>
</html>
