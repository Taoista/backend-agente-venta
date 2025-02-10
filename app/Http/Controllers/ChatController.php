<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OpenAIServiceController;
use App\Models\ViewProductos;


class ChatController extends Controller
{

    protected $openAIService;

    public function __construct(OpenAIServiceController $openAIService)
    {
        $this->openAIService = $openAIService;
    }

    public function chat(Request $request)
    {
        $pregunta = $request->input('pregunta');

        // Usamos un formato más flexible de búsqueda para la medida
        $neumaticos = ViewProductos::where('medida', 'LIKE', "%$pregunta%")
            ->orWhere('modelo', 'LIKE', "%$pregunta%")
            ->orWhere('medida', 'LIKE', "%$pregunta%")
            ->orderBy('precio', 'asc') // Ordenar por los más baratos
            ->limit(5)
            ->get();

        // Si no hay resultados, avisar
        if ($neumaticos->isEmpty()) {
            return response()->json([
                'respuesta' => "No encontré neumáticos con la medida solicitada: '$pregunta'. ¿Puedes verificar la medida o dar más detalles?"
            ]);
        }

        // Construir el contexto para la IA
        $contexto = "Aquí están los neumáticos más baratos con la medida solicitada:\n";
        foreach ($neumaticos as $neumatico) {
            $contexto .= "- {$neumatico->marca} {$neumatico->modelo}, medida {$neumatico->medida}, precio \${$neumatico->precio}, stock: {$neumatico->stock}\n";
        }

        // Enviar la pregunta + contexto a OpenAI
        $mensaje = "Un cliente pregunta: $pregunta\n\n$contexto\n\nResponde de forma clara y útil basándote en la información proporcionada.";

        $respuestaIA = $this->openAIService->getResponse($mensaje);

        return response()->json(['respuesta' => $respuestaIA]);
    }
}
