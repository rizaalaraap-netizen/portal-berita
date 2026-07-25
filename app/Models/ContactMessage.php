<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    public const STATUS_UNREAD = 'Belum Dibaca';
    public const STATUS_READ = 'Sudah Dibaca';

    protected $fillable = [
        'nama',
        'email',
        'subjek',
        'pesan',
        'status',
    ];

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    public function markAsRead(): void
    {
        $this->update(['status' => self::STATUS_READ]);
    }
}
