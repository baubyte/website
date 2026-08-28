# [baubyte.com.ar](https://baubyte.com.ar)

<p>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-13-FF2D20?style=flat-square&logo=laravel&logoColor=white">
  <img alt="PHP" src="https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white">
  <img alt="Inertia.js" src="https://img.shields.io/badge/Inertia.js-9553E9?style=flat-square&logo=inertia&logoColor=white">
  <img alt="Svelte" src="https://img.shields.io/badge/Svelte-5-FF3E00?style=flat-square&logo=svelte&logoColor=white">
  <img alt="Filament" src="https://img.shields.io/badge/Filament-5-F59E0B?style=flat-square&logo=laravel&logoColor=white">
  <img alt="Tailwind CSS" src="https://img.shields.io/badge/Tailwind_CSS-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white">
  <img alt="License" src="https://img.shields.io/badge/license-private-lightgrey?style=flat-square">
</p>

Sitio personal de portfolio, migrado de **CodeIgniter 4** a **Laravel 13 + Inertia + Svelte 5**. Público y panel de administración están completamente separados: el público nunca usa Filament, el admin nunca usa Inertia/Svelte/SSR.

---

## 📋 Índice

- [Stack](#-stack)
- [Arquitectura del chat (proxy a n8n)](#-arquitectura-del-chat-proxy-a-n8n)
- [Modo mantenimiento](#-modo-mantenimiento)
- [Setup local (DDEV)](#-setup-local-ddev)
- [Variables de entorno](#-variables-de-entorno)
- [Tests](#-tests)

---

## 🧱 Stack

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13, PHP 8.3+ |
| Frontend público | Inertia.js + Svelte 5 (sin SvelteKit), Tailwind + daisyUI, tema oscuro único |
| SSR | Entry point oficial de Inertia (`resources/js/ssr.js`), servicio Docker interno separado |
| Admin | Filament 5, auth nativa de Laravel (single-account, sin roles/permisos) |
| i18n | `erag/laravel-lang-sync-inertia` — una sola fuente de verdad en `lang/{locale}/front.php` |
| Rutas en el frontend | `tightenco/ziggy` |
| PDF | `fruitcake/laravel-weasyprint` (WeasyPrint para descarga del CV en CSS Paged Media) |
| Datos | Importados una vez desde la base legacy de CodeIgniter vía `php artisan legacy:import` |

**SSR con fallback automático**: si el servicio SSR (`baubyte-website-ssr`, sin exposición directa a Traefik) no está corriendo o falla, Inertia cae a client-side rendering sin código propio — es el comportamiento nativo de `Inertia\Ssr\HttpGateway::dispatch()`. Ver `App\Listeners\LogSsrFallback` para la observabilidad de ese fallback.

**i18n**: el backend usa `__('front.clave', [], $locale)`; el frontend usa `t('clave')`. `resources/js/lang/{locale}/front.json` se regenera con `php artisan erag:generate-lang` — nunca se edita a mano, y nunca se hardcodea un string bilingüe en un array PHP.

---

## 💬 Arquitectura del chat (proxy a n8n)

El formulario de contacto legacy fue reemplazado por un widget de chat (`resources/js/Components/ChatWidget.svelte`) que habla exclusivamente con `POST /api/chat`. El navegador **nunca** habla directo con n8n — la URL del webhook y el secreto compartido nunca salen del servidor.

```
ChatWidget → throttle:20,1 + EnsureSameOrigin → ChatMessageRequest (valida + Turnstile)
           → SendChatMessage (llama a n8n, guarda el Lead) → ChatController (traduce a HTTP)
```

### 🛡️ Protecciones contra abuso/costo

Cada mensaje real dispara una llamada a un LLM del lado de n8n — hay cuatro capas:

| Protección | Dónde |
|---|---|
| Throttle 20/min por IP | `routes/web.php` |
| Origen/Referer debe coincidir con `app.url` | `App\Http\Middleware\EnsureSameOrigin` |
| Techo diario global (`CHAT_DAILY_MESSAGE_LIMIT`, default 200) | `App\Actions\Chat\SendChatMessage::dailyLimitReached()` |
| Verificación real de Cloudflare Turnstile | `App\Http\Requests\ChatMessageRequest` |

Mientras `N8N_CHAT_WEBHOOK_URL`/`N8N_CHAT_WEBHOOK_SECRET` o `TURNSTILE_SITE_KEY`/`TURNSTILE_SECRET_KEY` estén vacíos, el chat sigue funcionando pero falla cerrado con un mensaje localizado / no pide verificación Turnstile — ninguno de los dos rompe el desarrollo local sin esas credenciales.

> **Nota de diseño — el campo `page`**: cada mensaje guarda en qué página estaba el visitante (`leads.page`, y se manda también a n8n). Hoy el sitio es de una sola página (`HomeController` es la única ruta de contenido), así que este campo siempre vale `/` — no aporta señal real todavía. Se dejó a propósito: empieza a tener sentido el día que el sitio tenga rutas de contenido propias (blog, portfolio de proyectos, etc.).

---

## 🔧 Modo mantenimiento

Mantenimiento nativo de Laravel (`artisan down`/`up`, driver de archivo), activable desde un Action de Filament (`App\Filament\Pages\ManageProfile`) vía `App\Services\MaintenanceToggler` — no hay tabla propia ni endpoint custom. `/admin/*` queda excluido del modo mantenimiento (`App\Http\Middleware\PreventRequestsDuringMaintenance`) para que el dueño no se bloquee a sí mismo.

---

## 🚀 Setup local (DDEV)

```bash
ddev start
ddev composer install
ddev npm install
ddev artisan migrate
ddev artisan legacy:import   # una sola vez, si hay datos legacy que migrar
ddev npm run dev             # o `ddev npm run build` para producción
```

---

## 🔑 Variables de entorno

Además de las estándar de Laravel:

| Variable | Requerida | Efecto si falta |
|---|:---:|---|
| `N8N_CHAT_WEBHOOK_URL` / `N8N_CHAT_WEBHOOK_SECRET` | ❌ | El chat responde "no disponible" sin llamar a n8n |
| `TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY` | ❌ | El chat no pide verificación anti-bot |
| `CHAT_DAILY_MESSAGE_LIMIT` | ❌ (default 200) | — |
| `INERTIA_SSR_URL` | ❌ | URL del servidor SSR (`http://ssr:13714` en prod Docker, `http://127.0.0.1:13714` en DDEV) |
| `WEASYPRINT_BINARY` | ❌ | Ruta al binario de WeasyPrint (`/usr/bin/weasyprint`) |

---

## 🚢 Despliegue en Producción (Deployer + Docker)

El despliegue está automatizado con **Deployer** y **Docker Compose** detrás de **Traefik**.

```bash
# Desplegar en producción
./vendor/bin/dep deploy

# Verificar estado de los contenedores
./vendor/bin/dep deploy:verify
```

El pipeline de deploy:
1. Sube y vincula `prod.env` como `.env` compartido.
2. Mantiene persistente el directorio `storage/` con sus symlinks.
3. Detiene la versión previa y compila las nuevas imágenes en Docker (etapa única de build en `Dockerfile` para evitar hydration mismatches).
4. Ejecuta `php artisan migrate --force` y `php artisan storage:link` dentro del contenedor.
5. Limpia versiones antiguas conservando los últimos 2 releases.

---

## ✅ Tests

```bash
ddev artisan test   # PHPUnit/Pest — requiere la conexión `legacy` de DDEV para los tests de importación
npm test            # Vitest — componentes Svelte
```
