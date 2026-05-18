<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DocumentFlow;

class CheckDocumentSla extends Command
{
    protected $signature = 'documents:check-sla';

    protected $description = 'Check expired document SLA';

    public function handle()
    {
        $flows = DocumentFlow::whereNotNull('sla_deadline')
            ->where('sla_expired', false)
            ->get();

        foreach ($flows as $flow) {

            if (now()->greaterThan($flow->sla_deadline)) {

                $flow->update([
                    'sla_expired' => true,
                ]);

                // 🔔 notificación
                app(\App\Services\NotificationService::class)
                    ->send(
                        $flow->sent_by,
                        'sla_expired',
                        'Documento vencido SLA',
                        $flow->document_id
                    );
            }
        }

        $this->info('SLA checked');
    }
}