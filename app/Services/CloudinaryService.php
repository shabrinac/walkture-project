<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * CloudinaryService
 *
 * Handles direct image uploads and deletion to Cloudinary API using cURL.
 * This completely avoids any third-party SDK conflicts (like Guzzle stream_for deprecations).
 */
class CloudinaryService
{
    private string $cloudName;
    private string $apiKey;
    private string $apiSecret;

    public function __construct()
    {
        $this->cloudName = env('CLOUDINARY_CLOUD_NAME', 'wljttasw');
        $this->apiKey    = env('CLOUDINARY_API_KEY', '994385613596287');
        $this->apiSecret = env('CLOUDINARY_API_SECRET', 'ML-awnqrbvVOeOvWTJy04PhxnVE');
    }

    /**
     * Upload an UploadedFile directly to Cloudinary via REST API.
     *
     * @param  UploadedFile  $file
     * @param  string        $folder   Target folder name (e.g. 'spots', 'avatars').
     * @return string                  The optimized secure delivery URL.
     *
     * @throws \RuntimeException on upload failure.
     */
    public function store(UploadedFile $file, string $folder = 'walkture'): string
    {
        $timestamp = time();
        $targetFolder = trim($folder, '/');
        
        // Prepare signature parameters (Cloudinary requires sorted keys for signing)
        $params = [
            'folder'    => $targetFolder,
            'timestamp' => $timestamp,
        ];
        
        $signature = $this->generateSignature($params);

        $uploadUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/upload";

        $postFields = [
            'file'      => new \CURLFile($file->getRealPath(), $file->getMimeType(), $file->getClientOriginalName()),
            'api_key'   => $this->apiKey,
            'timestamp' => $timestamp,
            'folder'    => $targetFolder,
            'signature' => $signature,
        ];

        $ch = curl_init($uploadUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            throw new \RuntimeException("Cloudinary Upload cURL error: {$curlErr}");
        }

        $result = json_decode($response, true);

        if ($httpCode !== 200) {
            $msg = $result['error']['message'] ?? 'Unknown Cloudinary error';
            throw new \RuntimeException("Cloudinary Upload failed (HTTP {$httpCode}): {$msg}");
        }

        return $this->getOptimizedUrl($result['public_id']);
    }

    /**
     * Delete an asset from Cloudinary using its public URL or Public ID.
     *
     * @param  string  $urlOrPublicId
     * @return bool
     */
    public function delete(string $urlOrPublicId): bool
    {
        $publicId = $this->extractPublicId($urlOrPublicId);

        if (!$publicId) {
            return false;
        }

        $timestamp = time();
        $params = [
            'public_id' => $publicId,
            'timestamp' => $timestamp,
        ];
        
        $signature = $this->generateSignature($params);

        $destroyUrl = "https://api.cloudinary.com/v1_1/{$this->cloudName}/image/destroy";

        $postFields = [
            'public_id' => $publicId,
            'api_key'   => $this->apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ];

        $ch = curl_init($destroyUrl);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postFields,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        return $httpCode === 200 && isset($result['result']) && $result['result'] === 'ok';
    }

    /**
     * Generate optimized secure delivery URL.
     * Appends f_auto,q_auto for optimal WebP/AVIF delivery.
     *
     * @param  string  $publicId
     * @return string
     */
    public function getOptimizedUrl(string $publicId): string
    {
        return "https://res.cloudinary.com/{$this->cloudName}/image/upload/f_auto,q_auto/{$publicId}";
    }

    /**
     * Generate SHA-1 Cloudinary signature.
     */
    private function generateSignature(array $params): string
    {
        ksort($params);
        $signString = '';
        foreach ($params as $key => $value) {
            $signString .= "{$key}={$value}&";
        }
        $signString = rtrim($signString, '&') . $this->apiSecret;
        return sha1($signString);
    }

    /**
     * Extract public ID from a Cloudinary URL.
     */
    private function extractPublicId(string $urlOrPublicId): ?string
    {
        if (!str_starts_with($urlOrPublicId, 'http')) {
            return $urlOrPublicId;
        }

        $path = parse_url($urlOrPublicId, PHP_URL_PATH);
        if (!$path) {
            return null;
        }

        $parts = explode('/', trim($path, '/'));
        $uploadIdx = array_search('upload', $parts);
        if ($uploadIdx === false) {
            return null;
        }

        $remaining = array_slice($parts, $uploadIdx + 1);

        // Remove transformations if present
        if (!empty($remaining) && (str_contains($remaining[0], 'f_auto') || str_contains($remaining[0], 'q_auto'))) {
            array_shift($remaining);
        }

        // Remove version number (e.g. v17202392)
        if (!empty($remaining) && str_starts_with($remaining[0], 'v') && is_numeric(substr($remaining[0], 1))) {
            array_shift($remaining);
        }

        $publicIdWithExtension = implode('/', $remaining);

        // Remove file extension
        $ext = pathinfo($publicIdWithExtension, PATHINFO_EXTENSION);
        if ($ext) {
            return substr($publicIdWithExtension, 0, -(strlen($ext) + 1));
        }

        return $publicIdWithExtension;
    }
}
