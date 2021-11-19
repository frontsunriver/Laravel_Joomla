<?php

namespace App\Console\Commands;

use App\Models\Earning;
use App\Models\Payout;
use Illuminate\Console\Command;

class PayoutCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payout will be calculated automatically';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->payout();
        update_opt('last_payout', time());
    }

    public function payout()
    {
        $allPartners = get_users_by_role('partner');
        if ($allPartners) {
            $minPayout = (float)get_option('min_balance', 0);
            foreach ($allPartners as $user_id => $name) {
                $earning_model = new Earning();
                $earning = $earning_model->getEarning($user_id);
                if ($earning) {
                    $balance = (float)$earning->balance;
                    if ($balance >= $minPayout) {
                        $amount = $balance;
                        $earning->payout = $earning->payout += $balance;
                        $earning->balance = 0;
                        $earning_model->updateEarning($user_id, (array)$earning);
                        $payoutID = 'PO-' . date('Ymd') . $user_id;
                        $data = [
                            'user_id' => $user_id,
                            'payout_id' => $payoutID,
                            'amount' => $amount,
                            'created' => time(),
                            'status' => 'pending'
                        ];
                        $payout_model = new Payout();
                        $new_payout_id = $payout_model->insertPayout($data);
                        do_action('hh_calculator_payout_each_partners', $user_id, $new_payout_id);
                    }
                }
            }
        }
    }
}
