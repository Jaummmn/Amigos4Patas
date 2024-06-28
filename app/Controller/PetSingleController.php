<?php

class PetSingleController
{
    public function index($idpet)
    {
        try 
        {
            Usuario::Login();
            $petSingle = Pets::selecionaPorId($idpet);
            $colecPostagens = Pets::selecionaRecentes();
            $mostrarInfoPet = Pets::mostrarInfoPet($idpet);

            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;

            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('petSingle.html');

            $imgPet = Pets::MostrarImage($petSingle['idPet']);
            $imgPathPetSingle = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;

            $idadePet = Pets::calcIdade($petSingle['idadePet']);
    
            $imgProfileCanilPage = Canil::MostrarImageCanil($petSingle['idCanil']);
            $imgPathCanilPageProfile = ($imgProfileCanilPage !== null && isset($imgProfileCanilPage['imgpath'])) ?$imgProfileCanilPage['imgpath'] : null;
         
            $parametros = array(
                'idadePet' => $idadePet,
                'idPetPage' => $petSingle['idPet'],
                'castrado' => $petSingle['castrado'],
                'canil_loc'=>$mostrarInfoPet['canil_localizacao'],
                'sexo' => $petSingle['sexo'],
                'nomePet' => $petSingle['nomePet'],
                'pet_porte' => $petSingle['pet_porte'],
                'idCanil' => $petSingle['idCanil'],
                'pet_descricao' => $petSingle['pet_descricao'],
                'raca' => $mostrarInfoPet['raca'],
                'cor' => $mostrarInfoPet['cor'],
                'nomeCanil' => $mostrarInfoPet['nomeUsuario'],  
                'numContato' => $mostrarInfoPet['numContato'],  
                'imgPathPetSingle' => $imgPathPetSingle ,
                'imgPathCanilPageProfile' =>$imgPathCanilPageProfile
            );

            foreach ($colecPostagens as $pet) {
                $parametros['nomePets'][] = $pet['nomePet'];
                $parametros['idPet'][] = $pet['idPet'];   
                $imgPet = Pets::MostrarImage($pet['idPet']);
                $imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
                $parametros['imgPathPet'][] = $imgPathPet;
            }

            $imgProfile = Usuario::MostrarImage($idLogin);
            $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ? $imgProfile['imgpath'] : null;

            $imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
            $imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;
        

            $parametros['nomeUsuario'] = $nomeUsuario;
            $parametros['idCanilUser'] = $idCanilUser;
            $parametros['idLogin'] = $idLogin;
            
            $parametros['imgPath'] = isset($idCanilUser) ? $imgPathCanilProfile : $imgPathProfile;

            $conteudo = $template->render($parametros);
            echo $conteudo;
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    
}


