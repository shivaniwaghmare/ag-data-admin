<?php

namespace App\Repositories\Farmer\FarmerEnrollmentPlace;

use Exception;
use App\Models\Farmer\FarmerEnrollmentPlace;
use App\Http\Requests\Farmer\FarmerEnrollmentPlace\FarmerEnrollmentPlaceRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Farmer\FarmerEnrollmentPlace\RepoInterface\FarmerEnrollmentPlaceRepoInterface;

class FarmerEnrollmentPlaceRepository implements FarmerEnrollmentPlaceRepoInterface
{
     /**
     * Find FarmerEnrollmentPlace and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return FarmerEnrollmentPlace::query()
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
     * Find all FarmerEnrollmentPlace as per condition for dropdown listing
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     */
    public function all(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
        return FarmerEnrollmentPlace::query()
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
        return FarmerEnrollmentPlace::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return FarmerEnrollmentPlace::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param FarmerEnrollmentPlaceRequest $data
     *
     * @return Array
     */
    public function add(FarmerEnrollmentPlaceRequest $data)
    {
        //Create a new entry in db
        try {
            FarmerEnrollmentPlace::create([
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
        return FarmerEnrollmentPlace::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param FarmerEnrollmentPlaceRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(FarmerEnrollmentPlaceRequest $request, int $id)
    {
        try {
            $farmerEnrollmentPlace = FarmerEnrollmentPlace::find($id);
            $farmerEnrollmentPlace->Name = $request->Name;
            $farmerEnrollmentPlace->save();
            return $farmerEnrollmentPlace;
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
        return FarmerEnrollmentPlace::whereIn("ID", $ids)
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
            return FarmerEnrollmentPlace::whereIn("ID", $ids)
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
            return FarmerEnrollmentPlace::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
