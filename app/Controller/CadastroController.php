<?php

class CadastroController
{
    public function index()
    {
        try 
        {
            Usuario::Login();
            $cidade =Canil::selecionarCidade();
         
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('Cadastro.html');

            $nomeUsuario = isset($_SESSION['nomeUsuario']) ? $_SESSION['nomeUsuario'] : null;
            $idLogin = isset($_SESSION['idLogin']) ? $_SESSION['idLogin'] : null;
            $idCanilUser = isset($_SESSION['idCanilUser']) ? $_SESSION['idCanilUser'] : null;


            $parametros['nomeUsuario'] = $nomeUsuario;
            $parametros['idCanilUser'] = $idCanilUser;
            $parametros['idLogin'] = $idLogin;

           
            $parametros = array('cidade'=> $cidade);
            $conteudo = $template->render($parametros);
            echo $conteudo;
           
        } catch (Exception $e) {
            echo $e->getMessage();
        }
            
    }
   
    public function insertUser()
    {
        try 
        {
            
        Usuario::InsertUser();
        header("Location: http://amigos4patas.com/?pagina=home");

        } catch (Exception $e) {
            echo $e->getMessage();
        }
      
 
            
    }
    public function insertCanil()
    {
        try 
        {
            Canil::insertCanil();
            header("Location: http://amigos4patas.com/");
            exit(); 
        } 
        catch (Exception $e) 
        {
          
            echo $e->getMessage();
        }
    }
    
    public static function LoginUser()
    {
        Usuario::Login();

    }
    
    public function LogOut()
    {
        Auth::Logout();
    }
    
}
