<?php

namespace Deployer;

// Incluimos la receta base de CodeIgniter 4
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

// [LA SOLUCIÓN PRINCIPAL]
// Definimos explícitamente el usuario del servidor web dentro del contenedor.
set('http_user', 'www-data');

// [CORRECCIONES IMPORTANTES]
// Compartimos el archivo .env para que no se borre en cada despliegue.
add('shared_files', ['.env']);
// Hacemos que la carpeta 'writable' sea compartida y tenga permisos de escritura.
add('shared_dirs', ['writable']);

// --- Configuración del Host ---
host("production")
    ->set('hostname', getenv('DEPLOY_HOST') ?: 'default-host')
    ->set('remote_user', getenv('DEPLOY_USER') ?: 'default-user')
    ->set('deploy_path', getenv('DEPLOY_PATH') ?: '/default/path');

// --- Tarea de Despliegue Personalizada ---
desc('Construye y levanta los contenedores de Docker');
task('deploy:docker', function () {
    // Añadimos un 'echo' para verificar que se ejecuta la última versión
    run('echo "--- EJECUTANDO EL SCRIPT DE DEPLOY CORRECTO ---" && cd {{release_path}} && docker compose up -d --build --remove-orphans');
});

// --- Flujo de Despliegue ---
desc('Despliega la aplicación');
task('deploy', [
    'deploy:prepare', // Prepara directorios, etc.
    'deploy:publish', // Publica la nueva versión
    'deploy:docker',  // Nuestra tarea para levantar Docker
]);

// --- Hooks ---
after('deploy:failed', 'deploy:unlock');
after('deploy', 'deploy:cleanup'); // Limpia versiones antiguas después de un despliegue exitoso