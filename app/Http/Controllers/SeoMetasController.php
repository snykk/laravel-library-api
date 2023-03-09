<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeoMetaSaveRequest;
use App\Http\Resources\SeoMetaCollection;
use App\Http\Resources\SeoMetaResource;
use App\Models\SeoMeta;
use App\QueryBuilders\SeoMetaBuilder;
use Illuminate\Http\JsonResponse;

/**
 * @group Seo Meta Management
 *
 * API Endpoints for managing seo metas.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SeoMetasController extends Controller
{
    /**
     * Determine if any access to this resource require authorization.
     *
     * @var bool
     */
    protected static $requireAuthorization = true;

    /**
     * SeoMetasController constructor.
     */
    public function __construct()
    {
        if (self::$requireAuthorization || (auth()->user() !== null)) {
            $this->authorizeResource(SeoMeta::class);
        }
    }

    /**
     * Resource Collection.
     * Display a collection of the seo meta resources in paginated document format.
     *
     * @authenticated
     *
     * @queryParam fields[seo_metas] *string* - No-example
     * Comma-separated field/attribute names of the seo_meta resource to include in the response document.
     * The available fields for current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `large_seo_image`, `small_seo_image`.
     * @queryParam page[size] *integer* - No-example
     * Describe how many records to display in a collection.
     * @queryParam page[number] *integer* - No-example
     * Describe the number of current page to display.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     * @queryParam sort *string* - No-example
     * Field/attribute to sort the resources in response document by.
     * The available fields for sorting operation in current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam filter[`filterName`] *string* - No-example
     * Filter the resources by specifying *attribute name* or *query scope name*.
     * The available filters for current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @qeuryParam search *string* - No-example
     * Filter the resources by specifying any keyword to search.
     *
     * @param \App\QueryBuilders\SeoMetaBuilder $query
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return SeoMetaCollection
     */
    public function index(SeoMetaBuilder $query): SeoMetaCollection
    {
        return new SeoMetaCollection($query->paginate());
    }

    /**
     * Create Resource.
     * Create a new seo meta resource.
     *
     * @authenticated
     *
     * @param \App\Http\Requests\SeoMetaSaveRequest $request
     * @param \App\Models\SeoMeta                   $seoMeta
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return JsonResponse
     */
    public function store(SeoMetaSaveRequest $request, SeoMeta $seoMeta): JsonResponse
    {
        $seoMeta->fill($request->only($seoMeta->offsetGet('fillable')))
            ->save();

        $seoMeta->saveSeoImage($request);

        $resource = (new SeoMetaResource($seoMeta))
            ->additional(['info' => 'The new seo meta has been saved.']);

        return $resource->toResponse($request)->setStatusCode(201);
    }

    /**
     * Show Resource.
     * Display a specific seo meta resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam seoMeta required *integer* - No-example
     * The identifier of a specific seo meta resource.
     *
     * @queryParam fields[seo_metas] *string* - No-example
     * Comma-separated field/attribute names of the seo_meta resource to include in the response document.
     * The available fields for current endpoint are: `id`, `seo_url`, `model`, `foreign_key`, `locale`, `seo_title`, `seo_description`, `open_graph_type`, `deleted_at`, `created_at`, `updated_at`.
     * @queryParam append *string* - No-example
     * Comma-separated mutated field/attribute names which you wish to append in the response document.
     * The available mutated fields for current endpoint are: `large_seo_image`, `small_seo_image`.
     * @queryParam include *string* - No-example
     * Comma-separated relationship names to include in the response document.
     * The available relationships for current endpoint are: ***`None`***.
     *
     * @param \App\QueryBuilders\SeoMetaBuilder $query
     * @param \App\Models\SeoMeta               $seoMeta
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * @return SeoMetaResource
     */
    public function show(SeoMetaBuilder $query, SeoMeta $seoMeta): SeoMetaResource
    {
        return new SeoMetaResource($query->find($seoMeta->getKey()));
    }

    /**
     * Update Resource.
     * Update a specific seo meta resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam seoMeta required *integer* - No-example
     * The identifier of a specific seo meta resource.
     *
     * @param \App\Http\Requests\SeoMetaSaveRequest $request
     * @param \App\Models\SeoMeta                   $seoMeta
     *
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\DiskDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist
     * @throws \Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \ErrorException
     *
     * @return SeoMetaResource
     */
    public function update(SeoMetaSaveRequest $request, SeoMeta $seoMeta): SeoMetaResource
    {
        $seoMeta->fill($request->only($seoMeta->offsetGet('fillable')));

        if ($seoMeta->isDirty()) {
            $seoMeta->save();
        }

        $seoMeta->saveSeoImage($request);

        return (new SeoMetaResource($seoMeta))
            ->additional(['info' => 'The seo meta has been updated.']);
    }

    /**
     * Delete Resource.
     * Delete a specific seo meta resource identified by the given id/key.
     *
     * @authenticated
     *
     * @urlParam seoMeta required *integer* - No-example
     * The identifier of a specific seo meta resource.
     *
     * @param \App\Models\SeoMeta $seoMeta
     *
     * @throws \Exception
     *
     * @return SeoMetaResource
     */
    public function destroy(SeoMeta $seoMeta): SeoMetaResource
    {
        $seoMeta->delete();

        return (new SeoMetaResource($seoMeta))
            ->additional(['info' => 'The seo meta has been deleted.']);
    }
}
