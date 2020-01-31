<?php

namespace App\Helper;

use DateTime;

class ShortURLHelper {

    /**
     * @var string
     */
    protected $serviceRootURL;

    /**
     * ShortURLHelper constructor.
     *
     * @param string $serviceRootURL
     */
    public function __construct(string $serviceRootURL) {
        $this->serviceRootURL = rtrim($serviceRootURL, "/");
    }

    /**
     * Render URL.
     *
     * @param string $sourceName
     * @return string
     */
    public function renderURL(string $sourceName): string {
        return implode("/", [$this->serviceRootURL, $sourceName]);
    }

    /**
     * Format provided date in specified format.
     *
     * @param DateTime $dateTime
     * @param string   $format
     * @return string
     */
    public function formatDate(DateTime $dateTime, string $format = "Y-m-d H:i:s"): string {
        return $dateTime->format($format);
    }

    /**
     * Validate provided date and return instance of DateTime when is correct.
     *
     * @param string $value
     * @return DateTime|null
     */
    public function validateDate(string $value): ?DateTime {
        if (false !== ($validDate = DateTime::createFromFormat("Y-m-d H:i:s", $value))) {
            return $validDate;
        }

        return null;
    }

    /**
     * Validate provided length and return integer when is correct.
     *
     * @param string $value
     * @return int|null
     */
    public function validateLength(string $value): ?int {
        if (true === (bool)preg_match("/^[0-9]+$/", $value)) {
            return (int)$value;
        }

        return null;
    }

    /**
     * Validate provided source name.
     *
     * @param string $value
     * @return bool
     */
    public function validateSourceName(string $value): bool {
        return (bool)preg_match("/^[a-z0-9+_\-]+$/", $value);
    }

    /**
     * Validate provided URL.
     *
     * @param string $value
     * @return bool
     */
    public function validateURL(string $value): bool {
        return (bool)preg_match("/^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)(?:\.(?:[a-z\x{00a1}-\x{ffff}0-9]+-?)*[a-z\x{00a1}-\x{ffff}0-9]+)*(?:\.(?:[a-z\x{00a1}-\x{ffff}]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/ius", $value);
    }

    /**
     * Generate random string with provided length.
     *
     * @param int    $length
     * @param string $characters
     * @return string
     */
    public static function generateRandom(int $length, string $characters = "23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ"): string {
        $randomString = "";
        $charactersLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
