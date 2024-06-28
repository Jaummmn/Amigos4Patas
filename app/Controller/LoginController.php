<?php

class LoginController
{

    public function index()
    {
        try 
        {
            Usuario::Login();
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('LoginMobile.html');

            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
         
			$parametros= array();
          	
				$imgProfile = Usuario::MostrarImage($idLogin);
				$imgPathProfile = ($imgProfile !== null && isset($imgProfile['imgpath'])) ?$imgProfile['imgpath'] : null;
			
				$imgProfileCanil = Canil::MostrarImageCanil($idCanilUser);
				$imgPathCanilProfile = ($imgProfileCanil !== null && isset($imgProfileCanil['imgpath'])) ? $imgProfileCanil['imgpath'] : null;
                
	
	
				$parametros['nomeUsuario'] = $nomeUsuario;
				$parametros['idCanilUser'] = $idCanilUser;
				$parametros['idLogin'] = $idLogin;
				
				if(isset($idCanilUser)){
					$parametros['imgPath']= $imgPathCanilProfile;
				}
				else
				{
					$parametros['imgPath']= $imgPathProfile;
				}
        
            
            
            $conteudo = $template->render($parametros);
            echo $conteudo;
           
        } catch (Exception $e) {
            echo $e->getMessage();
        }
            
    }
    public static function LoginUser()
    {
        Usuario::Login();
        
    }
}
