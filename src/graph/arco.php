<?php
class Arco implements ArcoInterface
{
    public Nodo $Partenza;
    public Nodo $Arrivo;
    public int $Peso;

    public function __construct(Nodo $partenza, Nodo $arrivo, int $peso)
    {
        $this->Partenza = $partenza;
        $this->Arrivo = $arrivo;
        $this->Peso = $peso;
    }

    public function equals(Arco $other): bool
    {
        return $this->Partenza->equals($other->Partenza) && $this->Arrivo->equals($other->Arrivo);
    }

    public function toString(): string
    {
        return $this->Partenza . " - " . $this->Arrivo . " | " . $this->Peso;
    }
}

interface ArcoInterface
{
    public function equals(Arco $other): bool;
    public function toString(): string;
}
?>
