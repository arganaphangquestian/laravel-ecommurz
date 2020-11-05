<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *     @OA\Xml(name="Cart"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="product_id", description="Relation to Product ID", type="integer", example="1"),
 *     @OA\Property(property="transaction_id", description="Relation to Transaction ID", type="integer", example="1"),
 *     @OA\Property(property="amount", description="Amount of product", type="integer", example="1"),
 *     @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *     @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *     @OA\Property(property="deleted_at", ref="#/components/schemas/BaseModel/properties/deleted_at")
 * )
 *
 * Class cart
 *
 */
class Cart extends Model
{

    protected $softDelete = true;
    
    protected $fillable = ['product_id', 'transaction_id', 'amount'];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at',
    ];

    
    public function rules() {
        return [
            'product_id' => 'required',
            'transaction_id' => 'required',
            'amount' => 'required',
        ];
    }

    /*
     * Relationship
     */
    public function product() {
        return $this->belongsTo('App\Models\Product', 'product_id', 'id');
    }

    public function transaction() {
        return $this->belongsTo('App\Models\Transaction', 'transaction_id', 'id');
    }
}
