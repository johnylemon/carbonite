<?php
namespace Lemon;

use Carbon\Carbon;

class Carbonite
{
    /**
     *  First Carbon instance
     *
     *   @var Carbon
     */
     protected $date1;


    /**
    *  Second Carbon instance
    *
    *   @var Carbon
    */
    protected $date2;


    /**
    *   Constructor. Breaks reference to the given Carbon objects
    *
    *   @param Carbon|null first date
    *   @param Carbon|null second date
    *   @param bool nstruct if Carbonite should take care of microtime
    *   @param bool $micro enables strict date comparisons (with microtime)
    */
    public function __construct(Carbon $date1 = NULL, Carbon $date2 = NULL, $micro = FALSE)
    {
        if($micro)
        {
            $this->_setDate('date1', $date1->copy());
            $this->_setDate('date2', $date2->copy());
        }
        else
        {
            $this->_setDate('date1', static::_clearCarbon($date1));
            $this->_setDate('date2', static::_clearCarbon($date2));
        }
    }


    /**
    *   Returns string representations
    *   of Carbon concatenated with hyphen
    *
    */
    public function __toString()
    {
        return implode(' - ', [$this->start(), $this->end()]);
    }


    /**
    *   Constructor
    *
    *   @param string date1 or date2 test
    *   @param Carbon|null Date to be set
    */
    protected function _setDate(string $name, $value)
    {
        $this->$name = $value ? $value : new Carbon;
    }


    /**
    *   Converts Carbon date or string to
    *   Carbon instance without microtime
    *   unless user provice mictorime in constructor
    *
    *   @param string date string that represents date or carbon instance
    *   @param Carbon new Carbon instance without microtime
    */
    protected static function _clearCarbon(string $date)
    {
        return Carbon::parse((string)$date);
    }


    /**
    *   Magic methods here
    *
    */
    public function __call($name, $args)
    {
        if(in_array($name, ['addSecond', 'addMinute', 'addHour', 'addDay', 'addWeek', 'addMonth', 'addYear']))
        {
            $this->end()->$name();
            $this->start()->$name();

            return $this;
        }
        elseif(in_array($name, ['addSeconds', 'addMinutes', 'addHours', 'addDays', 'addWeeks', 'addMonths', 'addYears']))
        {
            $this->end()->$name($args[0]);
            $this->start()->$name($args[0]);

            return $this;
        }
        elseif(in_array($name, ['subSecond', 'subMinute', 'subHour', 'subDay', 'subWeek', 'subMonth', 'subYear']))
        {
            $this->start()->$name();
            $this->end()->$name();

            return $this;
        }
        elseif(in_array($name, ['subSeconds', 'subMinutes', 'subHours', 'subDays', 'subWeeks', 'subMonths', 'subYears']))
        {
            $this->start()->$name($args[0]);
            $this->end()->$name($args[0]);

            return $this;
        }
        elseif(strpos($name, 'durationIn') == 0)
        {
            $key = 'durationIn';
            $unit = substr($name, strlen($key));
            if(in_array($unit, ['Seconds', 'Minutes', 'Hours', 'Days', 'Weeks', 'Months', 'Years']))
            {
                $method = 'diffIn' . $unit;
                return $this->end()->$method($this->start());
            }

            return $this;
        }
    }


    /**
    *   Returns information if strict mode is enabled
    *
    *   @return bool strict mode
    */
    public function strict()
    {
        $args = func_get_args();
        if(count($args))
            $this->strict = !!$args[0];

        return $this->strict;
    }


    /**
    *   Returns fresh copy of current instance
    *
    *   @return Carbonite freah copy  of current instance
    */
    public function copy()
    {
        return new self($this->date1->copy(), $this->date2->copy());
    }


