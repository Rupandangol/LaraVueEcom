<?php

namespace App\Services;

use App\DTOs\AiReponseDto;
use App\Interfaces\ResponseInserterInterface;
use App\Models\AiResponse;
use Illuminate\Support\Facades\Auth;

class AiResponseInserter implements ResponseInserterInterface
{
    public function insert(AiReponseDto $data)
    {
        $auth = Auth::guard('admin')->user();
        try {
            $ai_response = AiResponse::create([
                'prompt' => $data->prompt,
                'response' => $data->response,
                'model' => $data->model ?? null,
                'tokens_used' => $data->tokens_used ?? null,
                'status_code' => $data->status_code,
                'admin_id' => $auth ? $auth->id : null,
            ]);

            return $ai_response;
        } catch (\Exception $e) {
        }
    }
}
