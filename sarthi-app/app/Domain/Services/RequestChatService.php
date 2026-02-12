<?php

namespace App\Domain\Services;

use App\Domain\Enums\UserRole;
use App\Domain\Exceptions\DomainException;
use App\Events\RequestMessageCreated;
use App\Models\RequestMessage;
use App\Models\ServiceRequest;
use App\Models\User;

class RequestChatService
{
    public function sendMessage(ServiceRequest $request, User $sender, string $body): RequestMessage
    {
        $this->ensureCanChat($request, $sender);

        $message = $request->messages()->create([
            'sender_id' => $sender->id,
            'body' => $body,
        ]);

        RequestMessageCreated::dispatch($message->fresh(['sender']));

        return $message;
    }

    public function markSeen(ServiceRequest $request, User $viewer): void
    {
        if ($viewer->isRole(UserRole::Staff)) {
            $request->messages()->whereNull('seen_by_staff_at')->update(['seen_by_staff_at' => now()]);
            return;
        }

        if ($viewer->isRole(UserRole::Customer)) {
            $request->messages()->whereNull('seen_by_customer_at')->update(['seen_by_customer_at' => now()]);
            return;
        }

        throw new DomainException('Unsupported read receipt role.');
    }

    public function setChatEnabled(ServiceRequest $request, bool $enabled, User $staff): ServiceRequest
    {
        if (!$staff->isRole(UserRole::Staff) || $request->assigned_staff_id !== $staff->id) {
            throw new DomainException('Only assigned staff can control chat state.');
        }

        $request->update([
            'chat_enabled' => $enabled,
            'chat_closed_at' => $enabled ? null : now(),
        ]);

        return $request->refresh();
    }

    private function ensureCanChat(ServiceRequest $request, User $sender): void
    {
        $isStaff = $sender->isRole(UserRole::Staff) && $request->assigned_staff_id === $sender->id;
        $isCustomer = $sender->isRole(UserRole::Customer) && $request->customer_id === $sender->id;

        if (!$isStaff && !$isCustomer) {
            throw new DomainException('Sender is not authorized for this chat thread.');
        }

        if (!$request->chat_enabled && $isCustomer) {
            throw new DomainException('Chat is currently disabled for customer responses.');
        }
    }
}
