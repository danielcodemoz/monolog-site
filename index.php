<?php
declare(strict_types=1);
header('Content-Type: text/html; charset=UTF-8');
$DATA = json_decode((string)file_get_contents(__DIR__ . '/content.json'), true);
if (!is_array($DATA)) {
    http_response_code(500);
    echo 'content.json ilegível';
    exit;
}
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
<html lang="pt">
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
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
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
      <a href="#programa"><span data-en="The app"><?= e(t($DATA,'nav.link_shots')) ?></span></a>
      <a href="#funcionalidades"><span data-en="Features"><?= e(t($DATA,'nav.link_features')) ?></span></a>
      <a href="#porque"><span data-en="Why offline"><?= e(t($DATA,'nav.link_why')) ?></span></a>
      <a href="#sobre"><span data-en="About"><?= e(t($DATA,'nav.link_about')) ?></span></a>
      <button type="button" class="lang-toggle" id="lang-toggle" aria-label="Toggle language">
        <span class="lang-pt active">PT</span><span class="lang-divider">/</span><span class="lang-en">EN</span>
      </button>
      <a class="btn" href="#descarregar" data-en="Download"><?= e(t($DATA,'nav.cta')) ?></a>
    </nav>
  </div>
</header>

<main id="conteudo">
  <section class="hero">
    <img class="hero-img" src="/assets/hero-3d.webp?v=2" width="1536" height="1024" alt="<?= e(t($DATA,'alts.hero')) ?>" fetchpriority="high" decoding="async" sizes="100vw">
    <div class="hero-veil" aria-hidden="true"></div>
    <div class="wrap hero-copy">
      <p class="hero-kicker"><span data-en="Journal for Windows · local · no account"><?= e(t($DATA,'hero.kicker')) ?></span></p>
      <h1><span data-en="Your journal."><?= e(t($DATA,'hero.title')) ?></span><?php if (t($DATA,'hero.title_em') !== ''): ?> <em><span data-en="Only on your computer."><?= e(t($DATA,'hero.title_em')) ?></span></em><?php endif; ?></h1>
      <p class="lead"><?= p($DATA,'hero.lead') ?></p>
      <div class="hero-actions">
        <a class="btn btn-lg" href="<?= e($dl) ?>"><span data-en="Download for Windows"><?= e(t($DATA,'hero.cta')) ?></span></a>
        <a class="scroll" href="#programa"><span data-en="See the app"><?= e(t($DATA,'hero.scroll')) ?></span></a>
      </div>
      <p class="cta-note"><span data-en="Monolog-1.0.0-setup.exe · portable version also available"><?= e(t($DATA,'hero.cta_note')) ?></span></p>
    </div>
  </section>

  <section class="proof" aria-label="<?= e(t($DATA,'proof.aria')) ?>">
    <div class="wrap proof-in">
<?php foreach ($proofs as $k): ?>
    <article>
      <h3><span data-en="<?= e(['offline'=>'Offline','aes'=>'AES Vault','windows'=>'Windows','account'=>'No account'][$k] ?? '') ?>"><?= e(t($DATA,"proof.{$k}_label")) ?></span></h3>
      <p><span data-en="<?= e(['offline'=>'Works without Wi-Fi. Always. The network is not invited.','aes'=>'Your password. Your disk. Nobody else has the key.','windows'=>'A program on your computer. Not a website with a login.','account'=>'No signup. We do not track you. There is no server.'][$k] ?? '') ?>"><?= e(t($DATA,"proof.{$k}_text")) ?></span></p>
    </article>
<?php endforeach; ?>
    </div>
  </section>

  <section class="section" id="programa">
    <div class="wrap">
      <p class="kicker"><span data-en="The real app — not a made-up mockup"><?= e(t($DATA,'shots.kicker')) ?></span></p>
      <h2><span data-en="Open. Write. Close."><?= e(t($DATA,'shots.title')) ?></span></h2>
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
      <p class="kicker"><span data-en="What's inside"><?= e(t($DATA,'features.kicker')) ?></span></p>
      <h2><span data-en="A notebook with drawers. The drawer is not in the cloud."><?= e(t($DATA,'features.title')) ?></span></h2>
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
      <p class="kicker"><span data-en="Manifesto"><?= e(t($DATA,'why.kicker')) ?></span></p>
      <h2><span data-en="Why offline. Why only on your computer."><?= e(t($DATA,'why.title')) ?></span></h2>
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
      <p class="kicker"><span data-en="Windows 10 and 11"><?= e(t($DATA,'download.kicker')) ?></span></p>
      <h2><span data-en="Take the notebook to disk."><?= e(t($DATA,'download.title')) ?></span></h2>
      <p class="lead"><?= p($DATA,'download.lead') ?></p>
      <p><a class="btn btn-lg btn-ghost" href="<?= e($dl2) ?>"><span data-en="Download Monolog 1.0"><?= e(t($DATA,'download.cta')) ?></span></a></p>
      <p class="file"><?= e(t($DATA,'download.file')) ?></p>
      <p class="note"><span data-en="A portable version is also available: the journal can live on a USB stick."><?= e(t($DATA,'download.note')) ?></span></p>
      <p class="note"><span data-en="Windows. No account. No cloud. No server permission."><?= e(t($DATA,'download.reqs')) ?></span></p>
    </div>
  </section>
</main>

<footer>
  <div class="wrap">
    <div>
      <strong><?= e(t($DATA,'footer.brand')) ?></strong>
      <span class="tag"><?= e(t($DATA,'footer.tag')) ?></span>
    </div>
    <p class="foot-line"><span data-en="Local journal for Windows. Your words stay where you wrote them."><?= e(t($DATA,'footer.line')) ?></span></p>
    <p class="foot-meta"><span data-en="A desktop program. Not a service."><?= e(t($DATA,'footer.credit')) ?></span></p>
    <p class="foot-meta"><?= e(t($DATA,'footer.url')) ?></p>
  </div>
</footer>

<script>
(function () {
  var toggle = document.getElementById('lang-toggle');
  if (!toggle) return;
  var ptBtn = toggle.querySelector('.lang-pt');
  var enBtn = toggle.querySelector('.lang-en');
  var lang = localStorage.getItem('lang') || 'pt';
  function apply(langCode) {
    lang = langCode;
    localStorage.setItem('lang', lang);
    ptBtn.classList.toggle('active', lang === 'pt');
    enBtn.classList.toggle('active', lang === 'en');
    document.documentElement.lang = lang === 'en' ? 'en' : 'pt';
    document.querySelectorAll('[data-en]').forEach(function (el) {
      if (!el.hasAttribute('data-pt')) el.setAttribute('data-pt', el.textContent);
      el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-pt');
    });
  }
  toggle.addEventListener('click', function () { apply(lang === 'pt' ? 'en' : 'pt'); });
  if (lang === 'en') apply('en');
})();
</script>

</body>
</html>
