<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use AkmalRiyadi\LaravelBackendGenerator\Traits\HasFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
use AkmalRiyadi\LaravelBackendGenerator\Enums\PaginateType;
use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions;
use AkmalRiyadi\LaravelBackendGenerator\Resources\Paginate;

class BaseRepository
{

    use HasFile;

    /**
     * define Model
     * @property Model $model
     */
    protected $model;

    /**
     * define option relation
     * 
     * example for relation : $this->option['with']
     * example for count relation : $this->option['withCount']
     * 
     * this option define on parentRepository such as UserRepository
     * @property array $option 
     */
    protected $option;

    protected $resourceClass;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Show all item
     * 
     * @param Request $request
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param bool $filterOption option for scopeFilter on model::class
     * @param bool $paginateOption option for pagination data
     * @param bool $paginateRequest option for paginate from request
     * @param int | null $paginateCustomCount this will enable when paginateType is CUSTOM
     * @param bool $limitOption option if you want to show data as pagination without limit, if false this will force $requestLimit to MAX INT
     * @param string $columnOrder column order data
     * @param string $sortOrder sort method order data
     * @param bool $resourceOption class for api resource
     * 
     * @return array 
     */
    public function all(
        Request $request,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $filterOption = false,
        bool $paginateOption = false,
        bool $paginateRequest = true,
        ?int $paginateCustomCount = 5,
        string $columnOrder = 'created_at',
        string $sortOrder = 'desc',
        bool $resourceOption = false
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->orderBy($columnOrder, $sortOrder);
        if ($filterOption) {
            $query->filter($request->all());
        }

        if ($paginateOption) {
            return $this->pagination(query: $query, paginateRequest: $paginateRequest, request: $request, paginateCustomCount: $paginateCustomCount, resourceOption: $resourceOption);
        }

        if ($resourceOption) {
            return $this->resourceClass::collection($query->get());
        }
        $query = $query->get();
        return $query;
    }

    /**
     * find data with where clause
     * @param Request | null $request
     * @param string $column //column data for where
     * @param string $ident //data for check on yout column
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param string $columnOrder column order data
     * @param string $sortOrder sort method order data
     * @param QueryOptions $getOption use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions ["QueryOptions::GET","QueryOptions::FIRST"]
     * @param bool $paginateRequest Option for paginate from request
     * @param int | null $paginateCustomCount this will enable when paginateType is CUSTOM
     * @param bool $resourceOption class for api resource
     * 
     * @return Collection | array
     */
    public function where(
        ?Request $request = null,
        string $column,
        string $ident,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $columnOrder = 'created_at',
        string $sortOrder = 'desc',
        QueryOptions $getOption = QueryOptions::GET,
        bool $paginateRequest = true,
        ?int $paginateCustomCount = 5,
        bool $resourceOption = false
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->where($column, $ident)->orderBy($columnOrder, $sortOrder);
        $result = $query;
        switch ($getOption) {
            case QueryOptions::PAGINATE:
                return $this->pagination(query: $query, paginateRequest: $paginateRequest, request: $request, paginateCustomCount: $paginateCustomCount, resourceOption: $resourceOption);
            case QueryOptions::GET:
                $result = $query->get();
                break;
            default:
                $result = $query->first();
                break;
        }
        if ($resourceOption) {
            switch ($getOption) {
                case QueryOptions::FIRST:
                    $result = new $this->resourceClass($result);
                    break;
                default:
                    $result = $this->resourceClass::collection($result);
                    break;
            }
        }
        return $result;
    }

    /**
     * find item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param bool $resourceOption class for api resource
     * 
     * @return Model
     */
    public function find(
        int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $resourceOption = false,
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->find($id);
        $result = $query;
        if ($resourceOption) {
            $result = new $this->resourceClass($query);
        }
        return $result;
    }

    /**
     * find or fail item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param bool $resourceOption option for resource class
     * 
     * @return Model
     */
    public function findOrFail(
        int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $resourceOption = false,
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query = $query->findOrFail($id);
        $result = $query;
        if ($resourceOption) {
            $result = new $this->resourceClass($result);
        }
        return $result;
    }

