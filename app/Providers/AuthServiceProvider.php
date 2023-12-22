<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
 
       $this->env =  "http://localhost:8000";

       if(env('APP_ENV')!="local")
            $this->env = "https://tenocode.com";

        VerifyEmail::toMailUsing(function ($notifiable, $url) {

            //url generada por defecto
            //http://localhost:8000/api/register?expires=1670880604&hash=79080b54b70607b04d613389eed03c5a55669058&signature=f5321265a90301060d7101ae70e042b0f1767912d6020e9986039ad777c48c26
            
            //cambiamos la url a , auth/verifyEmail 
            //y luego le agregamos los datos de la url
        
            $site = $this->env."/auth/verifyEmail";
        
            $urlPieces = explode("/", $url);
            $data = $urlPieces[count($urlPieces)-1];
            $data = str_replace('?','_',$data);
            $data = str_replace('=','@',$data);
            $finalUrl = $site."/".$data;
        
            //cambiamos los datos del mensaje y el nombre del botón
            return (new MailMessage)
                ->subject(Lang::get('Verificación de Email - tenocode.com'))
                ->line(Lang::get('Favor verifica tu email visitando el siguiente link:'))
                ->action(Lang::get('Verifica tu Email'), $finalUrl)
                ->line(Lang::get('Este email ha sido enviado de forma automática, favor no responder.'));
        });

    }
}
