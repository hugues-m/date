<?php

namespace HMLB\Date;

use Closure;
use DatePeriod;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use HMLB\Date\Translation\DateLocalizationCapabilities;
use InvalidArgumentException;

/**
 * An immutable Date objects with a rich API.
 *
 * @author Hugues Maignol <hugues@hmlb.fr>
 */
class Date extends DateTimeImmutable
{
    use DateLocalizationCapabilities;

    const ATOM = 'Y-m-d\TH:i:sP';
    const COOKIE = 'l, d-M-y H:i:s T';
    const ISO8601 = 'Y-m-d\TH:i:sO';
    const RFC822 = 'D, d M y H:i:s O';
    const RFC850 = 'l, d-M-y H:i:s T';
    const RFC1036 = 'D, d M y H:i:s O';
    const RFC1123 = 'D, d M Y H:i:s O';
    const RFC2822 = 'D, d M Y H:i:s O';
    const RFC3339 = 'Y-m-d\TH:i:sP';
    const RSS = 'D, d M Y H:i:s O';
    const W3C = 'Y-m-d\TH:i:sP';

    /**
     * The day constants.
     */
    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;

    /**
     * Names of days of the week.
     *
     * @var array
     */
    protected static $days = [
        self::SUNDAY => 'Sunday',
        self::MONDAY => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY => 'Wednesday',
        self::THURSDAY => 'Thursday',
        self::FRIDAY => 'Friday',
        self::SATURDAY => 'Saturday',
    ];

    /**
     * Terms used to detect if a time passed is a relative date for testing purposes.
     *
     * @var array
     */
    protected static $relativeKeywords = [
        'this',
        'next',
        'last',
        'tomorrow',
        'yesterday',
        '+',
        '-',
        'first',
        'last',
        'ago',
    ];

    /**
     * Number of X in Y.
     */
    const YEARS_PER_CENTURY = 100;
    const YEARS_PER_DECADE = 10;
    const MONTHS_PER_YEAR = 12;
    const WEEKS_PER_YEAR = 52;
    const DAYS_PER_WEEK = 7;
    const HOURS_PER_DAY = 24;
    const MINUTES_PER_HOUR = 60;
    const SECONDS_PER_MINUTE = 60;

    /**
     * Default format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    const DEFAULT_TO_STRING_FORMAT = 'Y-m-d H:i:s';

    /**
     * Format to use for __toString method when type juggling occurs.
     *
     * @var string
     */
    protected static $toStringFormat = self::DEFAULT_TO_STRING_FORMAT;

    /**
     * First day of week.
     *
     * @var int
     */
    protected static $weekStartsAt = self::MONDAY;

    /**
     * Last day of week.
     *
     * @var int
     */
    protected static $weekEndsAt = self::SUNDAY;

    /**
     * Days of weekend.
     *
     * @var array
     */
    protected static $weekendDays = [self::SATURDAY, self::SUNDAY];

    /**
     * A test Date instance to be returned when now instances are created.
     *
     * @var Date
     */
    protected static $testNow;

    /**
     * Creates a DateTimeZone from a string or a DateTimeZone.
     *
     * @param DateTimeZone|string|null $object
     *
     * @throws InvalidArgumentException
     *
     * @return DateTimeZone
     */
    protected static function safeCreateDateTimeZone($object)
    {
        if ($object === null) {
            // Don't return null... avoid Bug #52063 in PHP <5.3.6
            return new DateTimeZone(date_default_timezone_get());
        }

        if ($object instanceof DateTimeZone) {
            return $object;
        }

        $tz = @timezone_open((string) $object);

        if ($tz === false) {
            throw new InvalidArgumentException('Unknown or bad timezone ('.$object.')');
        }

        return $tz;
    }

    ///////////////////////////////////////////////////////////////////
    //////////////////////////// CONSTRUCTORS /////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Create a new Date instance.
     *
     * Please see the testing aids section (specifically static::setTestNow())
     * for more on the possibility of this constructor returning a test instance.
     *
     * @param string                   $time
     * @param DateTimeZone|string|null $tz
     */
    public function __construct($time = 'now', $tz = null)
    {
        // If the class has a test now set and we are trying to create a now()
        // instance then override as required
        if (static::hasTestNow() && (empty($time) || $time === 'now' || static::hasRelativeKeywords($time))) {
            $testInstance = clone static::getTestNow();
            if (static::hasRelativeKeywords($time)) {
                $testInstance = $testInstance->modify($time);
            }

            //shift the time according to the given time zone
            if ($tz !== null && $tz !== static::getTestNow()->getTimezone()) {
                $testInstance = $testInstance->setTimezone($tz);
            } else {
                $tz = $testInstance->getTimezone();
            }

            $time = $testInstance->toDateTimeString();
        }

        parent::__construct($time, static::safeCreateDateTimeZone($tz));
    }

    /**
     * Create a Date instance from a DateTime one.
     *
     * @param DateTimeInterface $dt
     *
     * @return static
     */
    public static function instance(DateTimeInterface $dt)
    {
        return new static($dt->format('Y-m-d H:i:s.u'), $dt->getTimeZone());
    }

