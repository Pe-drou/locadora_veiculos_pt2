<?php
require_once __DIR__ . '/../services/auth.php';

use Auth\Auth;

$usuario = Auth::getUsuario();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home (Admin) - Loca dos veículos</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- bootstrap icones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- ce ese ese -->
    <style>
    .action-wrapper{
    display: flex;
    align-items: center;
    gap: 0.5rem;
    justify-content: flex-start;
}
.btn-group-actions{
    display: flex;
    gap: .5rem;
    align-items: center;
}
.delete-btn{
    order: 1;
}
.rent-group{
    display: flex;
    flex-direction: row;
    gap: 0.5rem;
    order: 2;
}
.days-input{
    width: 60px !important;
    padding: .25rem .5rem;
    text-align: center;
}
@media (max-width: 768px) {
    .action-wrapper{
        flex-direction: column;
        align-items: stretch;
    }
    .btn-group-actions{
        flex-direction: column;
    }
    .rent-group{
        order: 1;
        width: 100%;
    }
    .delete-btn{
        order: 2;
        width: 100%;
    }
    .days-input{
        width: 100% !important;
    }
}</style>
</head>
<body class="container py-4">
    <div class="container py-4">
        <!-- Barra de informações de usuário (adm) -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center inicio">
                    <h1>Sistema de locadora de veículos</h1>
                    <div class="d-flex align-items-center gap-3 user-info mx-3">
                        <span class="user-icon">
                            <i class="bi bi-person-circle" style="font-size:25px"></i>
                        </span>
                        <!-- Bem vindom [usuário] -->
                        <span class="welcome-text">
                            Bem-vindo, <strong><?= htmlspecialchars($usuario['username']) ?></strong>!
                        </span>
                        <!-- bomtão di logaut -->
                        <a href="?logout=1" class="btn btn-outline-danger d-flex align-items-center gap-1"><i class="bi bi-box-arrow-right"></i> Sair</a>
                    </div>
                </div>
            </div>
        </div>
        <?php if($mensagem):?>
        <div class="alert alert-info alert-dismissable fade show" role="alert">
            <?= htmlspecialchars($mensagem) ?>
            <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <!-- formulario para adicionar novo veículo -->
        <div class="row same-height-row">
            <?php if(Auth::isAdmin()): ?>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">Adicionar novos veículos</h4>
                    </div>
                    <div class="card-body">
                        <form action="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="modelo" class="form-label">Modelo:</label>
                                <input type="text" name="modelo" id="modelo" class="form-control">
                                <div class="invalid-feedback">
                                    Informe um modelo válido!
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="placa" class="form-label">Placa:</label>
                                <input type="text" name="placa" id="placa" class="form-control">
                                <div class="invalid-feedback">
                                    Informe uma placa válido!
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo:</label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="carro">Carro</option>
                                    <option value="moto">Moto</option>
                                    <option value="null" disabled selected hidden></option>
                                </select>
                            </div>
                            <button class="btn btn-outline-success w-100" type="submit" name="adicionar">
                                Adicionar veículo
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <!-- Calculadora de aluguel -->
            <div class="col-<?= Auth::isAdmin() ? 'md-6' : '12' ?>">
                <div class="card h-100">
                    <div class="card-header">
                        <h4 class="mb-0">Calcular a previsão de aluguel</h4>
                    </div>
                    <div class="card-body">
                        <form action="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="tipo" class="input-label">Tipo:</label>
                                <select name="tipo" id="tipo" class="form-select" required>
                                    <option value="carro">Carro</option>
                                    <option value="moto">Moto</option>
                                    <option value="null" selected hidden></option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="dias" class="input-label">Tempo em dias: </label>
                                <input type="number" name="dias" id="tempo" class="form-control" value="1" required>
                            </div>
                            <button class="btn btn-success w-100" type="submit" name="calcular">Calcular</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- tabela de veículos cadastrados -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Veículos cadastrados</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <th>Tipo</th>
                                    <th>Modelo</th>
                                    <th>Placa</th>
                                    <th>Status</th>
                                    <?php if(Auth::isAdmin()):?>
                                    <th>Ações</th>
                                    <?php endif;?>
                                </thead>
                                <tbody>
                                    <?php foreach($locadora->listarVeiculos() as $veiculo): ?>
                                    <tr>
                                        <td><?= $veiculo instanceof \Models\Carro ? 'Carro' : 'Moto' ?></td>
                                        <td><?= htmlspecialchars($veiculo->getModelo()) ?></td>
                                        <td><?= htmlspecialchars($veiculo->getPlaca()) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $veiculo->isDisponivel() ? 'success' : 'warning' ?>">
                                                <?= $veiculo->isDisppnivel() ? 'Disponível' : 'Alugado' ?>
                                            </span>
                                        </td>
                                        <?php if (Auth::isAdmin()): ?>
                                        <td>
                                            <!-- formulario de ações -->
                                            <div class="action-wrapper">
                                                <form action="POST" class="btn-group-actions">
                                                    <input type="hidden" name="modelo" value="<?php htmlspecialchars($veiculo->getModelo); htmlspecialchars($veiculo->getPlaca); ?>">

                                                    <!-- botôm de delete (sempre disponível pro adm) -->
                                                    <button class="btn btn-danger btn-sm delete-btn" type="submit" name="deletar">Deletar</button>

                                                    <!-- botoins condicionais -->
                                                    <div class="rent-group">
                                                        <?php if (!$veiculo->isDisponivel): ?>
                                                        <!-- veículo alugado -->
                                                        <button class="btn btn-warning btn-sm hidden" type="submit" name="devolver">Devolver</button>
                                                        <?php else: ?>
                                                        <!-- veículo disponível -->
                                                        <input type="number" name="dias" class="form-control days-input" value="1" min="1" required>
                                                        <button class="btn btn-primary btn-sm" type="submit" name="alugar">Alugar</button>
                                                        <?php endif ?>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</body>