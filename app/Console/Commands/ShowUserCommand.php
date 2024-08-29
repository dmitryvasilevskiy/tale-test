<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use DateTimeZone;

class ShowUserCommand extends Command
{
    protected $signature = 'app:show-user-command';

    protected $description = 'Command description';

    const HOUR = 5;

    const MINUTES = 50;

    public function handle(): void
    {
        $userTimezones = $this->getUserTimezones();
        $users = User::whereIn('timezone', $userTimezones)->pluck('name');

        if ($users->count()) {
            $this->info("Часовой пояс: {$this->getUTCOffset($userTimezones)}\n");
            $this->info("Список пользователей: {$users->toJson(64 | 128 | 256)}\n");
        } else {
            $this->info("Пользователей не найдено");
        }
    }

    private function getUTCOffset($userTimezones): string
    {
        $offset = Carbon::now($userTimezones[0])->utcOffset() / 60;
        return $offset >= 0 ? "UTC+$offset" : "UTC$offset";
    }

    private function getUserTimezones(): array
    {
        $timezones = DateTimeZone::listIdentifiers();
        $userTimezones = [];
        foreach ($timezones as $timezone) {
            $dt = Carbon::now($timezone);
            if ($dt->format('H') == self::HOUR) {
                $userTimezones[] = $timezone;
            }
        }
        return $userTimezones;
    }
}
