<?php
// define espaço para organização
namespace Models;

// inclui classe abstrata e interface
use Interfaces\locavel;
use Veiculo\veiculo;

// classe the representa uma moto
class Moto extends veiculo implements Locavel {

    public function calcularAluguel(int $dias):float{
        return $dias * DIARIA_MOTO;
    }

    public function alugar(): string {
        if ($this->disponivel){
            $this->disponivel = false;
            return "Moto '{$this->modelo}' alugada com sucesso";
        }
        return "Moto '{$this->modelo}' está indisponível";
    }

    public function devolver(): string
    {
        if (!$this->disponivel){
            $this->disponivel = true;
            return "Moto '{$this->modelo}' devolvida com sucesso";
        }
        return "Moto '{$this->modelo}' já disponível";
    }
}