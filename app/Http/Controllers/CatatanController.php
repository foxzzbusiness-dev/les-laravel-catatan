<?php

namespace App\Http\Controllers;

use App\Exports\CatatanExport;
use App\Models\Catatan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CatatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Catatan::where('user_id', auth()->id())
            ->where('is_archived', false);

        // Handle pencarian
        if ($request->filled('cari')) {
            $keyword = '%' . $request->cari . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                    ->orWhere('isi', 'like', $keyword);
            });
        }

        // Handle pengurutan
        $urut = $request->get('urut', 'terbaru'); // default: terbaru
        if ($urut == 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $catatan = $query->paginate(4);
        $jumlah_aktif = Catatan::where('user_id', auth()->id())->where('is_archived', false)->count();

        return view('catatan.index', compact('catatan', 'jumlah_aktif'));
    }

    public function show($id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('catatan.show', compact('catatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048'
        ]);

        $data = $request->only(['judul', 'isi']);
        $data['user_id'] = auth()->id(); // <-- SIMPAN USER YANG LOGIN

        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->hashName();
            $file->storeAs('lampiran', $filename, 'public');
            $data['lampiran'] = 'lampiran/' . $filename;
        }

        Catatan::create($data);
        return redirect('/catatan')->with('sukses', 'Catatan berhasil disimpan!');
    }

    public function create()
    {
        return view('catatan.create');
    }

    public function edit($id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('catatan.edit', compact('catatan'));
    }

    public function update(Request $request, $id)
    {
        // Pastikan catatan milik user yang sedang login
        $catatan = Catatan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Validasi input
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048'
        ]);

        $data = $request->only(['judul', 'isi']);

        // Jika ada lampiran baru
        if ($request->hasFile('lampiran')) {

            // Hapus lampiran lama
            if ($catatan->lampiran) {
                Storage::disk('public')->delete($catatan->lampiran);
            }

            // Simpan lampiran baru
            $file = $request->file('lampiran');
            $filename = time() . '_' . $file->hashName();

            $file->storeAs('lampiran', $filename, 'public');
            $data['lampiran'] = 'lampiran/' . $filename;
        }

        // Perbarui data catatan
        $catatan->update($data);

        return redirect("/catatan/{$catatan->id}")
            ->with('sukses', 'Catatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $catatan = Catatan::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        // Hapus lampiran jika ada
        if ($catatan->lampiran) {
            Storage::disk('public')->delete($catatan->lampiran);
        }
        $catatan->delete();
        return redirect('/catatan')->with('sukses', 'Catatan berhasil dihapus.');
    }

    public function archive($id)
    {
        $catatan = Catatan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        $catatan->is_archived = true;
        $catatan->save();
        return redirect('/catatan')->with('sukses', 'Catatan telah diarsipkan.');
    }

    public function unarchive($id)
    {
        $catatan = Catatan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        $catatan->is_archived = false;
        $catatan->save();
        return redirect('/catatan')->with('sukses', 'Catatan dipulihkan dari arsip.');
    }

    public function arsip(Request $request)
    {
        $query = Catatan::where('user_id', auth()->id())
            ->where('is_archived', true);

        if ($request->filled('cari')) {
            $keyword = '%' . $request->cari . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                    ->orWhere('isi', 'like', $keyword);
            });
        }

        $urut = $request->get('urut', 'terbaru');
        if ($urut == 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $catatan = $query->paginate(4);
        $jumlah_arsip = Catatan::where('user_id', auth()->id())->where('is_archived', true)->count();

        return view('catatan.arsip', compact('catatan', 'jumlah_arsip'));
    }

    public function export()
    {
        return Excel::download(
            new CatatanExport,
            'catatan_aktif_' . date('Y-m-d') . '.xlsx'
        );
    }

    public function importForm()
    {
        return view('catatan.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv,txt|max:2048'
        ]);

        $spreadsheet = IOFactory::load($request->file('file')->getPathname());
        $rows = $spreadsheet->getActiveSheet()->toArray();
        array_shift($rows); // lewati header

        $jumlah = 0;
        foreach ($rows as $row) {
            $judul = trim($row[0] ?? '');
            $isi   = trim($row[1] ?? '');

            // Lewati baris kosong
            if ($judul === '' || $isi === '') {
                continue;
            }

            // Baca tanggal, kalau gagal pakai waktu sekarang
            try {
                $tanggal = !empty($row[2]) ? Carbon::parse($row[2]) : now();
            } catch (\Exception $e) {
                $tanggal = now();
            }

            Catatan::create([
                'user_id'     => auth()->id(),
                'judul'       => $judul,
                'isi'         => $isi,
                'is_archived' => false,
                'created_at'  => $tanggal,
                'updated_at'  => now(),
            ]);
            $jumlah++;
        }

        return redirect('/catatan')->with('sukses', "Berhasil mengimpor {$jumlah} catatan.");
    }
}