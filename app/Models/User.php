<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Conectamos con tu tabla real
    protected $table = 'Empleados';
    protected $primaryKey = 'id_emp';

    // Tu dump no tiene created_at / updated_at
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'id_ca',
        'id_suc',
        'nickName',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Indicamos que el campo de login es nickName
     */
    public function username()
    {
        return 'nickName';
    }

    /**
     * Para que Laravel reconozca tu columna de contraseña
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}