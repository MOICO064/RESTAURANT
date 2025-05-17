<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SaleList
 * 
 * @property int $id
 * @property int|null $user_id
 * @property string $code
 * @property string $client_name
 * @property float $amount
 * @property float $tendered
 * @property int $payment_type
 * @property string|null $payment_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 * @property SaleProduct|null $sale_product
 *
 * @package App\Models
 */
class SaleList extends Model
{
	protected $table = 'sale_list';

	protected $casts = [
		'user_id' => 'int',
		'amount' => 'float',
		'tendered' => 'float',
		'payment_type' => 'int'
	];

	protected $fillable = [
		'user_id',
		'code',
		'client_name',
		'amount',
		'tendered',
		'payment_type',
		'payment_code'
	];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function products()
	{
		return $this->hasMany(SaleProduct::class, 'sale_id');
	}	

}
