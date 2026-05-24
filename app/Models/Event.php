<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Event extends Model
{
    // ...
    // ... ($fillable kalian dari pertemuan lalu biarkan tidak diubah) 

    // Menandakan atribut: 1 Event harus terpaut pada satu wujud Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
