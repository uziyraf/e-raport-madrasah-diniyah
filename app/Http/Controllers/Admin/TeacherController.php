<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->orderBy('name')->paginate(10);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $roles = Role::whereIn('name', ['kepala_sekolah', 'wali_kelas', 'guru_fan'])->pluck('name', 'id');
        return view('admin.teachers.create', compact('roles'));
    }

    public function store(StoreTeacherRequest $request)
    {
        $data = $request->validated();
        $userData = $data['account'] ?? null;
        unset($data['account']);

        if ($userData && ($userData['create_account'] ?? false)) {
            $user = User::create([
                'name' => $data['name'],
                'username' => $userData['username'],
                'email' => $data['email'],
                'password' => Hash::make($userData['password']),
                'status' => 'active',
            ]);
            $user->assignRole($userData['role']);
            $data['user_id'] = $user->id;
        }

        if ($request->hasFile('signature')) {
            $data['signature_path'] = $request->file('signature')->store('teacher-signatures', 'public');
        }

        Teacher::create($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->loadMissing(['user', 'homeroomAssignments.schoolClass.level', 'teachingAssignments.subject', 'teachingAssignments.schoolClass.level']);

        $currentHomeroom = $teacher->homeroomAssignments()
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->with('schoolClass.level', 'academicYear', 'semester')
            ->first();

        $currentTeaching = $teacher->teachingAssignments()
            ->whereHas('academicYear', fn($q) => $q->where('is_active', true))
            ->whereHas('semester', fn($q) => $q->where('is_active', true))
            ->with('subject', 'schoolClass.level')
            ->get();

        return view('admin.teachers.show', compact('teacher', 'currentHomeroom', 'currentTeaching'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->loadMissing('user');
        $roles = Role::whereIn('name', ['kepala_sekolah', 'wali_kelas', 'guru_fan'])->pluck('name', 'id');
        return view('admin.teachers.edit', compact('teacher', 'roles'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        $data = $request->validated();
        $userData = $data['account'] ?? null;
        unset($data['account']);

        if ($userData && ($userData['create_account'] ?? false)) {
            if ($teacher->user_id) {
                $user = $teacher->user;
                $userUpdateData = [
                    'name' => $data['name'],
                    'username' => $userData['username'],
                    'email' => $data['email'],
                ];
                if (!empty($userData['password'])) {
                    $userUpdateData['password'] = Hash::make($userData['password']);
                }
                $user->update($userUpdateData);
                $user->syncRoles([$userData['role']]);
            } else {
                $user = User::create([
                    'name' => $data['name'],
                    'username' => $userData['username'],
                    'email' => $data['email'],
                    'password' => Hash::make($userData['password']),
                    'status' => 'active',
                ]);
                $user->assignRole($userData['role']);
                $data['user_id'] = $user->id;
            }
        }

        if ($request->hasFile('signature')) {
            if ($teacher->signature_path) {
                Storage::disk('public')->delete($teacher->signature_path);
            }
            $data['signature_path'] = $request->file('signature')->store('teacher-signatures', 'public');
        }

        $teacher->update($data);

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->update(['status' => 'inactive']);
        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dinonaktifkan.');
    }
}
