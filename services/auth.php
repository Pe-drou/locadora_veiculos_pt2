<?php
// define espaço para organização
namespace Services;

class Auth{
    private array $usuarios = [];

    // método construtor
    public function __construct() {
        $this->carregarUsuarios();
    }

    // método para carregar usuários do arquivo JSon
    private function carregarUsuarios(): void{
        // verifica se o arquivo existe
        if(file_exists(ARQUIVO_USUARIOS)){
            // lê o arquivo e decodifica o JSON para o array
            $conteudo = json_decode(file_get_contents(ARQUIVO_USUARIOS), true);
            
            // verifica se é um array
            $this->usuarios = is_array($conteudo) ? $conteudo : [];

        }else{
            // se o arquivo não existe, cria um novo array vazio
            $this->usuarios = [
                [
                    'username' => 'admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'perfil' => 'admin'
                ],
                [
                    'username' => 'user',
                    'password' => password_hash('user123', PASSWORD_DEFAULT),
                    'perfil' => 'user'
                ]
            ];
            // salva o array no arquivo JSON
            $this->salvarUsuarios();
        }
    }

    // função para salvar os usuários no arquivo JSON
    private function salvarUsuarios(): void{
        // verifica se o diretório existe
        $dir = dirname(ARQUIVO_USUARIOS);
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }
        // salva o array no arquivo JSON
        file_put_contents(ARQUIVO_USUARIOS, json_encode($this->usuarios, JSON_PRETTY_PRINT));
    }

    // método para realizar login
    public function login(string $username, string $password): bool{
        foreach($this->usuarios as $usuario){

            if($usuario['username'] === $username && password_verify($password, $usuario['password'])) {
                $_SESSION['auth'] = [
                    'logado' => true,
                    'username' => $username,
                    'perfil' => $usuario['perfil']
                ];
                return true; // login feito
            }
        }
        return false; // login não feito
    }

    // termina a sessão
    public function logout(): void{
        session_destroy(); // destroi a sessão
    }

    // verificar se o usuário está logado
    public static function verificarLogin(): bool{
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === true;
    }
    public static function isPerfil(string $perfil): bool{
        return isset($_SESSION['auth']) && $_SESSION['auth']['perfil'] === $perfil;
    }

    // verificar se usuario é admin
    public static function isAdmin(): bool{
        return self::isPerfil('admin');
    }

    public static function getUsuario(): ?array{
        // retorna os dados da sessão ou nulo se não existir
        return $_SESSION['auth'] ?? null;
    }
}