<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;




class Usuario
{

    public static function selecionaPorId($idLogin)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT * FROM usuario WHERE Idlogin = :idLogin";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idLogin', $idLogin, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
  

       
        return $resultado;
    }
    public static function  selecionaPorIdUsuario($idlogin)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT * FROM usuario WHERE idLogin = :idLogin";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idLogin', $idlogin, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado;
    }
    public static function InsertImage(){

        $con = Connection::getConn();
 
        if(isset($_FILES['pictureUser'])) {
            $pictureUser = $_FILES['pictureUser'];
            $path = "app/Uploads/Archives/";
            $nomeArquivo = $pictureUser['name']; 
            $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
            if($extensao == 'jpg' || $extensao == 'png' || $extensao =='jpeg') {
                $novoNome = uniqid().'.'.$extensao;
                $upload = move_uploaded_file($pictureUser['tmp_name'], $path.$novoNome);
                if($upload) 
                {
                    $sql = "INSERT INTO images (ImgPath) VALUES (:imgPath)";
                    $sql = $con->prepare($sql);
                    $sql->bindValue(':imgPath', $novoNome, PDO::PARAM_STR);
                    $sql->execute();
                    $idImage = $con->lastInsertId();
                    return $idImage;
                } else {
                    echo '<script>
                alert("Falha ao fazer upload de arquivo.");
                
                var ultimaUrlVisitada = document.referrer;

             window.location.href = ultimaUrlVisitada;
                </script>';
                }
            } else {
                echo '<script>
                alert("Extensão de arquivo inválida. Apenas arquivos JPG e PNG são permitidos.");
               
                var ultimaUrlVisitada = document.referrer;

                window.location.href =ultimaUrlVisitada;
                </script>';
            exit();
            }
        }
    }
    
    public static function generateToken($length = 20) {
        return bin2hex(random_bytes($length / 2));
    }
    
    public static function InsertUser()
    {
        $con = Connection::getConn();
        try {
            // Gerar token e tempo de criação
            $token = self::generateToken();
            $token_time = date('Y-m-d H:i:s');
            $idImage = self::InsertImage();
    
            // Obter e sanitizar dados do usuário
            $nomeUsuario = ucfirst(trim(htmlspecialchars(filter_input(INPUT_POST, 'nomeUsuario'), ENT_QUOTES, 'UTF-8')));
            $emailUsuario = trim(filter_input(INPUT_POST, 'emailUsuario',));
            $senhaUsuario = trim($_POST['senhaUsuario']);
        
            $min_length = 8;
            $max_length = 20;
    
            // Validação de senha
            if (strlen($senhaUsuario) < $min_length || strlen($senhaUsuario) > $max_length) {
                echo '<script>
                    alert("Senha deve ter entre ' . $min_length . ' e ' . $max_length . ' caracteres");
                    window.location.href = "http://amigos4patas.com/?pagina=Cadastro";
                    </script>';
                exit();
            }
    
            // Hash da senha
            $hashSenha = password_hash($senhaUsuario, PASSWORD_DEFAULT);
    
            // Verificação se o email já está cadastrado
            $sql = "SELECT * FROM usuario WHERE user_email = :user_email or nomeUsuario = :nomeUsuario";
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':nomeUsuario', $nomeUsuario, PDO::PARAM_STR);
            $stmt->bindValue(':user_email', $emailUsuario, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (empty($resultado)) {
                // Inserir novo usuário
                $sql = "INSERT INTO usuario (user_email, user_senha, user_dataCreate, nomeUsuario, idImage, StatusConta, token, token_time )
                        VALUES (:user_email, :user_senha, NOW(), :nomeUsuario, :idImage, 'pendente', :token, :token_time)";
                $stmt = $con->prepare($sql);
                $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
                
                $stmt->bindValue(':user_email', $emailUsuario, PDO::PARAM_STR);
                $stmt->bindValue(':user_senha', $hashSenha, PDO::PARAM_STR);
                $stmt->bindValue(':nomeUsuario', $nomeUsuario, PDO::PARAM_STR);
                $stmt->bindValue(':token', $token, PDO::PARAM_STR);
                $stmt->bindValue(':token_time', $token_time, PDO::PARAM_STR);
                $stmt->execute();
    
               
                $user = $con->lastInsertId();
    
              
                self::sendVerificationEmail($emailUsuario, $nomeUsuario, $token);
    
                return ['idLogin' => $user, 'idImage' => $idImage];
            } else {
                echo '<script>
                    alert("Email ou nome usuário já cadastrado");
                    window.location.href = "http://amigos4patas.com/?pagina=Cadastro";
                    </script>';
                exit();
            }
        } catch (Exception $e) {
            echo '<script>
                    alert("Erro ao inserir usuário");
                    window.location.href = "http://amigos4patas.com/?pagina=Cadastro";
                    </script>';
        }
    }
    
