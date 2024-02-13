<?php

namespace App\Repositories\Commodity\HSNCode;

use Exception;
use App\Models\Commodity\HSNCode;
use App\Http\Requests\Commodity\HSNCode\HSNCodeRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Commodity\HSNCode\RepoInterface\HSNCodeRepoInterface;

class HSNCodeRepository implements HSNCodeRepoInterface
{
    /**
     * Find HSNCode and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return HSNCode::query()
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
     * Find all HSNCode as per condition for dropdown listing
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     */
    public function all(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return HSNCode::query()
            ->when(!empty($on) && count(explode(".", $on)) == 1, function ($query) use ($on, $search) {
                $query->where($on, '=', "{$search}");
            })
            ->orderBy('HSCode', 'desc')
            ->get();
    }

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        return HSNCode::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return HSNCode::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param HSNCodeRequest $data
     *
     * @return Array
     */
    public function add(HSNCodeRequest $data)
    {
        // dump($data->Description);
        //Create a new entry in db
        try {
            HSNCode::create([
                'CommodityID' => $data->CommodityID,
                'GeneralCommodityID' => $data->GeneralCommodityID,
                'CommodityClassID' => $data->CommodityClassID,
                'HSCode' => $data->HSCode,
                'UomID' => $data->UomID,
                'Description' => $data->Description
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
        return HSNCode::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param HSNCodeRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(HSNCodeRequest $request, int $id)
    {
    try {
        //update the record in db
        return HSNCode::where('ID',$id)->update([
            'CommodityID' => $request->CommodityID,
            'GeneralCommodityID' => $request->GeneralCommodityID,
            'CommodityClassID' => $request->CommodityClassID,
            'HSCode' => $request->HSCode,
            'UomID' => $request->UomID,
            'Description' => $request->Description
        ]);
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
        return HSNCode::whereIn("ID", $ids)
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
            return HSNCode::whereIn("ID", $ids)
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
            return HSNCode::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
