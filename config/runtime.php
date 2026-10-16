<?php

// Runtime options for public/index.php and bin/console, as in the games. On the
// host, the app secret and database settings live in cyoa.env, in the folder
// above the site's folder, never in the repository or the web root. Locally the
// file doesn't exist and the .env files are used as before.
$settings = '../cyoa.env';

// Shared hosts often disable escapeshellarg(). The console needs it to log a
// failed command, so without it the real error is hidden behind this one.
// Since PHP 8 a disabled function can be defined again.
if (!function_exists('escapeshellarg')) {
    function escapeshellarg(string $arg): string
    {
        return "'".str_replace("'", "'\\''", $arg)."'";
    }
}

return ['dotenv_extra_paths' => is_file(dirname(__DIR__).'/'.$settings) ? [$settings] : []];
