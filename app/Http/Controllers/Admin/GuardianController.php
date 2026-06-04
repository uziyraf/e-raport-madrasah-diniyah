<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGuardianRequest;
use App\Http\Requests\Admin\UpdateGuardianRequest;
use App\Models\Guardian;
use App\Models\Level;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GuardianController extends Controller
{
    public function index()
    {
        $guardians = Guardian::with('user', 'students')
            ->where(function ($q) {
                $search = request('search');
                if ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('students', fn($sq) => $sq
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%")
                        );
                }
            })
            ->orderBy('name')
            ->paginate(10);

        return view('admin.guardians.index', compact('guardians'));
    }

    public function create()
    {
        $students = $this->filteredStudents();
        $levels = Level::active()->orderBy('sort_order')->get();
        $classes = SchoolClass::active()->with('level')->orderBy('sort_order')->get();

        return view('admin.guardians.create', compact('students', 'levels', 'classes'));
    }

    public function store(StoreGuardianRequest $request)
    {
        $data = $request->validated();
        $userData = $data['account'] ?? null;
        $students = $data['students'] ?? [];
        unset($data['account'], $data['students']);

        if ($userData && ($userData['create_account'] ?? false)) {
            $user = User::create([
                'name' => $data['name'],
                'username' => $userData['username'],
                'email' => $userData['email'] ?? null,
                'password' => Hash::make($userData['password']),
                'status' => 'active',
            ]);
            $user->assignRole('wali_santri');
            $data['user_id'] = $user->id;
        }

        $guardian = Guardian::create($data);

        $guardian->students()->sync($students);

        return redirect()->route('admin.guardians.index')->with('success', 'Wali santri berhasil ditambahkan.');
    }

    public function show(Guardian $guardian)
    {
        $guardian->loadMissing(['user', 'students.activeEnrollment.schoolClass.level']);

        return view('admin.guardians.show', compact('guardian'));
    }

    public function edit(Guardian $guardian)
    {
        $guardian->loadMissing('user', 'students');
        $students = $this->filteredStudents();
        $levels = Level::active()->orderBy('sort_order')->get();
        $classes = SchoolClass::active()->with('level')->orderBy('sort_order')->get();

        return view('admin.guardians.edit', compact('guardian', 'students', 'levels', 'classes'));
    }

    public function update(UpdateGuardianRequest $request, Guardian $guardian)
    {
        $data = $request->validated();
        $userData = $data['account'] ?? null;
        $students = $data['students'] ?? [];
        unset($data['account'], $data['students']);

        if ($userData && ($userData['create_account'] ?? false)) {
            if ($guardian->user_id) {
                $userUpdate = [
                    'name' => $data['name'],
                    'username' => $userData['username'],
                    'email' => $userData['email'] ?? null,
                ];
                if (!empty($userData['password'])) {
                    $userUpdate['password'] = Hash::make($userData['password']);
                }
                $guardian->user->update($userUpdate);
            } else {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $userData['username'],
                    'email' => $userData['email'] ?? null,
                    'password' => Hash::make($userData['password']),
                    'status' => 'active',
                ]);
                $user->assignRole('wali_santri');
                $data['user_id'] = $user->id;
            }
        }

        $guardian->update($data);

        $guardian->students()->sync($students);

        return redirect()->route('admin.guardians.index')->with('success', 'Wali santri berhasil diperbarui.');
    }

    public function destroy(Guardian $guardian)
    {
        $guardian->update(['status' => 'inactive']);

        return redirect()->route('admin.guardians.index')->with('success', 'Wali santri berhasil dinonaktifkan.');
    }

    private function filteredStudents()
    {
        $search = request('student_search');
        $levelId = request('level_id');
        $classId = request('school_class_id');

        return Student::with('activeEnrollment.schoolClass.level')
            ->where(function ($q) use ($search) {
                if ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%");
                }
            })
            ->when($levelId, fn($q) => $q->whereHas('activeEnrollment.schoolClass', fn($sq) => $sq->where('level_id', $levelId)))
            ->when($classId, fn($q) => $q->whereHas('activeEnrollment', fn($sq) => $sq->where('school_class_id', $classId)))
            ->orderBy('name')
            ->paginate(15);
    }
}
