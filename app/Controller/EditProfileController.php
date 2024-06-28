<?php


class EditProfileController
{
    public function index()
    {
        try 
        {	
            Usuario::Login();
            Auth::AuthUsuarioLogado();
            $cidade = Canil::selecionarCidade();
          
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('EditProfile.html');
        
            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;

            $usuarioInfo = Usuario::SelectUsuario($idLogin, $idCanilUser);
            
            $imgProfile = Usuario::MostrarImage($idLogin);
            $imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ? $imgProfile['imgpath'] : null;
        
            $imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
            $imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;
            
            $banner = Canil::MostrarImageCanilBanner($idCanilUser);
       
            $parametros = array(
                'bannerPath' => ($banner !== false && isset($banner['imgpath'])) ? $banner['imgpath'] : null,
                'imgPathCanilProfile' => $imgPathCanilProfile,
                'cidade' => $cidade,
                'numContato'=>isset($usuarioInfo['numContato'])?$usuarioInfo['numContato']:null,
                'nomeCani' => isset($usuarioInfo['nomeCanil']) ? $usuarioInfo['nomeCanil'] : null,
                'canil_descricao' => isset($usuarioInfo['canil_descricao']) ? $usuarioInfo['canil_descricao'] : null,
                'doacao' => isset($usuarioInfo['doacao']) ? $usuarioInfo['doacao'] : null,
                'canil_localizacao' => isset($usuarioInfo['canil_localizacao']) ? $usuarioInfo['canil_localizacao'] : null,
            );
            $parametros['nomeUsuario'] = $nomeUsuario;
            $parametros['idCanilUser'] = $idCanilUser;
            $parametros['idLogin'] = $idLogin;

            if(isset($idCanilUser)){
                $parametros['imgPath'] = $imgPathCanilProfile;
            }
            else
            {
                $parametros['imgPath'] = $imgPathProfile;
            }
        
            $conteudo = $template->render($parametros);
            echo $conteudo;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

public static function updateCanil()
{
    Usuario::Login();
    Auth::AuthUsuarioLogado();
    $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
    Canil::UpdateCanil($idCanilUser);

    
   

  
    header("Location: https://amigos4patas.com/?pagina=Canil&idCanil=$idCanilUser");
}

public static function updateUser()
{
    Usuario::Login();
    Auth::AuthUsuarioLogado();
    $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
    Usuario::UpdateUser($idLogin);



    
    header("Location: https://amigos4patas.com/?pagina=User&idUser=$idLogin");
}


}
