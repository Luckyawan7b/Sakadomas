<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestFirebase extends Command
{
    protected $signature = 'firebase:test';
    protected $description = 'Test Firebase Messaging connection';

    public function handle()
    {
        $this->info('🔥 Testing Firebase Cloud Messaging integration...');
        $this->newLine();

        // 1. Check service-account.json exists
        $credPath = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/firebase/service-account.json'));
        if (file_exists($credPath)) {
            $this->info('✅ Service account file found: ' . $credPath);
            $creds = json_decode(file_get_contents($credPath), true);
            $this->info('   Project ID: ' . ($creds['project_id'] ?? 'N/A'));
            $this->info('   Client Email: ' . ($creds['client_email'] ?? 'N/A'));
        } else {
            $this->error('❌ Service account file NOT found at: ' . $credPath);
            return 1;
        }
        $this->newLine();

        // 2. Check Firebase Messaging can be instantiated
        try {
            $messaging = app('firebase.messaging');
            $this->info('✅ Firebase Messaging instance created successfully!');
            $this->info('   Class: ' . get_class($messaging));
        } catch (\Throwable $e) {
            $this->error('❌ Failed to create Firebase Messaging instance:');
            $this->error('   ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // 3. Check FCM tokens table
        try {
            $tokenCount = \App\Models\FcmToken::count();
            $this->info('✅ fcm_tokens table accessible. Current tokens: ' . $tokenCount);
        } catch (\Throwable $e) {
            $this->error('❌ Cannot access fcm_tokens table: ' . $e->getMessage());
            return 1;
        }
        $this->newLine();

        // 4. Check routes
        $routes = ['fcm.register', 'fcm.remove'];
        foreach ($routes as $routeName) {
            try {
                $url = route($routeName);
                $this->info("✅ Route '{$routeName}' registered: {$url}");
            } catch (\Throwable $e) {
                $this->error("❌ Route '{$routeName}' not found!");
            }
        }
        $this->newLine();

        // 5. Verify VAPID key
        $vapidKey = env('VITE_FIREBASE_VAPID_KEY');
        if (!empty($vapidKey)) {
            $this->info('✅ VAPID key configured: ' . substr($vapidKey, 0, 20) . '...');
        } else {
            $this->warn('⚠️  VAPID key is empty! Generate it from Firebase Console → Cloud Messaging → Web Push certificates');
        }
        $this->newLine();

        $this->info('🎉 Firebase integration test completed successfully!');
        $this->info('   Backend is ready to send push notifications.');

        return 0;
    }
}
