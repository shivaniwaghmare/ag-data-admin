<?php

namespace App\Models\Geographical;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Village extends Model
{
     use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'geo_village';

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
        'StateCode',
        'DistrictCode',
        'TehsilCode',
        'VillageCode',
        'Name',
        'Status',
    ];

    const CREATED_AT = 'CreatedAt';
    const UPDATED_AT = 'UpdatedAt';

     /**
     * Structure of dynamic form elements
     */
    protected static $tableFields = [
        [
        'name' => 'ID',
        'label' => 'ID',
        'type' => 'String',
        'tableComponent' => 'RegularText',
        'searchable' => 'ID',
        'validations' => [
            'required' => true,
            'max' => 50,
            'min' => 1,
        ]
       ],
       [
        'name' => 'StateName',
        'label' => 'State',
        'type' => 'String',
        'tableComponent' => 'RegularText',
        'searchable' => 'State.name'
    ],
    [
        'name' => 'StateCode',
        'label' => 'State',
        'type' => 'Number',
        'formComponent' => 'RegularSelect',
        'options' => [],
        'source' => "/geographical/state/list",
        'mount' => true,
        'validations' => [
            'required' => true,
        ]
    ],

    [
        'name' => 'DistrictName',
        'label' => 'District',
        'type' => 'String',
        'tableComponent' => 'RegularText',
        'searchable' => 'District.name'
    ],
    [
        'name' => 'DistrictCode',
        'label' => 'District',
        'type' => 'Number',
        'formComponent' => 'RegularSelect',
        'options' => [],
        'source' => "/geographical/district/list-by-parent",
        'watching' => "StateCode",
        'validations' => [
            'required' => true,
        ]
    ],

    [
        'name' => 'TehsilName',
        'label' => 'Tehsil',
        'type' => 'String',
        'tableComponent' => 'RegularText',
        'searchable' => 'Tehsil.name'
    ],
    [
        'name' => 'TehsilCode',
        'label' => 'Tehsil',
        'type' => 'Number',
        'formComponent' => 'RegularSelect',
        'options' => [],
        'source' => "/geographical/tehsil/list-by-parent",
        'watching' => "DistrictCode",
        'validations' => [
            'required' => true,
        ]
    ],

    [
        'name' => 'VillageCode',
        'label' => 'Village Code',
        'type' => 'Number',
        'tableComponent' => 'RegularText',
        'formComponent' => 'InputText',
        'searchable' => 'VillageCode',
        'validations' => [
            'required' => true,
        ]
       ],

       [
        'name' => 'Name',
        'label' => 'Village',
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
        ]
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
     * Set the Name attribute in upper case.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function Name(): Attribute
    {
        return Attribute::make(
            set: fn ($value, $attributes) => $attributes['Name'] = ucwords($value),
        );
    }

    /**
     * Get the stateGroup associated with the State.
     *
     * @return object
     */
    public function state()
    {
        return $this->belongsTo(
            related: State::class,
            foreignKey: 'StateCode',
            ownerKey: 'ID'
        );
    }

     /**
     * Get the districtGroup associated with the District.
     *
     * @return object
     */
    public function district()
    {
        return $this->belongsTo(
            related: District::class,
            foreignKey: 'DistrictCode',
            ownerKey: 'ID'
        );
    }

     /**
     * Get the tehsilGroup associated with the Tehsil.
     *
     * @return object
     */
    public function tehsil()
    {
        return $this->belongsTo(
            related: Tehsil::class,
            foreignKey: 'TehsilCode',
            ownerKey: 'ID'
        );
    }

}
