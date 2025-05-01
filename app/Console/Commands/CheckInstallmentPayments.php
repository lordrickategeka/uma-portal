<?php

namespace App\Console\Commands;

use App\Models\InstallmentSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckInstallmentPayments extends Command
{
    protected $signature = 'installments:check';
    protected $description = 'Check for upcoming and overdue installment payments';

    public function handle()
    {
        // Find upcoming payments (due in the next 3 days)
        $upcomingPayments = InstallmentSchedule::where('status', 'current')
            ->whereDate('due_date', '<=', Carbon::now()->addDays(3))
            ->whereDate('due_date', '>=', Carbon::now())
            ->get();
            
        foreach ($upcomingPayments as $payment) {
            $user = $payment->installmentPlan->user;
            $daysUntilDue = Carbon::now()->diffInDays($payment->due_date, false);
            
            $user->notify(new PaymentDueNotification($payment, $daysUntilDue));
        }
        
        // Find overdue payments
        $overduePayments = InstallmentSchedule::where('status', 'current')
            ->whereDate('due_date', '<', Carbon::now())
            ->get();
            
        foreach ($overduePayments as $payment) {
            $payment->update(['status' => 'overdue']);
            
            $user = $payment->installmentPlan->user;
            $daysOverdue = Carbon::now()->diffInDays($payment->due_date);
            
            $user->notify(new PaymentOverdueNotification($payment, $daysOverdue));
        }
    }
}
