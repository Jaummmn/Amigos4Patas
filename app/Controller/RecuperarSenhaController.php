<?php

class RecuperarSenhaController
{
 
    public function index()
    {
        try 
        {
            $loader = new \Twig\Loader\FilesystemLoader('app/View');
            $twig = new \Twig\Environment($loader);
            $template = $twig->load('RecuperaSenha.html');
            $parametros = array();
        
       

            $conteudo = $template->render($parametros);
            echo $conteudo;
           
           
        } catch (Exception $e) {
            echo $e->getMessage();
        }
            
    }

    public static function SendEmail()
    {
       Usuario::RecuperaSenha();
       echo '<script>
       alert("Email Enviado, verifique Spam");
       window.location.href = "http://amigos4patas.com/?pagina=Home";
               </script>';
       
    }

    public function UpdateUserPassword()
    {
        $loader = new \Twig\Loader\FilesystemLoader('app/View');
        $twig = new \Twig\Environment($loader);
        $template = $twig->load('Senha.html');

        $token=$_GET['token'];
       
        $parametros= array(
           'token' =>$token 
        );
        $conteudo = $template->render($parametros);
        echo $conteudo;
    }
   
    public static function UpdateSenha()
    {
       Usuario::UpdateSenha();
       echo '<script>
       alert("Senha Atualizada");
       window.location.href = "http://amigos4patas.com/?pagina=Home";
               </script>';
    }
    

}
