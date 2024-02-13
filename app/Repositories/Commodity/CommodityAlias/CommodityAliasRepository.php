<?php

namespace App\Repositories\Commodity\CommodityAlias;

use Exception;
use App\Models\Commodity\CommodityAlias;
use App\Http\Requests\Commodity\CommodityAlias\CommodityAliasRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Commodity\CommodityAlias\RepoInterface\CommodityAliasRepoInterface;

class CommodityAliasRepository implements CommodityAliasRepoInterface
{
     /**
     * Find CommodityAlias and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return CommodityAlias::query()
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
     * Find all CommodityAlias as per condition for dropdown listing
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     */
    public function all(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return CommodityAlias::query()
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
        return CommodityAlias::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return CommodityAlias::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param CommodityAliasRequest $data
     *
     * @return Array
     */
    public function add(CommodityAliasRequest $data)
    {
        //Create a new entry in db
        try {
            CommodityAlias::create([
                'Name' => $data->Name,
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
        return CommodityAlias::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CommodityAliasRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommodityAliasRequest $request, int $id)
    {
    try {
        //update the record in db
        return CommodityAlias::where('ID',$id)->update([
            'Name' => $request->Name,
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
        return CommodityAlias::whereIn("ID", $ids)
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
            return CommodityAlias::whereIn("ID", $ids)
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
            return CommodityAlias::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
