<?php

namespace Deployer;

require 'recipe/common.php';

// Cargar variables del archivo .env si no están en el entorno
$envFile = __DIR__.'/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        } // Ignorar comentarios
        putenv($line);
    }
}
// Config
set('application', 'Website Baubyte');
set('repository', getenv('DEPLOY_REPO') ?: 'git@github.com:baubyte/website.git');
set('keep_releases', 2);

// Definimos explícitamente el usuario del servidor web dentro del contenedor.
set('http_user', 'www-data');

// Compartimos el archivo .env para que no se borre en cada despliegue.
add('shared_files', ['.env']);
// Hacemos que la carpeta 'storage' sea compartida (Laravel usa storage en vez de writable).
add('shared_dirs', ['storage']);

// --- Configuración del Host ---
host('production')
    ->set('hostname', getenv('DEPLOY_HOST') ?: 'default-host')
    ->set('remote_user', getenv('DEPLOY_USER') ?: 'default-user')
    ->set('deploy_path', getenv('DEPLOY_PATH') ?: '/default/path');

// --- Tareas de Despliegue Personalizadas ---
desc('Construye y levanta los contenedores de Docker');
task('docker:down', function () {
    run('cd {{release_path}} && docker compose down');
    writeln('<info>✓ Contenedores Docker detenidos</info>');
});

task('deploy:docker', function () {
    $commitSha = run('cd {{release_path}} && git rev-parse HEAD');
    run("cd {{release_path}} && export IMAGE_TAG=$commitSha && docker compose pull && docker compose up -d --remove-orphans", timeout: 3600);
    writeln("<info>✓ Contenedores Docker iniciados con la imagen versión: $commitSha</info>");
});

desc('Importa datos legacy (ejecutar manualmente solo 1 vez)');
task('artisan:legacy:import', function () {
    run('cd {{current_path}} && docker compose exec -T baubyte-website php artisan legacy:import', timeout: 3600);
    writeln('<info>✓ Importación legacy completada</info>');
});

desc('Ejecuta las migraciones de Laravel dentro de Docker');
task('artisan:migrate', function () {
    // Usamos el exec para correr las migraciones en el contenedor que acabamos de levantar
    run('cd {{release_or_current_path}} && docker compose exec -T baubyte-website php artisan migrate --force', timeout: 360);
    writeln('<info>✓ Migraciones completadas</info>');
});

desc('Ejecuta storage:link de Laravel dentro de Docker');
task('artisan:storage:link', function () {
    run('cd {{release_path}} && docker compose exec -T baubyte-website php artisan storage:link');
    writeln('<info>✓ Symlink de storage creado</info>');
});

desc('Verifica el estado de los contenedores Docker');
task('docker:status', function () {
    $result = run('cd {{release_path}} && docker compose ps');
    writeln('<info>Estado de los contenedores:</info>');
    writeln($result);
});

task('copy:env', function () {
    // Subir el archivo al servidor (si estás subiendo el prod.env local)
    // Ojo: Esto asume que tienes un prod.env local y quieres forzarlo.
    upload('./prod.env', '{{deploy_path}}/shared/.env');
    writeln('<info>✓ Archivo .env copiado correctamente al servidor</info>');
});

desc('Verifica la configuración del entorno');
task('env:check', function () {
    if (test('[ -f {{deploy_path}}/shared/.env ]')) {
        writeln('<info>✓ Archivo .env existe en el servidor</info>');
    } else {
        writeln('<error>✗ Archivo .env no encontrado en el servidor</error>');
    }
});

// --- Flujo de Despliegue ---
desc('Despliega la aplicación');
task('deploy', [
    'deploy:prepare',      // Prepara directorios (releases, shared)
    'copy:env',            // Sube tu prod.env (siempre y cuando lo quieras sobreescribir)
    'deploy:publish',      // Symlink de current release
    'docker:down',         // Apaga la versión anterior
    'deploy:docker',       // Levanta la nueva (compila imágenes)
    // 'artisan:migrate',     // Comentado temporalmente por migración inicial
    'artisan:storage:link', // Asegura el symlink de public/storage
]);

desc('Verifica el estado completo del despliegue');
task('deploy:verify', [
    'env:check',
    'docker:status',
]);

// --- Hooks ---
after('deploy:failed', 'deploy:unlock');
after('deploy', 'deploy:cleanup'); // Limpia versiones antiguas

desc('Limpia imágenes viejas de Docker, preservando las de los releases actuales');
task('docker:prune', function () {
    // 1. Obtener los releases actuales que deployer está manteniendo
    $releases = run('ls -1 {{deploy_path}}/releases');
    $hashesToKeep = [];
    foreach (explode("\n", $releases) as $release) {
        $release = trim($release);
        if (!empty($release)) {
            $hash = run("cd {{deploy_path}}/releases/$release && git rev-parse HEAD");
            $hashesToKeep[] = $hash;
        }
    }

    // 2. Obtener todas las etiquetas (tags) de la imagen ghcr.io/baubyte/website
    $tags = run("docker images ghcr.io/baubyte/website --format '{{.Tag}}'");
    
    // 3. Borrar las que no correspondan a los hashes que queremos mantener
    $deleted = 0;
    foreach (explode("\n", $tags) as $tag) {
        $tag = trim($tag);
        if (empty($tag) || $tag === 'latest' || $tag === 'web-latest' || $tag === 'ssr-latest') {
            continue;
        }
        
        $shouldKeep = false;
        foreach ($hashesToKeep as $hash) {
            if (strpos($tag, $hash) !== false) {
                $shouldKeep = true;
                break;
            }
        }
        
        if (!$shouldKeep) {
            // Utilizamos || true para no fallar el deploy si Docker no puede borrarla
            run("docker rmi ghcr.io/baubyte/website:$tag || true", timeout: 300);
            $deleted++;
        }
    }
    
    // 4. Limpiar dangling images (imágenes sin tag) de forma segura
    run('docker image prune -f');
    
    writeln("<info>✓ Limpieza completada. Se eliminaron $deleted imágenes antiguas de versiones previas.</info>");
});
after('deploy', 'docker:prune'); // Limpia imagenes viejas de Docker
