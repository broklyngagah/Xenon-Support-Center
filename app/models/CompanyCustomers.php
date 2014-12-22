<?php

/**
 * CompanyCustomers
 *
 * @property integer $id
 * @property integer $company_id
 * @property integer $customer_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\CompanyCustomers whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyCustomers whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyCustomers whereCustomerId($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyCustomers whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyCustomers whereUpdatedAt($value)
 */
class CompanyCustomers extends Eloquent {

    protected $table="company_customers";


} 