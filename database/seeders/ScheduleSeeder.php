<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SchedulePeriod;
use App\Models\ServiceSession;
use App\Models\ScheduleAvailability;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // ambil 1 department (sesuaikan)
        $departmentId = \App\Models\Department::first()->id;

        // ======================
        // 1. CREATE PERIOD
        // ======================
        $period = SchedulePeriod::create([
            'id' => Str::uuid(),
            'department_id' => $departmentId,
            'month' => 5,
            'year' => 2026,
            'status' => 'open',
        ]);

        // ======================
        // 2. CREATE SESSIONS
        // ======================

        $startDate = Carbon::create(2026, 5, 1);

        $sessions = [];

        $week = 1;

        // cari semua minggu di bulan
        $sessionTimes = [
            1 => ['09:00', '10:30'],
            2 => ['11:00', '12:30'],
            3 => ['17:00', '18:30'],
        ];

        while ($startDate->month == 5) {

            if ($startDate->dayOfWeek == Carbon::SUNDAY) {

                foreach ($sessionTimes as $sessionNumber => [$start, $end]) {

                    $session = ServiceSession::create([
                        'id' => Str::uuid(),
                        'schedule_period_id' => $period->id,
                        'service_date' => $startDate->toDateString(),
                        'week_number' => $week,
                        'session_number' => $sessionNumber,
                        'start_time' => $start,
                        'end_time' => $end,
                    ]);

                    $sessions[] = $session;
                }

                $week++;
            }

            $startDate->addDay();
        }

        // ======================
        // 3. CREATE AVAILABILITY
        // ======================

        $users = User::take(30)->get(); // ambil 30 user

        foreach ($users as $user) {

            // RANDOM SCENARIO
            $mode = rand(1, 4);

            foreach ($sessions as $session) {

                $isAvailable = false;

                switch ($mode) {

                    // 1. rajin (banyak available)
                    case 1:
                        $isAvailable = rand(0, 100) < 80;
                        break;

                    // 2. normal
                    case 2:
                        $isAvailable = rand(0, 100) < 50;
                        break;

                    // 3. jarang
                    case 3:
                        $isAvailable = rand(0, 100) < 25;
                        break;

                    // 4. tidak submit sama sekali
                    case 4:
                        continue 2;
                }

                if ($isAvailable) {
                    ScheduleAvailability::create([
                        'id' => Str::uuid(),
                        'schedule_period_id' => $period->id,
                        'service_session_id' => $session->id,
                        'user_id' => $user->id,
                        'is_available' => true,
                    ]);
                }
            }
        }
    }
}
