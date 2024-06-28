<?php

class AdminCanilController
{
    public function index()
    {
        try 
        {
            Usuario::Login();
            // Verifica a autenticação do usuário antes de realizar qualquer operação
            Auth::AuthUsuarioCanil();
            
            $cores = Canil::selecionarCor();
            $raca = Canil::selecionarRaca();
            $porte = Canil::selecionarPorte();

            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('AdminCanil.html');
        
            $parametros = array('cores' => $cores, 'raca'=>$raca ,'porte'=>$porte);

            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;
            $parametros['nomeUsuario'] = $nomeUsuario ;
            $parametros['idCanilUser'] = $idCanilUser ;
            $parametros['idLogin'] = $idLogin ;
            
            $$idCanilUser =$_SESSION['idCanilUser'];
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
    
    public static function InsertPet()
    {
      try
      {
        Usuario::Login();
        $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;     
        $pet =  Pets::insertPet($idCanilUser);
        header("Location: http://amigos4patas.com/?pagina=petSingle&idpet=".$pet);
      }catch (Exception $e) {
        echo $e->getMessage();
    }
      
        
    }
}
