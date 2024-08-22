<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

    $query = "UPDATE procedimentos SET nome=?, descricao=?";
    $params = [$nome, $descricao];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $arquivo = $_FILES['imagem'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($arquivo['type'], $allowedTypes)) {
            $diretorio = "img/";
            $caminhoImagem = $diretorio . basename($arquivo["name"]);

            if (move_uploaded_file($arquivo["tmp_name"], $caminhoImagem)) {
                $query .= ", imagem=?";
                $params[] = $caminhoImagem;
            } else {
                echo "Falha ao mover o arquivo.";
                exit;
            }
        } else {
            echo "Tipo de arquivo não permitido.";
            exit;
        }
    }

    $query .= " WHERE id=?";
    $params[] = $id;

    $stmt = $conexao->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    if ($stmt->execute()) {
        echo "Procedimento atualizado com sucesso!";
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }
}
?>
