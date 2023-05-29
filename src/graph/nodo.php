<?php
class Nodo implements NodoInterface
{
    public Stazione $Stazione;
    public int $Informazione;
    public bool $Visitato;

    public function __construct(Stazione $stazione)
    {
        $this->Stazione = $stazione;
        $this->Visitato = false;
    }

    public function equals(Nodo $other): bool
    {
        return $this->Stazione->Nome === $other->Stazione->Nome;
    }

    public function toString(): string
    {
        return $this->Stazione->Nome;
    }
}

interface NodoInterface
{
    public function equals(Nodo $other): bool;
    public function toString(): string;
}
?>
