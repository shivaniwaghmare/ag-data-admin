<?php

namespace App\Services\Farmer\EducationFarmer;

use Exception;
use App\Http\Requests\Farmer\EducationFarmer\EducationFarmerRequest;
use App\Repositories\Farmer\EducationFarmer\EducationFarmerRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\Farmer\EducationFarmer\Lists\EducationFarmerListResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Resources\Farmer\EducationFarmer\Table\EducationFarmerTableCollection;


class EducationFarmerService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param EducationFarmerRepository $repository
     *
     * @return void
     */
    public function __construct(EducationFarmerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return EducationFarmerCollection
     */
    public function getAllPaginatedTableData(String $on = null, String $search = null)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Return in the given API resource format
        return new EducationFarmerTableCollection($this->repository->find($on, $search, $rowsPerPage));
    }

    /**
     * Creates a new Example in the DB
     *
     * @param EducationFarmerRequest $data
     *
     * @return Array
     */
    public function add(EducationFarmerRequest $data )
    {
        try {
            return $this->repository->add($data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }  catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the EducationFarmer model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findEducationFarmerById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new EducationFarmerListResource($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param EducationFarmerRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(EducationFarmerRequest $request, int $id)
    {
        // Retrieve the Example from the database
       try {
        $example = $this->repository->findById($id);
        if ($example) {
            return $this->repository->update($request, $id);
        }
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Get the dynamic table columns
     *
     * @return array
     */
    public function getTableFields(): array
    {
        return $this->repository->getTableFields();
    }

    /**
     * Get the dynamic form elements
     *
     * @return array
     */
    public function getFormFields(): array
    {
        return $this->repository->getFormFields();
    }

    /**

     * Update the status of an Example record to 'rejected'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateRejectStatus(array $id)
    {
        try {
            return $this->repository->updateStatusReject(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Example record to 'Active'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateFinalizeStatus(array $id)
    {
        try{
            return $this->repository->updateStatusFinalize(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Update the status of an Example record to 'Approved'.
     *
     * Get the id array
     *
     * @param array id
     */
    public function updateApproveStatus(array $id)
    {
        try{
            return $this->repository->updateStatusApprove(array($id));
        } catch(Exception $e) {
            throw $e;
        }
    }
}
