<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function getVersion(): JsonResponse
    {
        $composerData = json_decode(file_get_contents(base_path().'/composer.json'), true);

        return $this->successResponse(
            [
                'name' => $composerData['name'],
                'description' => $composerData['description'],
            ]
        );
    }
}
