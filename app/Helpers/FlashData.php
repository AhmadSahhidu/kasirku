<?php

namespace App\Helpers;

class FlashData
{
    /**
     * Set flash data for success alert
     */
    public static function success_alert($message): void
    {
        session()->flash('type', 'success');
        session()->flash('message', $message);
    }

    /**
     * Set flash data for danger alert
     */
    public static function danger_alert($message): void
    {
        session()->flash('type', 'danger');
        session()->flash('message', $message);
    }

    /**
     * Set flash data for warning alert
     */
    public static function warning_alert($message): void
    {
        session()->flash('type', 'warning');
        session()->flash('message', $message);
    }

    /**
     * Set flash data for info alert
     */
    public static function info_alert($message): void
    {
        session()->flash('type', 'info');
        session()->flash('message', $message);
    }
}
