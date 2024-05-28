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
     * @param string $resourceClass class for api resource
     * @param string $columnOrder column order data
     * @param string $sortOrder sort method order data
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
        string $columnOrder = 'created_at',
        string $sortOrder = 'desc',
        string $resourceClass = null
    ) {
        $request = $request->all();
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->orderBy($columnOrder, $sortOrder);
        if ($filterOption) {
            $query->filter($request);
        }

        if ($paginateOption) {
            return $this->pagination(query: $query, requestLimit: $request['limit'] ?? 5, resourceClass: $resourceClass);
        }

        if ($resourceClass) {
            return $resourceClass::collection($query->get());
        }
        $query = $query->get();
        return $query;
    }

    /**
     * find data with where clause
     *
     * @param string $column //column data for where
     * @param string $ident //data for check on yout column
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param string $columnOrder column order data
     * @param string $sortOrder sort method order data
     * @param QueryOptions $getOption use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions ["QueryOptions::GET","QueryOptions::FIRST"]
     * @param string $resourceClass class for api resource
     * 
     * @return Collection
     */
    public function where(
        string $column,
        string $ident,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $columnOrder = 'created_at',
        string $sortOrder = 'desc',
        QueryOptions $getOption = QueryOptions::GET,
        string $resourceClass = null
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->where($column, $ident)->orderBy($columnOrder, $sortOrder);
        $result = $query;
        switch ($getOption) {
            case QueryOptions::FIRST:
                $result = $query->first();
                break;
            default:
                $result = $query->get();
                break;
        }
        if ($resourceClass) {
            switch ($getOption) {
                case QueryOptions::FIRST:
                    $result = new $resourceClass($result);
                    break;
                default:
                    $result = $resourceClass::collection($result);
                    break;
            }
        }
        return $result;
    }

    /**
     * find item by id
     * @param string | int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param string $resourceClass class for api resource
     * 
     * @return Model
     */
    public function find(
        string | int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->find($id);
        $result = $query;
        if ($resourceClass) {
            $result = new $resourceClass($query);
        }
        return $result;
    }

    /**
     * find or fail item by id
     * @param string | int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * 
     * @return Model
     */
    public function findOrFail(
        string | int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query = $query->findOrFail($id);
        $result = $query;
        if ($resourceClass) {
            $result = new $resourceClass($result);
        }
        return $result;
    }

    /**
     * Create item
     * @param mixed $request
     * @return Model
     */
    public function create(mixed $request)
    {
        return $this->model->create($request->all());
    }

    /**
     * Update Item
     * @param string | int $id
     * @param Request $request
     * 
     * @return bool
     * @throws ModelNotFoundException
     */
    public function update(string | int $id, Request $request)
    {
        $source = $this->model->findOrFail($id);
        return $source->update($request->all());
    }

    /**
     * Delete Item
     * @param string | int $id
     * 
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(string | int $id)
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
     * @param string | int $id
     * @return bool
     */
    public function forceDelete(string | int $id)
    {
        $source = $this->model->withTrashed()->findOrFail($id);
        return $source->forceDelete($id);
    }

    /**
     * Restore item
     * @param string | int $id
     * @return bool
     */
    public function restore(string | int $id)
    {
        $source = $this->model->withTrashed()->findOrFail($id);
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
     * @param Builder $query Your database builder query
     * @param bool $limitOption option if you want to show data as pagination without limit, if false this will force $requestLimit to MAX INT
     * @param int $requestLimit limit data option
     * @param string $resourceClass class for api resource
     * 
     * @return array
     */
    public function pagination(
        Builder $query,
        bool $limitOption = true,
        int $requestLimit = 5,
        string $resourceClass = null
    ) {
        $limit = $requestLimit;
        if ($limitOption) {
            $limit =  PHP_INT_MAX;
        }
        $data = $query->paginate($limit);
        $pagination = new Paginate($data);
        if ($resourceClass) {
            $data = $resourceClass::collection($data);
            $data = [
                'pagination' => $pagination,
                'data' => $data
            ];
        }
        return $data;
    }

    /**
     * Transform data collection to pagination
     *
     * @param Collection $collection
     * @param boolean $limitOption
     * @param int $requestLimit
     * @param int $current
     * @param Request $request
     * 
     * @return LengthAwarePaginator
     */
    public function customPaginate(
        Collection $collection,
        bool $limitOption = true,
        int $requestLimit = 5,
        int $current = 1,
        Request $request
    ) {
        $limit = $requestLimit;
        if ($limitOption) {
            $limit =  PHP_INT_MAX;
        }
        $currentPage = $current;
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
