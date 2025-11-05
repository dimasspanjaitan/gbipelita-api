<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            /** @var \Illuminate\Http\Request $request */
            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('users', 'public');
            }

            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            if (!empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            } else {
                $user->assignRole('congregation');
            }

            DB::commit();

            return response()->json($user->load('roles'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
