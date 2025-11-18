<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class RecuperarContrasenaController extends Controller
{
public function solicitar(Request $request)
{
    $request->validate([
        "canal" => "required|in:email,sms,whatsapp",
        "dato" => "required|string",
    ]);

    $canal = $request->canal;
    $dato = $request->dato;

    // Buscar usuario
    $user = $canal === "email"
        ? User::where("email", $dato)->first()
        : User::where("telefono", $dato)->first();

        $user_number = '521' . $user->telefono;
    if (!$user) {
        return response()->json(["message" => "Usuario no encontrado"]);
    }

    $nombre = $user->persona->nombre ?? "Usuario";
    $link = url("/api/recuperar-password") . "?" .
        ($canal === "email" ? "correo=" : "telefono=") . urlencode($dato);

    switch ($canal) {
        case "email":
            Mail::raw(
                "Hola $nombre, da click aquí para recuperar tu contraseña: $link",
                function ($message) use ($user) {
                    $message->to($user->email)->subject("Recuperación de contraseña");
                }
            );
            break;

        case "sms":
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');

            $response = Http::withBasicAuth($sid, $token)
                ->asForm()
                ->post(
                    "https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages.json",
                    [
                        "To" => "+" . $user_number,
                        "From" => env('TWILIO_SMS_FROM', '+18153846889'),
                        "Body" => "Hola $nombre, recupera tu acceso aquí: $link",
                    ]
                );

            if ($response->failed()) {
                \Log::error("❌ Error enviando SMS vía Twilio: ", $response->json());
                return response()->json(["error" => "No se pudo enviar el SMS."], 500);
            }

            \Log::info("✅ SMS enviado con éxito", $response->json());
            break;

        case "whatsapp":
            try {
                $sid = env('TWILIO_SID');
                $token = env('TWILIO_TOKEN');
                $twilio = new \Twilio\Rest\Client($sid, $token);

                $message = $twilio->messages->create(
                    "whatsapp:+{$user_number}",
                    [
                        "from" => "whatsapp:+14155238886",
                        "body" => "Hola $nombre, para recuperar tu contraseña ingresa aquí: $link"
                    ]
                );

                \Log::info("✅ WhatsApp enviado: " . $message->sid);
            } catch (\Exception $e) {
                \Log::error("❌ Error al enviar WhatsApp: " . $e->getMessage());
                return response()->json([
                    "error" => "No se pudo enviar el mensaje de WhatsApp."
                ], 500);
            }
            break;
    }

    return response()->json(["message" => "Enlace enviado con éxito"]);
}


    public function vistaRecuperar(Request $request)
    {
        $correo = $request->query("correo");
        $telefono = $request->query("telefono");

        return view("auth.recuperar-password", compact("correo", "telefono"));
    }

    public function actualizarPassword(Request $request)
    {
        $request->validate([
            "correo" => "nullable|email",
            "telefono" => "nullable|string",
            "new_password" => "required|string|min:8|confirmed",
        ]);

        $user = null;

        if ($request->correo) {
            $user = User::where("email", $request->correo)->first();
        } elseif ($request->telefono) {
            $user = User::where("telefono", $request->telefono)->first();
        }

        if (!$user) {
            return back()->withErrors(["usuario" => "Usuario no encontrado"]);
        }

        $user->contrasena = Hash::make($request->new_password);
        $user->save();

        return redirect()
            ->back()
            ->with("success", true);
    }
}
