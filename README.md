# UniChat
## Guida all'installazione
* ### Pre-requisiti:
  * Aver installato Git
  * Aver installato XAMPP (Versione PHP 7.4.25)
  * Aver installato un Text Editor o un IDE per visualizzare il codice (VSCode, Sublime Text, PhpStorm ecc.)
* ### Steps:
    * Aprire il terminale di Git e recarsi nella cartella htdocs di XAMPP
    * Eseguire il seguente comando:
        ```
      git clone https://github.com/EdoPan/UniChat.git
      ```
    * Avviare dalla GUI di XAMPP il server Apache e MySQL
    * Aprire nel proprio browser di fiducia [PhpMyAdmin](http://localhost/phpmyadmin/)
    * Creare un Database e denominarlo UniChat
    * Importare il file `unichat-default.sql` presente nella cartella _Creazione Database_
    * Chiudere il browser
    * Aprire il Text Editor o IDE scelto per visualizzare il codice
    * Visualizzare il file `FConnection.php` presente nella cartella _Foundation_ e settare la password di accesso al database
    * Per la configurazione del recupero password si rimanda alla documentazione ufficiale della libreria usata ([PHPMailer](https://github.com/PHPMailer/PHPMailer)). In particolare le righe di codice da modificare si trovano nel file `CGestioneUtenti.php` presente nella cartella _Control_
    * Aprire nel browser [UniChat](http://localhost/UniChat/)
    * Effettuare il login come utente Admin (così da avere il pieno controllo)
      >Email: amministratore.disistema@student.univaq.it <br> Password: amministratore
* ### Documentazione:
  È possibile trovare ulteriore documentazione (UML, Casi d'uso e Bozze delle schermate) nella cartella _Documentazione_. Da notare che alcuni file presentano l'estensione `.drawio` per aprirli basterà recarsi su [Diagrams](https://app.diagrams.net/) e procedere da interfaccia grafica.
