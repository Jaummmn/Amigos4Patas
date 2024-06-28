<?php

class LogOutController
{
     public function index()
    {
    
    try 
        {   
            Usuario::Login();
         
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
       
           
        } catch (Exception $e) {
                echo $e->getMessage();
        }
    }
  
}
