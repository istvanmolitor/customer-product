# Customer Product

Ügyfél termékeinek kezelése – árak, kategóriák, készlet ügyfélszinten.

## Telepítés

A csomag a service provider auto-discovery segítségével automatikusan regisztrálódik.

## Seeder regisztrálása

A jogosultságok és kezdeti adatok beállításához regisztráld a seedert a `database/seeders/DatabaseSeeder.php` fájlban:

```php
use Molitor\CustomerProduct\database\seeders\CustomerProductSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CustomerProductSeeder::class,
        ]);
    }
}
```
