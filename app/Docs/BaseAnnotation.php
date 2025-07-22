<?php

namespace App\Docs;

/**
 *     @OA\Info(
 *         title="API Documentation",
 *         version="1.0.0",
 *         description="API documentation with Sanctum Bearer token authentication"
 *     ),
 *
 *     @OA\SecurityScheme(
 *         securityScheme="bearerAuth",
 *         type="http",
 *         scheme="bearer",
 *     )
 */
class BaseAnnotation {}
