<?php
/**
 * Created by PhpStorm.
 * 
 * User: Imran
 * Date: 9/19/14
 * Time: 4:08 PM
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @property integer $thread_id
 * @property string $ip_address
 * @property string $country_code
 * @property string $country
 * @property string $provider
 * @property string $current_page
 * @property string $all_pages
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereThreadId($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereIpAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereCountryCode($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereProvider($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereCurrentPage($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereAllPages($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\ThreadGeoInfo whereUpdatedAt($value)
 */

class ThreadGeoInfo extends Eloquent {

    protected $table="thread_geo_info";

} 