<?php
// Inclui o autoload do PHPMailer
require 'PHPMailer-master\src\PHPMailer.php';
require 'PHPMailer-master\src\SMTP.php';
require 'PHPMailer-master\src\Exception.php';

// Carrega a biblioteca do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Conexão com o banco de dados
$servername = "localhost";
$username = "root"; // Usuário do MySQL (padrão do XAMPP)
$password = ""; // Senha do MySQL (padrão do XAMPP)
$dbname = "hilda";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}


// Sanitização dos dados de entrada
$nome = filter_var(trim($_POST["nome"]), FILTER_SANITIZE_STRING);
$data_nascimento = $_POST["data_nascimento"];
$telefoneFormatado = filter_var(trim($_POST["telefone"]), FILTER_SANITIZE_STRING);
$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

// Formatação do telefone
$telefone = preg_replace('/[^0-9]/', '', $telefoneFormatado);

// Validações
if (empty($nome) || empty($data_nascimento) || empty($telefone) || empty($email)) {
    die("Todos os campos são obrigatórios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("E-mail inválido.");
}


// Prepara a query SQL para inserir os dados na tabela
$stmt = $conn->prepare("INSERT INTO usuarios (nome, data_nascimento, celular, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $data_nascimento, $telefone, $email);

if ($stmt->execute()) {
    echo "Cadastro realizado com sucesso!";
    // Criação de uma instância do PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();  // Define que vamos usar SMTP
        $mail->Host = 'smtp.gmail.com';  // Servidor SMTP do Gmail
        $mail->SMTPAuth = true;  // Ativa a autenticação SMTP
        $mail->Username = 'hildinha698@gmail.com';
        $mail->Password = ''; //senha google
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Criptografia TLS
        $mail->Port = 587;  // Porta para TLS

        // Defina o charset para UTF-8
        $mail->CharSet = 'UTF-8';

        // Remetente
        $mail->setFrom('hildinha698@gmail.com', 'Contato dev Hilda');
        $mail->addAddress($email, $nome);  // Adiciona o destinatário

        // Conteúdo do e-mail
        $mail->isHTML(true);  // Define o formato do e-mail como HTML
        $mail->Subject = 'Confirmação de Cadastro - Contato dev Hilda';
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <p style='font-size: 18px;'>Olá $nome, </p>
                <p>Seu cadastro foi realizado com sucesso!</p>
                <p><strong>Obrigada por se cadastrar no nosso site, em breve entraremos em contato para futuras negociações.</strong></p>
                <p>Para contatos imediatos, entre em contato pelo telefone: (89) 9 8809-4082.</p>
                <p>Você pode ver mais do meu trabalho e projetos no meu 
                    <a href='https://github.com/hiildh' style='color: #0066cc;' target='_blank'>GitHub</a>.
                </p>
                <p>Atenciosamente, <br> Hilda Helena Silva Alencar Luz</p>
                <img src='https://i.pinimg.com/1200x/33/cb/16/33cb16051f94c0205c96d088a93991e9.jpg' alt='paisagem' style='width: 200px; height: auto;'>
            </div>
        ";

        // Envia o e-mail
        $mail->send();
        echo "<br>Cadastro realizado com sucesso! E-mail de confirmação enviado.";

    } catch (Exception $e) {
        echo "Erro ao enviar o e-mail. Erro: {$mail->ErrorInfo}";
    }
    
} else {
    echo "Erro ao cadastrar: " . $stmt->error;
}
$stmt->close();
$conn->close();

?>
