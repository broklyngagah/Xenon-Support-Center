<?php

namespace KodeInfo\Templates;

class TemplatesListServiceProvider extends \Illuminate\Support\ServiceProvider{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'KodeInfo\Templates\TemplatesList',
            'KodeInfo\Templates\Mailchimp\TemplatesList'
        );
    }

}