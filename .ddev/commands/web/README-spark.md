# Comando Spark para DDEV

Este comando permite ejecutar el CLI de CodeIgniter 4 (`spark`) directamente a través de DDEV.

## Uso

```bash
ddev spark [comando] [argumentos]
```

## Ejemplos

- Listar todas las rutas:
  ```bash
  ddev spark routes
  ```

- Ejecutar migraciones:
  ```bash
  ddev spark migrate
  ```

- Limpiar caché:
  ```bash
  ddev spark cache:clear
  ```

- Creación de controladores, modelos, etc:
  ```bash
  ddev spark make:controller NombreController
  ddev spark make:model NombreModel
  ```

- Ver la lista completa de comandos disponibles:
  ```bash
  ddev spark list
  ```

## Ventajas

- No es necesario entrar al contenedor web para ejecutar comandos de CodeIgniter
- Funciona desde cualquier directorio dentro del proyecto
- Conserva la sintaxis estándar de CodeIgniter

## Notas

Este comando ejecuta `spark` desde la ruta raíz del proyecto (`/var/www/html/spark`), lo que permite utilizarlo desde cualquier subdirectorio del proyecto sin preocuparse por rutas relativas.
