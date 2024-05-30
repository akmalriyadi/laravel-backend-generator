<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use Illuminate\Http\Request;
use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
use AkmalRiyadi\LaravelBackendGenerator\Enums\PaginateType;
use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions;
use AkmalRiyadi\LaravelBackendGenerator\Traits\ResultService;

class BaseServiceApi
{
    use ResultService;

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
     * @param PaginateType $paginateType option for type paginate
     * @param int $paginateCustomCount this will enable when paginateType is CUSTOM
     * @param string $columnOrder column order data
     * @param bool $sortOrder sort method order data
     * @param string $resourceOption class for api resource
     * 
     * @return mixed
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
        try {
            $result = $this->mainRepository->all($request, $itemOptions, $withOption, $withCountOption, $filterOption, $paginateOption, $paginateRequest, $paginateCustomCount, $columnOrder, $sortOrder, $resourceOption);

            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
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
     * @return mixed
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
        try {
            $result = $this->mainRepository->where($request, $column, $ident, $itemOptions, $withOption, $withCountOption, $columnOrder, $sortOrder, $getOption, $paginateRequest, $paginateCustomCount, $resourceOption);

            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            $this->exceptionResponse($e);
        }
    }

    /**
     * find item by id
     * @param string | int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param string $resourceOption class for api resource
     * 
     * @return mixed
     */
    public function find(
        int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $resourceOption = false,
    ) {
        try {
            $result = $this->mainRepository->find($id, $itemOptions, $withOption, $withCountOption, $resourceOption);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * find or fail item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * 
     * @return mixed
     */
    public function findOrFail(
        int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $resourceOption = false,
    ) {
        try {
            $result = $this->mainRepository->findOrFail($id, $itemOptions, $withOption, $withCountOption, $resourceOption);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Create item
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        try {
            $result = $this->mainRepository->create($request);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Update Item
     * @param int $id
     * @param Request $request 
     * 
     * @return mixed
     */
    public function update(int $id, Request $request)
    {
        try {
            $result = $this->mainRepository->update($id, $request);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Delete Item
     * @param int $id
     * 
     * @return mixed
     */
    public function delete(int $id)
    {
        try {
            $result = $this->mainRepository->delete($id);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Destroy multiple item
     * @param array $id
     * @return mixed
     */
    public function destroy(array $id)
    {
        try {
            $result = $this->mainRepository->destroy($id);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Force delete item
     * @param int $id
     * @return mixed
     */
    public function forceDelete(int $id)
    {
        try {
            $result = $this->mainRepository->forceDelete($id);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Restore item
     * @param int $id
     * @return mixed
     */
    public function restore(int $id)
    {
        try {
            $result = $this->mainRepository->restore($id);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }
}
