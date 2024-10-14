<?php

namespace App\Http\Controllers;

use App\Services\FlyerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FlyerController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        $filename = $request->get('filename');

        $flyerService = new FlyerService();

        try {
            $addFlyersResponse = $flyerService->getFileFromS3AndSaveInDb($filename);
        } catch(\Exception $e){
            return response()
                ->json(['message' => 'Something went wrong, please try later.'], 500);
        }

        return response()
            ->json(
                [
                    'message' => [
                        'flyers added' => implode( ', ', $addFlyersResponse['validFlyers']),
                        'flyers with error' => implode( ', ', $addFlyersResponse['invalidFlyers'])
                    ]
                ], 200);
    }
}
