<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';

// inserir a classe de autenticação
use Services\Auth;

// inicializa a variável para mensagens de erro
$mensagem = '';

// instanciar a classe de autenticação
$auth = new Auth();

// verifica se já foi autenticado
if(Auth::verificarLogin()){

    // redireciona para a a páagina inicial
    header('Locadion:index.php');
    exit;
}

// verifica se o formulãrio foi enviado
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // pega os dados enviados
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // verifica o login
    if($auth->login($username, $password)){
        // se estiver correto, redireciona para a página inicial
        header('Location:index.php');
    } else {
        // se não estiver correto, dá uma mensagem de erro
        $mensagem = "Falha no login, verifique se o nome de usuário e senha estão corretos";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Loca dos veículos</title>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- bootstrap icones -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .login-container{
            max-width: 400px;
            margin: 100px auto;
        }
        .password-toggle{
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-30%);
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">
    <div class="login-container">
        <div class="card">
            <!-- título do card login -->
            <div class="card-header">
                <h4 class="mb-1">Login</h4>
            </div>
            <!-- corpo do card login -->
            <div class="card-body">

                <?php if($mensagem): ?>
                <div class="alert alert-danger"><?=htmlspecialchars($mensagem)?></div>
                <?php endif; ?>
                <form action="post" class="needs-validation" novalidate>
                    <input type="hidden">

                    <div class="mb-3">
                        <label for="user" class="form-label">usuário</label>
                        <input type="text" name="username" class="form-control" required autocomplete="off" placeholder="Digite o usuário">
                    </div>

                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Senha:</label>
                        <input type="password" name="password" class="form-control" required id="password">
                        <span class="password-toggle mt-3" onclick="togglePassword()">
                            <i class="bi bi-eye-fill"></i>
                        </span>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(){
            let passwordInput = document.getElementById('password')
            passwordInput.type = (passwordInput.type === password) ? 'text' : 'password';

        }

    </script>
</body>
</html>