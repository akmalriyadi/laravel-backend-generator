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
     * @param bool $limitOption option if you want to show data as pagination without limit, if false this will force $requestLimit to MAX INT
     * @param string $columnOrder column order data
     * @param string $sortOrder sort method order data
     * @param string $resourceClass class for api resource
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
        PaginateType $paginateType = PaginateType::REQUEST,
        ?int $paginateCustomCount = 5,
        bool $limitOption = true,
        ?string $columnOrder = 'created_at',
        ?string $sortOrder = 'desc',
        ?string $resourceClass = null
    ) {
        try {
            $result = $this->mainRepository->all($request, $itemOptions, $withOption, $withCountOption, $filterOption, $paginateOption, $paginateType, $paginateCustomCount, $limitOption, $columnOrder, $sortOrder, $resourceClass);

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
     * @param string $sortOrder sort method order data
     * @param QueryOptions $getOption use AkmalRiyadi\LaravelBackendGenerator\Enums\QueryOptions ["QueryOptions::GET","QueryOptions::FIRST"]
     * @param string $resourceClass class for api resource
     * 
     * @return mixed
     */
    public function where(
        ?Request $request,
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
        try {
            $result = $this->mainRepository->where($column, $ident, $itemOptions, $withOption, $withCountOption, $columnOrder, $sortOrder, $getOption, $resourceClass);

            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            $this->exceptionResponse($e);
        }
    }

    /**
     * find item by id
     * @param string | string | int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * @param string $resourceClass class for api resource
     * 
     * @return mixed
     */
    public function find(
        string | int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        try {
            $result = $this->mainRepository->find($id, $itemOptions, $withOption, $withCountOption, $resourceClass);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * find or fail item by id
     * @param string | int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["ItemOptions::DEFAULT","ItemOptions::WITH_TRASHED","ItemOptions::ONLY_TRASHED"]
     * @param bool $withOtion option for relation data, default is false
     * @param bool $withCountOption option for count relation data, default is false
     * 
     * @return mixed
     */
    public function findOrFail(
        string | int $id,
        ItemOptions $itemOptions = ItemOptions::DEFAULT,
        bool $withOption = false,
        bool $withCountOption = false,
        string $resourceClass = null,
    ) {
        try {
            $result = $this->mainRepository->findOrFail($id, $itemOptions, $withOption, $withCountOption, $resourceClass);
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
     * @param string | int $id
     * @param mixed $request 
     * 
     * @return mixed
     */
    public function update(string | int $id, mixed $request)
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
     * @param string | int $id
     * 
     * @return mixed
     */
    public function delete(string | int $id)
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
     * @param string | int $id
     * @return mixed
     */
    public function forceDelete(string | int $id)
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
     * @param string | int $id
     * @return mixed
     */
    public function restore(string | int $id)
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
