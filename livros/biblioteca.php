<?php
include 'conexão.php';

// Consultar livros da base de dados
$sql = "SELECT * FROM livro";
$stmt = $pdo->query($sql);
$livros = $stmt->fetchAll();

foreach ($livros as $livro) {
    echo '<div>';
    echo '<h3>' . $livro['titulo'] . '</h3>';
    echo '<p><strong>Autores:</strong> ' . $livro['autores'] . '</p>';
    echo '<img src="' . $livro['imagem_url'] . '" alt="' . $livro['titulo'] . '" style="max-width: 100px;">';
    echo '<a href="' . $livro['info_link'] . '" target="_blank">Mais informações</a>';
    echo '</div>';
}
?>
