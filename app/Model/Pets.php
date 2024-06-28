<?php


class Pets
{
    public static function selecionaRecentes()
    {
        $con = Connection::getConn();

        $sql = "SELECT * FROM `pet_padrao` ORDER BY `pet_padrao`.`idPet` DESC; ";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
       

       
        return $resultado;
    }

    public static function selecionaPorId($idpet)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT * FROM pet WHERE idPet = :idPet";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idPet', $idpet, PDO::PARAM_INT);
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
  

        if($resultado) {
            $resultado['castrado'] = ($resultado['castrado'] == 1) ? "Sim" : "Nao";
        } else {
            return null;
        }
       
        return $resultado;
    }

    public static function selecionaTotalPet()
    {
        $con = Connection::getConn();
        
        $sql = "SELECT COUNT(*) AS total_registros_Pets, COUNT(*) / COUNT(DISTINCT idPet) AS total_colunas FROM pet_padrao";
        $stmt = $con->prepare($sql);
      
        
        $stmt->execute();
        
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado;
    
    }
 public static function selecionaTotalPetAdotado()
{
    $con = Connection::getConn();
    
    $sql = "SELECT COUNT(*) AS total_registros_Pets_adotado, COUNT(*) / COUNT(DISTINCT idPet) AS total_colunas FROM pet_adotado;";
    $stmt = $con->prepare($sql);
    
    $stmt->execute();
    
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado;
}


    public static function mostrarInfoPet($idPet)
    {
        $con = Connection::getConn();
        
        $sql = "SELECT pet.nomePet, cor.cor, raca.raca, usuario.nomeUsuario,canil.numContato,canil.canil_localizacao 
        FROM pet JOIN cor ON pet.Idcor = cor.IdCor 
        JOIN canil ON pet.idCanil = canil.idCanil 
        JOIN raca ON pet.idRaca = raca.idRaca 
        JOIN usuario ON usuario.idLogin = canil.idLogin 
        WHERE pet.idPet =  :idPet";
                    
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idPet', $idPet, PDO::PARAM_INT);
        $stmt->execute();
    
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
     
        return $resultado;
    }

    public static function InsertImagePet(){
        $con = Connection::getConn();
 
        if(isset($_FILES['picturePet'])) {
            $pictureUser = $_FILES['picturePet'];
            $path = "app/Uploads/Archives/";
            $nomeArquivo = $pictureUser['name']; 
            $extensao = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
            if($extensao == 'jpg' || $extensao == 'png' || $extensao == 'jpeg'  ) {
                $novoNome = uniqid().'.'.$extensao;
                $upload = move_uploaded_file($pictureUser['tmp_name'], $path.$novoNome);
                if($upload ) {
                    $sql = "INSERT INTO images (ImgPath) VALUES (:imgPath)";
                    $sql = $con->prepare($sql);
                    $sql->bindValue(':imgPath', $novoNome, PDO::PARAM_STR);
                    $sql->execute();
                    $idImage = $con->lastInsertId();
                    return $idImage;
                } else {
                
                      echo '<script>
                alert("Falha ao fazer upload do arquivo.");
                window.location.href = "http://amigos4patas.com/?pagina=AdminCanil";
                </script>';
                }
            } else {
                echo '<script>
                alert("Extensão de arquivo inválida. Apenas arquivos JPG e PNG são permitidos.");
                window.location.href = "http://amigos4patas.com/?pagina=AdminCanil";
                </script>';
            }
        }
    }
    public static function insertPet($idCanilUser)
    {
        $con = Connection::getConn();
        $idImage = self::InsertImagePet();
       try
       {
        $especie= $_POST['especie'];
        $castrado = $_POST['castrado'];
        $nomePet = ucfirst($_POST['nomePet']);
        $idadePet = $_POST['idadePet'];
        $raca = $_POST['raca'];
        $cor = $_POST['cor'];
        $desc = ucfirst($_POST['descricao']);
        $sexo = $_POST['sexo'];
        $porte = $_POST['porte'];
        
        $sql = 'INSERT INTO pet (idCanil, nomePet, pet_porte, Idcor, idRaca, pet_descricao, postagemPet,idadePet,statusPet , idImage,sexo,castrado,EspeciePet) 
                VALUES (:idCanilUser, :nomePet, :porte, :cor, :raca, :descricao, NOW(), :idadePet, "Padrao", :idImage, :sexo,:castrado,:especiePet)';
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':especiePet', $especie, PDO::PARAM_STR);
        $stmt->bindValue(':castrado', $castrado, PDO::PARAM_INT);
        $stmt->bindValue(':idCanilUser', $idCanilUser, PDO::PARAM_INT);
        $stmt->bindValue(':nomePet', $nomePet, PDO::PARAM_STR);
        $stmt->bindValue(':porte', $porte, PDO::PARAM_STR);
        $stmt->bindValue(':cor', $cor, PDO::PARAM_INT); 
        $stmt->bindValue(':raca', $raca, PDO::PARAM_INT); 
        $stmt->bindValue(':descricao', $desc, PDO::PARAM_STR);
        $stmt->bindValue(':idadePet', $idadePet, PDO::PARAM_INT);
        $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
        $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR); 
        $stmt->execute();
        return $con->lastInsertId();
        
    

       }
     catch (PDOException $e) {
            echo '<script>
                alert("Erro ao inserir dados");
                window.location.href = "https://amigos4patas.com/?pagina=AdminCanil";
              </script>';
           
}

    }

   
    public static function calcIdade($anoNascimento) {
        // Obtendo o ano atual
        $anoAtual = (int)date("Y");
        
        // Calculando a idade subtraindo o ano de nascimento do ano atual
        $idade = $anoAtual - (int)$anoNascimento;
        
        // Retornando a idade em anos
        return $idade;
    }
    
    
    

