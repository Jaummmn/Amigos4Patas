<?php


class Canil
{
    public static function selecionaPorIdCanil($idCanil)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT u.nomeUsuario, c.idCanil, c.idLogin,c.canil_descricao,c.doacao,c.canil_localizacao FROM canil c INNER JOIN usuario u 
                ON c.idLogin = u.idLogin WHERE c.idCanil =  :idCanil";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil', $idCanil, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado;
    
    }
    public static function selecionaTotalCanil()
    {
        $con = Connection::getConn();
        
        $sql = "SELECT COUNT(*) AS total_registros, COUNT(*) / COUNT(DISTINCT idCanil) AS total_colunas FROM canil";
        $stmt = $con->prepare($sql);
      
        
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado;
    
    }

    
    public static function selecionaImageCanil($idCanil)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT images.nomePet, canil.idCanil AS idCanil 
        FROM pet 
        JOIN canil ON pet.idCanil = canil.idCanil 
        WHERE pet.idCanil = :idCanil";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil', $idCanil, PDO::PARAM_INT);
        
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
      
     
    
        return $resultado;
    }

    public static function selecionaPetsCanil($idCanil)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT pet_padrao .idPet, pet_padrao .nomePet, canil.idCanil AS idCanil 
        FROM pet_padrao 
        JOIN canil ON pet_padrao.idCanil = canil.idCanil 
        WHERE pet_padrao .idCanil = :idCanil";
                
    
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil', $idCanil, PDO::PARAM_INT);
        $stmt->execute();
    
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        
        return $resultado;
    }
    public static function selecionaPets_Adotados($idCanil)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT pet_adotado .idPet,pet_adotado .nomePet, canil.idCanil AS idCanil 
        FROM pet_adotado
        JOIN canil ON pet_adotado.idCanil = canil.idCanil 
        WHERE pet_adotado .idCanil = :idCanil";
                
    
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil', $idCanil, PDO::PARAM_INT);
        $stmt->execute();
    
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        
        return $resultado;
    }
    public static function selecionaPets_Arquivados($idCanil)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT pet_arquivado .idPet,pet_arquivado .nomePet, canil.idCanil AS idCanil 
        FROM pet_arquivado
        JOIN canil ON pet_arquivado.idCanil = canil.idCanil 
        WHERE pet_arquivado.idCanil = :idCanil";
                
    
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil', $idCanil, PDO::PARAM_INT);
        $stmt->execute();
    
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        
        
        return $resultado;
    }
    public static function selecionarCidade()
    {
        $con = Connection::getConn();
        
        $sql = "SHOW COLUMNS FROM canil WHERE Field = 'canil_localizacao';";
                
    
        $stmt = $con->prepare($sql);
        $stmt->execute();
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
     
        preg_match("/^enum\((.*)\)$/", $resultado['Type'], $matches);
        $resultado = explode(",", $matches[1]);
        
        $resultado = array_map(function($value) {
            return trim($value, "'");
        }, $resultado);
        
        return $resultado;
        
    }
    public static function selecionarCor()
    {
        $con = Connection::getConn();
        
        $sql = "SELECT * FROM cor";
                
    
        $stmt = $con->prepare($sql);
        $stmt->execute();
    
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if(!$resultado){
          
            echo '<script>
            alert("Não foi encontrado nenhum registro no banco");
  
            </script>';
        }
        
        return $resultado;
    }

    public static function selecionarRaca()
    {
        $con = Connection::getConn();
        
        $sql = "SELECT * FROM raca";
                
    
        $stmt = $con->prepare($sql);
        $stmt->execute();
    
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
       
        
        return $resultado;
    }
    public static function selecionarPorte()
    {
        $con = Connection::getConn();
        
        $sql = "SHOW COLUMNS FROM pet WHERE Field = 'pet_porte';";
        $stmt = $con->prepare($sql);
        $stmt->execute();
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
     
        preg_match("/^enum\((.*)\)$/", $resultado['Type'], $matches);
        $resultado = explode(",", $matches[1]);
        
        $resultado = array_map(function($value) {
            return trim($value, "'");
        }, $resultado);
        
        return $resultado;
    }
    public static function selecionarStatus()
    {
        $con = Connection::getConn();
        
        $sql = "SHOW COLUMNS FROM pet WHERE Field = 'statusPet';";
        $stmt = $con->prepare($sql);
        $stmt->execute();
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
     
        preg_match("/^enum\((.*)\)$/", $resultado['Type'], $matches);
        $resultado = explode(",", $matches[1]);
        
        $resultado = array_map(function($value) {
            return trim($value, "'");
        }, $resultado);
        
        return $resultado;
    }

    
    public static function InsertBanner(){
        
        $con = Connection::getConn();
 
        if(isset($_FILES['pictureBanner'])) {
            $pictureBanner = $_FILES['pictureBanner'];
            $nomeArquivoBanner = $pictureBanner['name'];
            $extensaoBanner = strtolower(pathinfo($nomeArquivoBanner, PATHINFO_EXTENSION));
            $path = "app/Uploads/Archives/";
            
            if($extensaoBanner == 'jpg' || $extensaoBanner ==  'png'  || $extensaoBanner == 'jpeg') {
                $novoNomeBanner = uniqid().'.'.$extensaoBanner;
                $upload = move_uploaded_file($pictureBanner['tmp_name'], $path.$novoNomeBanner);
                if($upload){
                    $sql = "INSERT INTO images (ImgPath) VALUES (:imgPath)";
                    $sql = $con->prepare($sql);
                    $sql->bindValue(':imgPath', $novoNomeBanner, PDO::PARAM_STR);
                    $sql->execute();
                    $idBanner = $con->lastInsertId();
                    return $idBanner;
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

             window.location.href = ultimaUrlVisitada;
                </script>';
                    exit();
            }
        }
    }
   
   
   
    
  public static function InsertCanil()
    {
        $con = Connection::getConn();
    if(isset($_POST)){
        $idBanner = self::InsertBanner();
        $Retornoid = Usuario::InsertUser();
    
        if ($Retornoid !== null) {
            
            $canilDescricao = trim($_POST['canil_descricao']);
            $pix = trim($_POST['chavePix']);
            $cidade = trim($_POST['cidade']);
           
             $numContato = trim(htmlspecialchars($_POST['NumUsuario'], ENT_QUOTES, 'UTF-8'));
            
         
            $numContato = preg_replace('/\D/', '', $numContato);
            $idLogin = $Retornoid['idLogin'];
            $idImage = $Retornoid['idImage'];
            
            
            $sql = "INSERT INTO canil (idLogin, canil_descricao, doacao, canil_localizacao, idImageBanner, idImagePerfil, numContato)
                    VALUES (:idLogin, :canilDescricao, :doacao, :canilLocalizacao, :idImageBanner, :idImagePerfil, :numContato)";
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':idLogin', $idLogin, PDO::PARAM_INT);
            $stmt->bindValue(':canilDescricao', $canilDescricao, PDO::PARAM_STR);
            $stmt->bindValue(':doacao', $pix, PDO::PARAM_STR);
            $stmt->bindValue(':canilLocalizacao', $cidade, PDO::PARAM_STR);
            $stmt->bindValue(':idImageBanner', $idBanner, PDO::PARAM_INT);
            $stmt->bindValue(':idImagePerfil', $idImage, PDO::PARAM_INT);
            $stmt->bindValue(':numContato', $numContato, PDO::PARAM_STR);
            
            $stmt->execute();
            $canil = $con->lastInsertId();
            return $canil;

        } else {
            echo '<script>
            alert("Falha ao inserir usuário.");
            window.location.href = "https://amigos4patas.com/?pagina=Cadastro";
            </script>';
                exit();
        }
    }

     
    }
     public static function UpdateCanil($idCanilUser)
    {
        $con = Connection::getConn();
              
                
                if (isset($_POST['UpdateCanil'])) {
          
            $nomeUsuario = ucfirst(trim(htmlspecialchars(filter_input(INPUT_POST, 'nomeUsuario', FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8')));
            $descricaoCanil = ucfirst(trim(htmlspecialchars(filter_input(INPUT_POST, 'canil_descricao', FILTER_SANITIZE_STRING), ENT_QUOTES, 'UTF-8')));
            $chavePix = htmlspecialchars(trim($_POST['chavePix']), ENT_QUOTES, 'UTF-8');
            $cidade = htmlspecialchars(trim($_POST['cidade']), ENT_QUOTES, 'UTF-8');
            $numContato = trim(htmlspecialchars($_POST['numContato'], ENT_QUOTES, 'UTF-8'));
            
         
            $numContato = preg_replace('/\D/', '', $numContato);
            
            // Agora $numContato contém apenas números

        
          
            $sql = "UPDATE canil c INNER JOIN usuario u 
                    ON c.idLogin = u.idLogin 
                    SET u.nomeUsuario = :nomeCanil, 
                        c.canil_descricao = :canil_descricao,
                        c.doacao = :doacao, 
                        c.canil_localizacao = :canil_localizacao,
                        c.numContato = :NumContato";
        
            if ($_FILES['pictureUser']['error'] === UPLOAD_ERR_OK) {
                $idImagePerfil = Usuario::InsertImage();
                $sql .= ", c.idImagePerfil = :idImagePerfil";
            }
        
            if ($_FILES['pictureBanner']['error'] === UPLOAD_ERR_OK) {
                $idImageBanner = Canil::InsertBanner();
                $sql .= ", c.idImageBanner = :idImageBanner";
            }
        
            $sql .= " WHERE c.idCanil = :idCanil";
        
          
            $stmt = $con->prepare($sql);
        
            $stmt->bindValue(':NumContato', $numContato, PDO::PARAM_STR);
            $stmt->bindValue(':nomeCanil', $nomeUsuario, PDO::PARAM_STR);
            $stmt->bindValue(':canil_descricao', $descricaoCanil, PDO::PARAM_STR);
            $stmt->bindValue(':doacao', $chavePix, PDO::PARAM_STR);
            $stmt->bindValue(':canil_localizacao', $cidade, PDO::PARAM_STR);
            $stmt->bindValue(':idCanil', $idCanilUser, PDO::PARAM_INT);
            
            if (isset($idImagePerfil)) {
                $stmt->bindValue(':idImagePerfil', $idImagePerfil, PDO::PARAM_INT);
            }
            if (isset($idImageBanner)) {
                $stmt->bindValue(':idImageBanner', $idImageBanner, PDO::PARAM_INT);
            }
        
            // Execute a declaração
            try {
                $stmt->execute();
                
            } catch (PDOException $e) {
             echo '<script>
                    alert("Erro ao atualizar dados");
                    window.location.href = "https://amigos4patas.com/?pagina=Canil&idCanil=' . $idCanilUser . '";
                  </script>';

            }
        }

      
    }
    public static function MostrarImageCanil($idCanil)
    {
        $con = Connection::getConn();

        $sql="SELECT canil.idCanil , images.imgpath FROM canil JOIN images ON canil.idImagePerfil = images.idImage WHERE canil.idCanil = :idCanil";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil',$idCanil, PDO::PARAM_STR);
        $stmt->execute();
        $img = $stmt->fetch(PDO::FETCH_ASSOC);
      
        return $img;

    }
    
    public static function MostrarImageCanilBanner($idCanil)
    {
        $con = Connection::getConn();

        $sql=" SELECT canil.idCanil, images.imgpath FROM canil JOIN images ON canil.idImageBanner = images.idImage WHERE canil.idCanil =:idCanil";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idCanil', $idCanil, PDO::PARAM_STR);
        $stmt->execute();
        $img = $stmt->fetch(PDO::FETCH_ASSOC);
      
        return $img;

    }

}
