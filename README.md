# Progetto
Backend -> laravel 11

Frontend -> Vue 3.4.x con Vuetify (ho scelto quest'ultimo per fare prima visti i componenti disponibili)

# Requisiti
I requisiti non mi son stai molto chiari, ho interpreto il testo e sviluppato l'applicativo in questo modo:
- due sistemi di auth: 
  - USER (accedono tramite SPA Vue)
  - CLIENT (utilizzo delle sole API. Applicativi terze parti o APP per smartphone ?!?)
- pagina LOGIN utente
- pagina PERSONE: una volta avvenuto il login, deve atterrare in una pagina PERSONE contenente una tabella con i dati delle persone registrate. 
  - Prevedere la paginazione della tabella. Di default la tabella restituisce le persone registrate dal più recente al meno recente. 
  - Prevedere un ordinamento alfabetico sul campo nome. 
  - Prevvedere una form di inserimento dati per la registrazione di informazioni di una nuova persona (nome, cognome, data di nascita, email, numero di telefono, codice fiscale). 
    - Nella form deve essere prevista una validazione dei dati e del formato dei dati lato client e lato api
- Ruoli utente: 
  - Gli utenti che si autenticano devono prevedere due ruoli diversi. Utenti che hanno permesso di modificare e cancellare e utenti che hanno permessi di sola visualizzazione della tabella
- Export CVS via email:
  - Prevedere pulsante di estrazione dei dati della tabella su file .csv ed invio del file per email (destinazione info@mycity.it) attraverso l’uso dei JOB. Se non si dispone di un SMTP da usare per l’invio, prevedere la funzionalità senza l’invio effettivo.
- Il progetto deve sfruttare il CRUD esposto mediante API
 
# Implementazione
Per la documentazione delle API e il loro utilizzo ho utilizzato **swagger**.

Le entità **user** e **client** e mi son sembrate diverse dalle **persone**, quindi gli ho trattati come oggetti separati.

**USER** e **CLIENT** hanno le corrispondenti tabelle su db.
- USER: accede con _email_ e _password_
- CLIENT: accede con _client_id_ e _secret_id_

Per entrambi ho creato alcuni utenti di default (utilizzando i seeders).
  - USER
    - ROLE_ADMIN: 
      - email: admin@admin.com
      - password: admin
    - ROLE_USER:
        - email: user@user.com
        - password: user
  - CLIENT
    - ROLE_ADMIN:
      - client_id: client_1
      - secret_id: client_1
    - ROLE_USER:
      - client_id: client_2
      - secret_id: client_2

Le api son suddivise in tre gruppi:
- **Auth** (contiene le api per **_login_**, **_logout_** e **_profile_** dei due attori USER e CLIENT)
- **Export** (contiene solo l'api per l'export e l'invio del csv via email come richiesto)
- **Personas** (contiene tutte le api REST sulle persone. Gli endpoint per la **_creazione_**, **_visualizzazione_**, **_update_** e **_delete_** di una singola persona sono autorizzate al solo ruolo **ROLE_ADMIN**. Per proteggerle ho creato il middleware **CheckRole**)

**ATTENZIONE**: Se effettuate i test delle api da swagger, una volta fatto il login e ottenuto il token occorre fare l'Authorize su swagger inserendo il prefisso **Bearer** _seguito da uno spazio prima del token_.

Per tutto il resto non mi sono inventato niente di particolare essendo il mio primo utilizzo di laravel.
Ho utilizzato **sail** visto che esegue un ambiente dockerizzato.
Ho aggiunto **mailpit** per poter simulare un mail server in locale e ottenere il file csv.

# Setup
Per creare/installare la build in locale:
```bash
./localbuild.sh
```

Creaiamo il file env copiandolo da env.local
```bash
cp .env.local .env
```

Eseguiamo l'ambiente con sail:
```bash
./vendor/bin/sail up
```

Apriamo una nuova shell ed entriamo dentro l'immagine docker in esecuzione:
```bash
./vendor/bin/sail shell
```

### Da dentro l'immagine docker:
Migration e seed (resetta tutto)
```bash
php artisan migrate:fresh --seed
```

Genera chiave configurazione di Passport (premere invio quando richiesto)
```bash
php artisan passport:client --personal
```

Genera la documentazione Swagger:
```bash
php artisan l5-swagger:generate
```

Esegui il worker della coda:
```bash
php artisan queue:work
```

## Endpoint 
Se tutto è andato a buon fine dovremmo avere:
- swagger: http://localhost/api/documentation
- mailpit: http://localhost:8025/
- database: 
  - localhost:3306 
  - user: sail
   - password: password 
