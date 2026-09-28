<?php

namespace App\Services;

use App\Models\LicenseRegistryEntry;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LicenseRegistryImporter
{
    /**
     * @return array{created:int, updated:int, skipped:int, errors:array<int, string>}
     */
    public function import(UploadedFile|string $file, string $category, int $userId): array
    {
        $path = $file instanceof UploadedFile ? $file->getRealPath() : $file;
        $handle = fopen((string) $path, 'r');

        if (! $handle) {
            return ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => ['Unable to open CSV file.']];
        }

        $summary = ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => []];
        $header = fgetcsv($handle, null, ',', '"', '');

        if (! $header) {
            fclose($handle);

            return ['created' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => ['CSV file is empty.']];
        }

        $columns = $this->columns($header);
        $rowNumber = 1;

        while (($row = fgetcsv($handle, null, ',', '"', '')) !== false) {
            $rowNumber++;

            if ($this->isBlankRow($row)) {
                continue;
            }

            $businessName = trim((string) $this->value($row, $columns, 'business_name'));
            $certificateNumber = trim((string) $this->value($row, $columns, 'certificate_number'));

            if ($businessName === '' || $certificateNumber === '') {
                $summary['skipped']++;
                $summary['errors'][] = "Row {$rowNumber}: business name and certificate number are required.";

                continue;
            }

            $entry = LicenseRegistryEntry::updateOrCreate(
                [
                    'category' => $category,
                    'certificate_number' => $certificateNumber,
                ],
                [
                    'registry_number' => $this->integer($this->value($row, $columns, 'registry_number')),
                    'business_name' => $businessName,
                    'issued_date' => $this->date($this->value($row, $columns, 'issued_date')),
                    'expiry_date' => $this->date($this->value($row, $columns, 'expiry_date')),
                    'status' => 'active',
                    'updated_by' => $userId,
                    'created_by' => $userId,
                ]
            );

            $entry->wasRecentlyCreated ? $summary['created']++ : $summary['updated']++;
        }

        fclose($handle);

        return $summary;
    }

    /**
     * @param  array<int, string|null>  $header
     * @return array<string, int>
     */
    private function columns(array $header): array
    {
        $columns = [];

        foreach ($header as $index => $column) {
            $key = Str::of((string) $column)->lower()->replaceMatches('/[^a-z0-9]+/', '_')->trim('_')->toString();

            match ($key) {
                'sno', 's_no', 'serial_no', 'serial_number' => $columns['registry_number'] = $index,
                'registered_business_name', 'business_name', 'registered_name' => $columns['business_name'] = $index,
                'license_certificate_number', 'certificate_number', 'licence_certificate_number' => $columns['certificate_number'] = $index,
                'issued_date', 'issue_date' => $columns['issued_date'] = $index,
                'expiry_date', 'expiration_date', 'expires_at' => $columns['expiry_date'] = $index,
                default => null,
            };
        }

        return $columns;
    }

    /**
     * @param  array<int, string|null>  $row
     * @param  array<string, int>  $columns
     */
    private function value(array $row, array $columns, string $key): ?string
    {
        return Arr::get($row, $columns[$key] ?? -1);
    }

    private function integer(?string $value): ?int
    {
        $value = trim((string) $value);

        return ctype_digit($value) ? (int) $value : null;
    }

    private function date(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        return Carbon::parse($value)->toDateString();
    }

    /**
     * @param  array<int, string|null>  $row
     */
    private function isBlankRow(array $row): bool
    {
        return collect($row)->every(fn ($value): bool => trim((string) $value) === '');
    }
}
