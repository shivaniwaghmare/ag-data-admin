<?php

namespace App\Repositories\General\ModeOfPayment;

use Exception;
use App\Models\General\ModeOfPayment;
use App\Http\Requests\General\ModeOfPayment\ModeOfPaymentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\General\ModeOfPayment\RepoInterface\ModeOfPaymentRepoInterface;

class ModeOfPaymentRepository implements ModeOfPaymentRepoInterface
{
    /**
     * Find ModeOfPayments and get results in pagination
     * @param string $on The field to search
     * @param string $search The value to search with a like '%%' wildcard
     * @param int $rowsPerPage Number of rows to display in a page
     */
    public function find(string $on = null, string $search = null, int $rowsPerPage = 50)
    {
         try {
            return ModeOfPayment::query()
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
            throw $e;
        }
    }

    /**
     * Get the list of table columns for the data table
     *
     * @return array
     */
    public function getTableFields(): array
    {
        try {
            return ModeOfPayment::getTableFields();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the list of form elements for the form builder
     *
     * @return array
     */
    public function getFormFields(): array
    {
        try {
            return ModeOfPayment::getFormFields();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Add a single record in the table
     *
     * @param ModeOfPaymentRequest $data
     *
     * @return Array
     */
    public function add(ModeOfPaymentRequest $data)
    {
        //Create a new entry in db
        try {
            ModeOfPayment::create([
                'Name' => $data->Name,
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
            return ModeOfPayment::findOrFail($id);
        } catch (ModelNotFoundException $e) {
           throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param ModeOfPaymentRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ModeOfPaymentRequest $request, int $id)
    {
        try {
            $modeOfPayment = ModeOfPayment::find($id);
            $modeOfPayment->Name = $request->Name;
            $modeOfPayment->save();
            return $modeOfPayment;
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

            $idcollection->map(function (array $ids) {
            return ModeOfPayment::whereIn("ID", $ids)
                ->update(['Status' => 'Rejected']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
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

            $idcollection->map(function (array $ids) {
            return ModeOfPayment::whereIn("ID", $ids)
                ->update(['Status' => 'Active']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
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

            $idcollection->map(function (array $ids) {
            return ModeOfPayment::whereIn("ID", $ids)
                ->update(['Status' => 'Approved']);
            })->all();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
