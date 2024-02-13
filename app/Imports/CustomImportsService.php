<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class CustomImportsService implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, SkipsEmptyRows
{
    use Importable;
    public $data;
    private $model, $validation;

    public function __construct(Model $model, array $validation)
    {
        $this->data = collect();
        $this->model = $model;
        $this->validation = $validation;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new $this->model($row);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function rules(): array
    {
        return $this->validation;
    }
}
