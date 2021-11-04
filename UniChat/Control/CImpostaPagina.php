<?php

class CImpostaPagina
{
    /**
     * Funzione responsabile di compiere tutte le operazioni necessarie a settare correttamente il top page e il side
     * page di alcune delle pagine dell'applicazione.
     * Se le operazioni vanno a buon fine allora viene restituito true, false altrimenti.
     * In quest'ultimo caso si raccomanda di gestire con un errore HTTP 500.
     * @param EUser|null $user Utente che può essere o meno presente nella sessione.
     * @return bool Esito dell'operazione.
     */
    public static function impostaModuli(?EUser $user): bool
    {
        $vPage = new VPage();
        $pm = FPersistentManager::getInstance();

        if (isset($user)) {
            $isAdmin = $pm->isA(FPersistentManager::ENTITY_ADMIN, $user->getID());
            $vPage->setMenuUtente($user, $isAdmin);
        } else {
            $vPage->setMenuUtente(null, null);
        }

        try {
            $categorie = $pm->loadAllCategorie();
        } catch (ValidationException $e) {
            $categorie = null;
        }

        if (isset($categorie)) {
            $vPage->setMenuLeft($categorie);
            $vPage->setBottoneFiltra($categorie);
            return true;
        } else {
            return false;
        }
    }
}