# GESS - IT

Guess-it es un juego multijugador en el que mientras uno dibuja, el resto tiene que adivinar la palabra lo más rápido posible para ganar.

## SETUP
### Clonar Repositorio 

```bash
git clone ....
```

### Instalar vía Composer

```bash
composer install
```

### Copiar el fichero .env.example  a .env edita las credenciales y la url


### Generar Application Key

```bash
php artisan key:generate
```

### Migrar base de datos

```bash
php artisan migrate
```

### Lanzar Seeders

```bash
php artisan db:seed
```

### Instalar las dependencias de Node

```bash
npm install

npm run dev
```
### Lanzar a producción

```bash
npm run build or yarn build
```

# Características

### Link storage

```bash
php artisan storage:link
```

<br><br>

# CONFIG MULTIPLAYER
## SETUP
### Configurar los siguientes 3 archivos:
### 1. .env: Reemplazar las siguientes líneas. 
```bash
DB_CONNECTION=mysql
DB_HOST=192.168.23.124
DB_PORT=3306
DB_DATABASE=guess_it
DB_USERNAME=Player1 / Player2 / Player3 / Player4
DB_PASSWORD=123

PUSHER_APP_ID=local
PUSHER_APP_KEY=local
PUSHER_APP_SECRET=local
PUSHER_HOST=192.168.23.124
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1
```
 - La IP del HOST puede variar.
 - Username selecionar 1 de los 4.

### 2. broadcasting.php: Modificar la conexíon pusher.
```php
'pusher' => [
            'driver' => 'pusher',
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),
            'options' => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'host' => env('PUSHER_HOST'),
                'port' => env('PUSHER_PORT'),
                'scheme' => env('PUSHER_SCHEME'),
                'encrypted' => false,
            ],
        ],
```

### 3. bootstrap.js: Modificar la configuracion Echo.
```js
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: '192.168.23.124',
    wsPort: 6001,
    forceTLS: false,
    disableStatus: true
});
```
 - Lo mismo que .env, la IP del HOST puede variar.

