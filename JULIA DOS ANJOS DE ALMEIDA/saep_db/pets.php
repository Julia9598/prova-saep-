<?php
require_once "protecao.php";
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "INSERT INTO pets (id_tutor, nome, especie, raca)
            VALUES (:id_tutor, :nome, :especie, :raca)";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ":id_tutor" => $_POST["id_tutor"],
        ":nome" => $_POST["nome"],
        ":especie" => $_POST["especie"],
        ":raca" => $_POST["raca"]
    ]);

    header("Location: pets.php");
    exit;
}

$tutores = $conexao->query(
    "SELECT id_tutor, nome FROM tutores ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$pets = $conexao->query(
    "SELECT pets.*, tutores.nome AS nome_tutor
     FROM pets
     INNER JOIN tutores ON pets.id_tutor = tutores.id_tutor
     ORDER BY pets.nome"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<link rel="stylesheet" href="style.css">
<meta charset="UTF-8">
<title>Cadastro de Pets</title>
</head>
<body>

<h1>Cadastro de Pets</h1>
<a href="painel.php">Voltar ao painel</a>

<h2>Novo pet</h2>

<form method="POST">

    <input type="text" name="nome"
           placeholder="Nome do animal" required>

    <input type="text" name="especie"
           placeholder="Espécie" required>

    <input type="text" name="raca"
           placeholder="Raça">

    <select name="id_tutor" required>
        <option value="">Selecione o tutor</option>

        <?php foreach ($tutores as $tutor): ?>
            <option value="<?= $tutor["id_tutor"] ?>">
                <?= htmlspecialchars($tutor["nome"]) ?>
            </option>
        <?php endforeach; ?>

    </select>

    <button type="submit">Cadastrar pet</button>
</form>

<h2>Pets cadastrados</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Nome</th>
    <th>Espécie</th>
    <th>Raça</th>
    <th>Tutor</th>
</tr>

<?php foreach ($pets as $pet): ?>
<tr>
    <td><?= htmlspecialchars($pet["nome"]) ?></td>
    <td><?= htmlspecialchars($pet["especie"] ?? "") ?></td>
    <td><?= htmlspecialchars($pet["raca"] ?? "") ?></td>
    <td><?= htmlspecialchars($pet["nome_tutor"]) ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>