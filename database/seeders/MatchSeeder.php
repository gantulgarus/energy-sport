<?php

namespace Database\Seeders;

use App\Models\Sport;
use App\Models\Team;
use App\Models\GameMatch;
use Illuminate\Database\Seeder;

class MatchSeeder extends Seeder
{
    public function run(): void
    {
        $teamIds = Team::pluck('id')->toArray();
        shuffle($teamIds);

        $sports = Sport::all()->keyBy('slug');

        // 5-р сарын 22-27-ны хооронд хуваарьлана
        $baseDate = '2026-05-22';

        $schedules = [
            'basketball' => [
                'venue' => 'Улаанбаатар спортын ордон',
                'genders' => ['male', 'female'],
                'rounds' => ['Бүлгийн шат', 'Хагас финал', 'Финал'],
                'times' => ['09:00', '11:00', '14:00', '16:00'],
                'day_offset' => 0,
            ],
            'volleyball' => [
                'venue' => 'Улаанбаатар спортын ордон',
                'genders' => ['male', 'female'],
                'rounds' => ['Бүлгийн шат', 'Хагас финал', 'Финал'],
                'times' => ['10:00', '13:00', '15:30'],
                'day_offset' => 1,
            ],
            'table-tennis' => [
                'venue' => 'Ширээний теннисний заал',
                'genders' => ['male', 'female'],
                'rounds' => ['1/8 финал', 'Хагас финал', 'Финал'],
                'times' => ['09:00', '11:30', '14:00', '16:30'],
                'day_offset' => 2,
            ],
            'chess' => [
                'venue' => 'Шатарны заал',
                'genders' => ['male', 'female'],
                'rounds' => ['1-р тойрог', '2-р тойрог', '3-р тойрог', 'Финал'],
                'times' => ['09:00', '13:00'],
                'day_offset' => 3,
            ],
            'esports' => [
                'venue' => 'И-спортын төв',
                'genders' => ['mixed'],
                'rounds' => ['Бүлгийн шат', 'Хагас финал', 'Финал'],
                'times' => ['10:00', '14:00', '18:00'],
                'day_offset' => 4,
            ],
            'tug-of-war' => [
                'venue' => 'Гадаа талбай',
                'genders' => ['mixed'],
                'rounds' => ['1/4 финал', 'Хагас финал', 'Финал'],
                'times' => ['10:00', '13:00', '15:00'],
                'day_offset' => 5,
            ],
        ];

        $idx = 0;
        $totalTeams = count($teamIds);

        foreach ($schedules as $slug => $config) {
            $sport = $sports[$slug] ?? null;
            if (!$sport) continue;

            $dayDate = date('Y-m-d', strtotime($baseDate . ' +' . $config['day_offset'] . ' days'));

            foreach ($config['genders'] as $gender) {
                foreach ($config['rounds'] as $ri => $round) {
                    $matchCount = max(1, 4 - $ri); // бүлгийн шатанд илүү тоглолт
                    $time = $config['times'][$ri % count($config['times'])];

                    for ($m = 0; $m < $matchCount; $m++) {
                        $t1 = $teamIds[$idx % $totalTeams];
                        $t2 = $teamIds[($idx + 1) % $totalTeams];
                        $idx += 2;

                        if ($t1 === $t2) {
                            $t2 = $teamIds[($idx + 2) % $totalTeams];
                        }

                        $scheduledAt = $dayDate . ' ' . $time . ':00';
                        // цагийг бага зэрэг ялгаатай болго
                        $minuteOffset = $m * 30;
                        $scheduledAt = date('Y-m-d H:i:s', strtotime($scheduledAt) + $minuteOffset * 60);

                        GameMatch::create([
                            'sport_id'     => $sport->id,
                            'team1_id'     => $t1,
                            'team2_id'     => $t2,
                            'gender'       => $gender,
                            'round'        => $round,
                            'venue'        => $config['venue'],
                            'scheduled_at' => $scheduledAt,
                            'status'       => 'scheduled',
                        ]);
                    }
                }
            }
        }
    }
}
