# Installation

### Clone the repo locally:

```
git clone https://github.com/Steval91/LaraBonus.git larabonus
cd larabonus
```

### Install PHP dependencies:

```
composer install
```

### Install NPM dependencies:

```
npm ci
```

### Build assets:

```
npm run dev
```

### Setup configuration:

```
cp .env.example .env
```

### Generate application key:

```
php artisan key:generate
```

### Create MySQL database, simply update your configuration accordingly.

### Run database migrations:

```
php artisan migrate
```

### Run database seeder:

```
php artisan db:seed
```

### Run the dev server (the output will give the address):

```
php artisan serve
```

### You're ready to go! Visit LaraBonus in your browser, and login with:

```
Username: admin@example.com
Password: password
```

or:

```
Username: user@example.com
Password: password
```
