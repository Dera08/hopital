<?php
namespace App\Models;
use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\{Model, Factories\HasFactory, SoftDeletes};
 class InvoiceItem extends Model
{
    use HasFactory;
     use BelongsToHospital;

    protected $fillable = [
        'hospital_id','invoice_id', 'description', 'quantity', 
        'unit_price', 'total', 'code'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}