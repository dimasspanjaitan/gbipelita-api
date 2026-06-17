<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Models\SchedulePeriod;
use App\Models\ServiceSession;
use App\Models\ScheduleAvailability;
use App\Models\ScheduleUserPeriodStatus;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        // ambil 1 department (sesuaikan)
        $departmentId = \App\Models\Department::query()->first()->id;

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
            1 => ['IBRA 1&2 (PAGI)', '09:00', '10:30', '11:00', '12:30'],
            2 => ['IBRA 3&4 (SORE)', '14:00', '15:30', '16:00', '17:30'],
            3 => ['IBRA 5&6 (MALAM)', '18:00', '19:30', '20:00', '21:30'],
        ];

        while ($startDate->month == 5) {

            if ($startDate->dayOfWeek == Carbon::SUNDAY) {

                foreach ($sessionTimes as $sessionNumber => [$name, $start, $end, $start2, $end2]) {

                    $session = ServiceSession::create([
                        'id' => Str::uuid(),
                        'name' => $name,
                        'time' => '(' . $start . '-' . $end . ') & (' . $start2 . '-' . $end2 . ')',
                        'schedule_period_id' => $period->id,
                        'service_date' => $startDate->toDateString(),
                        'week_number' => $week,
                        'session_number' => $sessionNumber,
                        'start_time' => $start,
                        'end_time' => $end2,
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
        $volunteers = User::query()
            ->role('Volunteer')
            ->where('status', 'active')
            ->get();

        foreach ($volunteers as $volunteer) {
            ScheduleUserPeriodStatus::create([
                'id' => Str::uuid(),
                'schedule_period_id' => $period->id,
                'user_id' => $volunteer->id,
                'has_submitted' => false,
            ]);
        }

        $users = $volunteers->take(40); // ambil 30 volunteer

        foreach ($users as $user) {
            /*
            |--------------------------------------------------------------------------
            | MODE
            |--------------------------------------------------------------------------
            | 1 = rajin (80%)
            | 2 = normal (50%)
            | 3 = jarang (25%)
            | 4 = tidak submit
            | 5 = submit tapi tidak available sama sekali
            |--------------------------------------------------------------------------
            */
            $mode = rand(1, 5);

            $hasSubmitted = $mode !== 4;
            ScheduleUserPeriodStatus::query()
                ->where('schedule_period_id', $period->id)
                ->where('user_id', $user->id)
                ->update([
                    'has_submitted' => $hasSubmitted
                ]);

            // mode = 4 = tidak submit
            if (!$hasSubmitted) {
                continue;
            }

            foreach ($sessions as $session) {

                $isAvailable = false;

                switch ($mode) {
                    // 1. rajin (banyak available)
                    case 1:
                        $isAvailable = rand(0, 100) <= 80;
                        break;

                    // 2. normal
                    case 2:
                        $isAvailable = rand(0, 100) <= 50;
                        break;

                    // 3. jarang
                    case 3:
                        $isAvailable = rand(0, 100) <= 25;
                        break;

                    // 5. submit tapi tidak available sama sekali
                    case 5:
                        $isAvailable = false;
                        break;
                }

                if (!$isAvailable) continue;

                ScheduleAvailability::create([
                    'id' => Str::uuid(),
                    'schedule_period_id' => $period->id,
                    'service_session_id' => $session->id,
                    'user_id' => $user->id,
                    'is_available' => true,
                ]);
            }
        }

        $submittedCount = ScheduleUserPeriodStatus::query()
            ->where('has_submitted', true)
            ->count();
        $notSubmittedCount = ScheduleUserPeriodStatus::query()
            ->where('has_submitted', false)
            ->count();

        $period->update([
            'submitted_count' => $submittedCount,
            'not_submitted_count' => $notSubmittedCount
        ]);
    }
}
