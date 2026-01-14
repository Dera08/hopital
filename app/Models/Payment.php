// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'reference', 'appointment_id', 'patient_id', 
        'amount', 'payment_method', 'payment_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($payment) {
            $payment->reference = 'PAY' . str_pad(Payment::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}