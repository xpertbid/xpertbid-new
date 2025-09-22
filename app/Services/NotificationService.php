<?php

namespace App\Services;

use App\Mail\UserWelcomeMail;
use App\Mail\PasswordResetMail;
use App\Mail\AuctionNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\User;
use App\Models\Auction;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send welcome email to new user
     */
    public function sendWelcomeEmail(User $user, string $password = null): bool
    {
        try {
            Mail::to($user->email)->send(new UserWelcomeMail($user, $password));
            
            Log::info('Welcome email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send password reset email
     */
    public function sendPasswordResetEmail(User $user, string $token): bool
    {
        try {
            Mail::to($user->email)->send(new PasswordResetMail(
                $user->email,
                $token,
                $user->name
            ));
            
            Log::info('Password reset email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send auction notification email
     */
    public function sendAuctionNotification(
        User $user, 
        Auction $auction, 
        string $type, 
        array $data = []
    ): bool {
        try {
            Mail::to($user->email)->send(new AuctionNotificationMail(
                $user,
                $auction,
                $type,
                $data
            ));
            
            Log::info('Auction notification email sent successfully', [
                'user_id' => $user->id,
                'auction_id' => $auction->id,
                'type' => $type,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send auction notification email', [
                'user_id' => $user->id,
                'auction_id' => $auction->id,
                'type' => $type,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send order confirmation email
     */
    public function sendOrderConfirmation(User $user, Order $order): bool
    {
        try {
            Mail::to($user->email)->send(new OrderConfirmationMail($user, $order));
            
            Log::info('Order confirmation email sent successfully', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'email' => $user->email
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Send bulk auction notifications
     */
    public function sendBulkAuctionNotifications(
        array $userIds, 
        Auction $auction, 
        string $type, 
        array $data = []
    ): array {
        $results = [
            'sent' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if ($this->sendAuctionNotification($user, $auction, $type, $data)) {
                $results['sent']++;
            } else {
                $results['failed']++;
                $results['errors'][] = [
                    'user_id' => $user->id,
                    'email' => $user->email
                ];
            }
        }

        return $results;
    }

    /**
     * Notify auction owner of new bid
     */
    public function notifyAuctionOwnerOfNewBid(Auction $auction, array $bidData): bool
    {
        $owner = $auction->vendor->user ?? null;
        
        if (!$owner) {
            return false;
        }

        return $this->sendAuctionNotification(
            $owner,
            $auction,
            'new_bid',
            $bidData
        );
    }

    /**
     * Notify user they've been outbid
     */
    public function notifyUserOutbid(User $user, Auction $auction, array $bidData): bool
    {
        return $this->sendAuctionNotification(
            $user,
            $auction,
            'outbid',
            $bidData
        );
    }

    /**
     * Notify users about auction ending soon
     */
    public function notifyAuctionEndingSoon(Auction $auction): array
    {
        // Get users who have bid on this auction or are watching it
        $userIds = $auction->bids()->pluck('user_id')->unique()->toArray();
        
        if (empty($userIds)) {
            return ['sent' => 0, 'failed' => 0, 'errors' => []];
        }

        return $this->sendBulkAuctionNotifications(
            $userIds,
            $auction,
            'auction_ending'
        );
    }

    /**
     * Notify auction winner
     */
    public function notifyAuctionWinner(User $winner, Auction $auction): bool
    {
        return $this->sendAuctionNotification(
            $winner,
            $auction,
            'auction_won'
        );
    }

    /**
     * Notify auction losers
     */
    public function notifyAuctionLosers(Auction $auction): array
    {
        // Get all bidders except the winner
        $winnerId = $auction->bids()->orderBy('amount', 'desc')->first()?->user_id;
        $loserIds = $auction->bids()
            ->where('user_id', '!=', $winnerId)
            ->pluck('user_id')
            ->unique()
            ->toArray();

        if (empty($loserIds)) {
            return ['sent' => 0, 'failed' => 0, 'errors' => []];
        }

        return $this->sendBulkAuctionNotifications(
            $loserIds,
            $auction,
            'auction_lost'
        );
    }

    /**
     * Send test email to verify email configuration
     */
    public function sendTestEmail(string $email): bool
    {
        try {
            Mail::raw('This is a test email from XpertBid to verify email configuration.', function ($message) use ($email) {
                $message->to($email)
                        ->subject('XpertBid - Test Email');
            });
            
            Log::info('Test email sent successfully', ['email' => $email]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send test email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
}
