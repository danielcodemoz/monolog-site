/*! Apoiar v1.1 \u2014 bot\u00e3o + janela de apoio (Daniel Marcos). Sem depend\u00eancias, sem servi\u00e7os externos. */
(function () {
  'use strict';
  if (window.__dpApoiar) return; window.__dpApoiar = 1;

  var script = document.currentScript || {};
  var ds = script.dataset || {};
  var CFG = {
    theme: ds.theme || 'auto',          // auto | dark | light | default-dark | default-light
    accent: ds.accent || '#e11d48',
    page: 'https://danielpro.dev/apoiar/',
    mpesa: '+258 84 556 6766', mpesaCopy: '845566766',
    name: 'Daniel Marcos Domingos Manganhe',
    paypal: 'https://www.paypal.com/donate?business=danielmarcos173%40gmail.com&currency_code=USD',
    kofi: 'https://ko-fi.com/danielpro'
  };
  var QR = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 25" class="qr" role="img" aria-label="QR M-Pesa"><path fill="#fff" d="M0 0h25v25h-25z"/><path class="qrline" stroke="#111" d="M2 2.5h7m2 0h4m1 0h7m-21 1h1m5 0h1m4 0h1m2 0h1m5 0h1m-21 1h1m1 0h3m1 0h1m1 0h2m2 0h1m1 0h1m1 0h3m1 0h1m-21 1h1m1 0h3m1 0h1m4 0h1m2 0h1m1 0h3m1 0h1m-21 1h1m1 0h3m1 0h1m1 0h2m4 0h1m1 0h3m1 0h1m-21 1h1m5 0h1m1 0h1m2 0h2m1 0h1m5 0h1m-21 1h7m1 0h1m1 0h1m1 0h1m1 0h7m-12 1h2m-10 1h1m2 0h1m1 0h1m1 0h1m2 0h3m1 0h2m1 0h1m-16 1h1m4 0h2m2 0h2m2 0h1m1 0h2m-19 1h11m3 0h2m3 0h1m-21 1h1m2 0h2m2 0h3m1 0h2m2 0h2m1 0h1m-19 1h5m1 0h4m1 0h1m1 0h1m1 0h1m1 0h4m-13 1h2m1 0h1m1 0h3m2 0h1m-19 1h7m2 0h1m3 0h8m-21 1h1m5 0h1m4 0h1m4 0h1m3 0h1m-21 1h1m1 0h3m1 0h1m1 0h4m1 0h2m2 0h2m1 0h1m-21 1h1m1 0h3m1 0h1m5 0h5m2 0h2m-21 1h1m1 0h3m1 0h1m2 0h2m1 0h1m2 0h2m1 0h1m-19 1h1m5 0h1m1 0h1m1 0h3m4 0h1m2 0h1m-21 1h7m2 0h1m1 0h1m1 0h3m2 0h3"/></svg>';

  var TX = {en: {
    btn: 'Support', title: 'Enjoyed my work? Support the project',
    sub: 'Any amount helps keep these projects free and updated.',
    mpesaHint: 'M-Pesa \u2192 Send money \u2192 this number', copy: 'Copy number', copied: 'Copied!',
    paypal: 'Donate with PayPal', paypalHint: 'Card or PayPal balance, from anywhere',
    kofi: 'Buy me a coffee on Ko-fi', kofiHint: 'Paid through PayPal', close: 'Close',
    more: 'Support page', thanks: 'Thank you!'
  }, pt: {
    btn: 'Apoiar', title: 'Gostou do que fiz? Apoie o projeto',
    sub: 'Qualquer valor ajuda a manter estes projetos gratuitos e atualizados.',
    mpesaHint: 'M-Pesa \u2192 Transferir dinheiro \u2192 este n\u00famero', copy: 'Copiar n\u00famero', copied: 'Copiado!',
    paypal: 'Doar com PayPal', paypalHint: 'Cart\u00e3o ou saldo PayPal, de qualquer pa\u00eds',
    kofi: 'Pagar um caf\u00e9 no Ko-fi', kofiHint: 'Pago atrav\u00e9s do PayPal', close: 'Fechar',
    more: 'P\u00e1gina de apoio', thanks: 'Obrigado!'
  }};
  var T = TX.pt;
  function lang() { T = /^en/i.test(document.documentElement.lang || '') ? TX.en : TX.pt; return T; }
  lang();


  var HEART = '<svg viewBox="0 0 24 24" width="1em" height="1em" aria-hidden="true" fill="currentColor"><path d="M12 21s-7.5-4.6-10-9.3C.4 8.4 2.2 4.5 5.9 4.1 8 3.9 9.8 5 12 7.4 14.2 5 16 3.9 18.1 4.1c3.7.4 5.5 4.3 3.9 7.6C19.5 16.4 12 21 12 21z"/></svg>';

  function isDark() {
    if (CFG.theme === 'dark') return true;
    if (CFG.theme === 'light') return false;
    var els = [document.documentElement, document.body];
    for (var i = 0; i < els.length; i++) {
      var e = els[i]; if (!e) continue;
      var c = ' ' + (e.className && e.className.baseVal === undefined ? e.className : '') + ' ';
      if (/ (dark|dark-mode|dark-theme|theme-dark)\s/.test(c)) return true;
      var a = (e.getAttribute('data-theme') || e.getAttribute('data-bs-theme') || e.getAttribute('data-mode') || '').toLowerCase();
      if (a === 'dark') return true;
      if (a === 'light' || / (light|light-mode|light-theme|theme-light)\s/.test(c)) return false;
    }
    if (CFG.theme === 'default-dark') return true;
    if (CFG.theme === 'default-light') return false;
    return !!(window.matchMedia && matchMedia('(prefers-color-scheme: dark)').matches);
  }

  /* ---------- bot\u00e3o (light DOM, herda a fonte do site) ---------- */
  var css = document.createElement('style');
  css.textContent =
    'button.dp-apoiar-btn{display:inline-flex!important;align-items:center;gap:.4em;font:inherit;font-size:.9em;font-weight:600;line-height:1.2;' +
    'padding:.45em .95em!important;border-radius:999px!important;border:1px solid currentColor!important;background:transparent;color:inherit;cursor:pointer;' +
    'opacity:.92;transition:opacity .15s,transform .15s,background .15s;white-space:nowrap;text-decoration:none;margin:0;vertical-align:middle;box-shadow:none}' +
    'button.dp-apoiar-btn:hover{opacity:1;transform:translateY(-1px);background:rgba(127,127,127,.12)}' +
    '.dp-apoiar-btn:focus-visible{outline:2px solid ' + CFG.accent + ';outline-offset:2px}' +
    'button.dp-apoiar-btn svg{display:inline-block!important;color:' + CFG.accent + ';flex:none;width:1em;height:1em}' +
    'button.dp-apoiar-btn.dp-link{border:0!important;padding:0!important;font-size:inherit;font-weight:inherit;background:none}' +
    'button.dp-apoiar-btn.dp-link:hover{background:none;text-decoration:underline}' +
    'button.dp-apoiar-float{position:fixed;left:16px;bottom:16px;z-index:2147483000;background:' + CFG.accent + ';color:#fff;border-color:transparent;box-shadow:0 6px 20px rgba(0,0,0,.25)}' +
    'button.dp-apoiar-float svg{color:#fff}button.dp-apoiar-float:hover{background:' + CFG.accent + '}';
  (document.head || document.documentElement).appendChild(css);

  function makeBtn(variant) {
    var b = document.createElement('button');
    b.type = 'button';
    b.className = 'dp-apoiar-btn' + (variant === 'link' ? ' dp-link' : '');
    b.setAttribute('aria-haspopup', 'dialog');
    b.innerHTML = HEART + '<span class="dp-l">' + T.btn + '</span>';
    b.addEventListener('click', function (e) { e.preventDefault(); open(b); });
    return b;
  }

  /* ---------- painel (shadow DOM, isolado do CSS do site) ---------- */
  var PANEL_CSS =
    ':host{all:initial}' +
    '.p{--bg:#fff;--fg:#0f172a;--mut:#475569;--line:#e2e8f0;--card:#f8fafc;--ac:' + CFG.accent + ';' +
    'font:15px/1.45 system-ui,-apple-system,"Segoe UI",Roboto,Inter,Arial,sans-serif;color:var(--fg);background:var(--bg);' +
    'border-radius:18px;box-sizing:border-box;width:100%;max-width:420px;padding:22px;position:relative;text-align:left}' +
    '.p.dk{--bg:#111827;--fg:#f1f5f9;--mut:#a3b1c6;--line:#283548;--card:#1a2436}' +
    '.p *{box-sizing:border-box}.p:focus{outline:none}' +
    'h2{margin:0 34px 4px 0;font-size:19px;line-height:1.25;font-weight:750;display:flex;gap:8px;align-items:center}' +
    'h2 svg{color:var(--ac);flex:none}' +
    '.sub{margin:0 0 16px;color:var(--mut);font-size:13.5px}' +
    '.card{border:1px solid var(--line);background:var(--card);border-radius:14px;padding:14px;margin-bottom:10px}' +
    '.mp{display:flex;gap:14px;align-items:center}' +
    '.qr{width:104px;height:104px;flex:none;border-radius:8px;background:#fff;padding:0;display:block}' +
    '.lab{font-size:12px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:#e4002b}' +
    '.p.dk .lab{color:#ff5a6e}' +
    '.num{font-size:20px;font-weight:750;letter-spacing:.02em;margin:2px 0 0;white-space:nowrap}' +
    '.nm{font-size:12.5px;color:var(--mut);margin:2px 0 8px}' +
    '.hint{font-size:12px;color:var(--mut);margin-top:6px}' +
    'button,a.b{font:inherit;cursor:pointer;text-decoration:none}' +
    '.cp{display:inline-flex;align-items:center;gap:6px;border:1px solid var(--line);background:var(--bg);color:var(--fg);' +
    'border-radius:999px;padding:6px 12px;font-size:13px;font-weight:600}' +
    '.cp:hover{border-color:var(--ac)}.cp.ok{border-color:#16a34a;color:#16a34a}' +
    'a.b{display:flex;align-items:center;gap:12px;border:1px solid var(--line);background:var(--card);color:var(--fg);border-radius:14px;padding:12px 14px;margin-bottom:10px;transition:border-color .15s,transform .15s}' +
    'a.b:hover{border-color:var(--ac);transform:translateY(-1px)}' +
    'a.b .ic{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;flex:none;color:#fff;font-weight:800;font-size:15px}' +
    'a.b strong{display:block;font-size:14.5px}a.b small{display:block;color:var(--mut);font-size:12px}' +
    '.x{position:absolute;top:14px;right:14px;width:32px;height:32px;border-radius:50%;border:0;background:transparent;color:var(--mut);font-size:22px;line-height:1}' +
    '.x:hover{background:var(--card);color:var(--fg)}' +
    '.ft{text-align:center;font-size:12.5px;margin-top:6px}.ft a{color:var(--mut)}.ft a:hover{color:var(--ac)}' +
    'button:focus-visible,a:focus-visible{outline:2px solid var(--ac);outline-offset:2px}' +
    '.ov{position:fixed;inset:0;z-index:2147483646;background:rgba(2,6,23,.62);display:flex;align-items:center;justify-content:center;padding:16px;' +
    'opacity:0;transition:opacity .16s;-webkit-backdrop-filter:blur(3px);backdrop-filter:blur(3px);overflow-y:auto}' +
    '.ov.on{opacity:1}.ov .p{box-shadow:0 24px 60px rgba(0,0,0,.35);transform:translateY(8px) scale(.98);transition:transform .16s;margin:auto}.ov.on .p{transform:none}' +
    '@media (max-width:380px){.p{padding:18px}.qr{width:88px;height:88px}.num{font-size:18px}}' +
    '@media (prefers-reduced-motion:reduce){.ov,.ov .p,a.b{transition:none}}';

  function panelHTML(modal) {
    return '<div class="p" role="' + (modal ? 'dialog' : 'region') + '" aria-modal="' + (modal ? 'true' : 'false') + '" aria-labelledby="dpa-t">' +
      (modal ? '<button class="x" type="button" aria-label="' + T.close + '">&times;</button>' : '') +
      '<h2 id="dpa-t">' + HEART + '<span>' + T.title + '</span></h2>' +
      '<p class="sub">' + T.sub + '</p>' +
      '<div class="card mp">' + QR +
        '<div><div class="lab">M-Pesa</div><div class="num">' + CFG.mpesa + '</div><div class="nm">' + CFG.name + '</div>' +
        '<button class="cp" type="button">' + T.copy + '</button><div class="hint">' + T.mpesaHint + '</div></div></div>' +
      '<a class="b" href="' + CFG.paypal + '" target="_blank" rel="noopener"><span class="ic" style="background:#003087">P</span>' +
        '<span><strong>' + T.paypal + '</strong><small>' + T.paypalHint + '</small></span></a>' +
      '<a class="b" href="' + CFG.kofi + '" target="_blank" rel="noopener"><span class="ic" style="background:#13c3ff">&#9749;</span>' +
        '<span><strong>' + T.kofi + '</strong><small>ko-fi.com/danielpro \u00b7 ' + T.kofiHint + '</small></span></a>' +
      (modal && location.hostname.replace(/^www\./, '') + location.pathname.replace(/\/$/, '') !== 'danielpro.dev/apoiar' ? '<div class="ft"><a href="' + CFG.page + '" target="_blank" rel="noopener">' + T.more + ' \u2192</a></div>' : '') +
      '</div>';
  }

  function copyText(txt, btn) {
    function done() { btn.textContent = T.copied; btn.classList.add('ok'); setTimeout(function () { btn.textContent = T.copy; btn.classList.remove('ok'); }, 1800); }
    if (navigator.clipboard && window.isSecureContext) { navigator.clipboard.writeText(txt).then(done, fallback); } else fallback();
    function fallback() {
      var t = document.createElement('textarea'); t.value = txt; t.setAttribute('readonly', ''); t.style.cssText = 'position:fixed;left:-9999px;top:0';
      document.body.appendChild(t); t.select(); try { document.execCommand('copy'); done(); } catch (e) {} document.body.removeChild(t);
    }
  }

  function wire(root) {
    var cp = root.querySelector('.cp');
    if (cp) cp.addEventListener('click', function () { copyText(CFG.mpesaCopy, cp); });
  }

  var host = null, lastFocus = null, prevOverflow = '';
  function open(from) {
    lang();
    lastFocus = from || document.activeElement;
    if (host) close(true);
    host = document.createElement('div');
    host.setAttribute('data-dp-apoiar-modal', '');
    var sr = host.attachShadow ? host.attachShadow({ mode: 'open' }) : host;
    sr.innerHTML = '<style>' + PANEL_CSS + '</style><div class="ov">' + panelHTML(true) + '</div>';
    var p = sr.querySelector('.p'); if (isDark()) p.classList.add('dk');
    var ov = sr.querySelector('.ov');
    ov.addEventListener('click', function (e) { if (e.target === ov) close(); });
    sr.querySelector('.x').addEventListener('click', function () { close(); });
    wire(sr);
    document.body.appendChild(host);
    prevOverflow = document.documentElement.style.overflow;
    document.documentElement.style.overflow = 'hidden';
    requestAnimationFrame(function () { ov.classList.add('on'); });
    setTimeout(function () { p.setAttribute('tabindex', '-1'); p.focus({ preventScroll: true }); }, 30);
    document.addEventListener('keydown', onKey, true);
  }
  function onKey(e) {
    if (!host) return;
    if (e.key === 'Escape') { e.preventDefault(); close(); return; }
    if (e.key === 'Tab') {
      var sr = host.shadowRoot || host, f = sr.querySelectorAll('button,a[href]');
      if (!f.length) return;
      var first = f[0], last = f[f.length - 1], act = sr.activeElement || document.activeElement;
      if (e.shiftKey && act === first) { e.preventDefault(); last.focus(); }
      else if (!e.shiftKey && act === last) { e.preventDefault(); first.focus(); }
    }
  }
  function close(silent) {
    if (!host) return;
    host.remove(); host = null;
    document.documentElement.style.overflow = prevOverflow;
    document.removeEventListener('keydown', onKey, true);
    if (!silent && lastFocus && lastFocus.focus) lastFocus.focus({ preventScroll: true });
  }

  function renderInline(el) {
    lang();
    var sr = el.attachShadow ? el.attachShadow({ mode: 'open' }) : el;
    sr.innerHTML = '<style>' + PANEL_CSS + '.p{margin:0 auto;box-shadow:0 10px 40px rgba(0,0,0,.12)}</style>' + panelHTML(false);
    var p = sr.querySelector('.p');
    function th() { p.classList.toggle('dk', isDark()); }
    th();
    new MutationObserver(th).observe(document.documentElement, { attributes: true, attributeFilter: ['class', 'data-theme'] });
    if (document.body) new MutationObserver(th).observe(document.body, { attributes: true, attributeFilter: ['class', 'data-theme'] });
    if (window.matchMedia) { var mq = matchMedia('(prefers-color-scheme: dark)'); mq.addEventListener && mq.addEventListener('change', th); }
    wire(sr);
  }

  function init() {
    var slots = document.querySelectorAll('[data-apoiar]');
    for (var i = 0; i < slots.length; i++) { if (!slots[i].__dpa) { slots[i].__dpa = 1; slots[i].appendChild(makeBtn(slots[i].getAttribute('data-apoiar'))); } }
    var inl = document.querySelectorAll('[data-apoiar-inline]');
    for (var j = 0; j < inl.length; j++) { if (!inl[j].__dpa) { inl[j].__dpa = 1; renderInline(inl[j]); } }
    if (ds.float && !slots.length && !inl.length && !document.querySelector('.dp-apoiar-float')) {
      var f = makeBtn(); f.classList.add('dp-apoiar-float'); if (ds.float === 'right') { f.style.left = 'auto'; f.style.right = '16px'; }
      document.body.appendChild(f);
    }
  }
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('[data-apoiar-open],a[href$="#apoiar"]');
    if (a) { e.preventDefault(); open(a); }
  });
  new MutationObserver(function () {
    lang(); var ls = document.querySelectorAll('.dp-apoiar-btn .dp-l');
    for (var k = 0; k < ls.length; k++) ls[k].textContent = T.btn;
  }).observe(document.documentElement, { attributes: true, attributeFilter: ['lang'] });
  window.DPApoiar = { open: open, close: close, init: init };
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
  if (/#apoiar$/.test(location.hash)) setTimeout(open, 300);
})();
