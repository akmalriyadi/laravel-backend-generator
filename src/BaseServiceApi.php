<?php

namespace AkmalRiyadi\LaravelBackendGenerator;

use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions;
use AkmalRiyadi\LaravelBackendGenerator\Traits\ResultService;

class BaseServiceApi
{
    use ResultService;

    protected $mainRepository;

    /**
     * Show all item
     * 
     * @param array $request must input $request->all()
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $filterOption
     * @param bool $paginateOption
     * 
     * @return mixed
     */
    public function all(
        array $request,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false,
        bool $filterOption = false,
        bool $paginateOption = false
    ) {
        try {
            $result = $this->mainRepository->all($request, $itemOptions, $withOption, $withCountOption, $filterOption, $paginateOption);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * find item by id
     * @param int $id
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return mixed
     */
    public function find(
        int $id,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false
    ) {
        try {
            $result = $this->mainRepository->find($id, $itemOptions, $withOption, $withCountOption);
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
     * @param ItemOptions $itemOptions use AkmalRiyadi\LaravelBackendGenerator\Enums\ItemOptions ["DEFAULT","WITH_TRASHED","ONLY_TRASHED"]
     * @param bool $withOption
     * @param bool $withCountOption
     * 
     * @return mixed
     */
    public function findOrFail(
        int $id,
        ItemOptions $itemOptions = null,
        bool $withOption = false,
        bool $withCountOption = false
    ) {
        try {
            $result = $this->mainRepository->findOrFail($id, $itemOptions, $withCountOption, $withCountOption);
            return $this->setResult($result)
                ->setCode(200)
                ->setStatus(true);
        } catch (\Exception $e) {
            return $this->exceptionResponse($e);
        }
    }

    /**
     * Create item
     * @param array $request this should only $request from \Illuminate\Http\Request;
     * @return mixed
     */
    public function create(array $request)
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
     * @param array $request this should only $request from \Illuminate\Http\Request;
     * 
     * @return mixed
     */
    public function update(int $id, array $request)
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