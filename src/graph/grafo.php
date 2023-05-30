<?php

class Grafo
{
    public array $Nodi;
    public array $Archi;

    public function __construct(array $nodi = null, array $archi = null)
    {
        if (isset($nodi) && isset($archi)) {
            $this->Nodi = $nodi;
            $this->Archi = $archi;
        } else {
            $this->Nodi = [];
            $this->Archi = [];
        }
    }

    public function aggiungiNodo(Nodo $nodo)
    {
        if (!in_array($nodo, $this->Nodi)) {
            array_push($this->Nodi, $nodo);
        }
    }

    public function aggiungiNodi(array $nodi)
    {
        foreach ($nodi as $nodo) {
            if (!in_array($nodo, $this->Nodi)) {
                array_push($this->Nodi, $nodo);
            }
        }
    }

    public function aggiungiArco(Arco $arco)
    {
        if (!in_array($arco, $this->Archi)) {
            //Equivalente del push
            $this->Archi[] = $arco;
            $arcoAlt = new Arco($arco->Arrivo, $arco->Partenza, $arco->Peso);
            $this->Archi[] = $arcoAlt;
        }
    }

    public function rimuoviNodo(Nodo $nodo)
    {
        $key = array_search($nodo, $this->Nodi);
        if ($key !== false) {
            unset($this->Nodi[$key]);
        }
        foreach ($this->Archi as $key => $arco) {
            if ($arco->getPartenza()->equals($nodo) || $arco->getArrivo()->equals($nodo)) {
                unset($this->Archi[$key]);
            }
        }
    }

    public function rimuoviArco(Arco $arco)
    {
        $key = array_search($arco, $this->Archi);
        if ($key !== false) {
            unset($this->Archi[$key]);
        }
        $counterpart = new Arco($arco->Arrivo, $arco->Partenza, $arco->Peso);
        $key = array_search($counterpart, $this->Archi);
        if ($key !== false) {
            unset($this->Archi[$key]);
        }
    }

    public function dijkstra(Nodo $inizio, Nodo $fine): array
    {
        $costiTotali = new ObjectStorage();
        $nodiPrecedenti = new ObjectStorage();
        $percorso = [];
        $priorityQueue = new ObjectStorage();
        $visitati = [];

        $costiTotali[$inizio] = 0;
        $priorityQueue[$inizio] = 0;

        foreach ($this->Nodi as $nodo) {
            if ($nodo != $inizio) {
                $costiTotali[$nodo] = PHP_INT_MAX;
            }
        }

        while ($priorityQueue->count() > 0) {
            $piuPiccolo = $this->getMinValueKey($priorityQueue);
            $priorityQueue->offsetSet($piuPiccolo, PHP_INT_MAX);
            $priorityQueue->offsetUnset($piuPiccolo);

            foreach ($this->viciniDi($piuPiccolo) as $vicino) {
                if (!in_array($vicino, $visitati)) {
                    $priorityQueue->offsetSet($vicino, $costiTotali->offsetGet($piuPiccolo) + $this->getArco($piuPiccolo, $vicino)->Peso);

                    $costoStradaAlternativa = $costiTotali->offsetGet($piuPiccolo) + $this->getArco($piuPiccolo, $vicino)->Peso;
                    $val = $costiTotali[$vicino];
                    if ($costoStradaAlternativa < $costiTotali->offsetGet($vicino)) {
                        $costiTotali[$vicino] = $costoStradaAlternativa;
                        if (!isset($nodiPrecedenti[$vicino])) {
                            $nodiPrecedenti[$vicino] = $piuPiccolo;
                        }
                        $priorityQueue->offsetSet($vicino, $costoStradaAlternativa);
                    }
                }
            }
            $visitati[] = $piuPiccolo;
            if (in_array($fine, $visitati)) {
                break;
            }
        }
        $percorso[] = $fine;
        $tmp = $fine;
        while (!in_array($inizio, $percorso)) {
            $percorso[] = $nodiPrecedenti[$tmp];
            $tmp = $nodiPrecedenti[$tmp];
        }
        $percorso = array_reverse($percorso);
        return $percorso;
    }



    private function viciniDi(Nodo $nodo)
    {
        $vicini = [];
        foreach ($this->Archi as $arco) {
            if ($arco->Partenza->equals($nodo)) {
                $vicini[] = $arco->Arrivo;
            }
        }
        return $vicini;
    }

    private function getMinValueKey(SplObjectStorage $storage)
    {
        $minValue = PHP_INT_MAX;
        $minKey = null;

        foreach ($storage as $key) {
            $value = $storage[$key];
            if ($value < $minValue) {
                $minValue = $value;
                $minKey = $key;
            }
        }

        return $minKey;
    }

    private function getArco(Nodo $partenza, Nodo $arrivo): Arco
    {
        foreach ($this->Archi as $arco) {
            if ($arco->Partenza == $partenza && $arco->Arrivo == $arrivo) {
                return $arco;
            }
        }
        return null;
    }

}
?>