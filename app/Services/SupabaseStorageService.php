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
    }

    /**
     * Upload file to Supabase Storage
     */
    public function upload(UploadedFile $file, string $path): array
    {
        $url = "{$this->supabaseUrl}/storage/v1/object/{$this->bucket}/{$path}";
        
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$this->supabaseKey}",
            'Content-Type' => $file->getMimeType(),
        ])->attach(
            'file',
            file_get_contents($file->getRealPath()),
            $file->getClientOriginalName()
        )->post($url);

        if ($response->successful()) {
            return [
                'success' => true,
                'path' => $path,
                'url' => $this->getPublicUrl($path),
            ];
        }

        return [
            'success' => false,
            'error' => $response->json()['message'] ?? 'Upload failed',
        ];
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
