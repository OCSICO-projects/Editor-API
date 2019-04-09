<?php

namespace App\Http\Services;

class AppVersionService
{
    const MAJOR = 1;
    const MINOR = 0;
    const PATCH = 0;

    static public function getVersion()
    {
        $commitHash = substr(file_get_contents(sprintf('../.git/refs/heads/%s', 'master')), 0, 7);

        return 'Version: ' . $commitHash;
        // todo uncomment when need version instead of commit hash
//        return sprintf('Version: %s.%s.%s, commit: %s', self::MAJOR, self::MINOR, self::PATCH, $commitHash);
    }
}
