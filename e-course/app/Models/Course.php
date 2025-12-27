<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Course extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = ['is_truly_active']; // Tambahkan attribute computed

    /**
     * Get true active status (considering expiry date)
     */
    public function getIsTrulyActiveAttribute()
    {
        // Check both is_active flag and expiry date
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Scope untuk course yang benar-benar aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('tanggal_selesai', '>=', Carbon::today());
    }

    /**
     * Scope untuk course yang expired
     */
    public function scopeExpired($query)
    {
        return $query->where('tanggal_selesai', '<', Carbon::today());
    }

    /**
     * Check if course is expired
     */
    public function isExpired()
    {
        return $this->tanggal_selesai->isPast();
    }

    // kategori course
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // teacher pengampu course
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // konten yang ada di course
    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    // student yang mengikuti course
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'student_id')
            ->withTimestamps();
    }
}