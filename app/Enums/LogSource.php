<?php

namespace App\Enums;

enum LogSource: string
{
    case API_GATEWAY = 'api-gateway';
    case AUTH_SERVICE = 'auth-service';
    case PAYMENT_SERVICE = 'payment-service';
    case EMAIL_SERVICE = 'email-service';
    case USER_SERVICE = 'user-service';
    case ADMIN_PANEL = 'admin-panel';
    case LOG_INGESTION = 'log-ingestion';
    case FILE_UPLOAD = 'file-upload';
    case WEBHOOK_STRIPE = 'webhook:stripe';
    case WEBHOOK_GITHUB = 'webhook:github';
    case EXTERNAL_CLIENT = 'external-client';
    case MOBILE_APP = 'mobile-app';
}
