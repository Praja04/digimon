<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'variant',
        'production_date',
        'batch_range',
        'storage',  // Langsung disimpan di sini
        'description',
    ];
    public function getBatchRangeArrayAttribute()
    {
        if (preg_match('/(\d+)\s*-\s*(\d+)/', $this->batch_range, $matches)) {
            return range((int)$matches[1], (int)$matches[2]);
        }
        return [$this->batch_range]; // fallback kalau cuma satu angka
    }

    public function MonitoringPasteurisasi()
    {
        return $this->hasMany(MonitoringPasteurisasi::class);
    }

    public function MonitoringStorageBeforeUse()
    {
        return $this->hasMany(MonitoringStorageBeforeUse::class);
    }

    public function MonitoringStorageMikro()
    {
        return $this->hasMany(MonitoringStorageMikroModel::class);
    }

    public function MonitoringStorage()
    {
        return $this->hasMany(MonitoringStorageModel::class);
    }

    public function MonitoringTurunBlending()
    {
        return $this->hasMany(MonitoringTurunBlending::class);
    }

    public function BlendingAwal()
    {
        return $this->hasMany(BlendingAwalModel::class);
    }

    public function blendingAfterAdjust()
    {
        return $this->hasMany(BlendingAfterAdjustModel::class);
    }

    public function blendingAfterAdjustMikro()
    {
        return $this->hasMany(BlendingAfterAdjustMikroModel::class);
    }

    public function GgaProcesses()
    {
        return $this->hasMany(GgaProcess::class);
    }

    public function GgasProcesses()
    {
        return $this->hasMany(GgasProcess::class);
    }

    public function isGGaComplete(): bool
    {
        $ggaItems = $this->GgaProcesses;
        $jumlahGGA = $ggaItems->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($jumlahGGA === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $ggaItems->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }


    public function isGGasComplete()
    {
        $ggasItems = $this->GgasProcesses;
        $jumlahGGAS = $ggasItems->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($jumlahGGAS === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $ggasItems->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }


    public function isBlendingAwalComplete()
    {
        $blending = $this->BlendingAwal;
        $data = $blending->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $blending->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }

    public function isBlendingAdjustMakroComplete()
    {
        $blending = $this->blendingAfterAdjust;
        $data = $blending->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $blending->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }

    public function isBlendingAdjustMikroComplete()
    {
        $blending = $this->blendingAfterAdjustMikro;
        $data = $blending->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $blending->every(function ($item) {
            return !is_null($item->ym);
        });

        return $isAllFilled;
    }


    public function isMonitoringBlendingComplete()
    {
        $monitoring = $this->MonitoringTurunBlending;
        $data = $monitoring->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $monitoring->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }

    public function isMonitoringPasteurisasiComplete()
    {
        $monitoring = $this->MonitoringPasteurisasi;
        $data = $monitoring->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $monitoring->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }

    public function isMonitoringStorageMakroComplete()
    {
        $monitoring = $this->MonitoringStorage;
        $data = $monitoring->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $monitoring->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }

    public function isMonitoringStorageMikroComplete()
    {
        $monitoring = $this->MonitoringStorageMikro;
        $data = $monitoring->count();

        // Jika tidak ada data GGA, belum lengkap
        if ($data === 0) {
            return false;
        }

        // Cek apakah semua data brix dan nacl sudah terisi
        $isAllFilled = $monitoring->every(function ($item) {
            return !is_null($item->disposition);
        });

        return $isAllFilled;
    }
}
