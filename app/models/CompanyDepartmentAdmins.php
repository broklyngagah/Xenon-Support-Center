<?php

/**
 * CompanyDepartmentAdmins
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $company_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\CompanyDepartmentAdmins whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyDepartmentAdmins whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyDepartmentAdmins whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyDepartmentAdmins whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\CompanyDepartmentAdmins whereUpdatedAt($value)
 */
class CompanyDepartmentAdmins extends Eloquent {

    protected $table="company_department_admins";

} 