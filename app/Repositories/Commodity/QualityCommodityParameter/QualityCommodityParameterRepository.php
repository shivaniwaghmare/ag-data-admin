<?php

namespace App\Repositories\Commodity\QualityCommodityParameter;

use Exception;
use App\Models\Commodity\QualityCommodityParameter;
use App\Http\Requests\Commodity\QualityCommodityParameter\QualityCommodityParameterRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Commodity\QualityCommodityParameter\RepoInterface\QualityCommodityParameterRepoInterface;

class QualityCommodityParameterRepository implements QualityCommodityParameterRepoInterface
{
    /**
     * Find QualityCommodityParameter and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return QualityCommodityParameter::query()
            ->when(!empty($on) && count(explode(".", $on)) == 1, function ($query) use ($on, $search) {
                $query->where($on, 'like', "%{$search}%");
            })
            ->when(count(explode(".", $on)) > 1, function ($query) use ($on, $search) {
                $on = explode(".", $on);
                $model = $on[0];
                $on = $on[1];
                $query->whereHas($model, function ($query2) use ($on, $search) {
                    $query2->where($on, 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')->paginate($rowsPerPage)
            ->appends(request()->query());
    }

     /**
     * Find all QualityCommodityParameter as per condition for dropdown listing
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     */
    public function all(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return QualityCommodityParameter::query()
            ->when(!empty($on) && count(explode(".", $on)) == 1, function ($query) use ($on, $search) {
                $query->where($on, '=', "{$search}");
            })
            ->orderBy('Name', 'desc')
            ->get();
    }

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        return QualityCommodityParameter::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return QualityCommodityParameter::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param QualityCommodityParameterRequest $data
     *
     * @return Array
     */
    public function add(QualityCommodityParameterRequest $data)
    {
        //Create a new entry in db
        try {
            QualityCommodityParameter::create([
                'CommodityID' => $data->CommodityID,
                'ParameterID' => $data->ParameterID
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Find record by ID
     * @param int $id
     */
    public function findById(int $id)
    {
        try {
        return QualityCommodityParameter::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param QualityCommodityParameterRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QualityCommodityParameterRequest $request, int $id)
    {
        try {
            $qualityCommodityParameter = QualityCommodityParameter::find($id);
            $qualityCommodityParameter->CommodityID = $request->CommodityID;
            $qualityCommodityParameter->ParameterID = $request->ParameterID;
            $qualityCommodityParameter->save();
            return $qualityCommodityParameter;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusReject(array $id)
    {
        try {
            $idcollection = collect($id);
        } catch (Exception $e) {
            throw $e;
        }
        $idcollection->map(function (array $ids) {
        return QualityCommodityParameter::whereIn("ID", $ids)
            ->update(['Status' => 'Rejected']);
        })->all();

    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusFinalize(array $id)
    {
        try {
            $idcollection = collect($id);
        } catch (Exception $e) {
            throw $e;
        }
        $idcollection->map(function (array $ids) {
            return QualityCommodityParameter::whereIn("ID", $ids)
                ->update(['Status' => 'Active']);
        })->all();
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusApprove(array $id)
    {
        try {
            $idcollection = collect($id);
        } catch (Exception $e) {
            throw $e;
        }
        $idcollection->map(function (array $ids) {
            return QualityCommodityParameter::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
