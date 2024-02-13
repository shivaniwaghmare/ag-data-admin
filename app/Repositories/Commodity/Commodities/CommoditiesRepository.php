<?php

namespace App\Repositories\Commodity\Commodities;

use Exception;
use App\Models\Commodity\Commodities;
use App\Http\Requests\Commodity\Commodities\CommoditiesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Commodity\Commodities\RepoInterface\CommoditiesRepoInterface;

class CommoditiesRepository implements CommoditiesRepoInterface
{
    /**
     * Find Commodities and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return Commodities::query()
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
     * Find all Commodities as per condition for dropdown listing
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     */
    public function all(string $on = null, string $search = null)
    {
        return Commodities::query()
            ->when(!empty($on) && count(explode(".", $on)) == 1, function ($query) use ($on, $search) {
                $query->where($on, '=', "{$search}");
            })
            ->orderBy('Name', 'asc')
            ->get();
    }

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        return Commodities::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return Commodities::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param CommoditiesRequest $data
     *
     * @return Array
     */
    public function add(string $fileUrl, CommoditiesRequest $data)
    {
        //Create a new entry in db
        try {
            Commodities::create([
                'Name' => $data->Name,
                'Description' => $data->Description,
                'ScientificName' => $data->ScientificName,
                'CommodityGroupID' => $data->CommodityGroupID,
                'CommodityGroupIndex' => $data->CommodityGroupIndex,
                'Logo' => $fileUrl
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Find record by ID
     * @param int $id
     */
    public function findById(int $id)
    {
        try {
            return Commodities::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CommoditiesRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommoditiesRequest $request, int $id)
    {
        try {
            $fileUrl = $request->input('Logo');
            //update the record in db
            return Commodities::where('ID', $id)->update([
                'Name' => $request->Name,
                'Description' => $request->Description,
                'ScientificName' => $request->ScientificName,
                'CommodityGroupID' => $request->CommodityGroupID,
                'CommodityGroupIndex' => $request->CommodityGroupIndex,
                'Logo' => $fileUrl
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
            return Commodities::whereIn("ID", $ids)
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
            return Commodities::whereIn("ID", $ids)
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
            return Commodities::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
