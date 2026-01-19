<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        /** @var Response $response */
        $response = Http::withToken(session('token'))
            ->get(config('services.api.url') . '/admin/dashboard');

        // Token expired
        if ($response->unauthorized()) {
            session()->forget('token');
            return redirect('/login')->withErrors([
                'auth' => 'Session habis, silakan login ulang'
            ]);
        }

        // API error
        if ($response->failed()) {
            abort(
                $response->status(),
                'Gagal mengambil data dashboard'
            );
        }

        // Ambil latest reports dari API
        $latestReports = $response->json('latest_reports') ?? [];

        // Hitung stats
        $total = $pending = $in_progress = $resolved = 0;

        foreach ($latestReports as $report) {
            $total++;
            $status = strtolower(trim($report['status'] ?? ''));

            if ($status === 'pending') $pending++;
            elseif ($status === 'in_progress') $in_progress++;
            elseif ($status === 'resolved') $resolved++;
        }

        $stats = [
            'total' => $total,
            'pending' => $pending,
            'in_progress' => $in_progress,
            'resolved' => $resolved,
        ];

        return view('dashboard', [
            'latestReports' => $latestReports,
            'stats' => $stats
        ]);
    }
    public function dataMaintenance()
    {
        /** @var Response $response */
        $response = Http::withToken(session('token'))
            ->get(config('services.api.url') . '/admin/reports'); // endpoint API

        // Token expired
        if ($response->unauthorized()) {
            session()->forget('token');
            return redirect('/login')->withErrors([
                'auth' => 'Session habis, silakan login ulang'
            ]);
        }

        // API error
        if ($response->failed()) {
            abort(
                $response->status(),
                'Gagal mengambil data maintenance'
            );
        }

        $reports = $response->json('reports') ?? [];
        $search   = request()->query('search');
        $status   = request()->query('status');
        $priority = request()->query('priority');

        $filtered = collect($reports)->filter(function ($report) use ($search, $status, $priority) {
            $match = true;

            if ($search) {
                $searchLower = strtolower($search);
                $match = $match && (
                    str_contains(strtolower($report['tvm_code'] ?? ''), $searchLower) ||
                    str_contains(strtolower($report['location'] ?? ''), $searchLower) ||
                    str_contains(strtolower($report['issue_type'] ?? ''), $searchLower)
                );
            }

            if ($status && strtolower($status) !== 'semua status') {
                $match = $match && strtolower($report['status'] ?? '') === strtolower($status);
            }

            if ($priority && strtolower($priority) !== 'semua priority') {
                $match = $match && strtolower($report['priority'] ?? '') === strtolower($priority);
            }

            return $match;
        })->values()->all();

        $filters = [
            'search' => $search,
            'status' => $status,
            'priority' => $priority,
        ];
        return view('data-maintenance', [
            'reports' => $filtered,
            'filters' => $filters
        ]);
    }
    public function tambahBarangBaru(Request $request)
{
    $validated = $request->validate([
        'nama_barang' => 'required|string|max:255',
        'stok'        => 'required|integer|min:0',
    ]);
    /** @var ClientResponse $res */
    $res = Http::withToken(session('token'))
        ->post(config('services.api.url') . '/admin/barang', $validated);

    if ($res->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors([
            'auth' => 'Session habis, silakan login ulang'
        ]);
    }

    if ($res->failed()) {
        return back()->withErrors([
            'error' => 'Gagal menambah barang (' . $res->status() . '): ' .
                ($res->json('message') ?? $res->body())
        ])->withInput();
    }

    return redirect()
        ->route('data.barang')
        ->with('success', 'Barang berhasil ditambahkan');
}
public function tambahStokBarang(Request $request, $id)
{
    $validated = $request->validate([
        'stok_tambah' => 'required|integer|min:1',
    ]);

    // 1) GET barang by id
    /** @var ClientResponse $getRes */
    $getRes = Http::withToken(session('token'))
        ->get(config('services.api.url') . "/admin/barang/{$id}");

    if ($getRes->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors(['auth' => 'Session habis, silakan login ulang']);
    }

    if ($getRes->failed()) {
        return back()->withErrors([
            'error' => 'Gagal mengambil barang (' . $getRes->status() . '): ' .
                ($getRes->json('message') ?? $getRes->body())
        ]);
    }

    $barang = $getRes->json('data') ?? $getRes->json();

    $stokSekarang = (int)($barang['stok'] ?? 0);
    $stokBaruInt  = $stokSekarang + (int)$validated['stok_tambah'];

    // 2) PUT update barang (JSON) — stok wajib string sesuai Go
    $payload = [
        'stok' => (string)$stokBaruInt, // ✅ string
    ];
    /** @var ClientResponse $putRes */
    $putRes = Http::withToken(session('token'))
        ->put(config('services.api.url') . "/admin/barang/{$id}", $payload);

    if ($putRes->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors(['auth' => 'Session habis, silakan login ulang']);
    }

    if ($putRes->failed()) {
        return back()->withErrors([
            'error' => 'Gagal update stok (' . $putRes->status() . '): ' .
                ($putRes->json('message') ?? $putRes->json('error') ?? $putRes->body())
        ]);
    }

    return redirect()->route('data.barang')->with('success', 'Stok berhasil ditambahkan');
}


    public function dataBarang()
{
    /** @var \Illuminate\Http\Client\Response $response */
    $response = Http::withToken(session('token'))
        ->get(config('services.api.url') . '/admin/barang'); // Endpoint API barang

    // Token expired
    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login')->withErrors([
            'auth' => 'Session habis, silakan login ulang'
        ]);
    }

    // API error
    if ($response->failed()) {
        abort(
            $response->status(),
            'Gagal mengambil data barang'
        );
    }

    $allBarang = $response->json('barang') ?? [];

    // Ambil filter & search dari query param
    $search = request()->query('search', '');
    $filteredBarang = collect($allBarang)
        ->filter(function ($item) use ($search) {
            return str_contains(strtolower($item['nama_barang']), strtolower($search));
        })->values();

    return view('data-barang', [
        'barang' => $filteredBarang,
        'search' => $search,
    ]);
}
 public function deleteBarang($id)
{
    /** @var \Illuminate\Http\Client\Response $res */
    $res = Http::withToken(session('token'))
        ->delete(config('services.api.url') . "/admin/barang/{$id}");

    if ($res->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors([
            'auth' => 'Session habis, silakan login ulang'
        ]);
    }

    if ($res->failed()) {
        return back()->withErrors([
            'error' => 'Gagal menghapus barang (' . $res->status() . '): ' .
                ($res->json('message') ?? $res->json('error') ?? $res->body())
        ]);
    }

    return redirect()
        ->route('data.barang')
        ->with('success', 'Barang berhasil dihapus');
}

    public function buatTicket(Request $request)
{
    /*
    |----------------------------------------------------------------------
    | POST : CREATE REPORT
    |----------------------------------------------------------------------
    */
    if ($request->isMethod('post')) {

        $validated = $request->validate([
            'barang_id'   => 'required|integer',
            'tvm_code'    => 'required|string|max:50',
            'location'    => 'required|string|max:255',
            'issue_type'  => 'required|string|max:255',
            'description' => 'required|string',
            'priority'    => 'nullable|in:low,medium,high,urgent',
            'image'       => 'nullable|image|max:2048',
        ]);
        
        // API Gin WAJIB multipart
        $http = Http::withToken(session('token'))->asMultipart();
        
        $payload = [
            ['name' => 'barang_id', 'contents' => $validated['barang_id']],
            ['name' => 'tvm_code', 'contents' => $validated['tvm_code']],
            ['name' => 'location', 'contents' => $validated['location']],
            ['name' => 'issue_type', 'contents' => $validated['issue_type']],
            ['name' => 'description', 'contents' => $validated['description']],
        ];

        if (!empty($validated['priority'])) {
            $payload[] = [
                'name' => 'priority',
                'contents' => $validated['priority'],
            ];
        }
        
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $payload[] = [
                'name'     => 'image',
                'contents' => fopen($file->getRealPath(), 'r'),
                'filename' => $file->getClientOriginalName(),
            ];
        }
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $http->post(
            config('services.api.url') . '/reports',
            $payload
        );

        if ($response->status() === 401) {
            session()->forget('token');
            return redirect()->route('login');
        }

        if ($response->failed()) {
            return back()
                ->withErrors([
                    'error' => $response->json('message') ?? 'Gagal membuat ticket'
                ])
                ->withInput();
        }

        return redirect()
            ->route('data.maintenance')
            ->with('success', 'Ticket berhasil dibuat');
    }

    /*
    |----------------------------------------------------------------------
    | GET : LOAD BARANG
    |----------------------------------------------------------------------
    */
    /** @var Response $barangResponse */
    $barangResponse = Http::withToken(session('token'))
        ->get(config('services.api.url') . '/barang');

    if ($barangResponse->status() === 401) {
        session()->forget('token');
        return redirect()->route('login');
    }

    $barangs = $barangResponse->successful()
        ? $barangResponse->json('barang')
        : [];

    return view('buat-ticket', compact('barangs'));
}
    public function users()
{
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->get(config('services.api.url') . '/admin/users');

    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login')->withErrors([
            'auth' => 'Session habis, silakan login ulang'
        ]);
    }

    if ($response->failed()) {
        abort($response->status(), 'Gagal mengambil data user');
    }

    $users = $response->json('users') ?? [];

    return view('manajemen-user', compact('users'));
}

