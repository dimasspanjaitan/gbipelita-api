<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    private const FILE_FIELDS = [
        'app_logo',
        'app_logo_alternative',
        'app_favicon',
        'login_background',
    ];

    private function processFiles(
        Request $request,
        Setting $setting
    ): array {
        $data = $request->all();

        foreach (self::FILE_FIELDS as $field) {
            if (!$request->hasFile($field)) {
                continue;
            }

            // Hapus file lama jika berasal dari storage kita
            if ($setting->{$field}) {
                $oldPath = str_replace(
                    Storage::disk('public')->url(''),
                    '',
                    $setting->{$field}
                );

                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request
                ->file($field)
                ->store('settings', 'public');

            $data[$field] = Storage::disk('public')->url($path);
        }

        return $data;
    }

    public function get(): JsonResponse
    {
        return response()->json(
            Setting::query()->sole()
        );
    }

    public function update(Request $request): JsonResponse
    {
        foreach (self::FILE_FIELDS as $field) {
            if ($request->hasFile($field)) {
                $request->validate([
                    $field => [
                        'image',
                        $field === 'login_background'
                            ? 'max:10240'
                            : 'max:2048',
                    ],
                ], [
                    "$field.image" => 'File harus berupa gambar.',
                    "$field.max" => $field === 'login_background'
                        ? 'Ukuran file maksimal 10 MB.'
                        : 'Ukuran file maksimal 2 MB.',
                ]);
            }
        }

        $setting = Setting::query()->sole();

        $setting->update(
            $this->processFiles(
                $request,
                $setting
            )
        );

        return response()->json(
            $setting->fresh()
        );
    }
}
