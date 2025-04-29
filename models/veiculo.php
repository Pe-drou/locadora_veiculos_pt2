<?php
namespace Models;

/**
 * Classe abstrata base para todos os tipos de veículos
 */
abstract class Veiculo {
    protected string $modelo;
    protected string $placa;
    protected bool $disponivel;

    public function __construct(string $modelo, string $placa) {
        $this->modelo = $modelo;
        $this->placa = $placa;
        $this->disponivel = true;
    }

    /**
     * Calcula o valor do aluguel baseado na quantidade de dias
     */
    abstract public function calcularAluguel(int $dias): float;

    public function isDisponivel(): bool {
        return $this->disponivel;
    }

    public function getModelo(): string {
        return $this->modelo;
    }

    public function getPlaca(): string {
        return $this->placa;
    }

    public function setDisponivel(bool $disponivel): void {
        $this->disponivel = $disponivel;
    }
}
