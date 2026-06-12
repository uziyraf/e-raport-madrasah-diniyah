<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-teal-950">Preview Raport Arab</h2>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-1 rounded-sm bg-teal-700 px-4 py-2 text-sm font-medium text-white transition hover:bg-teal-800 print:hidden">
                Cetak / Print
            </button>
        </div>
    </x-slot>

    <div class="print:bg-white">
        @include('report-cards.partials.arabic-template', ['reportData' => $reportData])
    </div>

    <div class="mt-4 print:hidden">
        <a href="{{ route('homeroom.report-cards.index') }}"
           class="inline-flex items-center gap-1 rounded-sm bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
            &larr; Kembali
        </a>
    </div>

    @push('styles')
        <style>
            @media print {
                body {
                    background: #fff !important;
                    margin: 0;
                    padding: 0;
                }
                .font-arabic {
                    font-family: 'Noto Naskh Arabic', 'Amiri', 'Traditional Arabic', serif;
                }
            }
        </style>
    @endpush
</x-app-layout>
