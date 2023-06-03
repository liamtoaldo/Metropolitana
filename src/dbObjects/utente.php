<?php
class Utente
{
    public int $idUtente;
    public string $Nome;
    public string $Cognome;
    public string $CF;
    public int $Eta;
    public string $Professione;
    public string $PasswordHash;
    public string $Username;
    public function __construct(int $idUtente, string $Nome, string $Cognome, string $CF, int $eta, string $Professione, string $PasswordHash, string $Username)
    {
        $this->idUtente = $idUtente;
        $this->Nome = $Nome;
        $this->Cognome = $Cognome;
        $this->CF = $CF;
        $this->Eta = $eta;
        $this->Professione = $Professione;
        $this->PasswordHash = $PasswordHash;
        $this->Username = $Username;
    }
}
?>