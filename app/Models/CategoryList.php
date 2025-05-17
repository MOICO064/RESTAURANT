<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CategoryList
 * 
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $status
 * @property int $delete_flag
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ProductList[] $product_lists
 *
 * @package App\Models
 */
class CategoryList extends Model
{
	protected $table = 'category_list';

	protected $casts = [
		'status' => 'int',
		'delete_flag' => 'int'
	];

	protected $fillable = [
		'name',
		'description',
		'status',
		'delete_flag'
	];

	public function product_lists()
	{
		return $this->hasMany(ProductList::class, 'category_id');
	}
}
