<?php
// Inicia a sessão para verificar a autenticação
session_start();

// Verifica se o utilizador está autenticado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Se não estiver autenticado, redireciona para a página de login
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área pessoal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <br><br>
            <h2>Menu</h2>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="logout.php">Sair</a></li>
                <li><a href="#">Ajuda</a></li>
            </ul>
        </aside>

        <!-- Conteúdo Principal -->
        <main class="main-content">
            <button class="toggle-btn" id="toggle-btn">☰</button>
            <header>
                <h1>Bem-vindo à tua área pessoal</h1>
                <p>Aqui pode adicionar informações, gráficos ou dados úteis.</p>
            </header>

            <!-- Secção de Widgets -->
            <section class="widgets">
                <div class="widget">
                    <h3>Widget 1</h3>
                    <p>Este é um espaço onde podes adicionar informações.</p>
                </div>
                <div class="widget">
                    <h3>Widget 2</h3>
                    <p>Pode ser usado para exibir outros dados ou estatísticas.</p>
                </div>
                <div class="widget">
                    <h3>Widget 3</h3>
                    <p>Personaliza esta área para as tuas necessidades.</p>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Seleciona o botão e a sidebar
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');

        // Mostrar a sidebar ao passar o cursor sobre o botão
        toggleBtn.addEventListener('mouseenter', () => {
            sidebar.classList.add('show');
        });

        // Esconder a sidebar ao sair do botão
        toggleBtn.addEventListener('mouseleave', () => {
            sidebar.classList.remove('show');
        });
    </script>
</body>
</html>
