<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class SupabaseStorageService
{
    protected $supabaseUrl;
    protected $supabaseKey;
    protected $bucket;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_STORAGE_URL');
        $this->supabaseKey = env('SUPABASE_SERVICE_KEY');
        $this->bucket = env('SUPABASE_STORAGE_BUCKET', 'avatars');
        
        // Debug logging (remove after fixing)
        \Log::info('SupabaseStorageService initialized', [
            'url_set' => !empty($this->supabaseUrl),
            'key_set' => !empty($this->supabaseKey),
            'key_length' => strlen($this->supabaseKey ?? ''),
            'key_starts_with' => substr($this->supabaseKey ?? '', 0, 20) . '...',
            'bucket' => $this->bucket,
        ]);
    }

    /**
     * Upload file to Supabase Storage
     */
    public function upload(UploadedFile $file, string $path): array
    {
        return $this->uploadToPath($file, $path);
    }

    /**
     * Upload file to a specific path in Supabase Storage
     */
    public function uploadToPath(UploadedFile $file, string $path): array
    {
        try {
            $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucket}/{$path}";
            
            // Read file contents
            $fileContents = file_get_contents($file->getRealPath());
            
            // Upload to Supabase Storage
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->supabaseKey}",
                'Content-Type' => $file->getMimeType(),
            ])->withBody($fileContents, $file->getMimeType())
            ->post($url);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path),
                ];
            }

            // Get detailed error from response
            $error = $response->json()['message'] ?? $response->json()['error'] ?? 'Upload failed';
            \Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'url' => $url,
            ]);

            return [
                'success' => false,
                'error' => $error . ' (Status: ' . $response->status() . ')',
            ];
        } catch (\Exception $e) {
            \Log::error('Supabase upload exception', [
                'error' => $e->getMessage(),
                'path' => $path,
            ]);
            
            return [
                'success' => false,
                'error' => 'Upload exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete file from Supabase Storage
     */
    public function delete(string $path): bool
    {
        $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucket}/{$path}";
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->supabaseKey}",
        ])->delete($url);

        return $response->successful();
    }

    /**
     * Get public URL for a file
     */
    public function getPublicUrl(string $path): string
    {
        return "{$this->supabaseUrl}/storage/v1/object/public/{$this->bucket}/{$path}";
    }

    /**
     * Check if storage is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->supabaseUrl) && !empty($this->supabaseKey);
    }
}
