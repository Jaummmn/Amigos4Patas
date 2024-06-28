<?php



class Postagem
{
    public static function InsertPostagem($idUsuario)
    {
        $con = Connection::getConn();
    
        if (isset($_POST['insertPostagem'])) {
           
            if (isset($_POST['textoPostagem']) && isset($_POST['tituloPostagem'])) {
                $textoPostagem = $_POST['textoPostagem'];
                $tituloPostagem = $_POST['tituloPostagem'];
            } else {
            
                echo "Erro: Os campos título e texto da postagem são obrigatórios.";
                return;
            }
    
            $idImage = null;
            if (isset($_FILES['pictureUser']) && $_FILES['pictureUser']['error'] !== UPLOAD_ERR_NO_FILE) {
                $idImage = Usuario::InsertImage();
            }
    
            $statusPostagem = 'padrao'; 
    
            $sql = "INSERT INTO postagem (textoPostagem, tituloPostagem, idUsuarioPostagem, IdImagePostagem, statusPostagem) 
                    VALUES (:textoPostagem, :tituloPostagem, :idUsuario, :idImage, :statusPostagem)";
            
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':textoPostagem', $textoPostagem, PDO::PARAM_STR);
            $stmt->bindValue(':tituloPostagem', $tituloPostagem, PDO::PARAM_STR);
            $stmt->bindValue(':idUsuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
            $stmt->bindValue(':statusPostagem', $statusPostagem, PDO::PARAM_STR);
            
            $stmt->execute();
           
          
         
        }
    }

    public static function SelecPostagem($idPostagem)
    {  
        $con = Connection::getConn();
        if(isset($idPostagem))
        {
            $sql = "SELECT * FROM postagem_padrao WHERE IdPostagem = :idPostagem";
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':idPostagem', $idPostagem, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado;
        }
    }
    public static function UpdatePostagem($idPostagem)
    {
        $con = Connection::getConn();
    
        if (isset($_POST['updatePostagem'])) {
            // Verifica se os campos textoPostagem e tituloPostagem estão definidos
            if (isset($_POST['textoPostagem']) && isset($_POST['tituloPostagem'])) {
                $textoPostagem = $_POST['textoPostagem'];
                $tituloPostagem = $_POST['tituloPostagem'];
            } else {
                // Tratar o caso onde os campos não estão definidos
                echo "Erro: Os campos título e texto da postagem são obrigatórios.";
                return;
            }
    
            $idImage = null;
            $updateImageSQL = "";
            if (isset($_FILES['pictureUser']) && $_FILES['pictureUser']['error'] !== UPLOAD_ERR_NO_FILE) {
                $idImage = Usuario::InsertImage();
                $updateImageSQL = ", IdImagePostagem = :idImage";
            }
    
            $sql = "UPDATE postagem 
                    SET textoPostagem = :textoPostagem, tituloPostagem = :tituloPostagem" . $updateImageSQL . " 
                    WHERE idPostagem = :idPostagem";
    
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':textoPostagem', $textoPostagem, PDO::PARAM_STR);
            $stmt->bindValue(':tituloPostagem', $tituloPostagem, PDO::PARAM_STR);
            $stmt->bindValue(':idPostagem', $idPostagem, PDO::PARAM_INT);
    
            if ($idImage !== null) {
                $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
            }
    
            $stmt->execute();
            echo  ' <script>
                            window.location.href = "http://amigos4patas.com/?pagina=feed";
                        </script>';
        }
    }
       public static function excluirPorId($idPostagem)
    {
        $con = Connection::getConn();
    
        if (isset($_POST)) {
            $sql = "UPDATE postagem 
                    SET statusPostagem = 'arquivado' 
                    WHERE idPostagem = :idPostagem";
    
            $stmt = $con->prepare($sql);
            
            $stmt->bindValue(':idPostagem', $idPostagem, PDO::PARAM_INT);
        
            $stmt->execute();
          
        }
    }

    
    
    public static function MostrarImagePostagem($idPostagem)
    {
        $con = Connection::getConn();

        $sql=" SELECT postagem_padrao.idPostagem, images.imgpath FROM postagem_padrao JOIN images ON postagem_padrao.idImagePostagem = images.idImage WHERE postagem_padrao.idPostagem = :idPostagem ";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idPostagem', $idPostagem, PDO::PARAM_STR);
        $stmt->execute();
        $img = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $img;

    }
    
    
    public static function SelecionarPostagens()
    {
    
        $con= Connection::getConn();
        $sql ="SELECT * FROM `postagem_padrao` ORDER BY `postagem_padrao`.`idPostagem` DESC";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        return $resultado;
    }
  
    public static function selecionaPostagemUsuario($idLogin)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT postagem_padrao.idPostagem, postagem_padrao.tituloPostagem, postagem_padrao.textoPostagem, postagem_padrao.idImagePostagem, usuario.nomeUsuario
                FROM postagem_padrao
                JOIN usuario ON usuario.idLogin = postagem_padrao.idUsuarioPostagem 
                WHERE postagem_padrao.idUsuarioPostagem = :idLogin";
        
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idLogin', $idLogin, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $resultado;
    }
    
}
