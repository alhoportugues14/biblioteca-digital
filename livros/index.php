<?php
include 'conexão.php'; // Conexão com o banco de dados

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_remover'])) {
        // Remover um livro da base de dados
        $id = intval($_POST['id_remover']);
        $stmt = $pdo->prepare("DELETE FROM livro WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } else {
        // Adicionar um livro
        $titulo = $_POST['titulo'];
        $autores = $_POST['autores'];
        $imagem_url = $_POST['imagem_url'];
        $info_link = $_POST['info_link'];
        $descricao = $_POST['descricao'];
        $genero = $_POST['genero'];
        $stock = intval($_POST['stock']);

        // Verificar se o livro já existe na base de dados
        $stmt = $pdo->prepare("SELECT id, stock FROM livro WHERE titulo = :titulo");
        $stmt->execute([':titulo' => $titulo]);
        $livroExistente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($livroExistente) {
            // Se já existe, aumentar o stock
            $novoStock = $livroExistente['stock'] + $stock;
            $updateStmt = $pdo->prepare("UPDATE livro SET stock = :stock WHERE id = :id");
            $updateStmt->execute([
                ':stock' => $novoStock,
                ':id' => $livroExistente['id']
            ]);
        } else {
            // Se não existe, inserir novo livro com o stock escolhido
            $sql = "INSERT INTO livro (titulo, autores, imagem_url, info_link, descricao, genero, stock) 
                    VALUES (:titulo, :autores, :imagem_url, :info_link, :descricao, :genero, :stock)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':autores' => $autores,
                ':imagem_url' => $imagem_url,
                ':info_link' => $info_link,
                ':descricao' => $descricao,
                ':genero' => $genero,
                ':stock' => $stock
            ]);
        }
    }
}

// Função para buscar livros do banco de dados
function getLivros($pdo) {
    $sql = "SELECT * FROM livro";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$livros = getLivros($pdo);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de Livros</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        h1 {
            color: #333;
        }
        .search-box {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"] {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .book-list {
            margin-top: 20px;
        }
        .book-item {
            margin-bottom: 20px;
            padding: 10px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .book-item img {
            max-width: 100px;
            max-height: 150px;
            float: left;
            margin-right: 15px;
        }
        .book-item h3 {
            margin: 0;
        }
        .remove-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        .remove-button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <h1>Pesquisa de Livros</h1>

    <div class="search-box">
        <input type="text" id="bookTitle" placeholder="Nome do livro...">
        <button onclick="searchBooks()">Pesquisar</button>
    </div>

    <div class="book-list" id="bookList"></div>

    <h2>Livros na Biblioteca</h2>
    <div class="book-list">
        <?php foreach ($livros as $livro): ?>
            <div class="book-item">
                <img src="<?php echo $livro['imagem_url']; ?>" alt="<?php echo $livro['titulo']; ?>">
                <h3><?php echo $livro['titulo']; ?></h3>
                <p><strong>Autores:</strong> <?php echo $livro['autores']; ?></p>
                <p><strong>Descrição:</strong> <?php echo $livro['descricao']; ?></p>
                <p><strong>Gênero:</strong> <?php echo $livro['genero']; ?></p>
                <p><strong>Stock:</strong> <?php echo $livro['stock']; ?></p>
                <p><a href="<?php echo $livro['info_link']; ?>" target="_blank">Mais informações</a></p>
                <form method="POST" action="index.php">
                    <input type="hidden" name="id_remover" value="<?php echo $livro['id']; ?>">
                    <button type="submit" class="remove-button">Remover</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
            function traduzirGenero(genero) {
        const traducoes = {
            "Fiction": "Ficção",
            "Nonfiction": "Não ficção",
            "Science Fiction": "Ficção Científica",
            "Fantasy": "Fantasia",
            "Mystery": "Mistério",
            "Thriller": "Suspense",
            "Romance": "Romance",
            "Horror": "Terror",
            "History": "História",
            "Biography": "Biografia",
            "Self-Help": "Autoajuda",
            "Health": "Saúde",
            "Business": "Negócios",
            "Technology": "Tecnologia",
            "Science": "Ciência",
            "Poetry": "Poesia",
            "Comics": "Banda Desenhada",
            "Art": "Arte",
            "Cooking": "Culinária",
            "Travel": "Viagens",
            "Religion": "Religião",
            "Children": "Infantil",
            "Young Adult": "Jovem Adulto",
            "Education": "Educação",
            "Sports": "Desporto",
            "Drama": "Drama"
        };

        return traducoes[genero] || genero; // Se não houver tradução, mantém o original
    }

        function searchBooks() {
            const bookTitle = document.getElementById('bookTitle').value;
            const url = `https://www.googleapis.com/books/v1/volumes?q=${bookTitle}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const books = data.items;
                    const bookList = document.getElementById('bookList');
                    bookList.innerHTML = ''; // Limpar resultados anteriores

                    if (books) {
                        books.forEach(book => {
                            const bookItem = document.createElement('div');
                            bookItem.classList.add('book-item');

                            const title = book.volumeInfo.title || 'Sem título';
                            const authors = book.volumeInfo.authors ? book.volumeInfo.authors.join(', ') : 'Autor desconhecido';
                            const image = book.volumeInfo.imageLinks ? book.volumeInfo.imageLinks.thumbnail : '';
                            const description = book.volumeInfo.description || 'Descrição não disponível';
                            const genres = book.volumeInfo.categories ? book.volumeInfo.categories.join(', ') : 'Gênero não disponível';
                            const infoLink = book.volumeInfo.infoLink || '#';

                            bookItem.innerHTML = `
                                <img src="${image}" alt="${title}">
                                <h3>${title}</h3>
                                <p><strong>Autores:</strong> ${authors}</p>
                                <p><strong>Descrição:</strong> ${description}</p>
                                <p><strong>Gênero:</strong> ${genres}</p>
                                <p><strong>Stock:</strong> 0</p>
                                <form method="POST" action="index.php">
                                    <input type="hidden" name="titulo" value="${title}">
                                    <input type="hidden" name="autores" value="${authors}">
                                    <input type="hidden" name="imagem_url" value="${image}">
                                    <input type="hidden" name="info_link" value="${infoLink}">
                                    <input type="hidden" name="descricao" value="${description}">
                                    <input type="hidden" name="genero" value="${genres}">
                                    <label for="stock">Quantidade:</label>
                                    <input type="number" name="stock" value="1" min="1">
                                    <button type="submit">Adicionar à Biblioteca</button>
                                </form>
                            `;

                            bookList.appendChild(bookItem);
                        });
                    }
                });
        }
    </script>

</body>
</html>
