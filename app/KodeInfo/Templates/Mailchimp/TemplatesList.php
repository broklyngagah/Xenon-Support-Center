<?php

namespace KodeInfo\Templates\Mailchimp;

use KodeInfo\Templates\TemplatesList as TemplatesListInterface;
use Mailchimp;
use Settings;


class TemplatesList implements TemplatesListInterface {


    protected $mailchimp;

    function __construct(){
        $raw_settings = Settings::where('key','mailchimp')->first();
        $settings = json_decode($raw_settings->value);

        if(strlen($settings->api_key)>0)
            $this->mailchimp = new Mailchimp($settings->api_key);
    }


    public function getTemplatesList(){
        return $this->mailchimp->templates->getList([],['include_drag_and_drop'=>true]);
    }

    public function getTemplate($template_id){
        return $this->mailchimp->templates->info($template_id);
    }

    public function getCampaignList(){
        return $this->mailchimp->campaigns->getList();
    }

    public function getCampaign($campaign_id){
        return $this->mailchimp->campaigns->content($campaign_id);
    }

    public function subscribeTo($listId, $email)
    {
        $this->mailchimp->lists->subscribe(
            $listId,
            ["email"=>$email],
            null, //email type default html
            false, //double optin
            true //update existing customer
        );
    }

    public function unsubscribeFrom($listId , $email)
    {
        $this->mailchimp->lists->unsubscribe(
            $listId,
            ["email"=>$email],
            false,
            false,
            false
        );
    }

    public function lists(){
        return $this->mailchimp->lists->getList();
    }

} 