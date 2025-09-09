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
    run('cd {{release_path}} && docker compose up -d --build --remove-orphans');
    writeln('<info>✓ Contenedores Docker iniciados en modo daemon</info>');
});

desc('Verifica el estado de los contenedores Docker');
task('docker:status', function () {
    $result = run('cd {{release_path}} && docker compose ps');
    writeln('<info>Estado de los contenedores:</info>');
    writeln($result);
});

desc('Configura los directorios de uploads para Docker con volúmenes');
task('docker:setup_uploads', function () {
    // Obtener deploy_path que siempre existe
    $deployPath = get('deploy_path');
    
    // Intentar obtener release_path de forma segura
    try {
        $releasePath = get('release_path');
        $hasReleasePath = true;
    } catch (\Exception $e) {
        $releasePath = null;
        $hasReleasePath = false;
    }
    
    // Verificar si estamos en un despliegue completo
    $isInDeployment = $hasReleasePath && test("[ -d '$releasePath' ]");
    
    if ($isInDeployment) {
        $workPath = $releasePath;
        writeln('<info>Usando release_path para configurar uploads...</info>');
    } else {
        $workPath = "$deployPath/current";
        // Verificar que current existe
        if (!test("[ -d '$deployPath/current' ]")) {
            writeln('<error>✗ No hay una versión desplegada. Ejecuta primero: dep deploy production</error>');
            return;
        }
        writeln('<info>Usando current para configurar uploads...</info>');
    }
    
    writeln('<info>Configurando uploads para Docker con volúmenes mapeados...</info>');
    
    // Crear estructura completa de directorios uploads
    run("mkdir -p '$workPath/writable/uploads/profile/images'");
    run("chmod -R 755 '$workPath/writable/uploads'");
    
    // IMPORTANTE: Como public está mapeado como volumen, debemos crear el enlace en el HOST
    // Verificar si el enlace simbólico ya existe en public (del host)
    if (test("[ -L '$workPath/public/uploads' ]")) {
        writeln('<info>✓ Enlace simbólico uploads ya existe en el host</info>');
    } else {
        // Eliminar directorio uploads si existe y no es un enlace
        if (test("[ -d '$workPath/public/uploads' ] && [ ! -L '$workPath/public/uploads' ]")) {
            run("rm -rf '$workPath/public/uploads'");
            writeln('<info>✓ Directorio uploads removido para crear enlace simbólico</info>');
        }
        
        // Crear enlace simbólico desde public/uploads a writable/uploads EN EL HOST
        // Como public y writable están al mismo nivel, el enlace es correcto
        run("ln -sf ../writable/uploads '$workPath/public/uploads'");
        writeln('<info>✓ Enlace simbólico uploads creado en el host</info>');
    }
    
    // Verificar que el enlace funciona y que la estructura de directorios es correcta
    if (test("[ -d '$workPath/public/uploads' ]")) {
        writeln('<info>✓ Directorio uploads accesible desde public (host)</info>');
        
        if (test("[ -d '$workPath/public/uploads/profile/images' ]")) {
            writeln('<info>✓ Estructura uploads/profile/images existe y es accesible</info>');
        } else {
            writeln('<error>✗ Error: estructura uploads/profile/images no accesible</error>');
        }
        
        // Verificar permisos
        if (test("[ -w '$workPath/writable/uploads' ]")) {
            writeln('<info>✓ Directorio uploads tiene permisos de escritura</info>');
        } else {
            writeln('<warning>⚠ Directorio uploads puede tener problemas de permisos</warning>');
        }
    } else {
        writeln('<error>✗ Error: uploads no es accesible desde public</error>');
    }
    
    writeln('<info>Nota: El enlace se creó en el host, por lo que será visible en ambos contenedores (PHP y Nginx)</info>');
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
    'docker:setup_uploads', // Configura directorios de uploads
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