<?php
$senha = "321";
$hash_admin = password_hash($senha, PASSWORD_DEFAULT);
$hash_usuario = password_hash($senha, PASSWORD_DEFAULT);

echo "hash para admin:" . $hash_admin . "<br>";
echo "hash para usuario" . $hash_usuario . "<br>";
echo $senha;
?>