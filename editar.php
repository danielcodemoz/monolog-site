<?php
declare(strict_types=1);
session_start();
header('Content-Type: text/html; charset=UTF-8');

require __DIR__ . '/editor-secret.php';
if (!isset($EDITOR_SECRET) || $EDITOR_SECRET === '') {
    http_response_code(500);
    echo 'editor-secret.php em falta';
    exit;
}

function e(?string $s): string {
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function load_content(): array {
    $raw = file_get_contents(__DIR__ . '/content.json');
    $data = json_decode((string)$raw, true);
    return is_array($data) ? $data : [];
}

function flatten(array $arr, string $prefix = ''): array {
    $out = [];
    foreach ($arr as $k => $v) {
        $key = $prefix === '' ? (string)$k : $prefix . '.' . $k;
        if (is_array($v)) {
            $out += flatten($v, $key);
        } else {
            $out[$key] = is_scalar($v) || $v === null ? (string)$v : json_encode($v, JSON_UNESCAPED_UNICODE);
        }
    }
    return $out;
}

function set_path(array &$arr, string $path, string $value): void {
    $parts = explode('.', $path);
    $ref =& $arr;
    foreach ($parts as $i => $p) {
        if ($i === count($parts) - 1) {
            $ref[$p] = $value;
            return;
        }
        if (!isset($ref[$p]) || !is_array($ref[$p])) {
            $ref[$p] = [];
        }
        $ref =& $ref[$p];
    }
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

$content = load_content();
$logged = !empty($_SESSION['mono_ok']);
$flash = '';
$error = '';

if (isset($_GET['sair'])) {
    $_SESSION = [];
    session_destroy();
    header('Location: /editar.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !$logged) {
    $given = (string)$_POST['password'];
    if (hash_equals((string)$EDITOR_SECRET, $given)) {
        $_SESSION['mono_ok'] = 1;
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        $logged = true;
        header('Location: /editar.php');
        exit;
    }
    $error = t($content, 'editor.error_password') ?: 'Palavra-passe errada.';
}

if ($logged && empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}

if ($logged && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar'])) {
    $token = (string)($_POST['csrf'] ?? '');
    if (!hash_equals((string)($_SESSION['csrf'] ?? ''), $token)) {
        $error = t($content, 'editor.error_csrf') ?: 'Sessão caducou.';
    } else {
        $flat = flatten($content);
        $posted = $_POST['f'] ?? [];
        if (is_array($posted)) {
            $incoming = flatten($posted);
            foreach ($flat as $key => $oldv) {
                if (array_key_exists($key, $incoming)) {
                    set_path($content, $key, (string)$incoming[$key]);
                }
            }
        }
        $json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            $error = t($content, 'editor.error_save') ?: 'Não deu para guardar.';
        } else {
            $ok = file_put_contents(__DIR__ . '/content.json', $json . "\n", LOCK_EX);
            if ($ok === false) {
                $error = t($content, 'editor.error_save') ?: 'Não deu para guardar.';
            } else {
                $flash = t($content, 'editor.success') ?: 'Guardado.';
                $content = load_content();
            }
        }
    }
}

$ed = $content['editor'] ?? [];
$pageTitle = t($content, 'editor.page_title') ?: 'Editar textos';
?><!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($pageTitle) ?></title>
<link rel="icon" href="/assets/favicon-32.png" type="image/png">
<style>
  :root{--cream:#F4EDE2;--paper:#C4B8A8;--brown:#6B5344;--teal:#3F5C45;--ink:#2c211c}
  *{box-sizing:border-box}
  body{margin:0;font-family:system-ui,sans-serif;background:var(--cream);color:var(--ink);line-height:1.45}
  .box{width:min(880px,calc(100% - 2rem));margin:2.5rem auto 4rem}
  h1{font-family:Georgia,serif;color:var(--brown);font-size:2rem;margin:.2rem 0 1rem}
  .card{background:#fff8ef;border:1px solid var(--paper);border-radius:14px;padding:1.3rem 1.2rem}
  label{display:block;font-size:.82rem;color:var(--teal);letter-spacing:.04em;margin:0 0 .35rem}
  input[type=password],input[type=text],textarea{
    width:100%;font:inherit;padding:.65rem .7rem;border:1px solid var(--paper);border-radius:8px;background:#fff;color:var(--ink)
  }
  textarea{min-height:5.2rem;resize:vertical}
  .row{margin:0 0 1rem}
  .btn{background:var(--brown);color:var(--cream);border:0;border-radius:999px;padding:.7rem 1.2rem;font:inherit;font-weight:650;cursor:pointer}
  .btn:hover{background:var(--teal)}
  .ok{background:#e5efe6;border:1px solid #3F5C45;color:#2a3d2e;padding:.8rem 1rem;border-radius:10px;margin:0 0 1rem}
  .err{background:#f8e8e4;border:1px solid #a45;color:#5a241c;padding:.8rem 1rem;border-radius:10px;margin:0 0 1rem}
  .hint{color:#6B5344;margin:0 0 1.2rem}
  .top{display:flex;justify-content:space-between;gap:1rem;align-items:center;flex-wrap:wrap;margin:0 0 1rem}
  .top a{color:var(--brown)}
  fieldset{border:1px solid var(--paper);border-radius:12px;margin:0 0 1.2rem;padding:1rem .9rem .4rem}
  legend{font-weight:700;color:var(--brown);padding:0 .4rem}
  .sticky{position:sticky;bottom:0;background:rgba(244,237,226,.94);padding:.8rem 0;display:flex;gap:.8rem;align-items:center}
</style>
</head>
<body>
<div class="box">
<?php if (!$logged): ?>
  <h1><?= e($pageTitle) ?></h1>
  <p class="hint"><?= e(t($content,'editor.intro')) ?></p>
  <?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif; ?>
  <form class="card" method="post" action="/editar.php" autocomplete="off">
    <div class="row">
      <label for="password"><?= e(t($content,'editor.password_label') ?: 'Palavra-passe') ?></label>
      <input id="password" name="password" type="password" required autofocus>
    </div>
    <button class="btn" type="submit"><?= e(t($content,'editor.password_submit') ?: 'Entrar') ?></button>
  </form>
<?php else:
    $flat = flatten($content);
    $groups = [];
    foreach ($flat as $key => $val) {
        $g = explode('.', $key, 2)[0];
        $groups[$g][$key] = $val;
    }
    $groupNames = [];
    foreach (array_keys($groups) as $gk) {
        $lab = t($content, 'editor.group_' . $gk);
        $groupNames[$gk] = $lab !== '' ? $lab : $gk;
    }
?>
  <div class="top">
    <h1><?= e($pageTitle) ?></h1>
    <div><a href="/"><?= e(t($content,'editor.view_site') ?: 'Ver o site') ?></a> · <a href="/editar.php?sair=1"><?= e(t($content,'editor.logout') ?: 'Sair') ?></a></div>
  </div>
  <p class="hint"><?= e(t($content,'editor.intro')) ?><br><?= e(t($content,'editor.hint')) ?></p>
  <?php if ($flash): ?><p class="ok"><?= e($flash) ?></p><?php endif; ?>
  <?php if ($error): ?><p class="err"><?= e($error) ?></p><?php endif; ?>
  <form method="post" action="/editar.php">
    <input type="hidden" name="csrf" value="<?= e((string)$_SESSION['csrf']) ?>">
    <input type="hidden" name="guardar" value="1">
<?php foreach ($groups as $g => $fields): ?>
    <fieldset>
      <legend><?= e($groupNames[$g] ?? $g) ?></legend>
<?php foreach ($fields as $key => $val):
        $long = (strlen($val) > 70) || str_contains($key, 'lead') || str_contains($key, 'text') || str_contains($key, 'description') || str_contains($key, 'intro') || str_contains($key, 'caption') || str_contains($key, 'note') || str_contains($key, '.p') || str_contains($key, 'line');
        $id = 'f_' . str_replace('.', '_', $key);
        $parts = explode('.', $key);
        $name = 'f';
        foreach ($parts as $part) {
            $name .= '[' . $part . ']';
        }
?>
      <div class="row">
        <label for="<?= e($id) ?>"><?= e($key) ?></label>
<?php if ($long): ?>
        <textarea id="<?= e($id) ?>" name="<?= e($name) ?>"><?= e($val) ?></textarea>
<?php else: ?>
        <input id="<?= e($id) ?>" name="<?= e($name) ?>" type="text" value="<?= e($val) ?>">
<?php endif; ?>
      </div>
<?php endforeach; ?>
    </fieldset>
<?php endforeach; ?>
    <div class="sticky">
      <button class="btn" type="submit"><?= e(t($content,'editor.save') ?: 'Guardar textos') ?></button>
    </div>
  </form>
<?php endif; ?>
</div>
</body>
</html>
