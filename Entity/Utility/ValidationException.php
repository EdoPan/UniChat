<?php
declare(strict_types=1);

class ValidationException extends Exception
{
    const ERROR_STRING_MESSAGE = "Il testo inserito presenta caratteri diversi da quelli alfabetici.";

    const ERROR_EMAIL_MESSAGE = "L'email fornita non è quella istituzionale.";

    const ERROR_EMAIL_LENGTH_MESSAGE = "L'email non è valida.";

    const ERROR_PASSWORD_MESSAGE = "La password inserita presenta dei caratteri non ammessi.";

    const ERROR_PASSWORD_LENGTH_MESSAGE = "La password deve essere di almeno 8 caratteri";

    const ERROR_IMAGE_MIME_TYPE_MESSAGE = "L'immagine caricata è di un formato non lecito";

    const ERROR_IMAGE_SIZE_MESSAGE = "L'immagine è troppo grande";

    const ERROR_ATTACHMENT_MIME_TYPE_MESSAGE = "L'allegato caricato è di un formato non lecito";

    const ERROR_ATTACHMENT_SIZE_MESSAGE = "L'allegato è troppo grande";

    const ERROR_STRING_CODE = 1;

    const ERROR_EMAIL_CODE = 2;

    const ERROR_EMAIL_LENGTH_CODE = 3;

    const ERROR_PASSWORD_CODE = 4;

    const ERROR_PASSWORD_LENGTH_CODE = 5;

    const ERROR_IMAGE_MIME_TYPE_CODE = 6;

    const ERROR_IMAGE_SIZE_CODE = 7;

    const ERROR_ATTACHMENT_MIME_TYPE_CODE = 8;

    const ERROR_ATTACHMENT_SIZE_CODE = 9;
}