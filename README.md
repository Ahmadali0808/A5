# Electronic Press Kit – Ahmad Ali

GitHub: https://github.com/Ahmadali0808/A5

## Installeren
1. Start Apache en MySQL in XAMPP
2. Maak in phpMyAdmin de databases `epk` en `epk_test` aan
3. Kopieer `.env.example` naar `.env` en zet `DB_DATABASE=epk`
4. Voer uit:

```
composer install
npm install
npm run build
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
php artisan serve
```

5. Ga naar http://localhost:8000

Inloggen: test@example.com / password

## Testen

```
php artisan test
```