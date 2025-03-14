<?php
// Inclui o arquivo de conexão com a base de dados
include("conexão.php");

// Inicializa variável de mensagem
$mensagem = "";

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitiza e recolhe os dados enviados
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $snome = mysqli_real_escape_string($conexao, trim($_POST['snome']));
    $email = mysqli_real_escape_string($conexao, trim($_POST['email']));
    $senha = mysqli_real_escape_string($conexao, $_POST['senha']);
    
    // Hash seguro da senha antes de armazenar na base de dados
    $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
    
    // Cria a query SQL para inserir os dados
    $sql = "INSERT INTO utilizadores (nome, snome, email, senha) VALUES ('$nome', '$snome', '$email', '$senha_hashed')";
    
    // Executa a query e verifica se foi bem-sucedida
    if (mysqli_query($conexao, $sql)) {
        
        header("Location: login.php");
        exit();
    } else {
        $mensagem = "Erro ao cadastrar utilizador: " . mysqli_error($conexao);
    }
}

// Fecha a conexão com a base de dados
mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/registo.css">
    <title>Registo</title>
</head>
<body>
    <!-- Exibe a mensagem de sucesso ou erro -->
    <?php if (!empty($mensagem)): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form action="" method="post" onsubmit="return verificarTermos();">
        <h2>Registo de Utilizadores</h2><br/>

        <input type="text" id="nome" name="nome" placeholder="Nome" required><br/>
        <input type="text" id="snome" name="snome" placeholder="Sobrenome" required><br/>
        <input type="email" id="email" name="email" placeholder="Email" required><br/>
        <input type="password" id="senha" name="senha" placeholder="Senha" required><br/><br/>
        
        <!-- Checkbox para aceitar os Termos e Condições -->
        <div class="container">
            <input type="checkbox" id="cbx" name="termos" required>
            <label for="cbx">Aceito os <a href="Termos e Condições de Utilização.pdf" target="_blank">Termos e Condições</a>.</label>
        </div>
        <br/>

        <input type="submit" value="Registar" />
        <a href="login.php"><input type="button" value="Login"/></a>
    </form>  

    <script>
        function verificarTermos() {
            const checkbox = document.getElementById('cbx');
            if (!checkbox.checked) {
                alert("Por favor, aceite os Termos e Condições antes de continuar.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
