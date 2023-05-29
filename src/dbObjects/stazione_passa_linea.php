<?php
class Stazione_passa_linea 
{
    public string $StazioneNome;
    public string $LineaNome;
    public int $Posizione;
    public function __construct($StazioneNome, $LineaNome, $Posizione) 
    {
        $this->StazioneNome = $StazioneNome;
        $this->LineaNome = $LineaNome;
        $this->Posizione = $Posizione;
    }
}
?>