    /**
     * Create item
     * @param Request $request
     * @return Model
     */
    public function create(Request $request)
    {
        return $this->model->create($request->all());
    }

    /**
     * Update Item
     * @param int $id
     * @param Request $request
     * 
     * @return bool
     * @throws ModelNotFoundException
     */
    public function update(int $id, Request $request)
    {
        $source = $this->model->findOrFail($id);
        return $source->update($request->all());
    }

    /**
     * Delete Item
     * @param int $id
     * 
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id)
    {
        $source = $this->model->findOrFail($id);
        return $source->delete();
    }

    /**
     * Destroy multiple item
     * @param array $id
     * @return int The number of items deleted
     */
    public function destroy(array $id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Force delete item
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id)
    {
        $source = $this->model->onlyTrashed()->findOrFail($id);
        return $source->forceDelete($id);
    }

    /**
     * Restore item
     * @param int $id
     * @return bool
     */
    public function restore(int $id)
    {
        $source = $this->model->onlyTrashed()->findOrFail($id);
        return $source->restore();
    }

    /**
     * Generate item option query model
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function itemOptionQuery(
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
    ) {
        switch ($itemOptions) {
            case ItemOptions::WITH_TRASHED:
                $query = $this->model->withTrashed();
                break;
            case ItemOptions::ONLY_TRASHED:
                $query = $this->model->onlyTrashed();
                break;
            default:
                $query = $this->model->newQuery();
                break;
        }

        if ($withOption) {
            $query->with($this->option['with'] ?? []);
        }

        if ($withCountOption) {
            $query->withCount($this->option['withCount'] ?? []);
        }

        return $query;
    }

    /**
     * Easly pagination model->paginate() to pagination data
     *
     * @param Builder $query // Your database builder query
     * @param Request $request
     * @param bool $paginateRequest
     * @param integer $paginateCustomCount
     * @param string|null $resourceOption
     * 
     * @return array
     */
    public function pagination(
        Builder $query,
        Request $request,
        bool $paginateRequest = true,
        ?int $paginateCustomCount = 5,
        bool $resourceOption = false
    ) {
        switch ($paginateRequest) {
            case PaginateType::CUSTOM:
                $limit = $paginateCustomCount;
                break;
            default:
                $limit = $request->limit ?? 5;
                break;
        }
        if (!$limit) {
            $limit =  PHP_INT_MAX;
        }
        $data = $query->paginate($limit);
        $pagination = new Paginate($data);
        if ($resourceOption) {
            $data = $this->resourceClass::collection($data);
            $data = [
                'pagination' => $pagination,
                'data' => $data
            ];
        } else {
            $data = [
                'pagination' => $pagination,
                'data' => $data->items()
            ];
        }
        return $data;
    }

    /**
     * Easly pagination collection data to pagination data
     *
     * @param Collection $collection
     * @param Request $request
     * @param bool $paginateRequest
     * @param int $paginateCustomCount
     * 
     * @return LengthAwarePaginator
     */
    public function customPaginate(
        Collection $collection,
        Request $request,
        bool $paginateRequest = true,
        ?int $paginateCustomCount = 5,
    ) {
        switch ($paginateRequest) {
            case PaginateType::CUSTOM:
                $limit = $paginateCustomCount;
                break;
            default:
                $limit = $request->limit ?? 5;
                break;
        }
        if (!$limit) {
            $limit =  PHP_INT_MAX;
        }
        $currentPage = $request->page;
        $currentPageItems = $collection->slice(($currentPage - 1) * $limit, $limit)->all();
        $paginator = new LengthAwarePaginator($currentPageItems, count($collection), $limit, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'page',
            'query' => $request->query(),
        ]);

        return $paginator;
    }

    /**
     * Update your .env data
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function storeConfiguration(string $key, string $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents(
                $path,
                str_replace(
                    $key . '=' . env($key),
                    $key . '=' . $value,
                    file_get_contents($path)
                )
            );
        }
    }
}
