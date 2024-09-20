<?php
namespace App\Providers\Socialite\DrChrono;

use SocialiteProviders\Manager\SocialiteWasCalled;

class DrChronoExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     * @return void
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('drchrono', Provider::class);
    }
}
