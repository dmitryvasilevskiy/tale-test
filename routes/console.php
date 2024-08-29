<?php

use App\Console\Commands;

Schedule::command('app:show-user-command')->hourlyAt(Commands\ShowUserCommand::MINUTES);
