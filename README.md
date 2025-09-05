# Website BAUBYTE

## Instrucciones
#### Requisitos tener docker y docker-compose
#### 1. Clonar repo
#### 2. Dentro del directorio del repo clonado abrir un Terminal y ejecutar:
    docker-compose up -d
#### 3. Una vez que se levanten los contenedores ejecutar usaremos unos helpers que se encuentran en el directorio [helpers-bash](./helpers-bash), para eso en le terminal no movemos al directorio:
    cd helpers-bash
#### 4. Vamos instalar todas las dependencias de Codeigniter 4 para eso usamos el helper [composer](./helpers-bash/composer) el cual pude recibir todos los argumentos de composer. Usamos el Terminal y ejecutamos:
    ./composer install
#### 5. Ahora copiamos el archivo de variables de entorno [env](./website-src/env) lo hacemos desde el terminal con:
##### 5.1
    cd website-src
##### 5.2
    cp env .env
#### 6. Vamos a generar una **key** para la aplicación, para eso vamos a usar otro helper [spark](./helpers-bash/spark) el cual pude recibir todos los argumentos de **spark**. Usamos el Terminal y ejecutamos:
##### 6.1 Nos movemos a:
    cd helpers-bash
##### 6.2 
    ./spark key:generate
#### 7. Ahora vamos a [Importar la base de datos ](./dB/website-db.sql), para eso vamos a usar [phpMyAdmin](http://localhost:81).
#### Usuario:
        root
#### Contraseña: 
        admin.root
#### 8. Si querés parar la ejecución del contenedor ejecutar desde el terminal (recodar estar en la raiz del repositorio):
    docker-compose down
