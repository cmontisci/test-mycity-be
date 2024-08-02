## Esecuzione processo

./vendor/bin/sail up

Pubblica i file di configurazione di Passport
php artisan passport:install

Eseguire la Migration
Esegui la migration per creare la tabella:
php artisan migrate

Esecuzione della Seed
Esegui il comando per popolare la tabella personas con i dati casuali:
php artisan db:seed

Generazione della Documentazione
Genera la documentazione Swagger:
php artisan l5-swagger:generate

Accesso alla Documentazione
Puoi ora accedere alla documentazione della tua API visitando /api/documentation 
nella tua applicazione Laravel. Se siamo in locale: http://localhost:80/api/documentation


Genera una chiave segreta JWT:
php artisan jwt:secret

Per resettare il db
php artisan migrate:fresh --seed
