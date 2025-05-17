<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemInfo
 * 
 * @property int $id
 * @property string $meta_field
 * @property string $meta_value
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class SystemInfo extends Model
{
	protected $table = 'system_info';

	protected $fillable = [
		'meta_field',
		'meta_value'
	];
	public $timestamps = false;

	public static function setValue($key, $value)
	{
		return static::updateOrCreate(
			['meta_field' => $key],
			['meta_value' => $value]
		);
	}
}
