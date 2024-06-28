<?php

class Auth
{
    public static function Logout()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $redirect_url = 'http://amigos4patas.com/?pagina=home';
    
        if (filter_var($redirect_url, FILTER_VALIDATE_URL) !== false &&
            parse_url($redirect_url, PHP_URL_HOST) === $_SERVER['HTTP_HOST'])
        {
            header("Location: $redirect_url");
            exit();

        } else {
    
            header("Location: http://{$_SERVER['HTTP_HOST']}/");
            exit();
        }
    }
    
    public static function AuthUsuarioCanil()
{
  
    // Verifica se o idCanilUser está definido e não é nulo
    if (!isset($_SESSION['idCanilUser']) || $_SESSION['idCanilUser'] === null)
    {
        header('Location: http://amigos4patas.com/');
        exit(); // Certifique-se de parar a execução após o redirecionamento
    }
}
public static function AuthUsuarioLogado()
{
    // Verifica se ambas as variáveis de sessão não estão definidas ou são null
    if ((!isset($_SESSION['idCanilUser']) || $_SESSION['idCanilUser'] === null) 
        && (!isset($_SESSION['idLogin']) || $_SESSION['idLogin'] === null))
    {
        // Redireciona para a página inicial
        header('Location: http://amigos4patas.com/');
        exit(); 
    }
}

public static function AuthUsuario()
{
    // Verifica se ambas as variáveis de sessão não estão definidas ou são null
    if (  !isset($_SESSION['idLogin']) || $_SESSION['idLogin'] === null)
    {
        // Redireciona para a página inicial
        header('Location: http://amigos4patas.com/');
        exit(); 
    }
}

    
}
