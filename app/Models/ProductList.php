<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductList
 * 
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $status
 * @property int $delete_flag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CategoryList $category_list
 * @property SaleProduct|null $sale_product
 *
 * @package App\Models
 */
class ProductList extends Model
{
	protected $table = 'product_list';

	protected $casts = [
		'category_id' => 'int',
		'price' => 'float',
		'status' => 'int',
		'delete_flag' => 'int'
	];

	protected $fillable = [
		'category_id',
		'name',
		'description',
		'price',
		'status',
		'delete_flag'
	];

	public function category_list()
	{
		return $this->belongsTo(CategoryList::class, 'category_id');
	}

	public function sale_product()
	{
		return $this->hasOne(SaleProduct::class, 'product_id');
	}
	
}
