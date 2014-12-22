<?php

namespace KodeInfo\Templates;

/**
 * Interface TemplatesList
 * @package KodeInfo
 */
interface TemplatesList{

    /**
     * @param $list
     * @param $email
     * @return mixed
     */
    public function subscribeTo($list,$email);

    /**
     * @param $list
     * @param $email
     * @return mixed
     */
    public function unsubscribeFrom($list,$email);

}