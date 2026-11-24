<?php

namespace App\Models\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasScannedPdf
{
    public function hasScannedPdf(): bool
    {
        $path = $this->scanned_pdf;

        return is_string($path) && $path !== '' && Storage::disk('public')->exists($path);
    }

    public function storeScannedPdf(UploadedFile $file, string $directory): string
    {
        $this->deleteScannedPdfFile();

        $path = $file->store($directory, 'public');
        $this->forceFill(['scanned_pdf' => $path])->save();

        return $path;
    }

    public function deleteScannedPdfFile(): void
    {
        $path = $this->scanned_pdf;
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        if ($this->scanned_pdf !== null) {
            $this->forceFill(['scanned_pdf' => null])->save();
        }
    }

    public function scannedPdfDownloadName(string $prefix): string
    {
        return $prefix.'.pdf';
    }
}
