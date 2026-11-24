<?php

namespace App\Http\Controllers\Admin\Concerns;

use App\Models\Contract;
use App\Models\ContractAct;
use App\Models\ContractAssignment;
use App\Models\ContractReport;
use App\Service\ContractActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ManagesScannedPdf
{
    protected function uploadScannedPdf(
        Request $request,
        Model $model,
        string $directory,
        string $redirectRoute,
        mixed $redirectParam
    ): RedirectResponse {
        $request->validate([
            'scanned_pdf' => ['required', 'file', 'mimes:pdf', 'max:20480'],
        ], [
            'scanned_pdf.required' => 'Выберите PDF-файл',
            'scanned_pdf.mimes' => 'Допустим только файл в формате PDF',
            'scanned_pdf.max' => 'Размер файла не должен превышать 20 МБ',
        ]);

        $replaced = $model->hasScannedPdf();
        $model->storeScannedPdf($request->file('scanned_pdf'), $directory);

        if ($this->isContractDocument($model)) {
            app(ContractActivityLogger::class)->scanUploaded(
                $model,
                $replaced,
                $this->contractForScanLog($model)
            );
        }

        return redirect()
            ->route($redirectRoute, $redirectParam)
            ->with('success', 'Скан оригинала загружен');
    }

    protected function downloadScannedPdf(Model $model, string $filename): StreamedResponse|RedirectResponse
    {
        if (! $model->hasScannedPdf()) {
            return redirect()->back()->with('error', 'Скан оригинала не загружен');
        }

        return Storage::disk('public')->download($model->scanned_pdf, $filename);
    }

    protected function destroyScannedPdf(
        Model $model,
        string $redirectRoute,
        mixed $redirectParam
    ): RedirectResponse {
        $hadScan = $model->hasScannedPdf();
        $model->deleteScannedPdfFile();

        if ($hadScan && $this->isContractDocument($model)) {
            app(ContractActivityLogger::class)->scanDeleted(
                $model,
                $this->contractForScanLog($model)
            );
        }

        return redirect()
            ->route($redirectRoute, $redirectParam)
            ->with('success', 'Скан оригинала удалён');
    }

    protected function isContractDocument(Model $model): bool
    {
        return $model instanceof Contract
            || $model instanceof ContractAssignment
            || $model instanceof ContractReport
            || $model instanceof ContractAct;
    }

    protected function contractForScanLog(Model $model): ?Contract
    {
        if ($model instanceof Contract) {
            return $model;
        }

        if (method_exists($model, 'contract')) {
            $model->loadMissing('contract');

            return $model->contract;
        }

        return null;
    }
}
