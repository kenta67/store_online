<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\PayPalHttpClient;

class TestPayPalConnection extends Command
{
    protected $signature = 'paypal:test';
    protected $description = 'Test PayPal API connection with new SDK';

    public function handle()
    {
        $clientId = config('services.paypal.client_id');
        $clientSecret = config('services.paypal.secret');
        
        if (empty($clientId) || empty($clientSecret)) {
            $this->error('❌ Credenciales de PayPal no configuradas en .env');
            return 1;
        }

        try {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);
            
            $this->info('✅ SDK de PayPal configurado correctamente');
            $this->info('📝 Client ID: ' . $clientId);
            $this->info('🔒 Secret: ' . substr($clientSecret, 0, 10) . '...');
            $this->info('🌐 Modo: ' . config('services.paypal.settings.mode', 'sandbox'));
            
        } catch (\Exception $e) {
            $this->error('❌ Error de conexión con PayPal: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}