<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueSlug
{
    public static function make(string $table, string $value, ?Model $ignore = null, string $column = 'slug', int $maxLength = 255): string
    {
        $base = Str::slug($value) ?: 'item';
        $base = Str::limit($base, $maxLength, '');
        $slug = $base;
        $counter = 1;

        while (self::exists($table, $column, $slug, $ignore)) {
            $suffix = '-'.$counter;
            $slug = Str::limit($base, $maxLength - strlen($suffix), '').$suffix;
            $counter++;
        }

        return $slug;
    }

    private static function exists(string $table, string $column, string $slug, ?Model $ignore): bool
    {
        return DB::table($table)
            ->where($column, $slug)
            ->when($ignore?->exists, fn ($query) => $query->where($ignore->getKeyName(), '!=', $ignore->getKey()))
            ->exists();
    }
}
