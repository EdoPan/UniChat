<?php
declare(strict_types=1);

/**
 * Classe usata per eseguire la validazione dell'input fornito alle classi Entities.
 */
class Validazione
{

    /**
     * Regular Expression rappresentante stringhe con soli caratteri alfabetici.
     * @var string
     */
    private $STRING_PATTERN = '/^[a-z A-z]+$/';

    /**
     * Regular Expression rappresentante stringhe che devono finire con @student.univaq.it e presentare alemeno un
     * carattere alfanumerico prima di @.
     * @var string
     */
    //private $EMAIL_PATTERN = '/[a-z.A-Z0-9]+@student.univaq.it$/';
    private $EMAIL_PATTERN = '/^[a-z.\-A-Z0-9]+@/';

    /**
     * Valore che indica la lunghezza minima di una email.
     * @var int
     */
    //private $MIN_EMAIL_LENGTH = 18;

    /**
     * Regular Expression rappresentante stringhe che devono presentare solo caratteri alfanumerici, i caratteri speciali
     * '@', '.', '\', '-', '_' e che devono essere lunghe almeno 8 caratteri.
     * @var string
     */
    private $PASSWORD_PATTERN = '/^[a-zA-z0-9@.\-_]{8,}$/';

    /**
     * Valore che indica la lunghezza minima di una password.
     * @var int
     */
    private $MIN_PASSWORD_LENGTH = 8;

    /**
     * Array contenente i formati immagini ammessi.
     * @var array|string[]
     */
    private $IMAGE_MIME_TYPE = array("image/jpeg", "image/png", "image/gif");

    /**
     * Valore che rappresenta la dimensione massima, espressa in Byte, di una immagine.
     * @var int
     */
    private $IMAGE_MAX_SIZE = 2097152;      //2 MB espresso in Byte

    /**
     * Array contenente i formati ammessi per gli allegati.
     * @var array|string[]
     */
    private $ATTACHMENT_MIME_TYPE = array("image/jpeg", "image/png", "application/pdf");

    /**
     * Valore che rappresenta la dimensione massima, espressa in Byte, di una immagine.
     * @var int
     */
    private $ATTACHMENT_MAX_SIZE = 5242880;     //5 MB espresso in Byte

    /**
     * Istanza della classe Validazione, si utilizza per il singleton.
     * @var null
     */
    private static $instance = null;

    /**
     * Costruttore di default.
     */
    private function __construct() {}

    /**
     * Restituisce l'istanza di Validazione. Se già esistente restituisce quella esistente, altrimenti la crea.
     * @return Validazione Istanza di Validazione.
     */
    public static function getInstance(): Validazione {
        if (self::$instance == null) {
            $classe = __CLASS__;
            self::$instance = new $classe;
        }
        return self::$instance;
    }

    /**
     * Verifica che la stringa fornita in ingresso rispetti lo STRING_PATTERN, in caso di esito negativo viene lanciata
     * un'eccezione con messaggio ERROR_STRING_MESSAGE e codice ERROR_STRING_CODE.
     * @throws ValidationException Eccezione lanciata nel momento in cui la validazione ha esito negativo.
     */
    public function validaStringa(string $stringa): void
    {
        if (!preg_match($this->STRING_PATTERN, $stringa)) {
            throw new ValidationException(ValidationException::ERROR_STRING_MESSAGE, ValidationException::ERROR_STRING_CODE);
        }
    }

    /**
     * Verifica che la stringa fornita in ingresso rispetti l'EMAIL_PATTERN, in caso di esito negativo viene lanciata
     * un'eccezione con messaggio ERROR_STRING_MESSAGE e codice ERROR_STRING_CODE.
     * Se invece la stringa fornita in ingresso ha lunghezza minore di MIN_EMAIL_LENGTH allora viene lanciata
     * un'eccezione con messaggio ERROR_EMAIL_LENGTH_MESSAGE e codice ERROR_EMAIL_LENGTH_CODE.
     * @throws ValidationException Eccezione lanciata nel momento in cui la validazione ha esito negativo.
     */
    public function validaEmail(string $email): void
    {
        if (!preg_match($this->EMAIL_PATTERN, $email)) {
            //if (strlen($email) <= $this->MIN_EMAIL_LENGTH) {
            //    throw new ValidationException(ValidationException::ERROR_EMAIL_LENGTH_MESSAGE, ValidationException::ERROR_EMAIL_LENGTH_CODE);
            //} else {
                throw new ValidationException(ValidationException::ERROR_EMAIL_MESSAGE, ValidationException::ERROR_EMAIL_CODE);
            //}
        }
    }

