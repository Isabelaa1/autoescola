<?php

require "config.php";

header("Content-Type: application/json; charset=utf-8");

$rota = $_GET["rota"] ?? "teste";

function teste() {
    echo json_encode([
        "mensagem" => "API da autoescola funcionando!"
    ]);
}

function listarAlunos($con) {

    try {
        $stmt = $con->query("SELECT * FROM Alunos");

        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($alunos);

    } catch (PDOException $e) {

        http_response_code(500);

        echo json_encode([
            "erro" => "Erro ao listar alunos."
        ]);
    }
}

function adicionarAluno($con) {

    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefone = $_POST["telefone"] ?? "";
    $data_nascimento = $_POST["data_nascimento"] ?? "";

    if (
        empty($nome) ||
        empty($email) ||
        empty($data_nascimento)
    ) {

        http_response_code(400);

        echo json_encode([
            "erro" => "Preencha nome, email e data de nascimento."
        ]);

        return;
    }

    try {

        $stmt = $con->prepare(
            "INSERT INTO Alunos
            (nome, email, telefone, data_nascimento)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $nome,
            $email,
            $telefone,
            $data_nascimento
        ]);

        http_response_code(201);

        echo json_encode([
            "mensagem" => "Aluno cadastrado com sucesso!",
            "id" => $con->lastInsertId(),
            "aluno" => [
                "nome" => $nome,
                "email" => $email,
                "telefone" => $telefone,
                "data_nascimento" => $data_nascimento
            ]
        ]);

    } catch (PDOException $e) {

        if ($e->getCode() == 23000) {

            http_response_code(409);

            echo json_encode([
                "erro" => "Este e-mail já está cadastrado."
            ]);

        } else {

            http_response_code(500);

            echo json_encode([
                "erro" => "Erro ao cadastrar aluno."
            ]);
        }
    }
}
function listarInstrutores($con) {

    try {
        $stmt = $con->query("SELECT * FROM Instrutores");

        $instrutores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($instrutores);

    } catch (PDOException $e) {

        http_response_code(500);

        echo json_encode([
            "erro" => "Erro ao listar instrutores."
        ]);
    }
}

function adicionarInstrutor($con) {

    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";
    $telefone = $_POST["telefone"] ?? "";
    $categoria_cnh = $_POST["categoria_cnh"] ?? "";

    if (
        empty($nome) ||
        empty($email) ||
        empty($categoria_cnh)
    ) {

        http_response_code(400);

        echo json_encode([
            "erro" => "Preencha nome, email e categoria da CNH."
        ]);

        return;
    }

    try {

        $stmt = $con->prepare(
            "INSERT INTO Instrutores
            (nome, email, telefone, categoria_cnh)
            VALUES (?, ?, ?, ?)"
        );

        $stmt->execute([
            $nome,
            $email,
            $telefone,
            $categoria_cnh
        ]);

        http_response_code(201);

        echo json_encode([
            "mensagem" => "Instrutor cadastrado com sucesso!",
            "id" => $con->lastInsertId(),
            "instrutor" => [
                "nome" => $nome,
                "email" => $email,
                "telefone" => $telefone,
                "categoria_cnh" => $categoria_cnh
            ]
        ]);

    } catch (PDOException $e) {

        if ($e->getCode() == 23000) {

            http_response_code(409);

            echo json_encode([
                "erro" => "Este e-mail já está cadastrado."
            ]);

        } else {

            http_response_code(500);

            echo json_encode([
                "erro" => "Erro ao cadastrar instrutor."
            ]);
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST"
    && $rota === "alunos/create") {

    adicionarAluno($con);

} elseif ($_SERVER["REQUEST_METHOD"] === "GET"
    && $rota === "alunos") {

    listarAlunos($con);

} elseif ($_SERVER["REQUEST_METHOD"] === "POST"
    && $rota === "instrutores/create") {

    adicionarInstrutor($con);

} elseif ($_SERVER["REQUEST_METHOD"] === "GET"
    && $rota === "instrutores") {

    listarInstrutores($con);

} else {

    teste();
}
?>