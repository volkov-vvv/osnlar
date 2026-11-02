<?php

namespace App\Service;

use RuntimeException;
use Symfony\Component\Process\Process;

class DocxToPdfConverter
{
    /**
     * Convert DOCX binary contents to PDF binary contents via LibreOffice.
     */
    public function convert(string $docxBinary): string
    {
        $workDir = sys_get_temp_dir().'/bio_docx_pdf_'.bin2hex(random_bytes(8));
        if (! mkdir($workDir, 0700) && ! is_dir($workDir)) {
            throw new RuntimeException('Не удалось создать временную папку для PDF');
        }

        $profileDir = $workDir.'/profile';
        $docxPath = $workDir.'/document.docx';
        $pdfPath = $workDir.'/document.pdf';

        try {
            if (file_put_contents($docxPath, $docxBinary) === false) {
                throw new RuntimeException('Не удалось сохранить временный DOCX');
            }

            $soffice = $this->resolveSofficeBinary();
            $process = new Process([
                $soffice,
                '--headless',
                '--nologo',
                '--nofirststartwizard',
                '--norestore',
                '-env:UserInstallation=file://'.$profileDir,
                '--convert-to',
                'pdf:writer_pdf_Export',
                '--outdir',
                $workDir,
                $docxPath,
            ]);
            $process->setTimeout(120);
            $process->run();

            if (! $process->isSuccessful() || ! is_file($pdfPath)) {
                $error = trim($process->getErrorOutput().' '.$process->getOutput());
                throw new RuntimeException(
                    'Не удалось сформировать PDF'.($error !== '' ? ': '.$error : '')
                );
            }

            $pdfBinary = file_get_contents($pdfPath);
            if ($pdfBinary === false || $pdfBinary === '') {
                throw new RuntimeException('Сформированный PDF пуст');
            }

            return $pdfBinary;
        } finally {
            $this->removeDirectory($workDir);
        }
    }

    protected function resolveSofficeBinary(): string
    {
        foreach (['/usr/bin/soffice', '/usr/bin/libreoffice', 'soffice', 'libreoffice'] as $binary) {
            if (str_contains($binary, '/')) {
                if (is_executable($binary)) {
                    return $binary;
                }
                continue;
            }

            $process = Process::fromShellCommandline('command -v '.escapeshellarg($binary));
            $process->run();
            $path = trim($process->getOutput());
            if ($path !== '' && is_executable($path)) {
                return $path;
            }
        }

        throw new RuntimeException('LibreOffice не найден на сервере');
    }

    protected function removeDirectory(string $directory): void
    {
        if (! is_dir($directory)) {
            return;
        }

        $items = scandir($directory);
        if ($items === false) {
            return;
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($directory);
    }
}
