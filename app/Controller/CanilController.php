<?php
class CanilController
{
    public function index($idCanil)
    {   
        try 
        {   
            Usuario::Login();
            $canil = Canil::selecionaPorIdCanil($idCanil);
            $canilPets = Canil::selecionaPetsCanil($idCanil);
            $canilPetsAdotados = Canil::selecionaPets_Adotados($idCanil);
            $canilPetsArquivados = Canil::selecionaPets_Arquivados($idCanil);

            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('Canil.html');
        
            $parametros = array(
                'nomeCanil' => $canil['nomeUsuario'],
                'canil_descricao' => $canil['canil_descricao'],
                'doacao' => $canil['doacao'],
                'canil_localizacao' => $canil['canil_localizacao'],
                'idCanil' => $canil['idCanil']
            );

            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;

            $imgProfile = Usuario::MostrarImage($idLogin);
            $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ? $imgProfile['imgpath'] : null;

            $imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
            $imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;

            $imgCanil = Canil::MostrarImageCanil($idCanil);
            $imgPathCanil = ($imgCanil !== null && isset($imgCanil['imgpath'])) ? $imgCanil['imgpath'] : null;

            $imgCanilBanner = Canil::MostrarImageCanilBanner($idCanil);
            $imgPathCanilBanner = ($imgCanilBanner !== null && isset($imgCanilBanner['imgpath'])) ? $imgCanilBanner['imgpath'] : null;

            $parametros['nomeUsuario'] = $nomeUsuario;
            $parametros['idCanilUser'] = $idCanilUser;
            $parametros['idLogin'] = $idLogin;
            $parametros['imgPathCanil'] = $imgPathCanil;
            $parametros['imgPathBanner'] = $imgPathCanilBanner;

            if (isset($idCanilUser)) {
                $parametros['imgPath'] = $imgPathCanilProfile;
            } else {
                $parametros['imgPath'] = $imgPathProfile;
            }

            // Initialize all the arrays used in the $parametros array
            $parametros['nomePets'] = array();
            $parametros['idPet'] = array();
            $parametros['imgPathPet'] = array();
            $parametros['nomePetsAdotado'] = array();
            $parametros['idPetAdotado'] = array();
            $parametros['imgPathPetAdotado'] = array();
            $parametros['nomePetsArquivado'] = array();
            $parametros['idPetArquivado'] = array();
            $parametros['imgPathPetArquivados'] = array();

            foreach ($canilPets as $pet) {
                $parametros['nomePets'][] = $pet['nomePet'];
                $parametros['idPet'][] = $pet['idPet'];   
                $imgPet = Pets::MostrarImage($pet['idPet']);
                $imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
                $parametros['imgPathPet'][] = $imgPathPet;
            }

            foreach ($canilPetsAdotados as $petAdotado) {
                $parametros['nomePetsAdotado'][] = $petAdotado['nomePet'];
                $parametros['idPetAdotado'][] = $petAdotado['idPet'];   
                $imgPet = Pets::MostrarImage($petAdotado['idPet']);
                $imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
                $parametros['imgPathPetAdotado'][] = $imgPathPet;
            }

            foreach ($canilPetsArquivados as $petArquivados) {
                $parametros['nomePetsArquivado'][] = $petArquivados['nomePet'];
                $parametros['idPetArquivado'][] = $petArquivados['idPet'];   
                $imgPet = Pets::MostrarImage($petArquivados['idPet']);
                $imgPathPet = ($imgPet !== null && isset($imgPet['imgpath'])) ? $imgPet['imgpath'] : null;
                $parametros['imgPathPetArquivados'][] = $imgPathPet;
            }

            $conteudo = $template->render($parametros);
            echo $conteudo;
          
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
