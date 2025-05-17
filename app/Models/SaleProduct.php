<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleProduct
 * 
 * @property int $sale_id
 * @property int $product_id
 * @property int $qty
 * @property float $price
 * 
 * @property ProductList $product_list
 * @property SaleList $sale_list
 *
 * @package App\Models
 */
class SaleProduct extends Model
{
	protected $table = 'sale_products';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'sale_id' => 'int',
		'product_id' => 'int',
		'qty' => 'int',
		'price' => 'float'
	];

	protected $fillable = [
		'sale_id',
		'product_id',
		'qty',
		'price'
	];

	public function product()
	{
		return $this->belongsTo(ProductList::class, 'product_id');
	}

	public function sale_list()
	{
		return $this->belongsTo(SaleList::class, 'sale_id');
	}
}
