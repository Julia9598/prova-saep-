<?php
require_once "protecao.php";
require_once "conexao.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE agendamentos
            SET data_agendamento = :data,
                horario = :horario
            WHERE id_agendamento = :id";

    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ":data" => $_POST["data"],
        ":horario" => $_POST["horario"],
        ":id" => $_POST["id_agendamento"]
    ]);

    header("Location: agendamentos.php");
    exit;
}

$agendamentos = $conexao->query(
    "SELECT agendamentos.*, pets.nome AS nome_pet,
            tutores.nome AS nome_tutor
     FROM agendamentos
     INNER JOIN pets ON agendamentos.id_pet = pets.id_pet
     INNER JOIN tutores ON pets.id_tutor = tutores.id_tutor
     ORDER BY agendamentos.data_agendamento,
              agendamentos.horario"
)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<link rel="stylesheet" href="style.css">
<meta charset="UTF-8">
<title>Agendamentos</title>
</head>
<body>

<h1>Agendamentos da Clínica</h1>
<a href="painel.php">Voltar ao painel</a>

<h2>Lista de agendamentos</h2>

<table border="1" cellpadding="8">
<tr>
    <th>Pet</th>
    <th>Tutor</th>
    <th>Descrição</th>
    <th>Data</th>
    <th>Horário</th>
    <th>Atualizar</th>
</tr>

<?php foreach ($agendamentos as $agendamento): ?>
<tr>
    <td><?= htmlspecialchars($agendamento["nome_pet"]) ?></td>
    <td><?= htmlspecialchars($agendamento["nome_tutor"]) ?></td>
    <td><?= htmlspecialchars($agendamento["descricao"] ?? "") ?></td>

    <td colspan="3">
        <form method="POST">
            <input type="hidden" name="id_agendamento"
                   value="<?= $agendamento["id_agendamento"] ?>">

            <input type="date" name="data"
                   value="<?= $agendamento["data_agendamento"] ?>"
                   required>

            <input type="time" name="horario"
                   value="<?= $agendamento["horario"] ?>"
                   required>

            <button type="submit">Salvar alteração</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>