<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @OA\Schema(
 *     @OA\Xml(name="Product"),
 *     @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="description", type="text", example="Product Description"),
 *     @OA\Property(property="image_url", type="text", example="Product Image URL"),
 *     @OA\Property(property="price", type="integer", example="10000"),
 *     @OA\Property(property="seller_id", type="integer", description="Relation to Seller Data(User)", example="1"),
 *     @OA\Property(property="created_at", ref="#/components/schemas/BaseModel/properties/created_at"),
 *     @OA\Property(property="updated_at", ref="#/components/schemas/BaseModel/properties/updated_at"),
 *     @OA\Property(property="deleted_at", ref="#/components/schemas/BaseModel/properties/deleted_at")
 * )
 *
 * Class Product
 *
 */
class Product extends Model
{

    protected $softDelete = true;
    
    protected $fillable = ['name', 'description', 'image_url', 'price', 'seller_id'];
    
    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at',
    ];

    public function rules() {
        return [
            'name' => 'required',
            'description' => 'required',
            'image_url' => 'required',
            'price' => 'required',
            'seller_id' => 'required',
        ];
    }
    /*
     * Relationship
     */

    public function seller() {
        return $this->belongsTo('App\Models\User', 'seller_id', 'id');
    }
}
