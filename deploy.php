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
task('docker:down', function () {
    run('cd {{release_path}} && docker compose down');
    writeln('<info>✓ Contenedores Docker detenidos</info>');
});

task('deploy:docker', function () {
    run('cd {{release_path}} && docker compose build && docker compose up -d --remove-orphans');
    writeln('<info>✓ Contenedores Docker iniciados en modo daemon</info>');
});

desc('Verifica el estado de los contenedores Docker');
task('docker:status', function () {
    $result = run('cd {{release_path}} && docker compose ps');
    writeln('<info>Estado de los contenedores:</info>');
    writeln($result);
});

task('copy:env', function () {
    // Subir el archivo al servidor
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
    'deploy:prepare',      // Prepara directorios, etc.
    'copy:env',           // Copia el archivo .env al servidor (antes de publish)
    'deploy:publish',     // Publica la nueva versión
    'docker:down',        // Detiene contenedores
    'deploy:docker',      // Inicia contenedores
]);

desc('Verifica el estado completo del despliegue');
task('deploy:verify', [
    'env:check',       // Verifica que el .env existe
    'docker:status',   // Verifica estado de Docker
]);

// --- Hooks ---
after('deploy:failed', 'deploy:unlock');
after('deploy', 'deploy:cleanup'); // Limpia versiones antiguas después de un despliegue exitoso