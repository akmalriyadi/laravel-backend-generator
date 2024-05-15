<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
use AkmalRiyadi\LaravelBackendGenerator\Traits\HasImage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseRepository
{

    use HasImage;

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
        array $request,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $filterOption = false,
        bool $paginateOption = false
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);

        if ($filterOption) {
            $query->filter($request);
        }

        if ($paginateOption) {
            $limit = $request['limit'] ?? 5;
            if ($limit <= 0) {
                return $query->get();
            }
            $query = $query->paginate($limit);
            $query = [
                'pagination' => [
                    'current_page' => $query->currentPage(),
                    'first_page_url' => $query->url(1),
                    'last_page_url' => $query->url($query->lastPage()),
                    'next_page_url' => $query->nextPageUrl(),
                    'prev_page_url' => $query->previousPageUrl(),
                    'from' => $query->firstItem(),
                    'last_page' => $query->lastPage(),
                    'links' => $query->linkCollection(),
                    'path' => $query->path(),
                    'per_page' => $query->perPage(),
                    'to' => $query->lastItem(),
                    'total' => $query->total()
                ],
                'data' => $query->items()
            ];

            return $query;
        }

        return $query->get();
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
        bool $withCountOption = false
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        return $query->find($id);
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
        bool $withCountOption = false
    ) {
        $query = $this->itemOptionQuery($itemOptions, $withOption, $withCountOption);
        return $query->findOrFail($id);
    }

    /**
     * Create item
     * @param mixed $request
     * @return Model
     */
    public function create(mixed $request)
    {
        return $this->model->create($request);
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
        return $source->update($request);
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
}