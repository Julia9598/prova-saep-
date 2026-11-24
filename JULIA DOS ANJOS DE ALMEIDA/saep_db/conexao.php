<?php
$host = "127.0.0.1";
$porta = "3307";
$banco = "saep_db";
$usuario = "root";
$senha = "";

try {
    $conexao = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $conexao->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    

} catch (PDOException $erro) {
    die("Erro na conexão: " . $erro->getMessage());
}
?>