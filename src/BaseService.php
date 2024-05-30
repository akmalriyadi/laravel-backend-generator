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
        return $this->mainRepository->all($request, $itemOptions, $withOption, $withCountOption, $filterOption, $paginateOption, $paginateRequest, $paginateCustomCount, $columnOrder, $sortOrder, $resourceOption);
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
     * @param bool $sortOrder sort method order data
     * @param QueryOptions $getOption use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions ["QueryOptions::GET","QueryOptions::FIRST"]
     * @param string $resourceOption class for api resource
     * 
     * @return Collection
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
        return $this->mainRepository->where($request, $column, $ident, $itemOptions, $withOption, $withCountOption, $columnOrder, $sortOrder, $getOption, $paginateRequest, $paginateCustomCount, $resourceOption);
    }

    /**
     * find item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param bool $resourceOption option for api resource
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
        return $this->mainRepository->find($id, $itemOptions, $withOption, $withCountOption, $resourceOption);
    }

    /**
     * find or fail item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param bool $resourceOption class for api resource
     * 
     * @return void
     */
    public function findOrFail(
        int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $resourceOption = false,
    ) {
        return $this->mainRepository->findOrFail($id, $itemOptions, $withOption, $withCountOption, $resourceOption);
    }

    /**
     * Create item
     * @param Request $request
     * @return Model
     */
    public function create(Request $request)
    {
        return $this->mainRepository->create($request->all());
    }

    /**
     * Update Item
     * @param int $id
     * @param mixed $request this should only $request from \Illuminate\Http\Request;
     * 
     * @return bool
     */
    public function update(int $id, Request $request)
    {
        return $this->mainRepository->update($id, $request->all());
    }

    /**
     * Delete Item
     * @param int $id
     * 
     * @return bool
     */
    public function delete(int $id)
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
     * @param int $id
     * @return bool
     */
    public function forceDelete(int $id)
    {
        return $this->mainRepository->forceDelete($id);
    }

    /**
     * Restore item
     * @param int $id
     * @return bool
     */
    public function restore(int $id)
    {
        return $this->mainRepository->restore($id);
    }
}