public static function filtrarPets()
    {
        try {
            $con = Connection::getConn();
    
            $params = [];
            $conditions = [];
    
            $especie = isset($_POST['especie']) ? trim($_POST['especie']) : '';
            $nomePet = isset($_POST['nomePet']) ? ucfirst(trim($_POST['nomePet'])) : '';
            $castrado = isset($_POST['castrado']) ? (int)trim($_POST['castrado']) : '';
            $sexo = isset($_POST['sexo']) ? trim($_POST['sexo']) : '';
            $porte = isset($_POST['pet_porte']) ? trim($_POST['pet_porte']) : '';
            $cor = isset($_POST['cor']) ? (int)$_POST['cor'] : 0;
            $raca = isset($_POST['raca']) ? (int)$_POST['raca'] : 0;
    
            if (!empty($especie)) {
                $conditions[] = "EspeciePet = :especie";
                $params[':especie'] = $especie;
            }
    
            if (!empty($castrado)) {
                $conditions[] = "castrado = :castrado";
                $params[':castrado'] = $castrado;
            }
    
            if (!empty($nomePet)) {
                $conditions[] = "nomePet LIKE :nomePet";
                $params[':nomePet'] = '%' . $nomePet . '%';
            }
    
            if (!empty($sexo)) {
                $conditions[] = "sexo = :sexo";
                $params[':sexo'] = $sexo;
            }
    
            if (!empty($porte)) {
                $conditions[] = "pet_porte = :pet_porte";
                $params[':pet_porte'] = $porte;
            }
    
            if (!empty($cor)) {
                $conditions[] = "IdCor = :cor";
                $params[':cor'] = $cor;
            }
    
            if (!empty($raca)) {
                $conditions[] = "idRaca = :raca";
                $params[':raca'] = $raca;
            }
    
            $sql = "SELECT * FROM pet_padrao";
    
            if (!empty($conditions)) {
                $sql .= " WHERE " . implode(" AND ", $conditions);
            }
    
            $stmt = $con->prepare($sql);
            $stmt->execute($params);
    
            $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (!empty($pets)) {
                return [
                    'pets' => $pets,
                    'params' => $params
                ];
            } else {
                echo '<script>
                        alert("nenhum pet encontrado");
                        window.location.href = "https://amigos4patas.com/?pagina=pets&metodo=filtrarPets";
                      </script>';
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao filtrar pets: " . $e->getMessage());
        }
    }
    public static function FiltroSexo()
    {
        $con = Connection::getConn();
        $sql = "SELECT * FROM pet_padrao WHERE sexo = 'Macho'";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public static function FiltroFemea()
    {
        $con = Connection::getConn();
        $sql = "SELECT * FROM pet_padrao WHERE sexo = 'Femea';";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
     public static function  FiltrarCaramelo()
    {
        $con = Connection::getConn();
        $sql = "SELECT * FROM pet_padrao WHERE idCor = 4";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public static function  FiltrarPorte()
    {
        $con = Connection::getConn();
        $sql = "SELECT * FROM pet_padrao WHERE pet_porte = 'medio'";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public static function  FiltrarCastrado()
    {
        $con = Connection::getConn();
        $sql = "SELECT * FROM pet_padrao WHERE castrado = 1";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    }
    public static function MostrarImage($idPet)
    {
        $con = Connection::getConn();

        $sql=" SELECT pet.idPet, images.imgpath FROM pet JOIN images ON pet.idImage = images.idImage WHERE pet.idPet = :idPet ";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(':idPet', $idPet, PDO::PARAM_STR);
        $stmt->execute();
        $img = $stmt->fetch(PDO::FETCH_ASSOC);
    
        return $img;

    }
    
    public static function UpdatePet($idPet)
    {
        try {
            $con = Connection::getConn();
            if (!empty($_POST)) {
                $castrado = $_POST['castrado'];
                $nomePet = ucfirst($_POST['nomePet']);
                $idadePet = $_POST['idadePet'];
                $especie = $_POST['especie'];
                $raca = $_POST['raca'];
                $cor = $_POST['cor'];
                $desc = ucfirst($_POST['descricao']);
                $sexo = $_POST['sexo'];
                $porte = $_POST['porte'];
                $statusPet = $_POST['statusPet'];
    
                $sql = "UPDATE pet 
                        SET nomePet = :nomePet, 
                            pet_porte = :porte, 
                            Idcor = :cor, 
                            idRaca = :raca, 
                            pet_descricao = :descricao, 
                            idadePet = :idadePet, 
                            statusPet = :statusPet, 
                            sexo = :sexo, 
                            EspeciePet = :especiePet,
                            castrado = :castrado";
    
                if (isset($_FILES['picturePet']) && $_FILES['picturePet']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $idImage = self::InsertImagePet();
                    $sql .= ", idImage = :idImage";
                }
    
                $sql .= " WHERE idPet = :idPet";
    
                $stmt = $con->prepare($sql);
    
                if (isset($_FILES['picturePet']) && $_FILES['picturePet']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $stmt->bindValue(':idImage', $idImage, PDO::PARAM_INT);
                }
    
                $stmt->bindValue(':nomePet', $nomePet, PDO::PARAM_STR);
                $stmt->bindValue(':porte', $porte, PDO::PARAM_STR);
                $stmt->bindValue(':cor', $cor, PDO::PARAM_INT);
                $stmt->bindValue(':raca', $raca, PDO::PARAM_INT);
                $stmt->bindValue(':descricao', $desc, PDO::PARAM_STR);
                $stmt->bindValue(':idadePet', $idadePet, PDO::PARAM_INT);
                $stmt->bindValue(':statusPet', $statusPet, PDO::PARAM_STR);
                $stmt->bindValue(':sexo', $sexo, PDO::PARAM_STR);
                $stmt->bindValue(':especiePet', $especie, PDO::PARAM_STR);
                $stmt->bindValue(':castrado', $castrado, PDO::PARAM_INT);
                $stmt->bindValue(':idPet', $idPet, PDO::PARAM_INT);
    
                $stmt->execute();
            }
        } catch (PDOException $e) {
             echo '<script>
                    alert("Erro ao atualizar dados");
                    window.location.href = "https://amigos4patas.com/?pagina=pets";
                  </script>';
        }
    }
    

    
    
    
    
}
