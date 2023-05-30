<?php
class Transiti
{
    // External and primary keys
    public int $IdViaggio;
    public string $StazionePartenza;
    public string $StazioneArrivo;
    public bool $InizioViaggio;
    public bool $FineViaggio;
    public DateTime $OraPartenza;
    public DateTime $OraArrivo;
    public int $PosizioneNelViaggio;
    public float $Costo;


    public function __construct(int $idViaggio, string $stazionePartenza, string $stazioneArrivo, bool $inizioViaggio, bool $fineViaggio, DateTime $oraPartenza, DateTime $oraArrivo, int $posizioneNelViaggio, float $costo)
    {
        $this->IdViaggio = $idViaggio;
        $this->StazionePartenza = $stazionePartenza;
        $this->StazioneArrivo = $stazioneArrivo;
        $this->InizioViaggio = $inizioViaggio;
        $this->FineViaggio = $fineViaggio;
        $this->OraPartenza = $oraPartenza;
        $this->OraArrivo = $oraArrivo;
        $this->PosizioneNelViaggio = $posizioneNelViaggio;
        $this->Costo = $costo;
    }

    public function __toString()
    {
        return $this->StazionePartenza . " -> " . $this->StazioneArrivo . " : " . $this->OraPartenza->format('H:i:s') . "";
    }
}
?>