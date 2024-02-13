<?php

namespace App\Repositories\Farmer\LanguageFarmer;

use Exception;
use App\Models\Farmer\LanguageFarmer;
use App\Http\Requests\Farmer\LanguageFarmer\LanguageFarmerRequest;
use App\Repositories\Farmer\LanguageFarmer\RepoInterface\LanguageFarmerRepoInterface;

class LanguageFarmerRepository implements LanguageFarmerRepoInterface
{

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        return LanguageFarmer::getTableFields();
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return LanguageFarmer::getFormFields();
    }


    /**
     * Add a single record in the table
     *
     * @param LanguageFarmerRequest $data
     *
     * @return Array
     */
    public function add(string $s3url, LanguageFarmerRequest $data)
    {
        //Create a new entry in db
        try {
            LanguageFarmer::create([
                'Name' => $data->Name,
                'AboutMe' => $data->AboutMe,
                'Gender' => $data->Gender,
                'ProfilePhoto' => $s3url
            ]);
        } catch (Exception $e) {
            return $e;
        }
    }


    /**
     * Add multiple records in the table with bulk insert query.
     * Divide the inputs in chunks and make multiple queries.
     * TODO: Implement DTO
     */
    public function addMany(LanguageFarmerRequest $data)
    {
        try {
        return LanguageFarmer::insert($data->toArray());
         } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Find LanguageFarmers and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function findAll(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
         try {
        return LanguageFarmer::query()
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
             } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Find record by ID
     * @param int $id Find the record by ID
     */
    public function findById(int $id)
    {
         // TODO: resource dto
        try {
        return LanguageFarmer::select(['Name', 'AboutMe', 'ProfilePhoto', 'Gender'])->where('ID', $id)->firstOrFail();
        } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Find records by name
     */
    public function findByName()
    {
    }

    /**
     * Update a LanguageFarmer
     */


      /**
     * Update the specified resource in db.
     *
     * @param \App\Http\Requests\App\Models\Farmer\LanguageFarmer\LanguageFarmerRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LanguageFarmerRequest $request, int $id)
    {
    try {
        $s3url = $request->input('ProfilePhoto');
        //update the record in db
        return LanguageFarmer::where('ID',$id)->update([
            'Name' => $request->Name,
            'AboutMe' => $request->AboutMe,
            'Gender' => $request->Gender,
            'ProfilePhoto' => $s3url
        ]);
         } catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Soft delete a LanguageFarmer
     */
    public function delete()
    {
    }

    /**
     * Get the list of ids from request
     *
     * @return array
     */
    public function updateStatusReject(array $id)
    {
        $idcollection = collect($id);
        $idcollection->map(function (array $ids) {
            return LanguageFarmer::whereIn("ID", $ids)
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
        $idcollection = collect($id);
        $idcollection->map(function (array $ids) {
            return LanguageFarmer::whereIn("ID", $ids)
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
        $idcollection = collect($id);
        $idcollection->map(function (array $ids) {
            return LanguageFarmer::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
        })->all();
    }
}
