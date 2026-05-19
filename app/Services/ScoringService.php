<?php

namespace App\Services;

class ScoringService
{
    // Points by place for each sport group
    private static array $scoring = [
        'chess'       => [1 => 8, 2 => 7, 3 => 6, 4 => 5, 'V-VIII' => 4, 'IX-XVI' => 3, 17 => 2, 18 => 1, 19 => 0.5],
        'table-tennis'=> [1 => 8, 2 => 7, 3 => 6, 4 => 5, 'V-VIII' => 4, 'IX-XVI' => 3, 17 => 2, 18 => 1, 19 => 0.5],
        'basketball'  => [1 => 12, 2 => 10, 3 => 8, 4 => 6, 'V-VIII' => 5, 'IX-XVI' => 4, 17 => 3, 18 => 2, 19 => 1],
        'volleyball'  => [1 => 12, 2 => 10, 3 => 8, 4 => 6, 'V-VIII' => 5, 'IX-XVI' => 4, 17 => 3, 18 => 2, 19 => 1],
        'esports'     => [1 => 10, 2 => 8, 3 => 6, 4 => 5, 'V-VIII' => 4, 'IX-XVI' => 3, 17 => 2, 18 => 1, 19 => 0.5],
        'tug-of-war'  => [1 => 12, 2 => 10, 3 => 8, 4 => 6, 'V-VIII' => 5, 'IX-XVI' => 4, 17 => 3, 18 => 2, 19 => 1],
    ];

    public static function getPoints(string $sportSlug, int $place): float
    {
        $table = self::$scoring[$sportSlug] ?? self::$scoring['basketball'];

        if (isset($table[$place])) {
            return (float) $table[$place];
        }
        if ($place >= 5 && $place <= 8) {
            return (float) $table['V-VIII'];
        }
        if ($place >= 9 && $place <= 16) {
            return (float) $table['IX-XVI'];
        }
        if ($place >= 19) {
            return (float) $table[19];
        }

        return 0.0;
    }

    public static function getScoringTable(string $sportSlug): array
    {
        return self::$scoring[$sportSlug] ?? self::$scoring['basketball'];
    }
}
