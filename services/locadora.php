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
    public function deletarVeiculo(string $placa, string $modelo){
        foreach($this->veiculos as $key => $veiculo){
            // verifica se o modelo e a placa correspondem
            if($veiculo->getModelo() === $modelo && $veiculo->getPlaca() === $placa){
                // remove o veículo do array
                unset($this->veiculos[$key]);

                // reorganizar os indices
                $this->veiculos = array_values($this->veiculos);

                // salva os veículos restantes no arquivo JSON
                $this->salvarVeiculos();
                return "Veículo '{$modelo}' removido com sucesso!";
            }
        }
        return "Veículo não encontrado";
    }

    // alugar veiculo por X dias
    public function alugarVeiculo(string $modelo, int $dias = 1): string{

        // percorre a lista de veículos
        foreach($this->veiculos as $veiculo){
            if($veiculo->getModelo() === $modelo && $veiculo->isDisponivel()){

                // calcular valor de aluguel
                $valorAluguel = $veiculo->calcularAluguel($dias);

                // marcar como alugado
                $mensagem = $veiculo->alugar();

                $this->salvarVeiculos();
                return $mensagem . "valor do aluguel: R$" . number_format($valorAluguel, 2, ',', '.');
            }            
        }
        return "veículo não disponível";
    }

    // devolver veiculo
    public function devolverDeiculo(string $modelo): string{
        foreach($this->veiculos as $veiculo){
            if($veiculo -> getModelo() === $modelo && !$veiculo->isDisponivel()){
                $mensagem = $veiculo->devolver();
                $this->salvarVeiculos();
                return $mensagem;
            }
        }
        return"Veículo já disponível ou não encontrado";
    }

    // retornar a lista de veiculos

    public function listarVeiculos(): array{
        return $this->veiculos;
    }

    // calcular previsão do valor
    public function calcularPrevisaoAluguel(string $tipo, int $dias): float{
        if ($tipo === 'Carro'){
            return (new Carro('',''))->calcularAluguel($dias);
        } else {
            return (new Moto('',''))->calcularAluguel($dias);
        }
    }
}