    /**
     * Create a date instance from a string.  This is an alias for the
     * constructor that allows better fluent syntax as it allows you to do
     * Date::parse('Monday next week')->fn() rather than
     * (new Date('Monday next week'))->fn().
     *
     * @param string                   $time
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function parse($time = 'now', $tz = null)
    {
        return new static($time, $tz);
    }

    /**
     * Get a Date instance for the current date and time.
     *
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function now($tz = null)
    {
        return new static('now', $tz);
    }

    /**
     * Create a Date instance for today.
     *
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function today($tz = null)
    {
        return static::now($tz)->startOfDay();
    }

    /**
     * Create a Date instance for tomorrow.
     *
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function tomorrow($tz = null)
    {
        return static::today($tz)->addDay();
    }

    /**
     * Create a Date instance for yesterday.
     *
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function yesterday($tz = null)
    {
        return static::today($tz)->subDay();
    }

    /**
     * Create a Date instance for the greatest supported date.
     *
     * @return Date
     */
    public static function maxValue()
    {
        if (PHP_INT_SIZE === 4) {
            // 32 bit (and additionally Windows 64 bit)
            return static::createFromTimestamp(PHP_INT_MAX);
        }

        // 64 bit
        return static::create(9999, 12, 31, 23, 59, 59);
    }

    /**
     * Create a Date instance for the lowest supported date.
     *
     * @return Date
     */
    public static function minValue()
    {
        if (PHP_INT_SIZE === 4) {
            // 32 bit (and additionally Windows 64 bit)
            return static::createFromTimestamp(~PHP_INT_MAX);
        }

        // 64 bit
        return static::create(1, 1, 1, 0, 0, 0);
    }

    /**
     * Create a new Date instance from a specific date and time.
     *
     * If any of $year, $month or $day are set to null their now() values
     * will be used.
     *
     * If $hour is null it will be set to its now() value and the default values
     * for $minute and $second will be their now() values.
     * If $hour is not null then the default values for $minute and $second
     * will be 0.
     *
     * @param int|null                 $year
     * @param int|null                 $month
     * @param int|null                 $day
     * @param int|null                 $hour
     * @param int|null                 $minute
     * @param int|null                 $second
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function create(
        $year = null,
        $month = null,
        $day = null,
        $hour = null,
        $minute = null,
        $second = null,
        $tz = null
    ) {
        $year = $year === null ? date('Y') : $year;
        $month = $month === null ? date('n') : $month;
        $day = $day === null ? date('j') : $day;

        if ($hour === null) {
            $hour = date('G');
            $minute = $minute === null ? date('i') : $minute;
            $second = $second === null ? date('s') : $second;
        } else {
            $minute = $minute === null ? 0 : $minute;
            $second = $second === null ? 0 : $second;
        }

        return static::createFromFormat(
            'Y-n-j G:i:s',
            sprintf('%s-%s-%s %s:%02s:%02s', $year, $month, $day, $hour, $minute, $second),
            $tz
        );
    }

    /**
     * Create a Date instance from just a date. The time portion is set to now.
     *
     * @param int|null                 $year
     * @param int|null                 $month
     * @param int|null                 $day
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function createFromDate($year = null, $month = null, $day = null, $tz = null)
    {
        return static::create($year, $month, $day, null, null, null, $tz);
    }

    /**
     * Create a Date instance from just a time. The date portion is set to today.
     *
     * @param int|null                 $hour
     * @param int|null                 $minute
     * @param int|null                 $second
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function createFromTime($hour = null, $minute = null, $second = null, $tz = null)
    {
        return static::create(null, null, null, $hour, $minute, $second, $tz);
    }

    /**
     * Create a Date instance from a specific format.
     *
     * @param string                   $format
     * @param string                   $time
     * @param DateTimeZone|string|null $tz
     *
     * @throws InvalidArgumentException
     *
     * @return static
     */
    public static function createFromFormat($format, $time, $tz = null)
    {
        if ($tz !== null) {
            $dt = parent::createFromFormat($format, $time, static::safeCreateDateTimeZone($tz));
        } else {
            $dt = parent::createFromFormat($format, $time);
        }

        if ($dt instanceof DateTimeInterface) {
            return static::instance($dt);
        }

        $errors = static::getLastErrors();
        throw new InvalidArgumentException(implode(PHP_EOL, $errors['errors']));
    }

    /**
     * Create a Date instance from a timestamp.
     *
     * @param int                      $timestamp
     * @param DateTimeZone|string|null $tz
     *
     * @return static
     */
    public static function createFromTimestamp($timestamp, $tz = null)
    {
        return static::now($tz)->setTimestamp($timestamp);
    }

    /**
     * Create a Date instance from an UTC timestamp.
     *
     * @param int $timestamp
     *
     * @return static
     */
    public static function createFromTimestampUTC($timestamp)
    {
        return new static('@'.$timestamp);
    }

    /**
     * Get a copy of the instance.
     *
     * @return static
     */
    public function copy()
    {
        return static::instance($this);
    }

    ///////////////////////////////////////////////////////////////////
    ///////////////////////// GETTERS AND SETTERS /////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * @return int
     */
    public function getYear()
    {
        return (int) $this->format('Y');
    }

    /**
     * @return int
     */
    public function getYearIso()
    {
        return (int) $this->format('o');
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return (int) $this->format('n');
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return (int) $this->format('j');
    }

    /**
     * @return int
     */
    public function getHour()
    {
        return (int) $this->format('G');
    }

    /**
     * @return int
     */
    public function getMinute()
    {
        return (int) $this->format('i');
    }

    /**
     * @return int
     */
    public function getSecond()
    {
        return (int) $this->format('s');
    }

    /**
     * @return int
     */
    public function getMicro()
    {
        return (int) $this->format('u');
    }

    /**
     * @return int
     */
    public function getDayOfWeek()
    {
        return (int) $this->format('w');
    }

    /**
     * @return int
     */
    public function getDayOfYear()
    {
        return (int) $this->format('z');
    }

    /**
     * @return int
     */
    public function getWeekOfYear()
    {
        return (int) $this->format('W');
    }

    /**
     * @return int
     */
    public function getDaysInMonth()
    {
        return (int) $this->format('t');
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return (int) $this->format('U');
    }

    /**
     * @return int
     */
    public function getWeekOfMonth()
    {
        return (int) ceil($this->getDay() / static::DAYS_PER_WEEK);
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return (int) $this->diffInYears();
    }

