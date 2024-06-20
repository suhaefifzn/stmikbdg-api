<?php

namespace App\Http\Controllers\Docs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocSuratController extends Controller
{
    public function index() {
        return view('docs.contents.surat.index', [
            'title' => 'Surat Masuk dan Keluar'
        ]);
    }

    public function suratTabs($name) {
        switch (strtolower($name)) {
            case 'all':
                $content = view('docs.contents.surat.all')->render();
                break;
            case 'admin':
                $content = view('docs.contents.surat.admin')->render();
                break;
            case 'sekretaris':
                $content = view('docs.contents.surat.sekretaris')->render();
                break;
            case 'wakil':
                $content = view('docs.contents.surat.wakil')->render();
                break;
            case 'karyawan':
                $content = view('docs.contents.surat.karyawan')->render();
                break;
            default:
                $content = 'Tab not found.';
                break;
        }

        return response()->json([
            'content' => $content
        ]);
    }
}
