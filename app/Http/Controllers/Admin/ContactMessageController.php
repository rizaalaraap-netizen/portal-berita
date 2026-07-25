<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageIndexRequest;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(ContactMessageIndexRequest $request): View
    {
        $filters = $request->validated();

        $messages = ContactMessage::query()
            ->when($filters['search'] ?? null, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('email', 'like', '%'.$search.'%')
                        ->orWhere('subjek', 'like', '%'.$search.'%');
                });
            })
            ->when($filters['status'] ?? null, fn ($query, string $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.contact-messages.index', [
            'messages' => $messages,
            'statuses' => [ContactMessage::STATUS_UNREAD, ContactMessage::STATUS_READ],
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        if ($contactMessage->status === ContactMessage::STATUS_UNREAD) {
            $contactMessage->markAsRead();
        }

        return view('admin.contact-messages.show', ['message' => $contactMessage->refresh()]);
    }

    public function markAsRead(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->markAsRead();

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Pesan berhasil dihapus.');
    }
}
