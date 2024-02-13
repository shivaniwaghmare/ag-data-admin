<?php

namespace App\Models\Commodity;

use App\Models\Commodity\HSNCode;
use App\Models\Commodity\Commodities;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Variety extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agri_variety';

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
        'CommodityID',
        'HsCodeID',
        'DomesticRestrictions',
        'InternationalRestrictions',
        'Name',
        'VarietyCode',
        'ParentVarietyID',
        'Status'
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
                'required' => true
            ]
        ],
        [
            'name' => 'CommodityName',
            'label' => 'Commodity',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'searchable' => 'Commodities.Name'
        ],
        [
            'name' => 'CommodityID',
            'label' => 'Commodity',
            'type' => 'Number',
            'formComponent' => 'RegularSelect',
            'options' => [],
            'source' => "/commodity/commodity/list",
            'mount' => true,
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'HsCodeName',
            'label' => 'HsCode',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'searchable' => 'HsCode.HSCode'
        ],
        [
            'name' => 'HsCodeID',
            'label' => 'HsCode',
            'type' => 'Number',
            'formComponent' => 'RegularSelect',
            'options' => [],
            'source' => "/commodity/hsn-code/list-by-parent",
            'watching' => "CommodityID",
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'DomesticRestrictions',
            'label' => 'Domestic Restrictions',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => 'Yes', 'label' => 'Yes'],
                ['value' => 'No', 'label' => 'No']
            ],
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'InternationalRestrictions',
            'label' => 'International Restrictions',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => 'Yes', 'label' => 'Yes'],
                ['value' => 'No', 'label' => 'No']
            ],
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'ParentVarietyName',
            'label' => 'Parent Variety',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'searchable' => 'ParentVariety.Name'
        ],
        [
            'name' => 'ParentVarietyID',
            'label' => 'ParentVariety',
            'type' => 'Number',
            'formComponent' => 'RegularSelect',
            'options' => [],
            'source' => "/commodity/variety/list",
            'mount' => true,
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'Name',
            'label' => 'Variety Name',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'InputText',
            'searchable' => 'Name',
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'VarietyCode',
            'label' => 'Variety Code',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'InputText',
            'searchable' => 'Name',
            'validations' => [
                'required' => true
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
     * Get the Commodity associated with the Variety.
     *
     * @return object
     */
    public function commodity()
    {
        return $this->belongsTo(
            related: Commodities::class,
            foreignKey: 'CommodityID',
            ownerKey: 'ID'
        );
    }

    /**
     * Get the HsCode associated with the Variety.
     *
     * @return object
     */
    public function HsnCode()
    {
        return $this->belongsTo(
            related: HSNCode::class,
            foreignKey: 'HsCodeID',
            ownerKey: 'ID'
        );
    }


    /**
     * Get the Variety associated with the Variety.
     *
     * @return object
     */
    public function variety()
    {
        return $this->belongsTo(
            related: Variety::class,
            foreignKey: 'ParentVarietyID',
            ownerKey: 'ID'
        );
    }


    /**
     * Get the Uom associated with the Variety.
     *
     * @return object
     */
    // public function uom()
    // {
    //     return $this->belongsTo(
    //         related: Uom::class,
    //         foreignKey: 'UomID',
    //         ownerKey: 'ID'
    //     );
    // }

}
