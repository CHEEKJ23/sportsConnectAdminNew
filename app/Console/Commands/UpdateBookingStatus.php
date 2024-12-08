<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateBookingStatus extends Command
{
    protected $signature = 'bookings:update-status';
    protected $description = 'Update the status of bookings that have passed';

    public function handle()
    {
        $now = Carbon::now()->format('H:i:s');
        $today = Carbon::now()->toDateString();

        $this->info("Current date: $today, Current time: $now");

        $bookings = Booking::where('date', '<=', $today)
            // ->where('endTime', '>=', $now)
            ->where('status', 'confirmed')
            ->get();

        foreach ($bookings as $booking) {
            $this->info("Updating booking ID: {$booking->id}, End Time: {$booking->endTime}");
            $booking->update(['status' => 'finished']);
        }

        $this->info('Booking statuses have been updated.');
    }
}
