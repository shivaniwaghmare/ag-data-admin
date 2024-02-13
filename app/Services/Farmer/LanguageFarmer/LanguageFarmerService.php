<?php

namespace App\Services\Farmer\LanguageFarmer;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use SebastianBergmann\Comparator\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\Farmer\LanguageFarmer\LanguageFarmerRequest;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use App\Repositories\Farmer\LanguageFarmer\LanguageFarmerRepository;
use App\Http\Resources\Farmer\LanguageFarmer\List\LanguageFarmerListResource;
use App\Http\Resources\Farmer\LanguageFarmer\List\LanguageFarmerListCollection;
use App\Http\Requests\Farmer\LanguageFarmer\LanguageFarmerRequest\LanguageFarmer;
use App\Http\Resources\Farmer\LanguageFarmer\Table\LanguageFarmerTableCollection;


class LanguageFarmerService
{
    protected $repository;

    /**
     * Constructor based dependency injection
     *
     * @param LanguageFarmerRepository $repository
     *
     * @return void
     */
    public function __construct(LanguageFarmerRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get list of all records separated by pagination
     *
     * @param String $on The field to search
     * @param String $search The value to search with a like '%%' wildcard
     *
     * @return LanguageFarmerCollection
     */
    public function getAllPaginatedTableData(String $on = null, String $search = null)
    {
        // Get number of rows to display in a DataTable
        // from the global configuration
        $rowsPerPage = config('custom.dataTablePagination');

        // Return in the given API resource format
        return new LanguageFarmerTableCollection($this->repository->findAll($on, $search, $rowsPerPage));
    }

    /**
     * Return all data in the list resource format
     *
     * @param String $on Search field
     * @param String $search Search topic
     *
     * @return  LanguageFarmerListCollection
     */
    public function getAllRecords($on = "", $search = "")
    {
        return new LanguageFarmerListCollection(
            $this->repository->findAll($on, $search, false)
        );
    }

    /**
     * Creates a new Example in the DB
     *
     * @param LanguageFarmerRequest $data
     *
     * @return Array
     */
    public function add(string $fileUrl, LanguageFarmerRequest  $data )
    {
        try {
           if ($fileUrl) {
                //set image field
                $data->merge([
                    'ProfilePhoto' => $fileUrl,
                ]);
        }
            return $this->repository->add($fileUrl, $data);
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    public function S3ImageUpload(Request $request)
    {
        try {
            $path = $request->file('ProfilePhoto')->store('images', 's3');
            $filePath_normal = 'photo/' . $request->file('ProfilePhoto')->hashName();
            $filePath_thumb = 'thumbnails/' . $request->file('ProfilePhoto')->hashName();
            $image_normal = Image::make( $request->file('ProfilePhoto'))->widen(800, function ($constraint) {$constraint->upsize();});
            $image_thumb = Image::make( $request->file('ProfilePhoto'))->crop(100,100);
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
            $imgUrl= parse_url($example->ProfilePhoto);
            Storage::disk('s3')->delete($imgUrl['path']);
            $photo = parse_url(Str::replace('images/', 'photo/', $example->ProfilePhoto));
            Storage::disk('s3')->delete($photo['path']);
            $thumbnails = parse_url(Str::replace('images/', 'thumbnails/', $example->ProfilePhoto));
            Storage::disk('s3')->delete($thumbnails['path']);
            return true;
            // return $request->file('ProfilePhoto')->delete('images', 's3');
        } catch (BadRequestException $e) {
            throw $e;
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    /**
     * Render the edit view for the LanguageFarmer model.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function findExampleById(int $id)
    {
        try {
            //return $this->repository->findById($id);
            return collect(new LanguageFarmerListResource($this->repository->findById($id)));
        } catch (NotFoundHttpException $e) {
            throw $e;
        }
    }

    /**
     * Update the specified resource in db.
     *
     * @param LanguageFarmerRequest $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(LanguageFarmerRequest $request, $fileUrl, int $id)
    {
        // Retrieve the Example from the database
       try {
        // Retrieve the Example from the database
        $example = $this->repository->findById($id);
        if ($fileUrl) {
            $request->merge([
                'ProfilePhoto' => $fileUrl,
            ]);
            $path = $request->file('ProfilePhoto')->store('images', 's3');
            $filePath_normal = 'photo/' . $request->file('ProfilePhoto')->hashName();
            $filePath_thumb = 'thumbnails/' . $request->file('ProfilePhoto')->hashName();
            $image_normal = Image::make( $request->file('ProfilePhoto'))->widen(800, function ($constraint) {$constraint->upsize();});
            $image_thumb = Image::make( $request->file('ProfilePhoto'))->crop(100,100);
                    Storage::disk('s3')->put($filePath_normal, $image_normal->stream());
                    Storage::disk('s3')->put($filePath_thumb, $image_thumb->stream());
                    Storage::disk('s3')->url($path);
        }
        if (!$example) {
            throw new \InvalidArgumentException('LanguageFarmer not found.');
        }
         return $this->repository->update($request, $id, $fileUrl);
        }catch (NotFoundHttpException $e) {
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
     * @return array
     */
    public function updateRejectStatus(array $id)
    {
        return $this->repository->updateStatusReject(array($id));
    }

    /**
     * Update the status of an Example record to 'Active'.
     *
     * Get the id array
     *
     * @return array
     */
    public function updateFinalizeStatus(array $id)
    {
        return $this->repository->updateStatusFinalize(array($id));
    }

    /**
     * Update the status of an Example record to 'Approved'.
     *
     * Get the id array
     *
     * @return array
     */
    public function updateApproveStatus(array $id)
    {
        return $this->repository->updateStatusApprove(array($id));
    }
}
