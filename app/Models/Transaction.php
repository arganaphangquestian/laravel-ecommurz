<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *     @OA\Xml(name="Transaction"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="buyer_id", type="integer", description="Relation to Buyer Data(User)", example="1"),
 *     @OA\Property(property="isPaid", type="boolean", description="Flag for payment status", example="false"),
 *     @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *     @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *     @OA\Property(property="deleted_at", ref="#/components/schemas/BaseModel/properties/deleted_at")
 * )
 *
 * Class Transaction
 *
 */
class Transaction extends Model
{

    protected $softDelete = true;
    
    protected $fillable = ['buyer_id', 'isPaid'];
    
    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at',
    ];

    public function rules() {
        return [
            'buyer_id' => 'required',
            'isPaid' => 'required',
        ];
    }

    /*
     * Relationship
     */

    public function carts() {
        return $this->hasMany('App\Models\Cart', 'transaction_id', 'id');
    }
}
