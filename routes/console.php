<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('uploads:check-spaces {--write : Upload a public probe file and verify it over HTTP}', function () {
    $disk = config('filesystems.disks.public', []);

    if (($disk['driver'] ?? null) !== 's3') {
        $this->error('The public disk is not using s3. Set PUBLIC_FILESYSTEM_DRIVER=s3 in production.');

        return 1;
    }

    foreach (['key', 'secret', 'region', 'bucket', 'endpoint', 'url'] as $key) {
        if (blank($disk[$key] ?? null)) {
            $this->error("Missing public disk configuration value: {$key}");

            return 1;
        }
    }

    $this->info('Public upload disk is configured for DigitalOcean Spaces.');
    $this->line('Bucket: '.$disk['bucket']);

    if (! $this->option('write')) {
        return 0;
    }

    $path = 'health-checks/'.Str::uuid().'.txt';
    Storage::disk('public')->put($path, 'ok', 'public');

    if (! Storage::disk('public')->exists($path)) {
        $this->error('Probe upload could not be read back from the public disk.');

        return 1;
    }

    $url = rtrim((string) $disk['url'], '/').'/'.$path;
    $response = Http::get($url);

    if (! $response->ok()) {
        $this->error('Probe upload was not publicly reachable.');

        return 1;
    }

    $this->info('Probe upload is writable, readable, and publicly reachable.');

    return 0;
})->purpose('Validate the public upload disk configuration for DigitalOcean Spaces');
