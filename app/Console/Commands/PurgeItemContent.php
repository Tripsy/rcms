<?php

namespace App\Console\Commands;

use App\Enums\DefaultOption;
use App\Exceptions\ConsoleException;
use App\Models\ItemContent;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PurgeItemContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:purge-item-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete item content(s) entries marked with `is_active` as NO having `updated_at` < 30 days';

    /**
     * Execute the console command.
     *
     * @throws ConsoleException
     */
    public function handle(): void
    {
        try {
            // 30 days ago
            $thresholdDate = Carbon::now()->subDays(30);

            ItemContent::where('is_active', DefaultOption::NO)
                ->where('updated_at', '<', $thresholdDate)
                ->delete();

            $this->info(__('console.purge-item-content.success'));

            Log::channel('console')->info(__('console.purge-item-content.success'), [
                'date' => Carbon::now()->format('Y-m-d H:i'),
            ]);
        } catch (\Exception $exception) {
            throw new ConsoleException(__('console.purge-item-content.failed'), 0, $exception);
        }
    }
}
