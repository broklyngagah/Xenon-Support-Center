<?php

namespace KodeInfo\Translator\Provider;

use Illuminate\Translation\TranslationServiceProvider;

class Translator extends TranslationServiceProvider
{
    public function boot()
    {
        $this->app->bindShared('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];

            $trans = new \KodeInfo\Translator\Translator($loader, $locale);

            $trans->setFallback($app['config']['app.fallback_locale']);

            return $trans;
        });

        parent::boot();
    }
}