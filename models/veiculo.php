<?php
namespace Veiculo;
// classe abstrata para todos os tipoos de veículos

abstract class veiculo {
    protected string $modelo;
    protected string $placa;
    protected bool $disponivel;

    public function __construct(string $modelo, string $placa, bool $disponivel)
    {
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->disponivel = true;
    }

    abstract public function calcularAluguel(int $dias) : float;
    
    public function isDisponivel() : bool {
        return $this->disponivel;
    }
    public function getModelo() : string{
        return $this->modelo;
    }
    public function getPlaca() : string{
        return $this->placa;
    }
    public function setDisponivel(bool $disponivel) : void{
        $this->disponivel = $disponivel;
    }
}