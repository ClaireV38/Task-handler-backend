<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Task Handler API",
    version: "1.0.0",
    description: "Documentation for the Task Handler backend API"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]
class OpenApiSpec
{
}