// =========================
// STORE USER
// =========================
public function storeUser(Request $request)
{
    $validated = $request->validate([
        'username'  => 'required|string',
        'full_name' => 'required|string',
        'email'     => 'required|email',
        'Phone'     => 'required|string',
        'role'      => 'required|in:admin,user,teknisi', // ✅ tambah teknisi
        'password'  => 'required|min:6',
    ]);
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->post(config('services.api.url') . '/admin/users', $validated);

    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login');
    }

    if ($response->failed()) {
        return back()->withErrors([
            'error' => $response->json('message') ?? 'Gagal menambahkan user'
        ])->withInput();
    }

    return redirect()
        ->route('manajemen.users')
        ->with('success', 'User berhasil ditambahkan');
}


// =========================
// UPDATE USER
// =========================
public function updateUser(Request $request, $id)
{
    $validated = $request->validate([
        'username'  => 'required|min:3',
        'email'     => 'required|email',
        'full_name' => 'required',
        'Phone'     => 'required',
        'role'      => 'required|in:admin,user,teknisi', // ✅ tambah teknisi
    ]);
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->put(config('services.api.url') . "/admin/users/{$id}", $validated);

    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login');
    }

    if ($response->failed()) {
        return back()->withErrors([
            'error' => $response->json('message') ?? 'Gagal update user'
        ])->withInput();
    }

    return redirect()
        ->route('manajemen.users')
        ->with('success', 'User berhasil diupdate');
}

