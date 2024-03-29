<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Example extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_example';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name',
        'AboutMe',
        'ProfilePhoto',
        'Gender',
        'CreatedAt',
        'UpdatedAt',
        'Status',
        'IsValid',
        'ErrorMessage',
    ];

    /**
     * Create and Updated field names
     */
    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

    /**
     * Structure of dynamic form elements
     *
     * @var array
     */
    protected static $tableFields = [
        [
            'name' => 'Name',
            'label' => 'Name',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'InputText',
            'searchable' => 'Name',
            'validations' => [
                'required' => true,
                'max' => 50,
                'min' => 1,
            ]
        ],
        [
            'name' => 'AboutMe',
            'label' => 'About Me',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'Textarea',
            'searchable' => 'AboutMe',
            'validations' => [
                'required' => true,
                'max' => 50,
                'min' => 1,
            ]
        ],
        [
            'name' => 'ProfilePhoto',
            'label' => 'Profile Photo',
            'type' => 'String',
            'tableComponent' => 'ThumbImage',
            'formComponent' => 'File',
            'validations' => [
                'required' => true,
                'mimes' => '.jpg, .jpeg, .png',
                'max' => 4096
            ],
        ],
        [
            'name' => 'Gender',
            'label' => 'Gender',
            'type' => 'String',
            'tableComponent' => 'OptionBadge',
            'formComponent' => 'Radio',
            'validations' => [
                'required' => true,
            ],
            'options' => [
                ['value' => 'Male', 'label' => 'Male'],
                ['value' => 'Female', 'label' => 'Female']
            ],
            'colors' => [
                'Male' => 'primary',
                'Female' => 'success'
            ]
        ],
        [
            'name' => 'Status',
            'label' => 'Status',
            'type' => 'Number',
            'tableComponent' => 'OptionBadge',
            'searchable' => 'Status',
            'options' => [
                ['value' => 0, 'label' => 'Active'],
                ['value' => 1, 'label' => 'Inactive'],
                ['value' => 2, 'label' =>  'Approved'],
                ['value' => 3, 'label' =>  'Rejected'],
                ['value' => 4, 'label' =>  'Deleted'],
            ],
            'colors' => [
                'Active'    => 'info',
                'Inactive'  => 'warning',
                'Approved'  => 'primary',
                'Rejected'  => 'secondary',
                'Deleted'   => 'danger'
            ]
        ],
    ];

    /**
     * Getter function for tableElements
     *
     * @return array
     */
    public static function getTableFields(): array
    {
        $fields = [];
        $tableFieldsCollection = collect(self::$tableFields)->whereNotNull('tableComponent');
        $fields = $tableFieldsCollection->map(function (array $field) {
            return [
                'name'           => $field['name'],
                'label'          => $field['label'],
                'tableComponent' => $field['tableComponent'],
                'colors'         => $field['colors'] ?? [],
                'searchable'     => $field['searchable'] ?? false,
            ];
        })->all();
        return $fields;
    }

    /**
     * Getter function for formElements
     *
     * @return array
     */
    public static function getFormFields(): array
    {
        $fields = [];
        $tableFieldsCollection = collect(self::$tableFields)->whereNotNull('formComponent');
        $fields = $tableFieldsCollection->map(function (array $field) {
            return [
                'name'            => $field['name'],
                'label'           => $field['label'],
                'type'            => $field['type'],
                'formComponent'   => $field['formComponent'],
                'options'         => $field['options'] ?? [],
                'validations'     => $field['validations'] ?? [],
                'source'          => $field['source'] ?? null,
                'watching'        => $field['watching'] ?? null,
                'mount'           => $field['mount'] ?? false,
            ];
        })->all();
        return $fields;
    }

    /**
     * Set the Name attribute.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function Name(): Attribute
    {
        return Attribute::make(
            set: fn ($value, $attributes) => $attributes['Name'] = ucwords($value),
        );
    }
}
