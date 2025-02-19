<?php
// config.php - Configuração do Banco de Dados
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'sistema_cadastro';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>

<!-- cadastro.php - Formulário de Cadastro -->
<form action="cadastro.php" method="POST">
    <input type="text" name="usuario" placeholder="Usuário" required>
    <input type="email" name="email" placeholder="E-mail" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Cadastrar</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config.php';
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES ('$usuario', '$email', '$senha')";
    if ($conn->query($sql) === TRUE) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro: " . $conn->error;
    }
}
?>

<!-- login.php - Formulário de Login -->
<form action="login.php" method="POST">
    <input type="text" name="usuario" placeholder="Usuário" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <button type="submit">Entrar</button>
</form>

<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require 'config.php';
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($senha, $row['senha'])) {
            $_SESSION['usuario'] = $usuario;
            header("Location: dashboard.php");
        } else {
            echo "Senha incorreta!";
        }
    } else {
        echo "Usuário não encontrado!";
    }
}
?>

<!-- dashboard.php - Área Protegida -->
<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
?>
Bem-vindo, <?php echo $_SESSION['usuario']; ?>!
<a href="logout.php">Sair</a>

<!-- logout.php -->
<?php
session_start();
session_destroy();
header("Location: login.php");
?>
