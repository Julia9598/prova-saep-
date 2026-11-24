<?php
session_start();

if (isset($_GET["sair"])) {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

require_once "conexao.php";

$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":email", $email);
    $consulta->execute();

    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

    if ($usuario && password_verify($senha, $usuario["senha"])) {

        session_regenerate_id(true);

        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["nome"] = $usuario["nome"];
        $_SESSION["ultimo_acesso"] = time();

        header("Location: painel.php");
        exit;

    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <title>Login - Clínica Veterinária</title>

    <style>
        body {
            font-family: Arial;
            background: #e8f0f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
        }

        input, button {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            box-sizing: border-box;
        }

        button {
            background: #287b9b;
            color: white;
            border: none;
            cursor: pointer;
        }

        .erro {
            color: red;
        }
    </style>
</head>

<body>

<div class="login">
    <h2>Clínica Veterinária</h2>
    <h3>Faça seu login</h3>

    <?php if ($erro != ""): ?>
        <p class="erro"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email"
               placeholder="Seu e-mail" required>

        <input type="password" name="senha"
               placeholder="Sua senha" required>

        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>