<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * SupabaseStorageService
 *
 * Uploads and deletes files from Supabase Storage using the
 * Supabase Storage REST API (not S3-compatible endpoint).
 * This is simpler, more reliable, and doesn't require aws-sdk-php.
 *
 * REST API docs: https://supabase.com/docs/reference/javascript/storage-from-upload
 */
class SupabaseStorageService
{
    private string $baseUrl;
    private string $serviceKey;
    private string $publicBase;

    public function __construct()
    {
        $this->baseUrl    = rtrim(config('services.supabase.url', env('SUPABASE_URL', '')), '/');
        $this->serviceKey = config('services.supabase.key', env('SUPABASE_SERVICE_KEY', ''));
        $this->publicBase = $this->baseUrl . '/storage/v1/object/public';
    }

    /**
     * Upload an UploadedFile to a Supabase Storage bucket.
     *
     * @param  UploadedFile  $file     The uploaded file from the request.
     * @param  string        $bucket   Bucket name (e.g. 'spots', 'photographers', 'avatars').
     * @param  string        $folder   Sub-folder inside the bucket (e.g. 'images').
     * @return string                  Full public URL of the uploaded file.
     *
     * @throws \RuntimeException on upload failure.
     */
    public function store(UploadedFile $file, string $bucket, string $folder = ''): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'jpg');
        $filename  = Str::uuid() . '.' . $extension;
        $path      = $folder ? trim($folder, '/') . '/' . $filename : $filename;

        $uploadUrl = $this->baseUrl . '/storage/v1/object/' . $bucket . '/' . $path;

        $ch = curl_init($uploadUrl);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => file_get_contents($file->getRealPath()),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->serviceKey,
                'Content-Type: '        . ($file->getMimeType() ?: 'application/octet-stream'),
                'x-upsert: true',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            throw new \RuntimeException("Supabase Storage cURL error: {$curlErr}");
        }

        if ($httpCode !== 200) {
            throw new \RuntimeException(
                "Supabase Storage upload failed (HTTP {$httpCode}): {$response}"
            );
        }

        return $this->getPublicUrl($bucket, $path);
    }

    /**
     * Delete a file from a Supabase Storage bucket.
     *
     * @param  string  $urlOrPath  Full public URL or relative path of the file.
     * @param  string  $bucket     The bucket the file is in.
     * @return bool
     */
    public function delete(string $urlOrPath, string $bucket): bool
    {
        $path = $this->extractPath($urlOrPath, $bucket);

        if (!$path) {
            return false;
        }

        $deleteUrl = $this->baseUrl . '/storage/v1/object/' . $bucket . '/' . $path;

        $ch = curl_init($deleteUrl);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->serviceKey,
            ],
        ]);

        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode === 200;
    }

    /**
     * Build the public URL for a bucket path.
     */
    public function getPublicUrl(string $bucket, string $path): string
    {
        return $this->publicBase . '/' . $bucket . '/' . ltrim($path, '/');
    }

    /**
     * Extract relative path from a full Supabase public URL.
     */
    private function extractPath(string $urlOrPath, string $bucket): ?string
    {
        if (!Str::startsWith($urlOrPath, 'http')) {
            return ltrim($urlOrPath, '/');
        }

        $prefix = $this->publicBase . '/' . $bucket . '/';
        if (Str::startsWith($urlOrPath, $prefix)) {
            return Str::after($urlOrPath, $prefix);
        }

        return null;
    }
}
