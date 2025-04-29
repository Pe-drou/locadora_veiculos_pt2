<?php
// define espaço para organização
namespace Interfaces;

// interface que define os metodos necessários para um veículo ser locável

interface locavel {
    public function alugar() : string;
    public function devolver() : string;
    public function isDisponivel() : bool;
}