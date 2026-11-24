<?php
require_once "conexao.php";

$senha = password_hash("123456", PASSWORD_DEFAULT);

$sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";
$stmt = $conexao->prepare($sql);

$stmt->execute([
    ":senha" => $senha,
    ":email" => "maria@clinica.com"
]);

echo "Senha da Maria atualizada!";
?>
