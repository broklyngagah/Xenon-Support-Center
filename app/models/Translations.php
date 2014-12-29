<?php


/**
 * Translations
 *
 * @property integer $id
 * @property string $language_name
 * @property string $language_code
 * @property boolean $active
 * @method static \Illuminate\Database\Query\Builder|\Translations whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\Translations whereLanguageName($value) 
 * @method static \Illuminate\Database\Query\Builder|\Translations whereLanguageCode($value) 
 * @method static \Illuminate\Database\Query\Builder|\Translations whereActive($value) 
 */
class Translations extends Eloquent {

    protected $table="translations";
    public $timestamps=false;

} 