    /**
     * @return int
     */
    public function getQuarter()
    {
        return (int) ceil($this->getMonth() / 3);
    }

    /**
     * @return int
     */
    public function getOffsetHours()
    {
        return $this->getOffset() / static::SECONDS_PER_MINUTE / static::MINUTES_PER_HOUR;
    }

    /**
     * @return int
     */
    public function getDst()
    {
        return $this->format('I') === '1';
    }

    /**
     * @return int
     */
    public function getDaylightSavingTime()
    {
        return $this->getDst();
    }

    /**
     * @return int
     */
    public function getLocal()
    {
        return $this->getOffset() === $this->setTimezone(date_default_timezone_get())->getOffset();
    }

    /**
     * @return bool
     */
    public function isUtc()
    {
        return $this->getOffset() === 0;
    }

    /**
     * @return int
     */
    public function getTimezoneName()
    {
        return $this->getTimezone()->getName();
    }

    /**
     * Set the instance's year.
     *
     * @param int $value
     *
     * @return static
     */
    public function year($value)
    {
        return $this->setDate($value, $this->getMonth(), $this->getDay());
    }

    /**
     * Set the instance's month.
     *
     * @param int $value
     *
     * @return static
     */
    public function month($value)
    {
        return $this->setDate($this->getYear(), $value, $this->getDay());
    }

    /**
     * Set the instance's day.
     *
     * @param int $value
     *
     * @return static
     */
    public function day($value)
    {
        return $this->setDate($this->getYear(), $this->getMonth(), $value);
    }

    /**
     * Set the instance's hour.
     *
     * @param int $value
     *
     * @return static
     */
    public function hour($value)
    {
        return $this->setTime($value, $this->getMinute(), $this->getSecond());
    }

    /**
     * Set the instance's minute.
     *
     * @param int $value
     *
     * @return static
     */
    public function minute($value)
    {
        return $this->setTime($this->getHour(), $value, $this->getSecond());
    }

    /**
     * Set the instance's second.
     *
     * @param int $value
     *
     * @return static
     */
    public function second($value)
    {
        return $this->setTime($this->getHour(), $this->getMinute(), $value);
    }

    /**
     * Set the date and time all together.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     *
     * @return static
     */
    public function setDateTime($year, $month, $day, $hour, $minute = 0, $second = 0)
    {
        return $this->setDate($year, $month, $day)->setTime($hour, $minute, $second);
    }

    /**
     * Set the time by time string.
     *
     * @param string $time
     *
     * @return static
     */
    public function setTimeFromTimeString($time)
    {
        $time = explode(':', $time);

        $hour = (int) $time[0];
        $minute = isset($time[1]) ? (int) $time[1] : 0;
        $second = isset($time[2]) ? (int) $time[2] : 0;

        return $this->setTime($hour, $minute, $second);
    }

    /**
     * Set the instance's timestamp.
     *
     * @param int $value
     *
     * @return static
     */
    public function timestamp($value)
    {
        return $this->setTimestamp($value);
    }

    /**
     * Alias for setTimezone().
     *
     * @param DateTimeZone|string $value
     *
     * @return static
     */
    public function timezone($value)
    {
        return $this->setTimezone($value);
    }

    /**
     * Set the instance's timezone from a string or object.
     *
     * @param DateTimeZone|string $value
     *
     * @return static
     */
    public function setTimezone($value)
    {
        return parent::setTimezone(static::safeCreateDateTimeZone($value));
    }

    ///////////////////////////////////////////////////////////////////
    /////////////////////// WEEK SPECIAL DAYS /////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Get the first day of week.
     *
     * @return int
     */
    public static function getWeekStartsAt()
    {
        return static::$weekStartsAt;
    }

    /**
     * Set the first day of week.
     *
     * @param int
     */
    public static function setWeekStartsAt($day)
    {
        static::$weekStartsAt = $day;
    }

    /**
     * Get the last day of week.
     *
     * @return int
     */
    public static function getWeekEndsAt()
    {
        return static::$weekEndsAt;
    }

    /**
     * Set the first day of week.
     *
     * @param int
     */
    public static function setWeekEndsAt($day)
    {
        static::$weekEndsAt = $day;
    }

    /**
     * Get weekend days.
     *
     * @return array
     */
    public static function getWeekendDays()
    {
        return static::$weekendDays;
    }

    /**
     * Set weekend days.
     *
     * @param array
     */
    public static function setWeekendDays($days)
    {
        static::$weekendDays = $days;
    }

    ///////////////////////////////////////////////////////////////////
    ///////////////////////// TESTING AIDS ////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Set a Date instance (real or mock) to be returned when a "now"
     * instance is created.  The provided instance will be returned
     * specifically under the following conditions:
     *   - A call to the static now() method, ex. Date::now()
     *   - When a null (or blank string) is passed to the constructor or parse(), ex. new Date(null)
     *   - When the string "now" is passed to the constructor or parse(), ex. new Date('now').
     *
     * Note the timezone parameter was left out of the examples above and
     * has no affect as the mock value will be returned regardless of its value.
     *
     * To clear the test instance call this method using the default
     * parameter of null.
     *
     * @param Date|null $testNow
     */
    public static function setTestNow(Date $testNow = null)
    {
        static::$testNow = $testNow;
    }

    /**
     * Get the Date instance (real or mock) to be returned when a "now"
     * instance is created.
     *
     * @return static the current instance used for testing
     */
    public static function getTestNow()
    {
        return static::$testNow;
    }

    /**
     * Determine if there is a valid test instance set. A valid test instance
     * is anything that is not null.
     *
     * @return bool true if there is a test instance, otherwise false
     */
    public static function hasTestNow()
    {
        return static::getTestNow() !== null;
    }

