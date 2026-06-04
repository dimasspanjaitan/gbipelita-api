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

    protected static array $usedNicknames = [];
    protected static array $usedUsernames = [];

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
            'phone' => '0812555500000',
            'address' => 'Medan Timur',
            'birth_date' => '2000-01-01'
        ];
    }

    private function generateUsername(string $firstName, string $lastName): string
    {
        if (empty(self::$usedUsernames)) {
            self::$usedUsernames = User::query()->pluck('username')->toArray();
        }

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
        // Load semua nickname dari database sekali saja
        if (empty(self::$usedNicknames)) {
            self::$usedNicknames = User::query()->pluck('nickname')->toArray();
        }

        $base = $firstName;

        if (! in_array($base, self::$usedNicknames, true)) {
            self::$usedNicknames[] = $base;
            return $base;
        }

        for ($i = 1; $i <= strlen($lastName); $i++) {
            $nickname = $firstName . ' ' . substr($lastName, 0, $i);

            if (! in_array($nickname, self::$usedNicknames, true)) {
                self::$usedNicknames[] = $nickname;
                return $nickname;
            }
        }

        $counter = 2;

        while (true) {
            $nickname = $firstName . ' ' . $lastName . $counter;

            if (! in_array($nickname, self::$usedNicknames, true)) {
                self::$usedNicknames[] = $nickname;
                return $nickname;
            }

            $counter++;
        }
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

    public static function resetNicknames(): void
    {
        self::$usedNicknames = [];
        self::$usedUsernames = [];
    }
}
