<?php
require_once "conexao.php";

$email = "admin@clinica.com";
$novaSenha = password_hash("password", PASSWORD_DEFAULT);

$sql = "UPDATE usuarios SET senha = :senha WHERE email = :email";

$stmt = $conexao->prepare($sql);
$stmt->execute([
    ":senha" => $novaSenha,
    ":email" => $email
]);

if ($stmt->rowCount() > 0) {
    echo "Senha corrigida com sucesso!";
} else {
    echo "Usuário não encontrado ou senha já estava atualizada.";
}
?>