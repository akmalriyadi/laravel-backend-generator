<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;

class BaseService
{
    protected $mainRepository;

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
        return $this->mainRepository->all($request, $itemOptions, $withOption, $withCountOption, $filterOption, $paginateOption);
    }

    /**
     * find item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return void
     */
    public function find(
        int $id,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false
    ) {
        return $this->mainRepository->find($id, $itemOptions, $withOption, $withCountOption);
    }

    /**
     * find or fail item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return void
     */
    public function findOrFail(
        int $id,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false
    ) {
        return $this->mainRepository->findOrFail($id, $itemOptions, $withCountOption, $withCountOption);
    }

    /**
     * Create item
     * @param array $request
     * @return void
     */
    public function create(array $request)
    {
        return $this->mainRepository->create($request);
    }

    /**
     * Update Item
     * @param int $id
     * @param mixed $request this should only $request from \Illuminate\Http\Request;
     * 
     * @return bool
     */
    public function update(int $id, mixed $request)
    {
        return $this->mainRepository->update($id, $request);
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