// =========================
// DELETE USER
// =========================
public function deleteUser($id)
{
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->delete(config('services.api.url') . "/admin/users/{$id}");

    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login');
    }

    if ($response->failed()) {
        return back()->withErrors([
            'error' => 'Gagal menghapus user'
        ]);
    }

    return redirect()
        ->route('manajemen.users')
        ->with('success', 'User berhasil dihapus');
}
    public function profile()
{
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->get(config('services.api.url') . '/users/profile');

    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login');
    }

    if ($response->failed()) {
        return redirect('/dashboard')->withErrors([
            'error' => 'Gagal mengambil data profile'
        ]);
    }

    $user = $response->json('data') ?? $response->json('user');

    return view('profile', compact('user'));
}
    public function updateProfile(Request $request)
{
    $user = session('user');

    $validated = $request->validate([
        'full_name' => 'required|string',
        'email'     => 'required|email',
    ]);
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->put(
            config('services.api.url') . "/users/profile/{$user['id']}",
            $validated
        );

    if ($response->failed()) {
        return back()->withErrors([
            'error' => $response->json('message') ?? 'Gagal update profile'
        ]);
    }

    // update session biar langsung sinkron
    session(['user' => array_merge($user, $validated)]);

    return back()->with('success', 'Profile berhasil diperbarui');
}
public function updateUserPassword(Request $request, $id)
{
    $validated = $request->validate([
        'password' => 'required|min:6|confirmed',
    ]);
    /** @var Response $res */
    $res = Http::withToken(session('token'))
        ->post(
            config('services.api.url') . "/admin/users/{$id}/password",
            [
                "new_password" => $validated['password']
            ]
        );

    if ($res->status() === 401) {
        session()->forget('token');
        return redirect('/login');
    }

    if ($res->failed()) {
        return back()->withErrors([
            'error' => 'Gagal reset password: ' . $res->body()
        ]);
    }

    return redirect()
        ->route('manajemen.users')
        ->with('success', 'Password user berhasil direset');
}

    public function updatePassword(Request $request)
{
    $validated = $request->validate([
        'old_password' => 'required',
        'password'     => 'required|min:6|confirmed',
    ]);
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->post(config('services.api.url') . '/users/change-password', [
            'old_password' => $validated['old_password'],
            'new_password' => $validated['password'],
        ]);

    if ($response->failed()) {
        return back()->withErrors([
            'error' => $response->json('message') ?? 'Gagal update password'
        ]);
    }

    return back()->with('success', 'Password berhasil diubah');
}
public function resetPasswordUser(Request $request, $id)
{
    $validated = $request->validate([
        'new_password' => 'required|min:6|confirmed',
    ]);

    // Ambil data user dari list user (biar dapat email/username)
    /** @var ClientResponse $usersRes */
    $usersRes = Http::withToken(session('token'))
        ->get(config('services.api.url') . '/admin/users');

    if ($usersRes->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors(['auth' => 'Session habis, silakan login ulang']);
    }

    if ($usersRes->failed()) {
        return back()->withErrors([
            'error' => 'Gagal ambil user: ' . ($usersRes->json('message') ?? $usersRes->body())
        ]);
    }

    $users = $usersRes->json('users') ?? [];
    $user = collect($users)->firstWhere('id', (int)$id);

    if (!$user) {
        return back()->withErrors(['error' => 'User tidak ditemukan']);
    }

    // Kirim ke API Go reset-password
    // ⚠️ SESUAIKAN KEY yang backend kamu terima: email/username/Phone
    $payload = [
        'email' => $user['email'],                 // ✅ paling umum
        // 'username' => $user['username'],        // alternatif
        // 'Phone' => $user['Phone'],              // alternatif case-sensitive
        'new_password' => $validated['new_password'],
    ];

    /** @var ClientResponse $res */
    $res = Http::withToken(session('token'))
        ->post(config('services.api.url') . '/users/reset-password', $payload);

    if ($res->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors(['auth' => 'Session habis, silakan login ulang']);
    }

    if ($res->failed()) {
        return back()->withErrors([
            'error' => 'Gagal reset password (' . $res->status() . '): ' .
                ($res->json('message') ?? $res->json('error') ?? $res->body())
        ]);
    }

    return redirect()
        ->route('manajemen.users')
        ->with('success', 'Password user berhasil direset');
}
// =========================
// UPDATE REPORT (MAINTENANCE)
// =========================
public function updateReport(Request $request, $id)
{
    $validated = $request->validate([
        'barang_id'   => 'required|integer',
        'tvm_code'    => 'required|string|max:50',
        'location'    => 'required|string|max:255',
        'issue_type'  => 'required|string|max:255',
        'description' => 'required|string',
        'priority'    => 'required|in:low,medium,high,urgent',
        'status'      => 'required|in:pending,in_progress,resolved,closed',
        'image' => 'required|image|max:2048',
    ]);

    $payload = [
        ['name' => 'barang_id',   'contents' => $validated['barang_id']],
        ['name' => 'tvm_code',    'contents' => $validated['tvm_code']],
        ['name' => 'location',    'contents' => $validated['location']],
        ['name' => 'issue_type',  'contents' => $validated['issue_type']],
        ['name' => 'description', 'contents' => $validated['description']],
        ['name' => 'priority',    'contents' => $validated['priority']],
        ['name' => 'status',      'contents' => $validated['status']],
    ];
    $file = $request->file('image');
$payload[] = [
  'name'     => 'image',
  'contents' => fopen($file->getRealPath(), 'r'),
  'filename' => $file->getClientOriginalName(),
];

    $response = Http::withToken(session('token'))
        ->asMultipart()
        ->put(config('services.api.url') . "/admin/reports/{$id}", $payload);
    /** @var Response $response */
    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login')->withErrors(['auth' => 'Session habis, silakan login ulang']);
    }

    if ($response->failed()) {
        return back()->withErrors([
            'error' => 'Gagal update ticket (' . $response->status() . '): ' .
                ($response->json('message') ?? $response->body())
        ])->withInput();
    }

    return redirect()->route('data.maintenance')->with('success', 'Ticket berhasil diupdate');
}