    /**
     * Determine if there is a relative keyword in the time string, this is to
     * create dates relative to now for test instances. e.g.: next tuesday.
     *
     * @param string $time
     *
     * @return bool true if there is a keyword, otherwise false
     */
    public static function hasRelativeKeywords($time)
    {
        // skip common format with a '-' in it
        if (preg_match('/[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/', $time) !== 1) {
            foreach (static::$relativeKeywords as $keyword) {
                if (stripos($time, $keyword) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    ///////////////////////////////////////////////////////////////////
    /////////////////////// STRING FORMATTING /////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Format the instance with the current locale.  You can set the current
     * locale using setlocale() http://php.net/setlocale.
     *
     * @param string $format
     *
     * @return string
     */
    public function formatLocalized($format)
    {
        // Check for Windows to find and replace the %e
        // modifier correctly
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format);
        }

        return strftime($format, strtotime($this));
    }

    /**
     * Reset the format used to the default when type juggling a Date instance to a string.
     */
    public static function resetToStringFormat()
    {
        static::setToStringFormat(static::DEFAULT_TO_STRING_FORMAT);
    }

    /**
     * Set the default format used when type juggling a Date instance to a string.
     *
     * @param string $format
     */
    public static function setToStringFormat($format)
    {
        static::$toStringFormat = $format;
    }

    /**
     * Format the instance as a string using the set format.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->format(static::$toStringFormat);
    }

    /**
     * Format the instance as date.
     *
     * @return string
     */
    public function toDateString()
    {
        return $this->format('Y-m-d');
    }

    /**
     * Format the instance as a readable date.
     *
     * @return string
     */
    public function toFormattedDateString()
    {
        return $this->format('M j, Y');
    }

    /**
     * Format the instance as time.
     *
     * @return string
     */
    public function toTimeString()
    {
        return $this->format('H:i:s');
    }

    /**
     * Format the instance as date and time.
     *
     * @return string
     */
    public function toDateTimeString()
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * Format the instance with day, date and time.
     *
     * @return string
     */
    public function toDayDateTimeString()
    {
        return $this->format('D, M j, Y g:i A');
    }

    /**
     * Format the instance as ATOM.
     *
     * @return string
     */
    public function toAtomString()
    {
        return $this->format(static::ATOM);
    }

    /**
     * Format the instance as COOKIE.
     *
     * @return string
     */
    public function toCookieString()
    {
        return $this->format(static::COOKIE);
    }

    /**
     * Format the instance as ISO8601.
     *
     * @return string
     */
    public function toIso8601String()
    {
        return $this->format(static::ISO8601);
    }

    /**
     * Format the instance as RFC822.
     *
     * @return string
     */
    public function toRfc822String()
    {
        return $this->format(static::RFC822);
    }

    /**
     * Format the instance as RFC850.
     *
     * @return string
     */
    public function toRfc850String()
    {
        return $this->format(static::RFC850);
    }

    /**
     * Format the instance as RFC1036.
     *
     * @return string
     */
    public function toRfc1036String()
    {
        return $this->format(static::RFC1036);
    }

    /**
     * Format the instance as RFC1123.
     *
     * @return string
     */
    public function toRfc1123String()
    {
        return $this->format(static::RFC1123);
    }

    /**
     * Format the instance as RFC2822.
     *
     * @return string
     */
    public function toRfc2822String()
    {
        return $this->format(static::RFC2822);
    }

    /**
     * Format the instance as RFC3339.
     *
     * @return string
     */
    public function toRfc3339String()
    {
        return $this->format(static::RFC3339);
    }

    /**
     * Format the instance as RSS.
     *
     * @return string
     */
    public function toRssString()
    {
        return $this->format(static::RSS);
    }

    /**
     * Format the instance as W3C.
     *
     * @return string
     */
    public function toW3cString()
    {
        return $this->format(static::W3C);
    }

    ///////////////////////////////////////////////////////////////////
    ////////////////////////// COMPARISONS ////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Determines if the instance is equal to another.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function equals(Date $dt)
    {
        return $this == $dt;
    }

    /**
     * Determines if the instance is not equal to another.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function notEquals(Date $dt)
    {
        return !$this->equals($dt);
    }

    /**
     * Determines if the instance is greater (after) than another.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function isAfter(Date $dt)
    {
        return $this > $dt;
    }

    /**
     * Determines if the instance is greater (after) than or equal to another.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function isAfterOrEquals(Date $dt)
    {
        return $this >= $dt;
    }

    /**
     * Determines if the instance is less (before) than another.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function isBefore(Date $dt)
    {
        return $this < $dt;
    }

    /**
     * Determines if the instance is less (before) or equal to another.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function isBeforeOrEquals(Date $dt)
    {
        return $this <= $dt;
    }

    /**
     * Determines if the instance is between two others.
     *
     * @param Date $dt1
     * @param Date $dt2
     * @param bool $equal Indicates if a > and < comparison should be used or <= or >=
     *
     * @return bool
     */
    public function isBetween(Date $dt1, Date $dt2, $equal = true)
    {
        if ($dt1->isAfter($dt2)) {
            $temp = $dt1;
            $dt1 = $dt2;
            $dt2 = $temp;
        }

        if ($equal) {
            return $this->isAfterOrEquals($dt1) && $this->isBeforeOrEquals($dt2);
        }

        return $this->isAfter($dt1) && $this->isBefore($dt2);
    }

    /**
     * Get the closest date from the instance.
     *
     * @param Date $dt1
     * @param Date $dt2
     *
     * @return static
     */
    public function closest(Date $dt1, Date $dt2)
    {
        return $this->diffInSeconds($dt1) < $this->diffInSeconds($dt2) ? $dt1 : $dt2;
    }

    /**
     * Get the farthest date from the instance.
     *
     * @param Date $dt1
     * @param Date $dt2
     *
     * @return static
     */
    public function farthest(Date $dt1, Date $dt2)
    {
        return $this->diffInSeconds($dt1) > $this->diffInSeconds($dt2) ? $dt1 : $dt2;
    }

    /**
     * Get the minimum instance between a given instance (default now) and the current instance.
     *
     * @param Date|null $dt
     *
     * @return static
     */
    public function min(Date $dt = null)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return $this->isBefore($dt) ? clone $this : clone $dt;
    }

    /**
     * Get the maximum instance between a given instance (default now) and the current instance.
     *
     * @param Date|null $dt
     *
     * @return static
     */
    public function max(Date $dt = null)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return $this->isAfter($dt) ? clone $this : clone $dt;
    }

