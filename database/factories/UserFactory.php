<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();
        $nickname = $this->generateUniqueNickname($firstName, $lastName);
        $username = strtolower($this->generateUsername($firstName, $lastName));
        $email = strtolower($username . "@gbipelita4.com");

        return [
            'id' => $this->faker->uuid(),
            'username' => $username,
            'nickname' => $nickname,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('asdfasdf'), // default password
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }

    private function generateUsername(string $firstName, string $lastName): string
    {
        // ambil 1–2 huruf dari last name, untuk variasi
        $shortLast = substr($lastName, 0, 2);

        // kadang tambahkan angka random biar unik
        $username = strtolower($firstName . $shortLast);

        if (rand(0, 1)) {
            $username .= rand(10, 99);
        }

        return $username;
    }

    private function generateUniqueNickname(string $firstName, string $lastName): string
    {
        $baseNickname = $firstName . ' ' . substr($lastName, 0, 1);
        $nickname = $baseNickname;

        $counter = 2;

        while (
            User::query()->where('nickname', $nickname)->exists()
        ) {
            $nickname = $baseNickname . $counter;
            $counter++;
        }

        return $nickname;
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign role ke user setelah dibuat.
     */
    public function withRole(string $role): static
    {
        return $this->afterCreating(function (User $user) use ($role) {
            $user->assignRole($role);
        });
    }
}
