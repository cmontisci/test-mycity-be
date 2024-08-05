# Progetto
Backend -> laravel 11

Frontend -> Vue 3.4.x con Vuetify (ho scelto quest'ultimo per fare prima visti i componenti disponibili)

# Requisiti
I requisiti non mi son stati molto chiari, ho interpreto il testo e sviluppato l'applicativo in questo modo:
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

Per l'autenticazione ho utilizzato passport e l'accesso avviene con un token Bearer.

Le entità **user** e **client** e mi son sembrate diverse dalle **persone**, quindi gli ho trattati come oggetti separati.

**USER** e **CLIENT** li ho gestiti come userTypes della tabella Users.
- USER: accede con _email_ e _password_
- CLIENT: accede con _client_id_ e _password_

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
      - password: secret_1
    - ROLE_USER:
      - client_id: client_2
      - password: secret_2

Le api son suddivise in tre gruppi:
- **Auth** (contiene le api per **_login_**, **_logout_** e **_profile_** dei due attori USER e CLIENT)
- **Export** (contiene solo l'api per l'export e l'invio del csv via email come richiesto)
- **Personas** (contiene tutte le api REST sulle persone. Gli endpoint per la **_creazione_**, **_visualizzazione_**, **_update_** e **_delete_** di una singola persona sono autorizzate al solo ruolo **ROLE_ADMIN**. Per proteggerle ho creato il middleware **CheckRole**)

**ATTENZIONE**: Se effettuate i test delle api da swagger, una volta fatto il login e ottenuto il token occorre fare l'Authorize su swagger inserendo il prefisso **Bearer** _seguito da uno spazio prima del token_.

Per tutto il resto non mi sono inventato niente di particolare essendo il mio primo utilizzo di laravel.
Ho utilizzato **sail** visto che esegue un ambiente dockerizzato.
Ho aggiunto **mailpit** per poter simulare un mail server in locale e ottenere il file csv.

# Osservazioni
- volevo utilizzare due tabelle distinte per USER e CLIENT ma ci stavo perdendo più tempo del prevvisto. Non ho ben capito se è un limite di laravel o mio (molto probabile).  Il problema che ho incontrato è questo: https://stackoverflow.com/questions/57484680/multi-users-with-passport-how-to-get-authenticated-user-type e si dovrebbe risolvere così: https://medium.com/@hamisjuma1/taking-laravel-passport-to-the-next-level-multi-model-authentication-authorization-and-auditing-81d702c15766 . Ho deciso quindi di tornare indietro e utilizzare una user_type_id all'interno della tabella users.
- lato frontend ci sono alcuni problemi sul component datepicker nella data di nascita. Anche li non ci ho perso più tempo del prevvisto. Mi sembra un errore di decodifica della data se la si inserisce a mano. Selezionandola dal calendario funziona correttamente.
- Non avevo mai utilizzato laravel e mi è sembrato magico per alcuni versi e ostico per altri. Magico perchè se si chiedono robe standard basta lanciare qualche comando e tutto funziona ma se si ha bisogno di qualcosa di custom mi sembra più complesso trovare una soluzione. Symfony mi sembra più "standard" come logiche e fa meno uso di convenzioni ma sicuramente questa sensazione è dovuta all'esperienza che ho con il framework.

# Setup
1. Per creare/installare la build in locale:
```bash
./localbuild.sh
```

2. Creaiamo il file env copiandolo da env.local
```bash
cp .env.local .env
```

3. Eseguiamo l'ambiente con sail:
```bash
./vendor/bin/sail up
```

4. Apriamo una nuova shell ed entriamo dentro l'immagine docker in esecuzione:
```bash
./vendor/bin/sail shell
```

### Da dentro l'immagine docker:
5. Migration
```bash
php artisan migrate
```
Seed (Se non viene migrato niente il db è apposto. Saltiamo al punto 6. Se invece avvengono delle migrazioni occorre proseguire con il seed
```bash
php artisan db:seed
```
oppure in caso di errori:
    ```bash
    php artisan migrate:fresh --seed
    ```

6. Genera chiave configurazione di Passport (premere invio quando richiesto)
```bash
php artisan passport:keys

php artisan passport:client --personal
```

7. Genera la documentazione Swagger:
```bash
php artisan l5-swagger:generate
```

8. Esegui il worker della coda:
```bash
php artisan queue:work
```

# Endpoint 
Se tutto è andato a buon fine dovremmo avere:
- swagger: http://localhost/api/documentation
- mailpit: http://localhost:8025/
- database: 
  - localhost:3306 
  - user: sail
   - password: password 


### Per fermare l'esecuzione:
```bash
./vendor/bin/sail down
```
