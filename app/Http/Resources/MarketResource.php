<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\HasPaginatedResource;
/**
 * @OA\Schema(
 *     schema="Market",
 *     type="object",
 *     title="Market Resource",
 *     description="Chi tiết thông tin thị trường",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="marketCode", type="string", example="MK001"),
 *     @OA\Property(property="marketName", type="string", example="Chợ Bến Thành"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Chợ nổi tiếng tại TP.HCM")
 * )
 */
class MarketResource extends JsonResource
{
    use HasPaginatedResource;
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'market_code' => $this->market_code,
            'market_name' => $this->market_name,
            'description' => $this->description,
        ];
    }
}
