<?php
// incluir o autoload
require_once __DIR__ . '/../vendor/autoload.php';

// incluir o asquivo com as variáves
require_once __DIR__ . '/../config/config.php';

session_start();

// inportar as classes locadora e auth
use Services\{Locadora, Auth};
// importar as classes Carro e Moto
use Models\{Carro, Moto};

// verificar se o usuário está logado
if(!Auth::verificarLogin()){
    header('Location:login.php');
    exit;
}

// condição para logout
if(isset($_GET['logout'])){
    (new Auth())->logout();
    header('Location:login.php');
    exit;
}

// criar uma instância da classe locadora
$locadora = new Locadora();

$mensagem = '';

$usuario = Auth::getUsuario();

// verificar os dados do formulário via POST
if($_SERVER['REQUEST_METHOS'] === 'POST'){

    // veririca se o usuário tem permissão de administrador
    if(isset($_POST['adicionar']) || isset($_POST['deletar']) || isset($_POST['alugar']) || isset($_POST['devolver'])){
        if(!Auth::isAdmin()){
            $mensagem = 'você não tem permissão para executar essa ação';
            goto renderizar;
        }
    }

    if(isset($_POST['adicionar'])){
        $modelo = $_POST['modelo'];
        $placa = $_POST['placa'];
        $tipo = $_POST['tipo'];

        $veiculo = ($tipo == 'Carro') ? new Carro($modelo, $placa) : new Moto($modelo, $placa);

        $locadora->adicionarVeiculo($veiculo);

        $mensagem = "Veículo adicionado com sucesso";
    } elseif(isset($_POST['alugar'])) {
        $dias = isset($_POST['dias']) ? (int)$_POST['dias'] : 1;

        $mensagem = $locadora->alugarVeiculo($_POST['modelo, dias']);
    } elseif(isset($_POST['devolver'])) {
        $mensagem = $locadora->devolverVeiculo($_POST['modelo']);
    } elseif(isset($_POST['deletar'])) {
        $mensagem = $locadora->deletarVeiculo($_POST['modelo'], $_POST['placa']);
    } elseif (isset($_POST['calcular'])){
        $dias = (int)$_POST['dias_calculo'];
        $tipo   = $_POST['tipo_calculo'];
        $valor = $locadora->calcularPrevisaoAluguel($dias, $tipo);

        $mensagem =  "Previsão para {$dias} dias: R$ " . number_format($valor, 2, ',', '.');
    }
}

renderizar: require_once __DIR__ . '/../views/template.php';