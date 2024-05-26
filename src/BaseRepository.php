<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
use AkmalRiyadi\LaravelBackendGenerator\Resources\Paginate;
use AkmalRiyadi\LaravelBackendGenerator\Traits\HasFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * @param array $request must input $request->all()
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $filterOption
     * @param bool $paginateOption
     * 
     * @return array 
     */
    public function all(
        array $request = [],
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $filterOption = false,
        bool $paginateOption = false,
        string $resourceClass = null,
        string $columnOrder = 'created_at',
        string $sortOrder = 'desc'
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->orderBy($columnOrder, $sortOrder);
        if ($filterOption) {
            $query->filter($request);
        }

        if ($paginateOption) {
            return $this->pagination(query: $query, resourceClass: $resourceClass);
        }

        if ($resourceClass) {
            return $resourceClass::collection($query->get());
        }

        return $query->get();
    }


    /**
     * Data pagination
     * @param $query
     * @return array
     */
    public function pagination(
        Builder $query,
        bool $limitOption = false,
        int $requestLimit = 5,
        int $limit = 0,
        string $resourceClass = null
    ) {
        $limit = $requestLimit < 1 ? PHP_INT_MAX : $requestLimit;
        $data = $query->paginate($limitOption ? $limit : $requestLimit);
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
     * find item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return Model
     */
    public function find(
        int $id,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->find($id);
        $result = $query;
        if ($resourceClass) {
            $result = $resourceClass::collection($query);
        }
        return $result;
    }

    /**
     * find or fail item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return Model
     */
    public function findOrFail(
        int $id,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        $query->findOrFail($id);
        $result = $query;
        if ($resourceClass) {
            $result = $resourceClass::collection($query);
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
     * @param int $id
     * @param mixed $request this should only $request from \Illuminate\Http\Request;
     * 
     * @return bool
     * @throws ModelNotFoundException
     */
    public function update(int $id, mixed $request)
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
        $source = $this->model->withTrashed()->findOrFail($id);
        return $source->forceDelete($id);
    }

    /**
     * Restore item
     * @param int $id
     * @return bool
     */
    public function restore(int $id)
    {
        $source = $this->model->withTrashed()->findOrFail($id);
        return $source->restore();
    }

    /**
     * Generate item option query model
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function itemOptionQuery(
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false,
    ) {
        $query = match ($itemOptions ?? ItemOptions::DEFAULT ) {
            ItemOptions::WITH_TRASHED => $this->model->withTrashed(),
            ItemOptions::ONLY_TRASHED => $this->model->onlyTrashed(),
            default => $this->model->newQuery()
        };

        if ($withOption) {
            $query->with($this->option['with'] ?? []);
        }

        if ($withCountOption) {
            $query->withCount($this->option['withCount'] ?? []);
        }

        return $query;
    }

    public function storeConfiguration($key, $value)
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