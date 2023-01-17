<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMeasurementRelationship extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'product_id', 'measurement_id', 'measurement_id'];

    protected $appends = [
        'measurement_name', 'attribute_name'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function product_measurement()
    {
        return $this->belongsTo(ProductMeasurement::class, 'measurement_id', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo(AttributeValue::class, 'product_attribute_value_id', 'id');
    }

    public function getAttributeNameAttribute()
    {
        if ($this->attribute)
            return $this->attribute->attribute->name . ' ' . $this->attribute->name;
        else
            return '';
    }
    
    public function getMeasurementNameAttribute()
    {
        if ($this->product_measurement)
            return $this->product_measurement->name . ' ' . $this->product_measurement->units;
        else
            return '';
    }
}
