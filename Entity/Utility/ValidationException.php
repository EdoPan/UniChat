<?php
declare(strict_types=1);

/**
 * Eccezione per la gestione della validazione dei dati forniti in input alle istanze delle classi Entities.
 */
class ValidationException extends Exception
{
    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una stringa che non contiene lettere alfabetiche.
     */
    const ERROR_STRING_MESSAGE = "Il testo inserito presenta caratteri diversi da quelli alfabetici.";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una email non istituzionale.
     */
    //const ERROR_EMAIL_MESSAGE = "L'email fornita non è quella istituzionale.";
    const ERROR_EMAIL_MESSAGE = "L'email non è valida.";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una email non valida.
     */
    //const ERROR_EMAIL_LENGTH_MESSAGE = "L'email non è valida.";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una password che presenta caratteri non ammessi.
     */
    const ERROR_PASSWORD_MESSAGE = "La password inserita presenta dei caratteri non ammessi.";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una password troppo breve.
     */
    const ERROR_PASSWORD_LENGTH_MESSAGE = "La password deve essere di almeno 8 caratteri";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una immagine di un formato non ammesso.
     */
    const ERROR_IMAGE_MIME_TYPE_MESSAGE = "L'immagine caricata è di un formato non lecito";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce una immagine di dimensioni troppo elevate.
     */
    const ERROR_IMAGE_SIZE_MESSAGE = "L'immagine è troppo grande";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce un allegato di un formato non ammesso.
     */
    const ERROR_ATTACHMENT_MIME_TYPE_MESSAGE = "L'allegato caricato è di un formato non lecito";

    /**
     * Costante che indica il messaggio di errore ottenuto se si inserisce un allegato di dimensioni troppo elevate.
     */
    const ERROR_ATTACHMENT_SIZE_MESSAGE = "L'allegato è troppo grande";

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una stringa che non contiene lettere alfabetiche.
     */
    const ERROR_STRING_CODE = 1;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una email non istituzionale.
     */
    const ERROR_EMAIL_CODE = 2;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una email non valida.
     */
    //const ERROR_EMAIL_LENGTH_CODE = 3;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una password che presenta caratteri non ammessi.
     */
    const ERROR_PASSWORD_CODE = 4;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una password troppo breve.
     */
    const ERROR_PASSWORD_LENGTH_CODE = 5;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una immagine di un formato non ammesso.
     */
    const ERROR_IMAGE_MIME_TYPE_CODE = 6;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce una immagine di dimensioni troppo elevate.
     */
    const ERROR_IMAGE_SIZE_CODE = 7;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce un allegato di un formato non ammesso.
     */
    const ERROR_ATTACHMENT_MIME_TYPE_CODE = 8;

    /**
     * Costante che indica il codice di errore ottenuto se si inserisce un allegato di dimensioni troppo elevate.
     */
    const ERROR_ATTACHMENT_SIZE_CODE = 9;
}