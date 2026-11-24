<?php
require_once "protecao.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel - Clínica Veterinária</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Clínica Veterinária</h1>

    <h2>Bem-vindo(a), <?php echo htmlspecialchars($_SESSION["nome"]); ?>!</h2>

    <p>Escolha o que deseja fazer:</p>

    <ul>
        <li><a href="tutores.php">Cadastrar e consultar tutores</a></li>
        <li><a href="pets.php">Cadastrar e consultar pets</a></li>
        <li><a href="agendamentos.php">Visualizar agendamentos</a></li>
        <li><a href="sair.php">Sair do sistema</a></li>
    </ul>

</body>
</html>