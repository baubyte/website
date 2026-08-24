# baubyte.com.ar

Sitio personal de portfolio, migrado de CodeIgniter 4 a Laravel 13 + Inertia + Svelte 5. Público (Inertia/Svelte, sin API REST propia) y panel de administración (Filament) están completamente separados: el público nunca usa Filament, el admin nunca usa Inertia/Svelte/SSR.

## Stack

- **Backend**: Laravel 13, PHP 8.3+.
- **Frontend público**: Inertia.js + Svelte 5 (sin SvelteKit), Tailwind + daisyUI, tema oscuro único (sin toggle).
- **SSR**: entry point oficial de Inertia (`resources/js/ssr.js`), como servicio Docker interno separado (`baubyte-website-ssr`, sin exposición directa a Traefik). Si el servicio SSR no está corriendo o falla, Inertia cae automáticamente a client-side rendering — no hay middleware de fallback hecho a mano, es el comportamiento nativo de `Inertia\Ssr\HttpGateway::dispatch()`. Ver `App\Listeners\LogSsrFallback` para la observabilidad de ese fallback.
- **Admin**: Filament 5, auth nativa de Laravel (single-account, sin roles/permisos).
- **i18n**: `erag/laravel-lang-sync-inertia` — una sola fuente de verdad (`lang/{locale}/front.php`), sincronizada automáticamente a `resources/js/lang/{locale}/front.json` para el frontend vía `php artisan erag:generate-lang`. El backend usa `__('front.clave', [], $locale)` normalmente; nunca hardcodear strings bilingües en un array PHP — si hace falta un string nuevo, va a `lang/`.
- **Rutas con nombre en el frontend**: `tightenco/ziggy` (`resources/js/lib/route.js`).
- **PDF**: `barryvdh/laravel-dompdf` para la descarga del CV.
- **Datos**: importados una sola vez desde la base legacy de CodeIgniter (misma instancia MySQL, conexión `legacy` de solo lectura) vía `php artisan legacy:import`, comando idempotente.

## Arquitectura del chat (proxy a n8n)

El formulario de contacto legacy fue reemplazado por un widget de chat (`resources/js/Components/ChatWidget.svelte`) que habla exclusivamente con `POST /api/chat`. El navegador nunca habla directo con n8n — la URL del webhook y el secreto compartido nunca salen del servidor.

Flujo: `ChatWidget` → `throttle:20,1` + `EnsureSameOrigin` → `ChatMessageRequest` (valida + verifica Turnstile) → `App\Actions\Chat\SendChatMessage` (llama a n8n, guarda el `Lead`) → `ChatController` (solo traduce el resultado a HTTP).

**Protecciones contra abuso/costo** (cada mensaje real dispara una llamada a un LLM del lado de n8n):
- `throttle:20,1` por IP en la ruta.
- `EnsureSameOrigin`: rechaza requests cuyo `Origin`/`Referer` no coincida con `app.url`.
- Techo diario global (`services.chat.daily_limit`, env `CHAT_DAILY_MESSAGE_LIMIT`, default 200): corta antes de llamar a n8n si ya se superó, independiente del throttle por IP.
- Cloudflare Turnstile (`services.turnstile.site_key`/`secret_key`, envs `TURNSTILE_SITE_KEY`/`TURNSTILE_SECRET_KEY`): verificación real del lado del servidor en `ChatMessageRequest`.

**Operacional, no configurado por defecto**: mientras `N8N_CHAT_WEBHOOK_URL`/`N8N_CHAT_WEBHOOK_SECRET` o `TURNSTILE_SITE_KEY`/`TURNSTILE_SECRET_KEY` estén vacíos, el chat sigue funcionando pero sin llamar al servicio real (falla cerrado con un mensaje localizado) / sin pedir verificación Turnstile. Ninguno de los dos rompe el desarrollo local sin esas credenciales.

**Nota de diseño — el campo `page`**: cada mensaje guarda en qué página estaba el visitante (`leads.page`, y se manda también a n8n). Hoy el sitio es de una sola página (`HomeController` es la única ruta de contenido), así que este campo siempre vale `/` — no aporta señal real todavía. Se dejó a propósito: empieza a tener sentido el día que el sitio tenga rutas de contenido propias (blog, portfolio de proyectos, etc.).

## Modo mantenimiento

Mantenimiento nativo de Laravel (`artisan down`/`up`, driver de archivo), activable desde un Action de Filament (`App\Filament\Pages\ManageProfile`) vía `App\Services\MaintenanceToggler` — no hay tabla propia ni endpoint custom. `/admin/*` queda excluido del modo mantenimiento (`App\Http\Middleware\PreventRequestsDuringMaintenance`) para que el dueño no se bloquee a sí mismo.

## Setup local (DDEV)

```bash
ddev start
ddev composer install
ddev npm install
ddev artisan migrate
ddev artisan legacy:import   # una sola vez, si hay datos legacy que migrar
ddev npm run dev             # o `ddev npm run build` para producción
```

Variables de entorno relevantes además de las estándar de Laravel:

| Variable | Requerida | Efecto si falta |
|---|---|---|
| `N8N_CHAT_WEBHOOK_URL` / `N8N_CHAT_WEBHOOK_SECRET` | No | El chat responde "no disponible" sin llamar a n8n |
| `TURNSTILE_SITE_KEY` / `TURNSTILE_SECRET_KEY` | No | El chat no pide verificación anti-bot |
| `CHAT_DAILY_MESSAGE_LIMIT` | No (default 200) | — |

## Tests

```bash
ddev artisan test        # PHPUnit/Pest (requiere la conexión `legacy` de DDEV para los tests de importación)
npx vitest run            # Vitest (componentes Svelte)
```
