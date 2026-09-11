# Site do Monolog

Esta pasta é **só o site** (https://monolog.danielpro.dev/).  
O programa Monolog (Electron) é outra coisa, noutro sítio. **Não mexas no programa para mudar textos do site.**

## Mudar os textos (o caminho certo)

1. Abre no browser: `https://monolog.danielpro.dev/editar.php`  
   (também pode ficar `/editar` se o Nginx tiver o extra do ficheiro `nginx-deny-snippet.conf`)
2. Põe a palavra-passe (está no ficheiro `EDITOR-PASSWORD.txt` **fora** desta pasta, em `monolog-web/`).
3. Muda as frases nas caixas.
4. Clica **Guardar textos**.
5. Abre a página principal e refresca.

Todos os textos visíveis vivem em `content.json`. O `index.php` lê esse ficheiro e desenha o site. Não precisas de saber HTML.

## O que não fazer

- Não abras nem edites pastas do programa (src, vault, Electron, `package.json` da app).
- Não publiques capturas com texto de diário ou tarefas.
- Não ponhas a palavra-passe do editor no `content.json`.

## Descarregar

O botão aponta para `/download/Monolog-1.0.0-setup.exe`.  
O ficheiro pode ainda não estar no servidor — o link já fica certo.

## Técnico (para quem instala no VPS)

- PHP 8.3, sem Composer.
- Apache: `.htaccess` já esconde `editor-secret.php`.
- Nginx: cola `nginx-deny-snippet.conf` no `server { }`.
- A pasta precisa de escrita em `content.json` para o editor guardar.
