<?php

/**
 * PairedTemplates
 *
 * @property integer $id
 * @property string $name
 * @property string $view
 * @property integer $template_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\PairedTemplates whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\PairedTemplates whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\PairedTemplates whereView($value)
 * @method static \Illuminate\Database\Query\Builder|\PairedTemplates whereTemplateId($value)
 * @method static \Illuminate\Database\Query\Builder|\PairedTemplates whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\PairedTemplates whereUpdatedAt($value)
 */
class PairedTemplates extends Eloquent {

    protected $table="paired_templates";


} 