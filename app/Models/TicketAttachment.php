<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

/**
 * Represents a file attached to a ticket.
 *
 * @property int    $id
 * @property int    $ticket_id
 * @property int    $user_id
 * @property string $original_name
 * @property string $path
 * @property string $mime_type
 * @property int    $size
 */
class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrl(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getSizeFormatted(): string
    {
        $bytes = $this->size;

        if ($bytes < 1024) {
            return "{$bytes} B";
        }

        if ($bytes < 1024 * 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return round($bytes / (1024 * 1024), 1) . ' MB';
    }

    public function getIconName(): string
    {
        $mime = $this->mime_type ?? '';

        return match (true) {
            str_contains($mime, 'image')                    => 'image',
            str_contains($mime, 'pdf')                      => 'picture_as_pdf',
            str_contains($mime, 'word') || str_contains($mime, 'document') => 'description',
            str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet') => 'table_chart',
            str_contains($mime, 'zip') || str_contains($mime, 'rar')       => 'folder_zip',
            str_contains($mime, 'video')                    => 'videocam',
            default                                         => 'attach_file',
        };
    }
}