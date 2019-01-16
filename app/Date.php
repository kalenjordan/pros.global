<?php

namespace App;

use \Carbon\Carbon;

class Date extends Carbon
{
    public function addBusinessDays($numDays)
    {
        for ($i = 0; $i < $numDays; $i++) {
            $this->addDay();
            $dayOfWeek = $this->format('w');
            if ($dayOfWeek == 5) {
                $this->addDays(2);
            } elseif ($dayOfWeek == 6) {
                $this->addDays(1);
            }
        }

        return $this;
    }

    public function subBusinessDays($numDays)
    {
        for ($i = 0; $i < $numDays; $i++) {
            $this->subDay();
            $dayOfWeek = $this->format('w');
            if ($dayOfWeek == 5) {
                $this->subDays(2);
            } elseif ($dayOfWeek == 6) {
                $this->subDays(1);
            }
        }

        return $this;
    }

    public function isDaysAgo($numDays)
    {
        return $this->addDays($numDays) < self::now();
    }

    public function isHoursAgo($numHours)
    {
        return $this->addHours($numHours) < self::now();
    }

    public function toDayDateString()
    {
        return $this->format('D, M j, Y');
    }

    public function toYearMonthDay()
    {
        return $this->format('Y-m-d');
    }

    public function toYearMonth()
    {
        return $this->format('Y-m');
    }

    public function toMonthYear()
    {
        return $this->format('M, Y');
    }

    public function shortDiffForHumans()
    {
        $diffInMinutes = $this->diffInMinutes(Date::now());
        if ($diffInMinutes == 0) {
            return 'now';
        } elseif ($diffInMinutes < 60) {
            return $diffInMinutes . 'm';
        } elseif ($diffInMinutes < 60 * 24) {
            return round($diffInMinutes / 60 ) . 'h';
        } elseif ($diffInMinutes < 60 * 24 * 14) {
            return round($diffInMinutes / 60 / 24) . 'd';
        } elseif ($diffInMinutes < 60 * 24 * 60) {
            return round($diffInMinutes / 60 / 24 / 7) . 'w';
        } elseif ($diffInMinutes < 60 * 24 * 700) {
            return round($diffInMinutes / 60 / 24 / 30) . 'mo';
        } else {
            return round($diffInMinutes / 60 / 24 / 365) . 'y';
        }
    }
}