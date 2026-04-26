<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FunctionCallService;

class AIAssistantController extends Controller
{
    public function execute(Request $request, FunctionCallService $service)
    {
        $intent = $request->intent;
        $data = $request->data;

        $result = $service->handle($intent, $data);

        return response()->json($result);
    }
}