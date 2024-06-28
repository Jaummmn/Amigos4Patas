<?php

class AdminCanilEditController
{
    public function index($idpet)
    {
        try 
        {
           
            Usuario::Login();
            // Verifica a autenticação do usuário antes de realizar qualquer operação
            Auth::AuthUsuarioCanil();
            $petSingle = Pets::selecionaPorId($idpet);
           // $mostrarInfoPet = Pets::mostrarInfoPet($idpet);

            $cores = Canil::selecionarCor();
            $raca = Canil::selecionarRaca();
            $porte = Canil::selecionarPorte();
            $statusPet = Canil::selecionarStatus();

            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('AdminCanilEdit.html');
        
            $imgPet = Pets::MostrarImage($petSingle['idPet']);
            $imgPathPetSingle = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
            $parametros= array();

            
            $parametros = array(
                'idadePet'=>$petSingle['idadePet'],
                'EspeciePet'=>$petSingle['EspeciePet'],
                'statusPet'=>$statusPet,
                'cores' => $cores,
                'racas'=>$raca,
                'porte'=>$porte,
                'EspeciePet',$petSingle['EspeciePet'],
                'StatusPetPage' => $petSingle['statusPet'],
                'racaPet' =>(int) $petSingle['idRaca'],
                'corPet' =>(int) $petSingle['Idcor'],
                'idPetPage' => $petSingle['idPet'],
                'castrado' => $petSingle['castrado'],
                'sexo' => $petSingle['sexo'],
                'nomePet' => $petSingle['nomePet'],
                'pet_porte' =>  $petSingle['pet_porte'],
                'idCanil' => $petSingle['idCanil'],
                'pet_descricao' => $petSingle['pet_descricao'],
                'imgPathPetSingle' =>  $imgPathPetSingle 
            );
           
      
    
            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
          
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
            $parametros['nomeUsuario'] = $nomeUsuario ;
            $parametros['idCanilUser'] = $idCanilUser ;
          
          
            $imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
            $imgPathCanil = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;

            $parametros['imgPath']= $imgPathCanil;
        
            $conteudo = $template->render($parametros);
            
            echo $conteudo;
        } 
        catch (Exception $e) 
        {
            echo $e->getMessage();
        }
    }
    
    public static function UpdatePet($idpet)
    {
      try
      {
        Usuario::Login();
        
        Pets::UpdatePet($idpet);
        

       header("Location: http://amigos4patas.com/?pagina=petSingle&idpet=".$idpet);
      }
        catch (Exception $e)
        {
        echo $e->getMessage();
        }
      
        
    }

}
