<?php

namespace App\Services;

use App\Dto\QrGeneratorDto;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrGeneratorService
{
    public function generate(QrGeneratorDto $qrGeneratorDto)
    {
        $qr = base64_encode(QrCode::format($qrGeneratorDto->type)
            ->size($qrGeneratorDto->size)
            ->generate($qrGeneratorDto->url));
        return "data:image/{$qrGeneratorDto->type};base64,{$qr}";
    }
}
