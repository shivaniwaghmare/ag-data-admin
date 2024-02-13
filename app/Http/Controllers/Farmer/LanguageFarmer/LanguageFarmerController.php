<?php

namespace App\Http\Controllers\Farmer\LanguageFarmer;

use Exception;
use Inertia\Inertia;
use Inertia\Response;
use App\Exports\ErrorExport;
use App\Models\Farmer\LanguageFarmer;
use Illuminate\Http\Request;
use App\Exports\CustomExport;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use App\Services\Farmer\LanguageFarmer\LanguageFarmerService;
use App\Http\Requests\Farmer\LanguageFarmer\LanguageFarmerRequest;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Http\Requests\ImportRequest\ImportValidationRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class LanguageFarmerController extends Controller
{
    protected $service;

    /**
     * Base URL of this module
     */
    private string $baseUrl = "/farmer/language-farmer";

    /**
     * Injects LanguageFarmer Service dependency through the constructor
     *
     * @param LanguageFarmerService $service
     *
     * @return Void
     */
    public function __construct(LanguageFarmerService $service)
    {
        $this->service = $service;
    }

    /**
     * List page to display all data with pagination support.
     *
     *@link http://farmer/language-farmer
     *
     *@http_method GET
     *
     * @param Request $request The request object
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $data = null;
        $code = "SUCCESS";
        try {
            return Inertia::render(
                component: 'Frontend/List',
                props: [
                    'type' => 'List',
                    'title' => "Language Farmer",
                    'data' => $this->service->getAllPaginatedTableData(
                        on: $request->on,
                        search: $request->search
                    ),
                    'filters' => $request->only(['on', 'search']),
                    'url' => $this->baseUrl,
                    'actions' => [
                        'Edit',
                        'Approve',
                        'Finalize',
                        'Reject'
                    ]
                ]
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (ModelNotFoundException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
                data: $data,
            );
        } else {
            return redirect($this->baseUrl);
        }
    }

    /**
     * Get all data with ID:Name pair in JSON format
     *
     * @param None
     * @return Inertia
     */
    public function list(Request $request)
    {
        return $this->service->getAllRecords();
    }

    /**
     * Displays a form to add new LanguageFarmer
     *
     *@link http://farmer/language-farmer/create/
     *
     *@http_method GET
     *
     * @param LanguageFarmerRequest $request The request object
     *
     * @return Response
     */
    public function create(LanguageFarmerRequest $request): Response

    {
        $code = "SUCCESS";
        try {
            $data = $this->service->create(data: $request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
        } else {
            return Inertia::render(
                component: 'Frontend/Create',
                props: [
                    'type' => 'Add',
                    'title' => 'Add Language Farmer',
                    'url' => $this->baseUrl,
                    'fields' => $this->service->getFormFields()
                ]
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     *@link http://farmer/language-farmer/store
     *
     *@http_method POST
     *
     * @param LanguageFarmerRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LanguageFarmerRequest $request)
    {
        $code = "SUCCESS";
        try {

            $fileUrl = $this->service->S3ImageUpload($request);
            $this->service->add($fileUrl, data: $request);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        code:
        $code;
        return redirect($this->baseUrl);
    }

    /**
     * Displays a form with values to update LanguageFarmer
     *
     *@link http://farmer/language-farmer/edit/id
     *
     *@http_method GET
     *
     * @return Response
     */
    public function edit(int $id): Response
    {
        $data = null;
        $code = "SUCCESS";
        try {
            $data = $this->service->findLanguageFarmerById($id);
            $fieldsCollection = collect($this->service->getFormFields())->map(function ($fields) use ($data) {
                $attrib = $fields['name'];
                $fields['value']  = $data[$attrib];
                return $fields;
            });

            return Inertia::render(
                component: 'Frontend/Create',
                props: [
                    'type' => 'Edit',
                    'title' => 'Language Farmer',
                    'url' => $this->baseUrl,
                    'fields' => $this->service->getTableFields()
                ]
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code != "SUCCESS") {
            dd($e);
            return ResponseHelper::respond(
                code: $code
            );
        }
        return redirect($this->baseUrl);
    }

    /**
     * show the status in list
     *
     *@link http://farmer/language-farmer/show/id
     *
     *@http_method GET
     *
     * @param Request $id The request object
     *
     * @return request
     */
    public function show()
    {
        //
    }
    /**
     * update the status in list
     *
     *@link http://farmer/language-farmer/update/id
     *
     *@http_method PATCH/PUT
     *
     * @param Request $request The request object
     * @param $int $id The request object
     *
     * @return request
     */
    public function update(int $id, LanguageFarmerRequest $request)
    {
        $code = "SUCCESS";
        $fileUrl = '';
        try {
            if ($request->hasFile('ProfilePhoto')) {
                $fileUrl = $this->service->S3ImageUpload($request);
                if ($fileUrl) {
                    //Remove old image
                    $this->service->S3ImageDelete($id);

                    $this->service->update($request, $fileUrl, $id);
                }
            }
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
            );
        } else {
            return redirect($this->baseUrl);
        }
    }

    /**
     * missing the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function missing(Request $request)
    {
        try {
            $data = $this->service->create(data: $request);
            return Inertia::render('Frontend/List', [
                'title' => "Language Farmer",
                'data' => $this->service->getAllPaginatedTableData($request->on, $request->search),
                'filters' => $request->only(['on', 'search']),
                'url' => $this->baseUrl,
                'actions' => ['Edit', 'Approve', 'Finalize', 'Reject']
            ]);
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        return ResponseHelper::respond(
            code: $code,
        );
    }

    /**
     * reject the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function reject(Request $request)
    {
        $code = "SUCCESS";
        try {
            $id = $request->all();
            $this->service->updateRejectStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
            );
        } else {
            return redirect($this->baseUrl);
        }
    }

    /**
     * finalize the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function finalize(Request $request)
    {
        $code = "SUCCESS";
        try {
            $id = $request->all();
            $this->service->updateFinalizeStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
            );
        } else {
            return redirect($this->baseUrl);
        }
    }

    /**
     * approve the status in list
     *
     * @param Request $request The request object
     *
     * @return request
     */
    public function approve(Request $request)
    {
        $code = "SUCCESS";
        try {
            $id = $request->all();
            $this->service->updateApproveStatus(
                id: $id
            );
        } catch (AuthenticationException $e) {
            $code = "HTTP_AUTHENTICATION_ERROR";
        } catch (NotFoundHttpException $e) {
            $code = "HTTP_REQUEST_FAILURE";
        } catch (BadRequestException $e) {   //if validation fails
            $code = "DATA_NOT_FOUND";
        } catch (QueryException $e) {
            $code = "QUERY_EXCEPTION";
        } catch (\Exception $e) {
            $code = "DATA_NOT_FOUND";
        }
        if ($code !== "SUCCESS") {
            return ResponseHelper::respond(
                code: $code,
            );
        } else {
            return redirect($this->baseUrl);
        }
    }
}