    /**
     * Verifica che la stringa fornita in ingresso rispetti il PASSWORD_PATTERN, in caso di esito negativo viene lanciata
     * un'eccezione con messaggio ERROR_PASSWORD_MESSAGE e codice ERROR_PASSWORD_CODE.
     * Se invece la stringa fornita in ingresso ha lunghezza minore di MIN_PASSWORD_LENGTH allora viene lanciata
     * un'eccezione con messaggio ERROR_PASSWORD_LENGTH_MESSAGE e codice ERROR_PASSWORD_LENGTH_CODE.
     * @throws ValidationException Eccezione lanciata nel momento in cui la validazione ha esito negativo.
     */
    public function validaPassword(string $password): void
    {
        if (!preg_match($this->PASSWORD_PATTERN, $password)) {
            if (strlen($password) < $this->MIN_PASSWORD_LENGTH) {
                throw new ValidationException(ValidationException::ERROR_PASSWORD_LENGTH_MESSAGE, ValidationException::ERROR_PASSWORD_LENGTH_CODE);
            } else {
                throw new ValidationException(ValidationException::ERROR_PASSWORD_MESSAGE, ValidationException::ERROR_PASSWORD_CODE);
            }
        }
    }

    /**
     * Verifica che il MIME type e la dimensione forniti in ingresso soddisfino i requisiti per le immagini, in caso
     * contrario viene lanciata l'eccezione ValidationException con messaggio ERROR_IMAGE_MIME_TYPE_MESSAGE e codice
     * ERROR_IMAGE_MIME_TYPE_CODE o con messaggio ERROR_IMAGE_SIZE_MESSAGE e codice ERROR_IMAGE_SIZE_CODE.
     * @throws ValidationException Eccezione lanciata nel momento in cui la validazione ha esito negativo.
     */
    public function validaImmagine(string $mimeType, int $size): void {
        if (!in_array($mimeType, $this->IMAGE_MIME_TYPE)) {
            throw new ValidationException(ValidationException::ERROR_IMAGE_MIME_TYPE_MESSAGE, ValidationException::ERROR_IMAGE_MIME_TYPE_CODE);
        }
        if ($size > $this->IMAGE_MAX_SIZE) {
            throw new ValidationException(ValidationException::ERROR_IMAGE_SIZE_MESSAGE, ValidationException::ERROR_IMAGE_SIZE_CODE);
        }
    }

    /**
     * Verifica che il MIME type e la dimensione forniti in ingresso soddisfino i requisiti per gli allegati, in caso
     * contrario viene lanciata l'eccezione ValidationException con messaggio ERROR_ATTACHMENT_MIME_TYPE_MESSAGE e codice
     * ERROR_ATTACHMENT_MIME_TYPE_CODE o con messaggio ERROR_ATTACHMENT_SIZE_MESSAGE e codice ERROR_ATTACHMENT_SIZE_CODE.
     * @throws ValidationException Eccezione lanciata nel momento in cui la validazione ha esito negativo.
     */
    public function validaAllegato(string $mimeType, int $size): void {
        if (!in_array($mimeType, $this->ATTACHMENT_MIME_TYPE)) {
            throw new ValidationException(ValidationException::ERROR_ATTACHMENT_MIME_TYPE_MESSAGE, ValidationException::ERROR_ATTACHMENT_MIME_TYPE_CODE);
        }
        if ($size > $this->ATTACHMENT_MAX_SIZE) {
            throw new ValidationException(ValidationException::ERROR_ATTACHMENT_SIZE_MESSAGE, ValidationException::ERROR_ATTACHMENT_SIZE_CODE);
        }
    }
}