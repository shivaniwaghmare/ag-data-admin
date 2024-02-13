<?php

namespace App\Services\Commodity\Commodities;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\Commodity\Commodities\CommoditiesRequest;
use App\Repositories\Commodity\Commodities\CommoditiesRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Http\Resources\Commodity\Commodities\Lists\CommoditiesListResource;
use App\Http\Resources\Commodity\Commodities\Lists\CommoditiesListCollection;
use App\Http\Resources\Commodity\Commodities\Table\CommoditiesTableCollection;


class CommoditiesService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param CommoditiesRepository $repository
     *
     * @return void
     */
    public function __construct(CommoditiesRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Return all data in the list resource format
     *
     * @param String $on Search field
     * @param String $search Search topic
     *
     * @return  CommoditiesListCollection
     */
    public function getAllRecords($on = "", $search = "")
    {
        return new  CommoditiesListCollection(
            $this->repository->all($on, $search)
        );
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return CommoditiesCollection
     */
    public function getAllPaginatedTableData(String $on = null, String $search = null)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Return in the given API resource format
        return new CommoditiesTableCollection($this->repository->find($on, $search, $rowsPerPage));
    }

    /**
     * Creates a new Example in the DB
     *
     * @param CommoditiesRequest $data
     *
     * @return Array
     */
    public function add(string $fileUrl, CommoditiesRequest $data)
    {
        try {
            if ($fileUrl) {
                //set image field
                $data->merge([
                    'Logo' => $fileUrl,
                ]);
            }
            return $this->repository->add($fileUrl, $data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function S3ImageUpload(Request $request)
    {
        try {
            $path = $request->file('Logo')->store('images', 's3');
            $filePath_normal = 'photo/' . $request->file('Logo')->hashName();
            $filePath_thumb = 'thumbnails/' . $request->file('Logo')->hashName();
            $image_normal = Image::make($request->file('Logo'))->widen(800, function ($constraint) {
                $constraint->upsize();
            });
            $image_thumb = Image::make($request->file('Logo'))->crop(100, 100);
            Storage::disk('s3')->put($filePath_normal, $image_normal->stream());
            Storage::disk('s3')->put($filePath_thumb, $image_thumb->stream());
            return Storage::disk('s3')->url($path);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }
    public function S3ImageDelete(int $id)
    {
        try {
            $example = $this->repository->findById($id);
            $imgUrl = parse_url($example->Logo);
            Storage::disk('s3')->delete($imgUrl['path']);
            $photo = parse_url(Str::replace('images/', 'photo/', $example->Logo));
            Storage::disk('s3')->delete($photo['path']);
            $thumbnails = parse_url(Str::replace('images/', 'thumbnails/', $example->Logo));
            Storage::disk('s3')->delete($thumbnails['path']);
            return true;
            // return $request->file('Logo')->delete('images', 's3');
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the Commodities model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findCommoditiesById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new CommoditiesListResource($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
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
    public function update(CommoditiesRequest $request, $fileUrl, int $id)
    {
        // Retrieve the Example from the database
        try {
            $commodities = $this->repository->findById($id);
            if ($fileUrl) {
                $request->merge([
                    'Logo' => $fileUrl,
                ]);
                $path = $request->file('Logo')->store('images', 's3');
                $filePath_normal = 'photo/' . $request->file('Logo')->hashName();
                $filePath_thumb = 'thumbnails/' . $request->file('Logo')->hashName();
                $image_normal = Image::make($request->file('Logo'))->widen(800, function ($constraint) {
                    $constraint->upsize();
                });
                $image_thumb = Image::make($request->file('Logo'))->crop(100, 100);
                Storage::disk('s3')->put($filePath_normal, $image_normal->stream());
                Storage::disk('s3')->put($filePath_thumb, $image_thumb->stream());
                Storage::disk('s3')->url($path);
            }
            if ($commodities) {
                return $this->repository->update($request, $id, $fileUrl);
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
        } catch (Exception $e) {
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
        try {
            return $this->repository->updateStatusFinalize(array($id));
        } catch (Exception $e) {
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
        try {
            return $this->repository->updateStatusApprove(array($id));
        } catch (Exception $e) {
            throw $e;
        }
    }
}