    /**
     * Determines if the instance is a weekday.
     *
     * @return bool
     */
    public function isWeekday()
    {
        return !$this->isWeekend();
    }

    /**
     * Determines if the instance is a weekend day.
     *
     * @return bool
     */
    public function isWeekend()
    {
        return in_array($this->getDayOfWeek(), self::$weekendDays);
    }

    /**
     * Determines if the instance is yesterday.
     *
     * @return bool
     */
    public function isYesterday()
    {
        return $this->toDateString() === static::yesterday($this->getTimezone())->toDateString();
    }

    /**
     * Determines if the instance is today.
     *
     * @return bool
     */
    public function isToday()
    {
        return $this->toDateString() === static::now($this->getTimezone())->toDateString();
    }

    /**
     * Determines if the instance is tomorrow.
     *
     * @return bool
     */
    public function isTomorrow()
    {
        return $this->toDateString() === static::tomorrow($this->getTimezone())->toDateString();
    }

    /**
     * Determines if the instance is in the future, ie. greater (after) than now.
     *
     * @return bool
     */
    public function isFuture()
    {
        return $this->isAfter(static::now($this->getTimezone()));
    }

    /**
     * Determines if the instance is in the past, ie. less (before) than now.
     *
     * @return bool
     */
    public function isPast()
    {
        return $this->isBefore(static::now($this->getTimezone()));
    }

    /**
     * Determines if the instance is a leap year.
     *
     * @return bool
     */
    public function isLeapYear()
    {
        return $this->format('L') === '1';
    }

    /**
     * Checks if the passed in date is the same day as the instance current day.
     *
     * @param Date $dt
     *
     * @return bool
     */
    public function isSameDay(Date $dt)
    {
        return $this->toDateString() === $dt->toDateString();
    }

    /**
     * Checks if this day is a Sunday.
     *
     * @return bool
     */
    public function isSunday()
    {
        return $this->getDayOfWeek() === static::SUNDAY;
    }

    /**
     * Checks if this day is a Monday.
     *
     * @return bool
     */
    public function isMonday()
    {
        return $this->getDayOfWeek() === static::MONDAY;
    }

    /**
     * Checks if this day is a Tuesday.
     *
     * @return bool
     */
    public function isTuesday()
    {
        return $this->getDayOfWeek() === static::TUESDAY;
    }

    /**
     * Checks if this day is a Wednesday.
     *
     * @return bool
     */
    public function isWednesday()
    {
        return $this->getDayOfWeek() === static::WEDNESDAY;
    }

    /**
     * Checks if this day is a Thursday.
     *
     * @return bool
     */
    public function isThursday()
    {
        return $this->getDayOfWeek() === static::THURSDAY;
    }

    /**
     * Checks if this day is a Friday.
     *
     * @return bool
     */
    public function isFriday()
    {
        return $this->getDayOfWeek() === static::FRIDAY;
    }

    /**
     * Checks if this day is a Saturday.
     *
     * @return bool
     */
    public function isSaturday()
    {
        return $this->getDayOfWeek() === static::SATURDAY;
    }

    ///////////////////////////////////////////////////////////////////
    /////////////////// ADDITIONS AND SUBTRACTIONS ////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Add years to the instance. Positive $value travel forward while
     * negative $value travel into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addYears($value)
    {
        return $this->modify((int) $value.' year');
    }

    /**
     * Add a year to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addYear($value = 1)
    {
        return $this->addYears($value);
    }

    /**
     * Remove a year from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subYear($value = 1)
    {
        return $this->subYears($value);
    }

    /**
     * Remove years from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subYears($value)
    {
        return $this->addYears(-1 * $value);
    }

    /**
     * Add months to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addMonths($value)
    {
        return $this->modify((int) $value.' month');
    }

    /**
     * Add a month to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addMonth($value = 1)
    {
        return $this->addMonths($value);
    }

    /**
     * Remove a month from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subMonth($value = 1)
    {
        return $this->subMonths($value);
    }

    /**
     * Remove months from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subMonths($value)
    {
        return $this->addMonths(-1 * $value);
    }

    /**
     * Add months without overflowing to the instance. Positive $value
     * travels forward while negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addMonthsNoOverflow($value)
    {
        $date = $this->addMonths($value);

        if ($date->getDay() !== $this->getDay()) {
            $date = $date->day(1)->subMonth();
            $date = $date->day($date->getDaysInMonth());
        }

        return $date;
    }

    /**
     * Add a month with no overflow to the instance.
     **.
     *
     * @return static
     */
    public function addMonthNoOverflow()
    {
        return $this->addMonthsNoOverflow(1);
    }

