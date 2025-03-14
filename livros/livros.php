<?php
include 'conexão.php';
?>

<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, shrink-to-fit=no" />
  <title>Bookiee - Encontre livros na base de dados</title>
  <meta name="description" content="Encontre livros na base de dados" />
  <link rel="icon" href="icons/favicon.ico" />
  <meta name="theme-color" content="#ffffff" />
  <link href="https://fonts.googleapis.com/css?family=Montserrat:500,600,900&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="index.css" />
</head>

<body>
  <aside>
    <div class="logo">
      <a href="index.php">Bookiee</a>
    </div>

    <nav>
      <ul>
        <li class="subhead">DESCOBRIR</li>
        <li><a class="nav scrolltoview current" href="#search"><span class="icon">🔍</span>Pesquisar</a></li>
        <li><a class="nav scrolltoview" href="#foryou"><span class="icon">💖</span>Para si</a></li>

        <li class="subhead">CATEGORIAS</li>
        <li><a class="nav scrolltoview" href="#fiction" data-genero="Ficção"><span class="icon">👽</span>Ficção</a></li>
        <li><a class="nav scrolltoview" href="#poetry" data-genero="Poesia"><span class="icon">🌈</span>Poesia</a></li>
        <li><a class="nav scrolltoview" href="#fantasy" data-genero="Fantasia"><span class="icon">🌺</span>Fantasia</a></li>
        <li><a class="nav scrolltoview" href="#romance" data-genero="Romance"><span class="icon">💕</span>Romance</a></li>
        <li class="nav trigger"><span class="icon">✨</span>Mais</li>
      </ul>
    </nav>

    <div class="spacer"></div>
    <label class="theme-switch" for="checkbox" title="Modo noturno">
      <input type="checkbox" id="checkbox" aria-label="Modo noturno" />
      <div class="slider round"></div>
    </label>
  </aside>

  <main id="main" class="main">
    <article>
      <section id="search" class="results">
        <div class="flex">
          <input id="search-box" class="search-box" placeholder="Pesquise livros por nome, autor, género, etc ..."
            aria-label="Pesquisar livros" />
          <button class="info" onclick="location.href='index.php'"
            style="margin-left: 24px; padding: 8px 16px;">
            <img src="icons/logo.svg" alt="logo" style="height: 35px;" />
          </button>
        </div>
        <div class="list-book search">
          <div class="prompt">Insira um termo de pesquisa</div>
        </div>
      </section>

      <section id="foryou" class="results">
        <div class="list-book foryou">
          <a class="category" href="#love">
            <div class="book">
              <div class="book-info">
                <h1 class="section-title">Top 100 Diário</h1>
              </div>
            </div>
          </a>
          <a class="category" href="#feminism">
            <div class="book">
              <div class="book-info">
                <h1 class="section-title">Novos lançamentos</h1>
              </div>
            </div>
          </a>

          <a class="category" href="#authors">
            <div class="book">
              <div class="book-info">
                <h1 class="section-title">Autores em destaque</h1>
              </div>
            </div>
          </a>
        </div>
      </section>

      <!-- Seção para exibir os livros filtrados por categoria -->
      <section id="livros" class="results">
        <div class="flex">
          <h1 class="section-title">Livros Disponíveis</h1>
        </div>

        <!-- Lista de livros -->
        <div class="list-book" id="livros-list">
          <?php
          // Consultar todos os livros
          $sql = "SELECT * FROM livro";
          $stmt = $pdo->query($sql);
          $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

          if (empty($livros)) {
              echo '<p>Nenhum livro encontrado.</p>';
          } else {
              foreach ($livros as $livro) {
                  echo '<div class="book-item" data-genero="' . htmlspecialchars($livro['genero']) . '">';
                  echo '<h3>' . htmlspecialchars($livro['titulo']) . '</h3>';
                  echo '<p><strong>Autores:</strong> ' . htmlspecialchars($livro['autores']) . '</p>';
                  echo '<img src="' . htmlspecialchars($livro['imagem_url']) . '" alt="' . htmlspecialchars($livro['titulo']) . '" style="max-width: 100px;">';
                  echo '<p><strong>Gênero:</strong> ' . htmlspecialchars($livro['genero']) . '</p>';
                  echo '<p><strong>Stock:</strong> ' . htmlspecialchars($livro['stock']) . '</p>';
                  echo '<a href="#" class="info-link" 
                            data-titulo="' . htmlspecialchars($livro['titulo']) . '"
                            data-autores="' . htmlspecialchars($livro['autores']) . '"
                            data-genero="' . htmlspecialchars($livro['genero']) . '"
                            data-descricao="' . htmlspecialchars($livro['descricao']) . '"
                            data-stock="' . htmlspecialchars($livro['stock']) . '"
                            data-imagem="' . htmlspecialchars($livro['imagem_url']) . '">Mais informações</a>';
                  echo '</div>';
              }
          }
          ?>
        </div>
      </section>

      <!-- Modal para exibir todas as informações -->
      <div id="modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <img id="modal-imagem" src="" alt="Capa do livro" style="max-width: 100px; margin-bottom: 10px;">
          <h2 id="modal-titulo"></h2>
          <div class="modal-info">
            <p><strong>Autores:</strong> <span id="modal-autores"></span></p>
            <p><strong>Gênero:</strong> <span id="modal-genero"></span></p>
            <p><strong>Stock:</strong> <span id="modal-stock"></span></p>
            <p><strong>Descrição:</strong> <span id="modal-descricao"></span></p>
          </div>
        </div>
      </div>

      <footer id="footer" class="footer">
        <div class="temp"></div>
      </footer>
    </article>
  </main>

  <script>
    // Função para filtrar livros por gênero
    function filtrarLivrosPorGenero(genero) {
      const livros = document.querySelectorAll('.book-item');
      livros.forEach(livro => {
        if (livro.getAttribute('data-genero') === genero) {
          livro.style.display = 'block';
        } else {
          livro.style.display = 'none';
        }
      });
    }

    // Adicionar evento de clique às categorias
    document.querySelectorAll('.nav[data-genero]').forEach(categoria => {
      categoria.addEventListener('click', (e) => {
        e.preventDefault();
        const genero = categoria.getAttribute('data-genero');
        filtrarLivrosPorGenero(genero);
      });
    });

    // Modal (mantido igual)
    const modal = document.getElementById('modal');
    const modalTitulo = document.getElementById('modal-titulo');
    const modalAutores = document.getElementById('modal-autores');
    const modalGenero = document.getElementById('modal-genero');
    const modalStock = document.getElementById('modal-stock');
    const modalDescricao = document.getElementById('modal-descricao');
    const modalImagem = document.getElementById('modal-imagem');
    const closeModal = document.querySelector('.close');

    document.querySelectorAll('.info-link').forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        modalTitulo.textContent = link.getAttribute('data-titulo');
        modalAutores.textContent = link.getAttribute('data-autores');
        modalGenero.textContent = link.getAttribute('data-genero');
        modalStock.textContent = link.getAttribute('data-stock');
        modalDescricao.textContent = link.getAttribute('data-descricao');
        modalImagem.src = link.getAttribute('data-imagem');
        modal.style.display = 'flex';
      });
    });

    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  </script>
</body>

</html>