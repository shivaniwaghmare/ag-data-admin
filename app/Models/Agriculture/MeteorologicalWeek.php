<?php

namespace App\Models\Agriculture;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeteorologicalWeek extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agri_meteorological_weeks';

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
        'WeekNo',
        'StartDay',
        'StartMonth',
        'EndDay',
        'EndMonth',
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
            'name' => 'WeekNo',
            'label' => 'Week No',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => '1', 'label' => '1'],
                ['value' => '2', 'label' => '2']
            ],
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'StartDay',
            'label' => 'Start Date',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => '1', 'label' => '1'],
                ['value' => '2', 'label' => '2']
            ],
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'StartMonth',
            'label' => 'StartMonth',
            'type' => 'String',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => '1', 'label' => 'January -1'],
                ['value' => '2', 'label' => 'February -2']
            ],
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'EndDay',
            'label' => 'End Date',
            'type' => 'String',
            'tableComponent' => 'RegularText',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => '1', 'label' => '1'],
                ['value' => '2', 'label' => '2']
            ],
            'validations' => [
                'required' => true
            ]
        ],
        [
            'name' => 'EndMonth',
            'label' => 'EndMonth',
            'type' => 'String',
            'formComponent' => 'RegularSelect',
            'options' => [
                ['value' => '1', 'label' => 'January -1'],
                ['value' => '2', 'label' => 'February -2']
            ],
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

    //Display start date in list
    public function getStartDayAttribute()
    {
        $day = $this->attributes['StartDay'];
        $month = $this->attributes['StartMonth'];

        // Format month as its corresponding name
        $formattedMonth = date("F", mktime(0, 0, 0, $month, 1));

        return $day . ' ' . $formattedMonth;
    }

    //Display end date in list
    public function getEndDayAttribute()
    {
        $day = $this->attributes['EndDay'];
        $month = $this->attributes['EndMonth'];

        // Format month as its corresponding name
        $formattedMonth = date("F", mktime(0, 0, 0, $month, 1));

        return $day . ' ' . $formattedMonth;
    }
}