    /**
     * Remove a month with no overflow from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subMonthNoOverflow($value = 1)
    {
        return $this->subMonthsNoOverflow($value);
    }

    /**
     * Remove months with no overflow from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subMonthsNoOverflow($value)
    {
        return $this->addMonthsNoOverflow(-1 * $value);
    }

    /**
     * Add days to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addDays($value)
    {
        return $this->modify((int) $value.' day');
    }

    /**
     * Add a day to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addDay($value = 1)
    {
        return $this->addDays($value);
    }

    /**
     * Remove a day from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subDay($value = 1)
    {
        return $this->subDays($value);
    }

    /**
     * Remove days from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subDays($value)
    {
        return $this->addDays(-1 * $value);
    }

    /**
     * Add weekdays to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addWeekdays($value)
    {
        // fix for php bug #54909
        $t = $this->toTimeString();

        return $this->modify((int) $value.' weekday')->setTimeFromTimeString($t);
    }

    /**
     * Add a weekday to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addWeekday($value = 1)
    {
        return $this->addWeekdays($value);
    }

    /**
     * Remove a weekday from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subWeekday($value = 1)
    {
        return $this->subWeekdays($value);
    }

    /**
     * Remove weekdays from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subWeekdays($value)
    {
        return $this->addWeekdays(-1 * $value);
    }

    /**
     * Add weeks to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addWeeks($value)
    {
        return $this->modify((int) $value.' week');
    }

    /**
     * Add a week to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addWeek($value = 1)
    {
        return $this->addWeeks($value);
    }

    /**
     * Remove a week from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subWeek($value = 1)
    {
        return $this->subWeeks($value);
    }

    /**
     * Remove weeks to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subWeeks($value)
    {
        return $this->addWeeks(-1 * $value);
    }

    /**
     * Add hours to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addHours($value)
    {
        return $this->modify((int) $value.' hour');
    }

    /**
     * Add an hour to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addHour($value = 1)
    {
        return $this->addHours($value);
    }

    /**
     * Remove an hour from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subHour($value = 1)
    {
        return $this->subHours($value);
    }

    /**
     * Remove hours from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subHours($value)
    {
        return $this->addHours(-1 * $value);
    }

    /**
     * Add minutes to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addMinutes($value)
    {
        return $this->modify((int) $value.' minute');
    }

    /**
     * Add a minute to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addMinute($value = 1)
    {
        return $this->addMinutes($value);
    }

    /**
     * Remove a minute from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subMinute($value = 1)
    {
        return $this->subMinutes($value);
    }

    /**
     * Remove minutes from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subMinutes($value)
    {
        return $this->addMinutes(-1 * $value);
    }

    /**
     * Add seconds to the instance. Positive $value travels forward while
     * negative $value travels into the past.
     *
     * @param int $value
     *
     * @return static
     */
    public function addSeconds($value)
    {
        return $this->modify((int) $value.' second');
    }

    /**
     * Add a second to the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function addSecond($value = 1)
    {
        return $this->addSeconds($value);
    }

    /**
     * Remove a second from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subSecond($value = 1)
    {
        return $this->subSeconds($value);
    }

    /**
     * Remove seconds from the instance.
     *
     * @param int $value
     *
     * @return static
     */
    public function subSeconds($value)
    {
        return $this->addSeconds(-1 * $value);
    }

