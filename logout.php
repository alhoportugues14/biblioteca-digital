<?php
// Inicia a sessão
session_start();

// Destrói a sessão de autenticação
session_destroy();

// Redireciona para a página de login ou página inicial
header("Location: login.php");
exit;
?>