private static function sendVerificationEmail($email, $name, $token)
{
    $mail = new PHPMailer(true);
    try {
        $verificationLink = "http://amigos4patas.com/?pagina=ValidaConta&token=" . $token;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreplyamigos4patas@gmail.com';
        $mail->Password = 'nrju xaij jdwk tbez';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('noreplyamigos4patas@gmail.com', 'Amigo4Patas');
        $mail->addAddress($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Bem vindo!';
        $mail->Body = "Clique no link para autenticar a conta: <a href=\"$verificationLink\">$verificationLink</a>";

        $mail->send();
    } catch (Exception $e) {
         echo '<script>
        alert("Erro inesperado");
        window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha";
                </script>' ;
    }
}



public static function TokenValidation()
{
    try {   
        $con = Connection::getConn();
        // Suponha que você tenha recebido o token via GET
        $token = $_GET['token'];

        // Recuperar informações do usuário com o token
        $stmt = $con->prepare("SELECT idLogin, token_time FROM usuario WHERE token = ?");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user) {
            $tokenTime = new DateTime($user['token_time']);
            $currentTime = new DateTime();
            $interval = $currentTime->diff($tokenTime);
    
            // Verificar se o token é válido por menos de 24 horas
                if ($interval->h < 24) {
                    // Atualizar o status da conta para 'autenticado'
                    $stmt = $con->prepare("UPDATE usuario SET StatusConta = 'autenticado', token = NULL, token_time = NULL WHERE idLogin = ?");
                    $stmt->execute([$user['idLogin']]);
                    
                } else {
                    echo '<script>
                    alert("Token expirado");
                    window.location.href = "http://amigos4patas.com/?pagina=Home";
                    </script>';
                    
                }
        } else {
                echo '<script>
                alert("Token Invalido");
                window.location.href = "http://amigos4patas.com/?pagina=Home";
                </script>';
        }
    } catch (Exception $e) {
        echo '<script>
        alert("Erro inesperado");
        window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha";
                </script>' ;
    }
}

   

    