// =========================
// DELETE REPORT (MAINTENANCE)
// =========================
public function deleteReport($id)
{
    /** @var Response $response */
    $response = Http::withToken(session('token'))
        ->delete(config('services.api.url') . "/admin/reports/{$id}");

    if ($response->unauthorized()) {
        session()->forget('token');
        return redirect('/login')->withErrors([
            'auth' => 'Session habis, silakan login ulang'
        ]);
    }

    if ($response->failed()) {
        return back()->withErrors([
            'error' => $response->json('message') ?? 'Gagal menghapus ticket'
        ]);
    }

    return redirect()
        ->route('data.maintenance')
        ->with('success', 'Ticket berhasil dihapus');
}
public function history()
{   
    /** @var ClientResponse $res */
    $res = Http::withToken(session('token'))
        ->get(config('services.api.url') . '/reports/history');

    if ($res->status() === 401) {
        session()->forget('token');
        return redirect('/login')->withErrors([
            'auth' => 'Session habis, silakan login ulang'
        ]);
    }

    if ($res->failed()) {
        return back()->withErrors([
            'error' => 'Gagal mengambil history (' . $res->status() . '): ' .
                ($res->json('message') ?? $res->body())
        ]);
    }

    // ✅ Karena response berupa array root: [...]
    $history = $res->json() ?? [];

    // ✅ Sort terbaru dulu berdasarkan CreatedAt
    usort($history, function ($a, $b) {
        $ta = strtotime($a['CreatedAt'] ?? '1970-01-01');
        $tb = strtotime($b['CreatedAt'] ?? '1970-01-01');
        return $tb <=> $ta;
    });

    return view('history', compact('history'));
}
}
