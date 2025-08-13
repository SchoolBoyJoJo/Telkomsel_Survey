<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\SurveyAnswer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $surveyType = $request->get('survey_type', 'telkomsel');

        // Ambil data survey berdasarkan tipe
        $rawSurveys = SurveyAnswer::where('survey_type', $surveyType)
            ->latest()
            ->get();

        // Decode JSON 'answers' ke array dan tambahkan created_at
        $decodedSurveys = $rawSurveys->map(function ($item) {
            $decoded = json_decode($item->answers, true);
            $decoded['created_at'] = $item->created_at;
            return $decoded;
        });

        // Data usia untuk grafik
        $usiaCounts = $decodedSurveys->pluck('usia')->countBy()->sortKeys();


        // Pie Chart Telkomsel
        $jenisKelaminCounts = $decodedSurveys->pluck('jenis_kelamin')->countBy();
        $jenisTempatTinggalCounts = $decodedSurveys->pluck('jenis_tempat_tinggal')->countBy();
        $statusPekerjaanCounts = $decodedSurveys->pluck('status_pekerjaan')->countBy();
        $pendapatanPribadiCounts = $decodedSurveys->pluck('pendapatan')->countBy();
        $aktifTelkomselCounts = $decodedSurveys->pluck('aktif_telkomsel')->countBy();
        $multisimerCounts = $decodedSurveys->pluck('multisimer')->countBy();
        $simKeduaCounts = $decodedSurveys->pluck('sim_kedua')->countBy();
        $wifiRumahCounts = $decodedSurveys->pluck('wifi_rumah')->countBy();
        $providerWifiCounts = $decodedSurveys->pluck('provider_wifi')->countBy();
        $wifiVsDataLuarCounts = $decodedSurveys->pluck('wifi_vs_data_luar')->countBy();
        $durasiWifiPublikCounts = $decodedSurveys->pluck('durasi_wifi_publik')->countBy();
        $keluarKotaBulananCounts = $decodedSurveys->pluck('keluar_kota_bulanan')->countBy();
        $keluargaTelkomselCounts = $decodedSurveys->pluck('keluarga_telkomsel')->countBy();
        $aktifitasInternetBeratCounts = $decodedSurveys->pluck('aktifitas_internet_berat')->countBy();
        $jenisPaketCounts = $decodedSurveys->pluck('jenis_paket')->countBy();
        $sumberPembelianPaketCounts = $decodedSurveys->pluck('sumber_pembelian_paket')->countBy();
        $hargaPaketWajarCounts = $decodedSurveys->pluck('harga_paket_wajar')->countBy();
        $tertarikPromoLainCounts = $decodedSurveys->pluck('tertarik_promo_lain')->countBy();

        // Bar chart Telkomsel
        $penilaianKualitasTelkomselCounts = $decodedSurveys->pluck('penilaian_kualitas_telkomsel')->countBy()->sortKeys();
        $frekuensiGangguanCounts = $decodedSurveys->pluck('frekuensi_gangguan')->countBy()->sortKeys();
        $kemudahanBeliTelkomselCounts = $decodedSurveys->pluck('kemudahan_beli_telkomsel')->countBy()->sortKeys();
        $sepadanHargaTelkomselCounts = $decodedSurveys->pluck('sepadan_harga_telkomsel')->countBy()->sortKeys();
        $kemudahanPindahProviderCounts = $decodedSurveys->pluck('kemudahan_pindah_provider')->countBy()->sortKeys();
        $mahalDibandingkanCounts = $decodedSurveys->pluck('mahal_dibandingkan')->countBy()->sortKeys();

        // Pie chart Indihome
        $jenisKelaminIndiCounts = $decodedSurveys->pluck('jenis_kelamin')->countBy();
        $pekerjaanIndiCounts = $decodedSurveys->pluck('pekerjaan')->countBy();
        $pendapatanIndiCounts = $decodedSurveys->pluck('pendapatan')->countBy();
        $tempatTinggalIndiCounts = $decodedSurveys->pluck('tempat_tinggal')->countBy();
        $aktifIndihomeCounts = $decodedSurveys->pluck('aktif_indihome')->countBy();
        $wifiVsDataCounts = $decodedSurveys->pluck('wifi_vs_data')->countBy();
        $alasanWifiVsDataCounts = $decodedSurveys->pluck('alasan_wifi_vs_data')->countBy();
        $wifiGratisUmumCounts = $decodedSurveys->pluck('wifi_gratis_umum')->countBy();
        $gangguanWifiCounts = $decodedSurveys->pluck('gangguan_wifi')->countBy();
        $responTeknisiCounts = $decodedSurveys->pluck('respon_teknisi')->countBy();
        $biayaWifiCounts = $decodedSurveys->pluck('biaya_wifi')->countBy();
        $ekspektasiBiayaCounts = $decodedSurveys->pluck('ekspektasi_biaya')->countBy();
        $ekspektasiKecepatanCounts = $decodedSurveys->pluck('ekspektasi_kecepatan')->countBy();
        $sumberIndihomeCounts = $decodedSurveys->pluck('sumber_indihome')->countBy();
        $providerTerbaikCounts = $decodedSurveys->pluck('provider_terbaik')->countBy();

        // Bar chart indihome
        $hematWifiGratisCounts = $decodedSurveys->pluck('hemat_wifi_gratis')->countBy()->sortKeys();
        $ekspektasiTeknisiCounts = $decodedSurveys->pluck('ekspektasi_teknisi')->countBy()->sortKeys();
        $pengaruhTeknisiCounts = $decodedSurveys->pluck('pengaruh_teknisi')->countBy()->sortKeys();
        $sebandingWifiCounts = $decodedSurveys->pluck('sebanding_wifi')->countBy()->sortKeys();

        // Saran
        $saranTelkomsel = $decodedSurveys
            ->pluck('saran_telkomsel')
            ->filter(function ($value) {
                return !empty($value);
            })
            ->values();
        
        $saranIndihome = $rawSurveys->map(function ($item) {
            $decoded = json_decode($item->answers, true);

            if (!isset($decoded['saran_kritik'])) {
                return null;
            }

            $saran = $decoded['saran_kritik'];

            if ($saran === 'Ada (isi di kolom di bawah)' || $saran === 'Lainnya') {
                return $decoded['saran_kritik_lainnya'] ?? null;
            }

            if ($saran !== 'Tidak ada') {
                return $saran;
            }

            return null;
        })->filter()->values();

        
        return view('dashboard', [
            // Telkomsel
            'decodedSurveys'  => $decodedSurveys,
            'usiaCounts'      => $usiaCounts,
            'selectedType'    => $surveyType,
            'saranTelkomsel'  => $saranTelkomsel,
            'saranIndihome'   => $saranIndihome,
            'jenisKelaminCounts'=> $jenisKelaminCounts,
            'jenisTempatTinggalCounts' => $jenisTempatTinggalCounts,
            'statusPekerjaanCounts'    => $statusPekerjaanCounts,
            'pendapatanPribadiCounts' => $pendapatanPribadiCounts,
            'aktifTelkomselCounts'     => $aktifTelkomselCounts,
            'multisimerCounts'         => $multisimerCounts,
            'simKeduaCounts'           => $simKeduaCounts,
            'wifiRumahCounts'          => $wifiRumahCounts,
            'providerWifiCounts'       => $providerWifiCounts,
            'wifiVsDataLuarCounts'     => $wifiVsDataLuarCounts,
            'durasiWifiPublikCounts'   => $durasiWifiPublikCounts,
            'keluarKotaBulananCounts'  => $keluarKotaBulananCounts,
            'keluargaTelkomselCounts' => $keluargaTelkomselCounts,
            'aktifitasInternetBeratCounts' => $aktifitasInternetBeratCounts,
            'jenisPaketCounts' => $jenisPaketCounts,
            'sumberPembelianPaketCounts' => $sumberPembelianPaketCounts,
            'penilaianKualitasTelkomselCounts' => $penilaianKualitasTelkomselCounts,
            'frekuensiGangguanCounts' => $frekuensiGangguanCounts,
            'kemudahanBeliTelkomselCounts' => $kemudahanBeliTelkomselCounts,
            'hargaPaketWajarCounts' => $hargaPaketWajarCounts,
            'sepadanHargaTelkomselCounts' => $sepadanHargaTelkomselCounts,
            'tertarikPromoLainCounts' => $tertarikPromoLainCounts,
            'kemudahanPindahProviderCounts' => $kemudahanPindahProviderCounts,
            'mahalDibandingkanCounts' => $mahalDibandingkanCounts,

            // Indihome
            'jenisKelaminIndiCounts' => $jenisKelaminIndiCounts,
            'pekerjaanIndiCounts' => $pekerjaanIndiCounts,
            'pendapatanIndiCounts' => $pendapatanIndiCounts,
            'tempatTinggalIndiCounts' => $tempatTinggalIndiCounts,
            'aktifIndihomeCounts' => $aktifIndihomeCounts,
            'wifiVsDataCounts' => $wifiVsDataCounts,
            'alasanWifiVsDataCounts' => $alasanWifiVsDataCounts,
            'wifiGratisUmumCounts' => $wifiGratisUmumCounts,
            'hematWifiGratisCounts' => $hematWifiGratisCounts,
            'gangguanWifiCounts' => $gangguanWifiCounts,
            'responTeknisiCounts' => $responTeknisiCounts,
            'ekspektasiTeknisiCounts' => $ekspektasiTeknisiCounts,
            'pengaruhTeknisiCounts' => $pengaruhTeknisiCounts,
            'biayaWifiCounts' => $biayaWifiCounts,
            'sebandingWifiCounts' => $sebandingWifiCounts,
            'ekspektasiBiayaCounts' => $ekspektasiBiayaCounts,
            'ekspektasiKecepatanCounts' => $ekspektasiKecepatanCounts,
            'sumberIndihomeCounts' => $sumberIndihomeCounts,
            'providerTerbaikCounts' => $providerTerbaikCounts
        ]);
    }

    public function download(Request $request): StreamedResponse
    {
        $surveyType = $request->get('survey_type', 'telkomsel');
        $fileName = 'survey_' . $surveyType . '_' . now()->format('Ymd_His') . '.csv';

        $surveys = SurveyAnswer::where('survey_type', $surveyType)->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->stream(function () use ($surveys) {
            $handle = fopen('php://output', 'w');

            if ($surveys->isNotEmpty()) {
                // Header kolom CSV
                fputcsv($handle, array_keys($surveys->first()->toArray()));

                // Baris data
                foreach ($surveys as $survey) {
                    fputcsv($handle, $survey->toArray());
                }
            } else {
                fputcsv($handle, ['Tidak ada data untuk tipe survey ini.']);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