    ///////////////////////////////////////////////////////////////////
    /////////////////////////// DIFFERENCES ///////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Get the difference in years.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInYears(Date $dt = null, $abs = true)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return (int) $this->diff($dt, $abs)->format('%r%y');
    }

    /**
     * Get the difference in months.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMonths(Date $dt = null, $abs = true)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return $this->diffInYears($dt, $abs) * static::MONTHS_PER_YEAR + (int) $this->diff($dt, $abs)->format('%r%m');
    }

    /**
     * Get the difference in weeks.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInWeeks(Date $dt = null, $abs = true)
    {
        return (int) ($this->diffInDays($dt, $abs) / static::DAYS_PER_WEEK);
    }

    /**
     * Get the difference in days.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInDays(Date $dt = null, $abs = true)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return (int) $this->diff($dt, $abs)->format('%r%a');
    }

    /**
     * Get the difference in days using a filter closure.
     *
     * @param Closure   $callback
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInDaysFiltered(Closure $callback, Date $dt = null, $abs = true)
    {
        return $this->diffFiltered(Interval::day(), $callback, $dt, $abs);
    }

    /**
     * Get the difference in hours using a filter closure.
     *
     * @param Closure   $callback
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInHoursFiltered(Closure $callback, Date $dt = null, $abs = true)
    {
        return $this->diffFiltered(Interval::hour(), $callback, $dt, $abs);
    }

    /**
     * Get the difference by the given interval using a filter closure.
     *
     * @param Interval  $ci  An interval to traverse by
     * @param Closure   $callback
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffFiltered(Interval $ci, Closure $callback, Date $dt = null, $abs = true)
    {
        $start = $this;
        $end = $dt ?: static::now($this->getTimezone());
        $inverse = false;

        if ($end < $start) {
            $start = $end;
            $end = $this;
            $inverse = true;
        }

        $period = new DatePeriod($start, $ci, $end);
        $vals = array_filter(
            iterator_to_array($period),
            function (DateTimeInterface $date) use ($callback) {
                return call_user_func($callback, Date::instance($date));
            }
        );

        $diff = count($vals);

        return $inverse && !$abs ? -$diff : $diff;
    }

    /**
     * Get the difference in weekdays.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInWeekdays(Date $dt = null, $abs = true)
    {
        return $this->diffInDaysFiltered(
            function (Date $date) {
                return $date->isWeekday();
            },
            $dt,
            $abs
        );
    }

    /**
     * Get the difference in weekend days using a filter.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInWeekendDays(Date $dt = null, $abs = true)
    {
        return $this->diffInDaysFiltered(
            function (Date $date) {
                return $date->isWeekend();
            },
            $dt,
            $abs
        );
    }

    /**
     * Get the difference in hours.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInHours(Date $dt = null, $abs = true)
    {
        return (int) ($this->diffInSeconds($dt, $abs) / static::SECONDS_PER_MINUTE / static::MINUTES_PER_HOUR);
    }

    /**
     * Get the difference in minutes.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInMinutes(Date $dt = null, $abs = true)
    {
        return (int) ($this->diffInSeconds($dt, $abs) / static::SECONDS_PER_MINUTE);
    }

    /**
     * Get the difference in seconds.
     *
     * @param Date|null $dt
     * @param bool      $abs Get the absolute of the difference
     *
     * @return int
     */
    public function diffInSeconds(Date $dt = null, $abs = true)
    {
        $dt = $dt ?: static::now($this->getTimezone());
        $value = $dt->getTimestamp() - $this->getTimestamp();

        return $abs ? abs($value) : $value;
    }

    /**
     * The number of seconds since midnight.
     *
     * @return int
     */
    public function secondsSinceMidnight()
    {
        return $this->diffInSeconds($this->copy()->startOfDay());
    }

    /**
     * The number of seconds until 23:23:59.
     *
     * @return int
     */
    public function secondsUntilEndOfDay()
    {
        return $this->diffInSeconds($this->copy()->endOfDay());
    }

    /**
     * Get the difference in a human readable format in the current locale.
     *
     * When comparing a value in the past to default now:
     * 1 hour ago
     * 5 months ago
     *
     * When comparing a value in the future to default now:
     * 1 hour from now
     * 5 months from now
     *
     * When comparing a value in the past to another value:
     * 1 hour before
     * 5 months before
     *
     * When comparing a value in the future to another value:
     * 1 hour after
     * 5 months after
     *
     * @param Date|null $other
     * @param bool      $absolute removes time difference modifiers ago, after, etc
     *
     * @return string
     */
    public function diffForHumans(Date $other = null, $absolute = false)
    {
        $isNow = $other === null;

        if ($isNow) {
            $other = static::now($this->getTimezone());
        }

        $diffInterval = $this->diff($other);

        switch (true) {
            case ($diffInterval->y > 0):
                $unit = 'year';
                $count = $diffInterval->y;
                break;

            case ($diffInterval->m > 0):
                $unit = 'month';
                $count = $diffInterval->m;
                break;

            case ($diffInterval->d > 0):
                $unit = 'day';
                $count = $diffInterval->d;
                if ($count >= self::DAYS_PER_WEEK) {
                    $unit = 'week';
                    $count = (int) ($count / self::DAYS_PER_WEEK);
                }
                break;

            case ($diffInterval->h > 0):
                $unit = 'hour';
                $count = $diffInterval->h;
                break;

            case ($diffInterval->i > 0):
                $unit = 'minute';
                $count = $diffInterval->i;
                break;

            default:
                $count = $diffInterval->s;
                $unit = 'second';
                break;
        }

        if ($count === 0) {
            $count = 1;
        }

        $time = static::translator()->transChoice($unit, $count, [':count' => $count]);

        if ($absolute) {
            return $time;
        }

        $isFuture = $diffInterval->invert === 1;

        $transId = $isNow ? ($isFuture ? 'from_now' : 'ago') : ($isFuture ? 'after' : 'before');

        // Some langs have special pluralization for past and future tense.
        $tryKeyExists = $unit.'_'.$transId;
        if ($tryKeyExists !== static::translator()->transChoice($tryKeyExists, $count)) {
            $time = static::translator()->transChoice($tryKeyExists, $count, [':count' => $count]);
        }

        return static::translator()->trans($transId, [':time' => $time]);
    }

    ///////////////////////////////////////////////////////////////////
    //////////////////////////// MODIFIERS ////////////////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Resets the time to 00:00:00.
     *
     * @return static
     */
    public function startOfDay()
    {
        return $this->hour(0)->minute(0)->second(0);
    }

    /**
     * Resets the time to 23:59:59.
     *
     * @return static
     */
    public function endOfDay()
    {
        return $this->hour(23)->minute(59)->second(59);
    }

    /**
     * Resets the date to the first day of the month and the time to 00:00:00.
     *
     * @return static
     */
    public function startOfMonth()
    {
        return $this->startOfDay()->day(1);
    }

    /**
     * Resets the date to end of the month and time to 23:59:59.
     *
     * @return static
     */
    public function endOfMonth()
    {
        return $this->day($this->getDaysInMonth())->endOfDay();
    }

    /**
     * Resets the date to the first day of the year and the time to 00:00:00.
     *
     * @return static
     */
    public function startOfYear()
    {
        return $this->month(1)->startOfMonth();
    }

    /**
     * Resets the date to end of the year and time to 23:59:59.
     *
     * @return static
     */
    public function endOfYear()
    {
        return $this->month(static::MONTHS_PER_YEAR)->endOfMonth();
    }

    /**
     * Resets the date to the first day of the decade and the time to 00:00:00.
     *
     * @return static
     */
    public function startOfDecade()
    {
        return $this->startOfYear()->year($this->getYear() - $this->getYear() % static::YEARS_PER_DECADE);
    }

    /**
     * Resets the date to end of the decade and time to 23:59:59.
     *
     * @return static
     */
    public function endOfDecade()
    {
        return $this->endOfYear()->year(
            $this->getYear() - $this->getYear() % static::YEARS_PER_DECADE + static::YEARS_PER_DECADE - 1
        );
    }

    /**
     * Resets the date to the first day of the century and the time to 00:00:00.
     *
     * @return static
     */
    public function startOfCentury()
    {
        return $this->startOfYear()->year($this->getYear() - $this->getYear() % static::YEARS_PER_CENTURY);
    }

    /**
     * Resets the date to end of the century and time to 23:59:59.
     *
     * @return static
     */
    public function endOfCentury()
    {
        return $this->endOfYear()->year(
            $this->getYear() - $this->getYear() % static::YEARS_PER_CENTURY + static::YEARS_PER_CENTURY - 1
        );
    }

    /**
     * Resets the date to the first day of week (defined in $weekStartsAt) and the time to 00:00:00.
     *
     * @return static
     */
    public function startOfWeek()
    {
        if ($this->getDayOfWeek() !== static::$weekStartsAt) {
            return $this->previous(static::$weekStartsAt);
        }

        return $this->startOfDay();
    }

    /**
     * Resets the date to end of week (defined in $weekEndsAt) and time to 23:59:59.
     *
     * @return static
     */
    public function endOfWeek()
    {
        if ($this->getDayOfWeek() !== static::$weekEndsAt) {
            return $this->next(static::$weekEndsAt)->endOfDay();
        }

        return $this->endOfDay();
    }

    /**
     * Modify to the next occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the next occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function next($dayOfWeek = null)
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->getDayOfWeek();
        }

        return $this->startOfDay()->modify('next '.static::$days[$dayOfWeek]);
    }

    /**
     * Modify to the previous occurrence of a given day of the week.
     * If no dayOfWeek is provided, modify to the previous occurrence
     * of the current day of the week.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function previous($dayOfWeek = null)
    {
        if ($dayOfWeek === null) {
            $dayOfWeek = $this->getDayOfWeek();
        }

        return $this->startOfDay()->modify('last '.static::$days[$dayOfWeek]);
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * first day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function firstOfMonth($dayOfWeek = null)
    {
        $date = $this->startOfDay();

        if ($dayOfWeek === null) {
            return $date->day(1);
        }

        return $this->modify('first '.static::$days[$dayOfWeek].' of '.$this->format('F').' '.$this->getYear());
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current month. If no dayOfWeek is provided, modify to the
     * last day of the current month.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function lastOfMonth($dayOfWeek = null)
    {
        $date = $this->startOfDay();

        if ($dayOfWeek === null) {
            return $date->day($this->getDaysInMonth());
        }

        return $this->modify('last '.static::$days[$dayOfWeek].' of '.$this->format('F').' '.$this->getYear());
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current month. If the calculated occurrence is outside the scope
     * of the current month, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth
     * @param int $dayOfWeek
     *
     * @return mixed
     */
    public function nthOfMonth($nth, $dayOfWeek)
    {
        $dt = $this->copy()->firstOfMonth();
        $check = $dt->format('Y-m');
        $dt = $dt->modify('+'.$nth.' '.static::$days[$dayOfWeek]);

        return $dt->format('Y-m') === $check ? $this->modify((string) $dt) : false;
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * first day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function firstOfQuarter($dayOfWeek = null)
    {
        return $this->day(1)->month($this->getQuarter() * 3 - 2)->firstOfMonth($dayOfWeek);
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current quarter. If no dayOfWeek is provided, modify to the
     * last day of the current quarter.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function lastOfQuarter($dayOfWeek = null)
    {
        return $this->day(1)->month($this->getQuarter() * 3)->lastOfMonth($dayOfWeek);
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current quarter. If the calculated occurrence is outside the scope
     * of the current quarter, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth
     * @param int $dayOfWeek
     *
     * @return Date|bool
     */
    public function nthOfQuarter($nth, $dayOfWeek)
    {
        $dt = $this->day(1)->month($this->getQuarter() * 3);
        $lastMonth = $dt->getMonth();
        $year = $dt->getYear();
        $dt = $dt->firstOfQuarter()->modify('+'.$nth.' '.static::$days[$dayOfWeek]);

        return ($lastMonth < $dt->getMonth() || $year !== $dt->getYear()) ? false : $this->modify((string) $dt);
    }

    /**
     * Modify to the first occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * first day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function firstOfYear($dayOfWeek = null)
    {
        return $this->month(1)->firstOfMonth($dayOfWeek);
    }

    /**
     * Modify to the last occurrence of a given day of the week
     * in the current year. If no dayOfWeek is provided, modify to the
     * last day of the current year.  Use the supplied consts
     * to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int|null $dayOfWeek
     *
     * @return static
     */
    public function lastOfYear($dayOfWeek = null)
    {
        return $this->month(static::MONTHS_PER_YEAR)->lastOfMonth($dayOfWeek);
    }

    /**
     * Modify to the given occurrence of a given day of the week
     * in the current year. If the calculated occurrence is outside the scope
     * of the current year, then return false and no modifications are made.
     * Use the supplied consts to indicate the desired dayOfWeek, ex. static::MONDAY.
     *
     * @param int $nth
     * @param int $dayOfWeek
     *
     * @return mixed
     */
    public function nthOfYear($nth, $dayOfWeek)
    {
        $dt = $this->copy()->firstOfYear()->modify('+'.$nth.' '.static::$days[$dayOfWeek]);

        return $this->getYear() === $dt->getYear() ? $this->modify((string) $dt) : false;
    }

    /**
     * Modify the current instance to the average of a given instance (default now) and the current instance.
     *
     * @param Date|null $dt
     *
     * @return static
     */
    public function average(Date $dt = null)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return $this->addSeconds((int) ($this->diffInSeconds($dt, false) / 2));
    }

    /**
     * Check if its the birthday. Compares the date/month values of the two dates.
     *
     * @param Date|null $dt The instance to compare with or null to use current day.
     *
     * @return bool
     */
    public function isBirthday(Date $dt = null)
    {
        $dt = $dt ?: static::now($this->getTimezone());

        return $this->format('md') === $dt->format('md');
    }

    /**
     *   Alters the timestamp.
     *
     * @param string $value
     *
     * @return static
     */
    public function modify($value)
    {
        return parent::modify($value);
    }
}