    /**
    *   Creates new Carbonite instance
    *
    *   @param Carbon|null First date to be set
    *   @param Carbon|null Second date to be set
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with referenced dates
    */
    public static function new(Carbon $date1 = NULL, Carbon $date2 = NULL, $micro = FALSE)
    {
        return new self($date1, $date2, $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole day
    *
    *   @param Carbon $date Day we want to use
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with non referenced date
    */
    public static function day(Carbon $date, bool $micro = FALSE)
    {
        return self::new($date->copy()->startOfDay(), $date->copy()->endOfDay(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole week
    *
    *   @param Carbon $date Day we want to use
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with non referenced date
    */
    public static function week(Carbon $date, bool $micro = FALSE)
    {
        return self::new($date->copy()->startOfWeek(), $date->copy()->endOfWeek(), $micro);
    }

    /**
    *   Creates new Carbonite instance that contains whole month
    *
    *   @param Carbon $date Day we want to use
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with non referenced date
    */
    public static function month(Carbon $date, bool $micro = FALSE)
    {
        return self::new($date->copy()->startOfMonth(), $date->copy()->endOfMonth(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole year
    *
    *   @param Carbon $date Day we want to use
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with non referenced date
    */
    public static function year(Carbon $date, bool $micro = FALSE)
    {
        return self::new($date->copy()->startOfYear(), $date->copy()->endOfYear(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole decade
    *
    *   @param Carbon $date Day we want to use
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with non referenced date
    */
    public static function decade(Carbon $date, bool $micro = FALSE)
    {
        return self::new($date->copy()->startOfDecade(), $date->copy()->endOfDecade(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole century
    *
    *   @param Carbon $date Day we want to use
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance with non referenced date
    */
    public static function century(Carbon $date, bool $micro = FALSE)
    {
        return self::new($date->copy()->startOfCentury(), $date->copy()->endOfCentury(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole current day
    *
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance
    */
    public static function today(bool $micro = FALSE)
    {
        return self::day(Carbon::now(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole yesterday day
    *
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance
    */
    public static function yesterday(bool $micro = FALSE)
    {
        return self::day(Carbon::yesterday(), $micro);
    }


    /**
    *   Creates new Carbonite instance that contains whole tomorrow day
    *
    *   @param bool $micro Strict mode
    *   @return Carbonite new Carbonite instance
    */
    public static function tomorrow(bool $micro = FALSE)
    {
        return self::day(Carbon::tomorrow(), $micro);
    }


    /**
    *   Returns earlier date in current range
    *
    *   @return Carbon earlier date
    */
    public function start()
    {
        return $this->date1->min($this->date2);
    }


    /**
    *   Returns greater date in current range
    *
    *   @return Carbon greater date
    */
    public function end()
    {
        return $this->date1->max($this->date2);
    }


    /**
    *   Checks if date is in range
    *   Wrapper for native Carbon between method
    *
    *   @param Carbon $date date we want to check
    *   @param bool $equal (optional) opional param to check boundings
    *   @return bool
    */
    public function has(Carbon $date, bool $equal = TRUE)
    {
        return $date->between($this->start(), $this->end(), $equal);
    }


    /**
    *   Checks if ranges are same
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function same(Carbonite $range)
    {
        return $this->start()->eq($range->start()) && $this->end()->eq($range->end());
    }


    /**
    *   Checks if ranges are same
    *   Alias for `same` method
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function eq(Carbonite $range)
    {
        return $this->same($range);
    }


    /**
    *   Checks if current range starts with same date as given
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function startsWith(Carbonite $range)
    {
        return $this->start()->eq($range->start());
    }


    /**
    *   Checks if current range ends with same date as given
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function endsWith(Carbonite $range)
    {
        return $this->end()->eq($range->end());
    }


    /**
    *   Checks if current range is shorter than given
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function lt(Carbonite $range)
    {
        return $this->durationInSeconds() < $range->durationInSeconds();
    }


    /**
    *   Checks if current range is shorter or equal than given
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function lte(Carbonite $range)
    {
        return $this->durationInSeconds() <= $range->durationInSeconds();
    }


    /**
    *   Checks if current range is larger than given
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function gt(Carbonite $range)
    {
        return $this->durationInSeconds() > $range->durationInSeconds();
    }


    /**
    *   Checks if current range is larger or equal than given
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function gte(Carbonite $range)
    {
        return $this->durationInSeconds() >= $range->durationInSeconds();
    }


    /**
    *   Checks if current range is in given one
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function in(Carbonite $range)
    {
        return $this->start()->gt($range->start()) && $this->end()->lt($range->end());
    }


    /**
    *   Checks if current range is in or same as given one
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function inEq(Carbonite $range)
    {
        return $this->in($range) || $this->eq($range);
    }


    /**
    *   Checks if current range contains given one
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function contains(Carbonite $range)
    {
        return $this->start()->lt($range->start()) && $this->end()->gt($range->end());
    }


    /**
    *   Checks if current have common pat with given one
    *
    *   @param Carbonite $range range to check
    *   @return bool
    */
    public function overlaps(Carbonite $range)
    {
        return !!$this->common($range);
    }


    /**
    *   Splits Carbonite instance to two, using Carbon date
    *
    *   @param Carbon $date divider
    *   @return array Array of Carbonite instances. Empty when date is not in range
    */
    public function split(Carbon $date)
    {
        if(!$this->has($date))
            return [];

        return [self::new($this->start()->copy(), $date->copy()), self::new($date->copy(), $this->end()->copy())];
    }


    /**
    *   Merges two Carbonite instances
    *   I there is no common part, returns self copy
    *
    *   @param Carbonite $range Carbonite to me merged with
    *   @return Carbonite mergeed Carbonite
    */
    public function merge(Carbonite $range)
    {
        if($this->different($range))
            return $this->copy();

        return $this->mergeOuter($range);
    }


    /**
    *   Enforces merging of two Carbonite instances
    *   Uses the earliest and the latest dates
    *
    *   @param Carbonite $range Carbonite to me merged with
    *   @return Carbonite mergeed Carbonite
    */
    public function mergeOuter(Carbonite $range)
    {
        return self::new($this->start()->min($range->start())->copy(), $this->end()->max($range->max())->copy());
    }


    /**
    *   Checks if current instance has no common part
    *   with given one
    *
    *   @param Carbonite $range Carbonite instance to check
    *   @return bool
    */
    public function different(Carbonite $range)
    {
        return $this->end()->lt($range->start()) || $range->end()->lt($this->start());
    }


    /**
    *   Returns common part of two Carbonite instances
    *
    *   @param Carbonite $range Carbonite instance to check
    *   @return Carbonite|null common part, or null if there is no common part
    */
    public function common(Carbonite $range)
    {
        if($this->different($range))
            return NULL;

        if($this->eq($range))
            return $range->copy();

        if($this->contains($range))
            return $range->copy();

        if($this->in($range))
            return $this->copy();

        if($this->has($range->start(), TRUE) && $range->has($this->end(), TRUE))
            return new Carbonite($range->start()->copy(), $this->end()->copy());

        if($this->has($range->end(), TRUE) && $range->has($this->start(), TRUE))
            return new Carbonite($this->start()->copy(), $range->end()->copy());

        return NULL;
    }


    /**
    *   Calculates gap between Carbonites
    *
    *   @param Carbonite $range Carbonite instance to check
    *   @return Carbonite|null Carbonite representation of gap
    *   or NULL when there is no gap
    */
    public function gap(Carbonite $range)
    {
        if($this->end()->lt($range->start()))
            return new Carbonite($this->end()->copy(), $range->start()->copy());

        if($range->end()->lt($this->start()))
            return new Carbonite($range->end()->copy(), $this->start()->copy());

        return NULL;
    }
}
