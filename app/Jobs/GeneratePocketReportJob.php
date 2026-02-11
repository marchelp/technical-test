<?php

namespace App\Jobs;

use App\Exports\PocketExport;
use App\Models\Expenses;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class GeneratePocketReportJob implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $pocket_id;
    protected $type;
    protected $date;
    protected $filename;

    /**
     * Create a new job instance.
     */
    public function __construct($pocket_id, $type, $date, $filename)
    {
        $this->pocket_id = $pocket_id;
        $this->type = $type;
        $this->date = $date;
        $this->filename = $filename;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Excel::store(
            new PocketExport($this->pocket_id, $this->type, $this->date),
            'reports/' . $this->filename . '.xlsx'
        );
    }
}
