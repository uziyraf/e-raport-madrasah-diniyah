<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-teal-950">Edit Wali Santri</h2>
    </x-slot>

    <div class="rounded-lg bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] outline outline-1 outline-offset-[-1px] outline-stone-300">
        <form action="{{ route('admin.guardians.update', $guardian) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.guardians._form')

            <div class="mt-6 flex items-center gap-3 border-t border-stone-300 pt-6">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-sm bg-teal-950 px-4 py-3 text-sm font-medium text-white transition hover:bg-emerald-900">
                    Perbarui
                </button>
                <a href="{{ route('admin.guardians.index') }}"
                   class="inline-flex items-center justify-center rounded-sm bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 outline outline-1 outline-neutral-500 transition hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
