<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckPayPalEnv extends Command
{
    protected $signature = 'paypal:env';
    protected $description = 'Check PayPal environment variables';

    public function handle()
    {
        $this->info('🔍 Verificando variables de entorno de PayPal...');
        
        $variables = [
            'PAYPAL_SANDBOX_CLIENT_ID' => env('PAYPAL_SANDBOX_CLIENT_ID'),
            'PAYPAL_SANDBOX_SECRET' => env('PAYPAL_SANDBOX_SECRET'),
            'PAYPAL_MODE' => env('PAYPAL_MODE'),
            'PAYPAL_CLIENT_ID' => env('PAYPAL_CLIENT_ID'),
            'PAYPAL_SECRET' => env('PAYPAL_SECRET'),
        ];

        foreach ($variables as $key => $value) {
            if (!empty($value)) {
                $this->info("✅ $key: " . ($key === 'PAYPAL_SANDBOX_SECRET' ? substr($value, 0, 10) . '...' : $value));
            } else {
                $this->error("❌ $key: NO CONFIGURADO");
            }
        }

        $this->info("\n🔧 Verificando configuración de servicios...");
        $this->info("Client ID desde config: " . (config('services.paypal.client_id') ? '✅ CONFIGURADO' : '❌ NO CONFIGURADO'));
        $this->info("Secret desde config: " . (config('services.paypal.secret') ? '✅ CONFIGURADO' : '❌ NO CONFIGURADO'));
        $this->info("Mode desde config: " . config('services.paypal.settings.mode', 'NO CONFIGURADO'));

        return 0;
    }
}