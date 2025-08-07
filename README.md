# Nikiton: Auth API Service

## Install dev prototype

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Add local dev url in **.env** file:

```env
APP_URL=http://127.0.0.1:8000
```

Add database connection in **.env** file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nikiton_api_service
DB_USERNAME=root
DB_PASSWORD=root
```

Then:

```bash
php artisan config:cache 
php artisan view:clear 
php artisan route:cache 
php artisan optimize:clear
```

Migrations:

```bash
php artisan migrate
```

Laravel Passport:

```bash
php artisan passport:keys --force
php artisan passport:client --personal
```

Add Passport Client ID and Passport Client Secret to **.env** file (you can get info, when you made `passport:client --personal`):

```env
PASSPORT_PASSWORD_CLIENT_ID=
PASSPORT_PASSWORD_CLIENT_SECRET=
```

Add default test user credentials to **.env** file:

```env
USER_DEFAULT_LOGIN="USER_AUTH_EMAIL"
USER_DEFAULT_PASSWORD="USER_AUTH_PASSWORD"
```

Generate API doc:

```bash
php artisan l5-swagger:generate
```
