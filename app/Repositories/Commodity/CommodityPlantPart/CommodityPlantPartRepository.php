<?php

namespace App\Repositories\Commodity\CommodityPlantPart;

use Exception;
use App\Models\Commodity\CommodityPlantPart;
use App\Http\Requests\Commodity\CommodityPlantPart\CommodityPlantPartRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Commodity\CommodityPlantPart\RepoInterface\CommodityPlantPartRepoInterface;

class CommodityPlantPartRepository implements CommodityPlantPartRepoInterface
{
     /**
     * Find CommodityPlantPart and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return CommodityPlantPart::query()
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
     * Find all CommodityPlantPart as per condition for dropdown listing
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     */
    public function all(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return CommodityPlantPart::query()
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
        return CommodityPlantPart::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return CommodityPlantPart::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param CommodityPlantPartRequest $data
     *
     * @return Array
     */
    public function add(string $fileUrl,CommodityPlantPartRequest $data)
    {
        //Create a new entry in db
        try {
            CommodityPlantPart::create([
                'CommodityID' => $data->CommodityID,
                'PhenophaseID' => $data->PhenophaseID,
                'PlantPartID' => $data->PlantPartID,
                'ImageID' => $data->ImageID,
                'FileUrl' => $fileUrl,
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
        return CommodityPlantPart::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param CommodityPlantPartRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CommodityPlantPartRequest $request, int $id)
    {
    try {
        $fileUrl = $request->input('FileUrl');
        //update the record in db
        return CommodityPlantPart::where('ID',$id)->update([
            'CommodityID' => $request->CommodityID,
            'PhenophaseID' => $request->PhenophaseID,
            'PlantPartID' => $request->PlantPartID,
            'ImageID' => $request->ImageID,
            'FileUrl' => $fileUrl
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
        return CommodityPlantPart::whereIn("ID", $ids)
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
            return CommodityPlantPart::whereIn("ID", $ids)
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
            return CommodityPlantPart::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
