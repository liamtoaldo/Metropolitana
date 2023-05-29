<?php
class Stazione
{
    public string $Nome;
    public float $Latitudine;
    public float $Longitudine;

    public function __construct(string $nome, float $latitudine, float $longitudine)
    {
        $this->Nome = $nome;
        $this->Latitudine = $latitudine;
        $this->Longitudine = $longitudine;
    }
}
?>
