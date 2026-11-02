<?php

namespace App\Libraries;

use App\Models\HolidayModel;

class CalendarService
{
    private static ?array $holidayCache = null;

    /**
     * Get array of holiday date strings ('YYYY-MM-DD').
     *
     * @return array
     */
    public static function getHolidayDates(): array
    {
        if (self::$holidayCache !== null) {
            return self::$holidayCache;
        }

        try {
            $holidayModel = new HolidayModel();
            $records = $holidayModel->select('date')->findAll();
            self::$holidayCache = array_column($records, 'date');
        } catch (\Throwable $e) {
            log_message('error', 'CalendarService::getHolidayDates failed: ' . $e->getMessage());
            self::$holidayCache = [];
        }

        return self::$holidayCache;
    }

    /**
     * Clear cached holidays (useful for tests or after holiday updates).
     */
    public static function clearCache(): void
    {
        self::$holidayCache = null;
    }

    /**
     * Check if a given date is on a weekend (Saturday or Sunday).
     *
     * @param string|\DateTimeInterface $date
     * @return bool
     */
    public static function isWeekend($date): bool
    {
        $dt = ($date instanceof \DateTimeInterface) ? $date : new \DateTime($date);
        $dayOfWeek = (int)$dt->format('w'); // 0 = Sunday, 6 = Saturday
        return ($dayOfWeek === 0 || $dayOfWeek === 6);
    }

    /**
     * Check if a given date is a registered holiday.
     *
     * @param string|\DateTimeInterface $date
     * @param array|null $holidayDates
     * @return bool
     */
    public static function isHoliday($date, ?array $holidayDates = null): bool
    {
        $dt = ($date instanceof \DateTimeInterface) ? $date : new \DateTime($date);
        $dateStr = $dt->format('Y-m-d');
        $holidays = $holidayDates ?? self::getHolidayDates();

        return in_array($dateStr, $holidays, true);
    }

    /**
     * Check if a given date is a working day (neither weekend nor holiday).
     *
     * @param string|\DateTimeInterface $date
     * @param array|null $holidayDates
     * @return bool
     */
    public static function isWorkingDay($date, ?array $holidayDates = null): bool
    {
        return !self::isWeekend($date) && !self::isHoliday($date, $holidayDates);
    }

    /**
     * Add a number of working days to a start date, skipping weekends and holidays.
     * Matches the frontend addWorkingDays() implementation.
     *
     * @param string|\DateTimeInterface $startDate
     * @param int $daysToAdd
     * @param array|null $holidayDates
     * @return \DateTime
     */
    public static function addWorkingDays($startDate, int $daysToAdd, ?array $holidayDates = null): \DateTime
    {
        $current = ($startDate instanceof \DateTime) ? clone $startDate : new \DateTime($startDate);
        $current->setTime(0, 0, 0);

        $holidays = $holidayDates ?? self::getHolidayDates();
        $daysAdded = 0;

        while ($daysAdded < $daysToAdd) {
            $current->modify('+1 day');
            if (self::isWorkingDay($current, $holidays)) {
                $daysAdded++;
            }
        }

        return $current;
    }

    /**
     * Calculate difference in working days between two dates.
     * Matches the frontend getWorkingDaysDiff() implementation.
     * Returns positive if start < end, negative if start > end, 0 if same day.
     *
     * @param string|\DateTimeInterface $startDate
     * @param string|\DateTimeInterface $endDate
     * @param array|null $holidayDates
     * @return int
     */
    public static function getWorkingDaysDiff($startDate, $endDate, ?array $holidayDates = null): int
    {
        $start = ($startDate instanceof \DateTime) ? clone $startDate : new \DateTime($startDate);
        $end = ($endDate instanceof \DateTime) ? clone $endDate : new \DateTime($endDate);

        $start->setTime(0, 0, 0);
        $end->setTime(0, 0, 0);

        if ($start == $end) {
            return 0;
        }

        $isNegative = false;
        if ($start > $end) {
            $isNegative = true;
            $temp = $start;
            $start = $end;
            $end = $temp;
        }

        $holidays = $holidayDates ?? self::getHolidayDates();
        $workingDaysCount = 0;
        $current = clone $start;

        while ($current < $end) {
            $current->modify('+1 day');
            if (self::isWorkingDay($current, $holidays)) {
                $workingDaysCount++;
            }
        }

        return $isNegative ? -$workingDaysCount : $workingDaysCount;
    }
}

