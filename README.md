# Electronic Press Kit – Ahmad Ali

GitHub: https://github.com/Ahmadali0808/A5

Een webapplicatie in **Laravel** waarmee bands hun eigen *Electronic Press Kit* (EPK) kunnen maken: een openbare pagina met info, foto's en video's voor boekers en pers.

## Functies
- Inloggen en registreren, dashboard en eigen gegevens aanpassen
- Bands **aanmaken, wijzigen en verwijderen** en extra beheerders toevoegen
- Openbare EPK-pagina per band met **foto-upload** en **YouTube-video's**
- Achtergrond- en tekstkleur van de EPK zelf instellen
- Openbare **zoekfunctie** op bandnaam
- **Unit tests** (Band, Video) en **feature tests** (login, gast, dashboard, bands beheren)

## Gebruikte technieken
PHP · Laravel (MVC, Eloquent-relaties, migrations, seeders, factories, Form Requests) · MySQL · Blade · Vite · PHPUnit

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