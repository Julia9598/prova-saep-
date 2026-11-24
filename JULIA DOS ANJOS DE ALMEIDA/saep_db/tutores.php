<?php
require_once "protecao.php";
require_once "conexao.php";

$chave = "chave_demo_saep_2026";
$mensagem = "";
$editar = null;

// Salvar ou atualizar tutor
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id_tutor"] ?? "";
    $nome = trim($_POST["nome"]);
    $cpf = preg_replace('/\D/', '', $_POST["cpf"]);
    $telefone = trim($_POST["telefone"]);
    $email = trim($_POST["email"]);

    if ($id != "") {
        if ($cpf != "") {
            $sql = "UPDATE tutores SET nome=:nome,
                    cpf_criptografado=AES_ENCRYPT(:cpf,:chave),
                    telefone=:telefone, email=:email
                    WHERE id_tutor=:id";
        } else {
            $sql = "UPDATE tutores SET nome=:nome,
                    telefone=:telefone, email=:email
                    WHERE id_tutor=:id";
        }
    } else {
        $sql = "INSERT INTO tutores
                (nome, cpf_criptografado, telefone, email)
                VALUES (:nome, AES_ENCRYPT(:cpf,:chave),
                :telefone, :email)";
    }

    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(":nome", $nome);
    $stmt->bindValue(":telefone", $telefone);
    $stmt->bindValue(":email", $email);

    if ($id != "") {
        $stmt->bindValue(":id", $id);
    }

    if ($id == "" || $cpf != "") {
        $stmt->bindValue(":cpf", $cpf);
        $stmt->bindValue(":chave", $chave);
    }

    $stmt->execute();
    header("Location: tutores.php");
    exit;
}

// Buscar tutor para edição
if (isset($_GET["editar"])) {
    $stmt = $conexao->prepare(
        "SELECT id_tutor, nome, telefone, email
         FROM tutores WHERE id_tutor = ?"
    );
    $stmt->execute([$_GET["editar"]]);
    $editar = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Pesquisa de tutores
$busca = trim($_GET["busca"] ?? "");

$sql = "SELECT id_tutor, nome, telefone, email
        FROM tutores
        WHERE nome LIKE :busca OR email LIKE :busca
        ORDER BY nome";

$stmt = $conexao->prepare($sql);
$stmt->execute([":busca" => "%$busca%"]);
$tutores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<link rel="stylesheet" href="style.css">
<meta charset="UTF-8">
<title>Tutores</title>
</head>
<body>

<h1>Gerenciamento de Tutores</h1>
<a href="painel.php">Voltar ao painel</a>

<h2><?= $editar ? "Editar tutor" : "Cadastrar tutor" ?></h2>

<form method="POST">
    <input type="hidden" name="id_tutor"
           value="<?= htmlspecialchars($editar["id_tutor"] ?? "") ?>">

    <input name="nome" placeholder="Nome" required
           value="<?= htmlspecialchars($editar["nome"] ?? "") ?>">

    <input name="cpf" placeholder="CPF (somente números)"
           maxlength="14" <?= $editar ? "" : "required" ?>>

    <input name="telefone" placeholder="Telefone"
           value="<?= htmlspecialchars($editar["telefone"] ?? "") ?>">

    <input type="email" name="email" placeholder="E-mail"
           value="<?= htmlspecialchars($editar["email"] ?? "") ?>">

    <button type="submit">Salvar tutor</button>
</form>

<h2>Pesquisar tutores</h2>

<form method="GET">
    <input name="busca" placeholder="Digite o nome ou e-mail"
           value="<?= htmlspecialchars($busca) ?>">
    <button type="submit">Buscar</button>
</form>

<h2>Lista de tutores</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Nome</th>
    <th>Telefone</th>
    <th>E-mail</th>
    <th>Ação</th>
</tr>

<?php foreach ($tutores as $tutor): ?>
<tr>
    <td><?= htmlspecialchars($tutor["nome"]) ?></td>
    <td><?= htmlspecialchars($tutor["telefone"] ?? "") ?></td>
    <td><?= htmlspecialchars($tutor["email"] ?? "") ?></td>
    <td>
        <a href="tutores.php?editar=<?= $tutor["id_tutor"] ?>">
            Editar
        </a>
    </td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>