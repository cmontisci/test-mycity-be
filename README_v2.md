## Esecuzione processo

./vendor/bin/sail up

Pubblica i file di configurazione di Passport
php artisan passport:install

Eseguire la Migration
Esegui le migrazioni per creare le tabelle necessarie per la coda:
php artisan queue:table

Esegui la migration per creare la tabella:
php artisan migrate

Esecuzione della Seed
Esegui il comando per popolare la tabella personas con i dati casuali:
php artisan db:seed

Esegui il worker della coda:
php artisan queue:work

Generazione della Documentazione
Genera la documentazione Swagger:
php artisan l5-swagger:generate

Accesso alla Documentazione
Puoi ora accedere alla documentazione della tua API visitando /api/documentation 
nella tua applicazione Laravel. Se siamo in locale: http://localhost:80/api/documentation

Per il server mail:
http://localhost:8025/

Genera una chiave segreta JWT:
php artisan jwt:secret

Per resettare il db
php artisan migrate:fresh --seed

Show router list
php artisan route:list

Per pulire la cache delle rotte:
php artisan route:clear
php artisan route:cache

Cancellare la Cache delle Configurazioni:
php artisan config:clear

Rigenerare la Cache delle Configurazioni:
php artisan config:cache

Cancellare la Cache delle Viste:
php artisan view:clear

Cancellare la Cache Applicativa:
php artisan cache:clear