public static function Login()
{
    session_start();
   
    $con = Connection::getConn();
    
    if (isset($_POST['login']) && isset($_POST['senha'])) {
        $login = $_POST['login']; 
        $senha = $_POST['senha'];

        // Buscar o usuário pelo email ou nome de usuário
        $sql = "SELECT * FROM usuario WHERE user_email = :loginUser";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':loginUser', $login, PDO::PARAM_STR);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if($userData){
            // Verificar a senh
            if (password_verify($senha, $userData['user_senha'])) {
                // Verificar o status da conta
                if ($userData['StatusConta'] === 'autenticado') {
                   
                    // A conta está autenticada
                    $sql = "SELECT * FROM usuario JOIN canil ON usuario.idLogin = canil.idLogin WHERE usuario.idLogin = :idLogin";
                    $stmt = $con->prepare($sql);
                    $stmt->bindValue(':idLogin', $userData['idLogin'], PDO::PARAM_STR);
                    $stmt->execute();
                    $userDataCanil = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!isset($userDataCanil['idCanil'])) {
                        // Se for um usuário comum
                        $_SESSION['idLogin'] = $userData['idLogin'];
                        $_SESSION['nomeUsuario'] = $userData['nomeUsuario'];
                        echo '<script>
                            window.location.href = "http://amigos4patas.com/?pagina=Home";
                        </script>';
                    } else {

                        $_SESSION['idCanilUser'] = $userDataCanil['idCanil'];
                        $_SESSION['nomeUsuario'] = $userDataCanil['nomeUsuario'];
                        echo '<script>
                            window.location.href = "http://amigos4patas.com/?pagina=Home";
                        </script>';
                    }
                } else {
                    echo '<script>
                    alert("Conta não verificada. Por favor, verifique seu email.");
                    window.location.href = "http://amigos4patas.com/?pagina=Home";
                </script>';
                }
            } else {
                echo '<script>
                alert("Senha incorreta.");
                window.location.href = "http://amigos4patas.com/?pagina=Home";
            </script>';
            }
        } else {
            echo '<script>
                alert("Usuário não encontrado.");
                window.location.href = "http://amigos4patas.com/?pagina=Home";
            </script>';
        }
    }
}

    
    public static function MostrarImage($idLogin)
    {
        $con = Connection::getConn();

        $sql=" SELECT usuario.idLogin, images.imgpath , usuario.nomeUsuario FROM usuario JOIN images ON usuario.idImage = images.idImage WHERE usuario.idLogin = :idLogin ";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idLogin', $idLogin, PDO::PARAM_STR);
        $stmt->execute();
        $img = $stmt->fetch(PDO::FETCH_ASSOC);
      
        return $img;

    }
  

    public static function SendRecuperaSenha($email,$token)
    {
        $mail = new PHPMailer(true);
        try {
    
            $verificationLink = "http://amigos4patas.com/?pagina=RecuperarSenha&metodo=UpdateUserPassword&token=".$token;
            
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'noreplyamigos4patas@gmail.com';
            $mail->Password = 'nrju xaij jdwk tbez';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('noreplyamigos4patas@gmail.com', 'Amigo4Patas');
            $mail->addAddress($email);
    
            $mail->isHTML(true);
            $mail->Subject = 'Redefinir senha';
         
            $mail->Body = "Clique no link para alterar a senha da conta: <a href=\"$verificationLink\">$verificationLink</a>";
            $mail->send();
        } catch (Exception $e) {
            echo '<script>
            alert("Error");
            window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha";
                    </script>';
            
        }
    }

    public static function RecuperaSenha() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["emailUsuario"])) {
            $con = Connection::getConn(); 
            $email = $_POST["emailUsuario"];
    
            $stmt = $con->prepare("SELECT idLogin FROM usuario WHERE user_email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $token = self::generateToken();
                $stmt = $con->prepare("UPDATE usuario SET token = ? WHERE idLogin = ?");
                $stmt->execute([$token, $user['idLogin']]);
               
               
                self::SendRecuperaSenha($email, $token);
            
            } else {
                echo '<script>
                alert("O email fornecido nao esta associado a nenhuma conta");
                window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha";
                        </script>';
            }
        } else {
            
            echo '<script>
                alert("Por favor, forneça um email válido.);
                window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha";
                        </script>';
        }
    }
    
    public static function UpdateSenha() {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
               
                $con = Connection::getConn(); 
            
                $token = htmlspecialchars(trim($_GET['token']), ENT_QUOTES, 'UTF-8');
                $senhaUsuario = trim($_POST['SenhaUsuario']);
                
          
                $min_length = 8;
                $max_length = 20;
            
                if (strlen($senhaUsuario) < $min_length || strlen($senhaUsuario) > $max_length) {
                    echo '<script>
                        alert("Senha muito curta ou muito longa");
                        var urlParams = new URLSearchParams(window.location.search);
                        var token = urlParams.get("token");
                        window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha&metodo=UpdateUserPassword&token=" + token;
                    </script>';
                    exit();
                }
            
              
                $hashSenha = password_hash($senhaUsuario, PASSWORD_DEFAULT);
            
              
                try {
                    $con->beginTransaction();
                    
                   
                    $sql = "UPDATE usuario SET user_senha = :user_senha WHERE token = :token";
                    $stmt = $con->prepare($sql);
                    $stmt->bindValue(':user_senha', $hashSenha, PDO::PARAM_STR);
                    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
                    $stmt->execute();
            
                   
                    $sql = "UPDATE usuario SET token = NULL WHERE token = :token";
                    $stmt = $con->prepare($sql);
                    $stmt->bindValue(':token', $token, PDO::PARAM_STR);
                    $stmt->execute();

                    $con->commit();
            
                    echo '<script>
                        alert("Senha atualizada com sucesso!");
                        window.location.href = "http://amigos4patas.com/?pagina=Login";
                    </script>';
                } catch (PDOException $e) {
                   
                    $con->rollBack();
                    echo '<script>
                        alert("Erro ao atualizar a senha");
                        var urlParams = new URLSearchParams(window.location.search);
                        var token = urlParams.get("token");
                        window.location.href = "http://amigos4patas.com/?pagina=RecuperarSenha&metodo=UpdateUserPassword&token=" + token;
                    </script>';
                }
}

    }
    public static function SelectUsuario($idLogin, $idCanilUser)
    {
        $con = Connection::getConn();
        $resultado = null;
    
        if(isset($idCanilUser))
        {
            $sql = "SELECT * FROM canil WHERE IdCanil = :idCanil";
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':idCanil', $idCanilUser, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        else if(isset($idLogin)){
            $sql = "SELECT * FROM usuario WHERE IdLogin = :idLogin";
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':idLogin', $idLogin, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    
        return $resultado;
    }
    
    
   
    
    public static function UpdateUser($idLogin)
{
    $con = Connection::getConn();

    if (isset($_POST['UpdateUser'])) {
        $nomeUsuario = $_POST['nomeUsuario'];
        $idImagePerfil = null;
        
       
        $sql = "UPDATE usuario SET nomeUsuario = :nomeUsuario";
        
       
        if ($_FILES['pictureUser']['error'] === UPLOAD_ERR_OK) {
            $idImagePerfil = self::InsertImage();
            $sql .= ", idImage = :idImage";
        }
        
      
        $sql .= " WHERE idLogin = :idLogin";
        

        $stmt = $con->prepare($sql);
        
       
        $stmt->bindValue(':nomeUsuario', $nomeUsuario, PDO::PARAM_STR);
        $stmt->bindValue(':idLogin', $idLogin, PDO::PARAM_STR);
        
        if ($idImagePerfil !== null) {
            $stmt->bindValue(':idImage', $idImagePerfil, PDO::PARAM_INT);
        }
        
        // Executa o statement
        $stmt->execute();
    }
}

}