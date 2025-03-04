<?php

namespace App\Services;

use App\Interfaces\FieldRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Field;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FieldService
{
    protected $fieldRepository;

    public function __construct(FieldRepositoryInterface $fieldRepository)
    {
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * Lấy danh sách lĩnh vực có phân trang.
     *
     * @param string|null $search
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getFields(?string $search, int $perPage = 10, ?string $include = null): LengthAwarePaginator
    {
        return $this->fieldRepository->getFields($search, $perPage, $include);
    }

    /**
     * Lấy thông tin lĩnh vực theo ID.
     *
     * @param int $id
     * @return Field
     * @throws ModelNotFoundException
     */
    public function getFieldById(int $id): Field
    {
        return $this->fieldRepository->findById($id);
    }

    /**
     * Tạo mới một lĩnh vực.
     *
     * @param array $data
     * @return Field
     */
    public function createField(array $data): Field
    {
        return $this->fieldRepository->create($data);
    }

    /**
     * Cập nhật thông tin lĩnh vực.
     *
     * @param int $id
     * @param array $data
     * @return Field
     * @throws ModelNotFoundException
     */
    public function updateField(int $id, array $data): Field
    {
        return $this->fieldRepository->update($id, $data);
    }

    /**
     * Xóa lĩnh vực theo ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteField(int $id): bool
    {
        return $this->fieldRepository->delete($id);
    }
}
