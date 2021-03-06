<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int id
 * @property int serviceTypeId
 * @property ServiceUnit serviceUnit
 * @property ServiceType serviceType
 */
class Service extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    const SERVICE_CAPACITY_INACTIVE = 0; // Не имеет вместимость
    const SERVICE_CAPACITY_ACTIVE   = 1; // Имеет вместимость

    protected $table = 'services';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_center_id', 'service_type_id', 'name', 'price', 'capacity', 'point', 'color',
    ];

    public function businessCenter()
    {
        return $this->belongsTo('App\Models\BusinessCenter');
    }

    public function serviceType()
    {
        return $this->belongsTo('App\Models\ServiceType','service_type_id');
    }

    public function serviceUnit()
    {
        return $this->hasOne('App\Models\ServiceUnit', 'service_id', 'id');
    }

    public function orderServices()
    {
        return $this->hasMany('App\Models\OrderService', 'service_id');
    }

    public function scopeWhereHasCurrentBusinessCenter(Builder $builder, User $user)
    {
        $builder->whereHas('businessCenter', function ($q) use ($user) {
            $q->where('id', $user->business_center_id);
        });
    }

    public function scopeWhereServiceTypeId($query, $id)
    {
        return $query->where('service_type_id', $id);
    }
}
