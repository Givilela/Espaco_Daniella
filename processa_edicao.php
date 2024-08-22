<?php
include('conexao.php'); // Inclui a conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitiza e recupera os valores do formulário
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $descricao = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING);

    $query = "UPDATE procedimentos SET nome=?, descricao=?";
    $params = [$nome, $descricao];

    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $arquivo = $_FILES['imagem'];
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif'];

        // Valida o tipo do arquivo
        if (in_array($arquivo['type'], $tiposPermitidos)) {
            $diretorio = "img/";
            $caminhoImagem = $diretorio . basename($arquivo["name"]);

            // Move o arquivo enviado para o diretório desejado
            if (move_uploaded_file($arquivo["tmp_name"], $caminhoImagem)) {
                $query .= ", imagem=?";
                $params[] = $caminhoImagem;
            } else {
                echo "Erro ao mover o arquivo.";
                exit;
            }
        } else {
            echo "Tipo de arquivo não permitido.";
            exit;
        }
    }

    $query .= " WHERE id=?";
    $params[] = $id;

    // Prepara e executa a instrução SQL
    $stmt = $conexao->prepare($query);
    $stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);

    // Verifica se a atualização foi bem-sucedida
    if ($stmt->execute()) {
        echo "Procedimento atualizado com sucesso!";
        // Opcionalmente, redireciona para a página de administração
        // header("Location: adm.html");
    } else {
        echo "Erro ao atualizar: " . $stmt->error;
    }

    $stmt->close();
    $conexao->close();
}
?>
