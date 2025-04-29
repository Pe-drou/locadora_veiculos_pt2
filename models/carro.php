<?php
namespace Models;
use Interfaces\locavel;
use Veiculo\veiculo;
// classe the representa um carro
class Carro extends veiculo implements Locavel {

    public function calcularAluguel(int $dias):float{
        return $dias * DIARIA_CARRO;
    }

    public function alugar(): string {
        if ($this->disponivel){
            $this->disponivel = false;
            return "Carro '{$this->modelo}' alugado com sucesso";
        }
        return "Carro '{$this->modelo}' está indisponível";
    }

    public function devolver(): string
    {
        if (!$this->disponivel){
            $this->disponivel = true;
            return "Carro '{$this->modelo}' devolvido com sucesso";
        }
        return "Carro '{$this->modelo}' já disponível";
    }
}