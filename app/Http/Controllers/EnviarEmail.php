<?php

namespace App\Http\Controllers;

use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EnviarEmail extends Controller
{
   public function sendEmail(String $name,String $title , String $msg, String $userEmail)
    {
        try {
            $title = $title;
            $body = $msg;

            Mail::to($userEmail)->send(new EmailSender($title, $body));

            return response()->json(["message" => "Email enviado com sucesso"],200);
        } catch (\Throwable $th) {
            return response()->json(["erros" => "Erro ao enviar ".$th],404);
        }

    }
}
