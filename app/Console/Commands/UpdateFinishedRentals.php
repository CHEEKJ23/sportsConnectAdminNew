<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EquipmentRental;
use App\Models\Equipment;
use Carbon\Carbon;

class UpdateFinishedRentals extends Command
{
    protected $signature = 'rentals:update-finished';
    protected $description = 'Update rentals to finished status and restore equipment quantity';

    public function handle()
    {
       
        $now = Carbon::now();

     
        $rentals = EquipmentRental::where('endTime', '<=', $now)
            ->where('rentalStatus', '!=', 'Finished')
            ->get();

        foreach ($rentals as $rental) {
      
            $equipment = Equipment::find($rental->equipmentID);

            if ($equipment) {
           
                $equipment->increment('quantity_available', $rental->quantity_rented);

             
                $rental->update(['rentalStatus' => 'Finished']);
            }
        }

        $this->info('Finished rentals updated successfully.');
    }
}