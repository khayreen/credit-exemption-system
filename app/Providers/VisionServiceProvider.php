<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google\Cloud\Vision\V1\Client\ImageAnnotatorClient;

class VisionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register Google Cloud Vision API client with environment-based configuration
        $this->app->singleton(ImageAnnotatorClient::class, function ($app) {
            $credentialsPath = env('GOOGLE_CLOUD_CREDENTIALS_PATH');
            
            // Default to storage path if not specified
            if (empty($credentialsPath)) {
                $credentialsPath = storage_path('app/keys/service-account-credentials.json');
            } else {
                // If relative path, make it absolute
                if (!str_starts_with($credentialsPath, '/') && !str_contains($credentialsPath, ':')) {
                    $credentialsPath = base_path($credentialsPath);
                }
            }
            
            if (!file_exists($credentialsPath)) {
                throw new \Exception('Google Cloud Vision API credentials file not found at: ' . $credentialsPath);
            }
            
            // Set environment variable for Google Cloud SDK
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $credentialsPath);
            
            $config = [
                'credentials' => $credentialsPath
            ];
            
            // Add project ID if specified
            if ($projectId = env('GOOGLE_CLOUD_PROJECT_ID')) {
                $config['projectId'] = $projectId;
            }
            
            return new ImageAnnotatorClient($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
