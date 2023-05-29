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


    public function dijkstra(Nodo $inizio, Nodo $fine)
    {
        $costiTotali = new SplObjectStorage;
        $nodiPrecedenti = [];
        $percorso = [];
        $priorityQueue = new SplObjectStorage;
        $visitati = [];

        $costiTotali[$inizio] = 0;
        $priorityQueue[$inizio] = 0;

        foreach ($this->Nodi as $nodo) {
            if ($nodo !== $inizio) {
                $costiTotali[$nodo] = PHP_INT_MAX;
            }
        }

        while (!empty($priorityQueue)) {
            $piuPiccolo = $priorityQueue[array_search(min($priorityQueue), $priorityQueue)];
            $priorityQueue[$piuPiccolo] = PHP_INT_MAX;
            unset($priorityQueue[$piuPiccolo]);

            foreach ($this->viciniDi($piuPiccolo) as $vicino) {
                if (!in_array($vicino, $visitati, true)) {
                    $priorityQueue[$vicino] = $costiTotali[$piuPiccolo] + $this->archi[$piuPiccolo][$vicino]->peso;

                    $costoStradaAlternativa = $costiTotali[$piuPiccolo] + $this->archi[$piuPiccolo][$vicino]->peso;

                    if ($costoStradaAlternativa < $costiTotali[$vicino]) {
                        $costiTotali[$vicino] = $costoStradaAlternativa;
                        if (!$nodiPrecedenti->contains($vicino)) {
                            $nodiPrecedenti[$vicino] = $piuPiccolo;
                        }
                        $priorityQueue[$vicino] = $costoStradaAlternativa;
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
}
?>