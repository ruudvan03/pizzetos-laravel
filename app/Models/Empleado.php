<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'Empleados';
    protected $primaryKey = 'id_emp';
    public $timestamps = false; 

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'nickName',
        'id_ca',
        'id_suc',
        'status',
        'password'
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'id_ca'); 
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_suc');
    }
}