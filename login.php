<?php
session_start();
include 'conexão.php'; // Inclui a conexão MySQLi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$conexao) {
        die("Erro de conexão: " . mysqli_connect_error());
    }

    $email = trim($_POST['email']);
    $password = $_POST['senha'];
    $tipo=$_POST['Tipo'];
    // Prepara a consulta para evitar SQL Injection
    $sql = "SELECT id, senha, Tipo FROM utilizadores WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            mysqli_stmt_bind_result($stmt, $id, $hashed_password, $tipo);
            mysqli_stmt_fetch($stmt);

            // Verifica se a senha foi corretamente encriptada na base de dados
            if (!password_verify($password, $hashed_password)) {
                $erro = "Senha incorreta!";
            } else {
                // Inicia a sessão e define as variáveis de sessão
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $email;
                $_SESSION['logged_in'] = true;
                
                // Redireciona para a página correspondente ao tipo de utilizador
                if ($tipo == 'Admin') {
                    header("Location: dashboard_AD.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            }
        } else {
            $erro = "Email não encontrado!";
        }
        mysqli_stmt_close($stmt);
    } else {
        $erro = "Erro na preparação da consulta: " . mysqli_error($conexao);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/login.css">
    <script>
        function mostrarPopup(mensagem) {
            document.getElementById('popup-mensagem').innerText = mensagem;
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function fecharPopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>
</head>
<body>

    <!-- Overlay para escurecer o fundo -->
    <div id="overlay" class="overlay" style="display: none;"></div>

    <!-- Popup de erro -->
    <div id="popup" class="popup" style="display: none;">
        <p id="popup-mensagem"></p>
        <button onclick="fecharPopup()">Fechar</button>
    </div>

    <form action="" method="POST">
        <h2>Login</h2><br><br>

        <?php if (isset($erro)) { echo "<script>mostrarPopup('$erro');</script>"; } ?>

        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="senha" placeholder="Senha" required><br><br>

        <button type="submit">Entrar</button><br><br>

        <label>Ainda não possui conta? Registe-se aqui:</label>
        <a href="registo.php"><input type="button" value="Registar-se"></a>
    </form>

</body>
</html>
