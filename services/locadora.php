<?php
// Define o namespace onde essa classe está localizada
namespace Services;

// Importa as classes Veiculo, Carro e Moto do namespace Models
use Models\{Veiculo, Carro, Moto};

/**
 * Classe responsável por gerenciar as operações da locadora
 */
class Locadora {
    // Array privado que armazena os veículos cadastrados na locadora
    private array $veiculos = [];

    // Construtor da classe que chama o método para carregar os veículos do arquivo
    public function __construct() {
        $this->carregarVeiculos();
    }

    /**
     * Carrega os veículos do arquivo JSON
     */
    private function carregarVeiculos(): void {
        // Verifica se o arquivo JSON com os veículos existe
        if (file_exists(ARQUIVO_JSON)) {
            // Lê o conteúdo do arquivo e decodifica para array associativo
            $dados = json_decode(file_get_contents(ARQUIVO_JSON), true);
            // Percorre cada item do array para recriar os objetos Carro ou Moto
            foreach ($dados as $dado) {
                // Instancia o veículo com base no tipo salvo
                if ($dado['tipo'] === 'Carro') {
                    $veiculo = new Carro($dado['modelo'], $dado['placa']);
                } else {
                    $veiculo = new Moto($dado['modelo'], $dado['placa']);
                }
                // Define se o veículo está disponível ou não
                $veiculo->setDisponivel($dado['disponivel']);
                // Adiciona o veículo ao array interno da locadora
                $this->veiculos[] = $veiculo;
            }
        }
    }

    /**
     * Salva os veículos no arquivo JSON
     */
    private function salvarVeiculos(): void {
        $dados = [];
        // Constrói um array associativo com os dados de cada veículo
        foreach ($this->veiculos as $veiculo) {
            $dados[] = [
                'tipo' => ($veiculo instanceof Carro) ? 'Carro' : 'Moto', // Define o tipo de veículo
                'modelo' => $veiculo->getModelo(), // Obtém o modelo
                'placa' => $veiculo->getPlaca(), // Obtém a placa
                'disponivel' => $veiculo->isDisponivel() // Verifica se está disponível
            ];
        }
        
        // Obtém o diretório do caminho do arquivo
        $dir = dirname(ARQUIVO_JSON);
        // Se o diretório não existir, cria com permissões completas
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        
        // Escreve os dados no arquivo JSON com formatação legível
        file_put_contents(ARQUIVO_JSON, json_encode($dados, JSON_PRETTY_PRINT));
    }


    // adicionar novo veículo
    public function adicionarVeiculo(Veiculo $veiculo): void{
        $this->veiculos[] = $veiculo;
        $this->salvarVeiculos();
    }

    // remover veículo

    // alugar veiculo por X dias

    // devolver veiculo

    // retornar a lista de veiculos

    // calcular previsão do valor
}