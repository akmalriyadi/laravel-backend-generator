<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
use AkmalRiyadi\LaravelBackendGenerator\Enums\PaginateType;
use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions;

class BaseService
{
    protected $mainRepository;

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
        PaginateType $paginateType = PaginateType::REQUEST,
        int $paginateCustomCount = 5,
        bool $limitOption = true,
        string $columnOrder = 'created_at',
        string $sortOrder = 'desc',
        string $resourceClass = null
    ) {
        return $this->mainRepository->all($request, $itemOptions, $withOption, $withCountOption, $filterOption, $paginateOption, $paginateType, $paginateCustomCount, $limitOption, $columnOrder, $sortOrder, $resourceClass);
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
        string $getOption = 'get',
        string $resourceClass = null
    ) {
        return $this->mainRepository->where($column, $ident, $itemOptions, $withOption, $withCountOption, $columnOrder, $sortOrder, $getOption, $resourceClass);
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
        return $this->mainRepository->find($id, $itemOptions, $withOption, $withCountOption, $resourceClass);
    }

    /**
     * find or fail item by id
     * @param string | int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return void
     */
    public function findOrFail(
        string | int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        return $this->mainRepository->findOrFail($id, $itemOptions, $withOption, $withCountOption, $resourceClass);
    }

    /**
     * Create item
     * @param mixed $request
     * @return Model
     */
    public function create(mixed $request)
    {
        return $this->mainRepository->create($request);
    }

    /**
     * Update Item
     * @param string | int $id
     * @param mixed $request this should only $request from \Illuminate\Http\Request;
     * 
     * @return bool
     */
    public function update(string | int $id, mixed $request)
    {
        return $this->mainRepository->update($id, $request);
    }

    /**
     * Delete Item
     * @param string | int $id
     * 
     * @return bool
     */
    public function delete(string | int $id)
    {
        return $this->mainRepository->delete($id);
    }

    /**
     * Destroy multiple item
     * @param array $id
     * @return int The number of items deleted
     */
    public function destroy(array $id)
    {
        return $this->mainRepository->destroy($id);
    }

    /**
     * Force delete item
     * @param string | int $id
     * @return bool
     */
    public function forceDelete(string | int $id)
    {
        return $this->mainRepository->forceDelete($id);
    }

    /**
     * Restore item
     * @param string | int $id
     * @return bool
     */
    public function restore(string | int $id)
    {
        return $this->mainRepository->restore($id);
    }
}
