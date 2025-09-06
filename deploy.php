<?php

namespace Deployer;

require 'recipe/codeigniter4.php';

// Cargar variables del archivo .env si no están en el entorno
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Ignorar comentarios
        putenv($line);
    }
}
// Config 
set('application', 'Website Baubyte');
set('repository', getenv('DEPLOY_REPO') ?: 'git@github.com:baubyte/website.git');
set('keep_releases', 2);

add('shared_files', []);
add('shared_dirs', []);
add('writable_dirs', []);

// Hosts

host("production")
    ->set('hostname', getenv('DEPLOY_HOST') ?: 'default-host')
    ->set('remote_user', getenv('DEPLOY_USER') ?: 'default-user')
    ->set('deploy_path', getenv('DEPLOY_PATH') ?: '/default/path');

// --- Tarea de Despliegue Personalizada ---
desc('Despliega la aplicación con Docker');
task('deploy:docker', function () {
    run('cd {{release_path}} && docker-compose up -d --build --remove-orphans');
});

// --- Flujo de Despliegue ---
desc('Despliega la aplicación');
task('deploy', [
    'deploy:prepare', // Prepara directorios, etc.
    'deploy:publish', // Publica la nueva versión
    'deploy:docker',  // Nuestra tarea para levantar Docker
]);
// Hooks
after('deploy:failed', 'deploy:unlock');
after('deploy', 'deploy:cleanup');