<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * The host pulls new code without running any commands, so each deploy
     * (named in the BUILD file the deploy workflow writes) gets a fresh cache.
     */
    public function getCacheDir(): string
    {
        $build = is_file($this->getProjectDir().'/BUILD') ? trim((string) file_get_contents($this->getProjectDir().'/BUILD')) : '';

        return $this->getProjectDir().'/var/cache/'.$this->environment.($build !== '' ? '-'.$build : '');
    }
}
