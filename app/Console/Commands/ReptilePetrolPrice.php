<?php

namespace App\Console\Commands;

use App\Services\Reptile\PetrolPrice;
use Illuminate\Console\Command;

class ReptilePetrolPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reptile:petrol_price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '爬取油价';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $class = new PetrolPrice();

        $class->getPrice();
    }
}
