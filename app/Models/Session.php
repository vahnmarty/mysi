<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getOS()
    {
        $userAgent = $this->user_agent;

        // Define patterns for different operating systems
        $osPatterns = [
            '/Macintosh.*Mac OS X ([\d_]+)/' => 'Mac OS X',
            '/Windows NT ([\d.]+)/' => 'Windows',
            '/Linux/' => 'Linux',
            '/iPhone.*OS ([\d_]+)/' => 'iOS',
            '/Android ([\d.]+)/' => 'Android',
        ];

        foreach ($osPatterns as $pattern => $osName) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return $osName . (isset($matches[1]) ? ' ' . str_replace('_', '.', $matches[1]) : '');
            }
        }

        return 'Unknown OS';
    }

    /**
     * Extract the browser from the user agent string.
     *
     * @return string
     */
    public function getBrowser()
    {
        $userAgent = $this->user_agent;

        // Define patterns for different browsers
        $browserPatterns = [
            '/Chrome\/([\d.]+)/' => 'Chrome',
            '/Safari\/([\d.]+)/' => 'Safari',
            '/Firefox\/([\d.]+)/' => 'Firefox',
            '/MSIE ([\d.]+)/' => 'Internet Explorer',
            '/Trident\/.*rv:([\d.]+)/' => 'Internet Explorer',
            '/Edge\/([\d.]+)/' => 'Edge',
        ];

        foreach ($browserPatterns as $pattern => $browserName) {
            if (preg_match($pattern, $userAgent, $matches)) {
                return $browserName . (isset($matches[1]) ? ' ' . $matches[1] : '');
            }
        }

        return 'Unknown Browser';
    }
}
