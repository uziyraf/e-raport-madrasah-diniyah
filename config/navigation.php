<?php

return [
    'super_admin' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],

        'master_data' => [
            'label' => 'Master Data',
            'icon' => 'bx bx-layer',
            'active_patterns' => [
                'admin.users.*',
                'admin.teachers.*',
                'admin.students.*',
                'admin.guardians.*',
                'admin.levels.*',
                'admin.school-classes.*',
                'admin.subjects.*',
            ],
            'children' => [
                'data_user' => ['label' => 'Data User', 'route' => 'super_admin.users.index', 'icon' => 'bx bx-user'],
                'data_guru' => ['label' => 'Data Guru', 'route' => 'admin.teachers.index', 'icon' => 'bx bx-user'],
                'data_santri' => ['label' => 'Data Santri', 'route' => 'admin.students.index', 'icon' => 'bx bxs-user-rectangle'],
                'data_wali_santri' => ['label' => 'Data Wali Santri', 'route' => 'admin.guardians.index', 'icon' => 'bx bx-user-voice'],
                'jenjang_kelas' => ['label' => 'Jenjang', 'route' => 'admin.levels.index', 'icon' => 'bx bx-building'],
                'kelas' => ['label' => 'Kelas', 'route' => 'admin.school-classes.index', 'icon' => 'bx bx-layer'],
                'fan_mapel' => ['label' => 'Fan/Mapel', 'route' => 'admin.subjects.index', 'icon' => 'bx bx-book-open'],
            ],
        ],

        'akademik' => [
            'label' => 'Akademik',
            'icon' => 'bx bx-book',
            'active_patterns' => [
                'admin.academic-years.*',
                'admin.semesters.*',
                'admin.homeroom-assignments.*',
                'admin.teaching-assignments.*',
                'admin.jadwal-pelajaran.*',
                'admin.placements.*',
                'admin.promotions.*',
                'jadwal-pelajaran.*',
            ],
            'children' => [
                'tahun_ajaran' => ['label' => 'Tahun Ajaran', 'route' => 'admin.academic-years.index', 'icon' => 'bx bx-calendar'],
                'semester' => ['label' => 'Semester', 'route' => 'admin.semesters.index', 'icon' => 'bx bx-calendar-alt'],
                'wali_kelas' => ['label' => 'Wali Kelas', 'route' => 'admin.homeroom-assignments.index', 'icon' => 'bx bx-chalkboard'],
                'guru_fan_mapel' => ['label' => 'Guru Fan/Mapel', 'route' => 'admin.teaching-assignments.index', 'icon' => 'bx bx-chalkboard'],
                'jadwal_pelajaran' => [
                    'label' => 'Jadwal Pelajaran',
                    'icon' => 'bx bx-time',
                    'children' => [
                        'list_jadwal' => ['label' => 'List Semua Jadwal Pelajaran', 'route' => 'jadwal-pelajaran.index', 'icon' => 'bx bx-list-ul'],
                        'template_jadwal' => ['label' => 'Template Jadwal Pelajaran', 'route' => 'admin.jadwal-pelajaran.template', 'icon' => 'bx bx-table'],
                        'pembuatan_jadwal' => ['label' => 'Pembuatan Jadwal Pelajaran', 'route' => 'admin.jadwal-pelajaran.index', 'icon' => 'bx bx-edit'],
                    ],
                ],
                'kenaikan_penempatan_santri' => ['label' => 'Kenaikan/Penempatan Santri', 'route' => 'admin.placements.index', 'icon' => 'bx bx-transfer'],
            ],
        ],

        'monitoring' => [
            'label' => 'Monitoring',
            'icon' => 'bx bx-desktop',
            'active_patterns' => [
                'admin.grades.*',
                'admin.journals.*',
                'admin.attendances.*',
            ],
            'children' => [
                'monitoring_nilai' => ['label' => 'Monitoring Nilai', 'route' => 'admin.grades.index', 'icon' => 'bx bx-notepad'],
                'monitoring_jurnal' => ['label' => 'Monitoring Jurnal', 'route' => 'admin.journals.index', 'icon' => 'bx bx-book-content'],
                'monitoring_absensi' => ['label' => 'Monitoring Absensi', 'route' => 'admin.attendances.index', 'icon' => 'bx bx-list-ul'],
            ],
        ],

        'raport' => [
            'label' => 'Raport',
            'icon' => 'bx bx-file',
            'active_patterns' => [
                'admin.report-cards.*',
                'admin.exports.*',
            ],
            'children' => [
                'preview_raport' => ['label' => 'Preview Raport', 'route' => 'admin.report-cards.index', 'icon' => 'bx bx-file'],
                'export_data' => ['label' => 'Export Data', 'route' => 'admin.exports.index', 'icon' => 'bx bx-download'],
            ],
        ],
    ],

    'kepala_sekolah' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],

        'data_akademik' => [
            'label' => 'Data Akademik',
            'icon' => 'bx bx-book',
            'active_patterns' => [
                'admin.teachers.*',
                'admin.students.*',
                'admin.guardians.*',
                'admin.levels.*',
                'admin.school-classes.*',
                'admin.subjects.*',
                'admin.academic-years.*',
                'admin.semesters.*',
            ],
            'children' => [
                'data_guru' => ['label' => 'Data Guru', 'route' => 'admin.teachers.index', 'icon' => 'bx bx-user'],
                'data_santri' => ['label' => 'Data Santri', 'route' => 'admin.students.index', 'icon' => 'bx bxs-user-rectangle'],
                'data_wali_santri' => ['label' => 'Data Wali Santri', 'route' => 'admin.guardians.index', 'icon' => 'bx bx-user-voice'],
                'jenjang_kelas' => ['label' => 'Jenjang', 'route' => 'admin.levels.index', 'icon' => 'bx bx-building'],
                'kelas' => ['label' => 'Kelas', 'route' => 'admin.school-classes.index', 'icon' => 'bx bx-layer'],
                'fan_mapel' => ['label' => 'Fan/Mapel', 'route' => 'admin.subjects.index', 'icon' => 'bx bx-book-open'],
                'tahun_ajaran' => ['label' => 'Tahun Ajaran', 'route' => 'admin.academic-years.index', 'icon' => 'bx bx-calendar'],
                'semester' => ['label' => 'Semester', 'route' => 'admin.semesters.index', 'icon' => 'bx bx-calendar-alt'],
            ],
        ],

        'monitoring' => [
            'label' => 'Monitoring',
            'icon' => 'bx bx-desktop',
            'active_patterns' => [
                'jadwal-pelajaran.*',
                'admin.grades.*',
                'admin.journals.*',
                'admin.attendances.*',
            ],
            'children' => [
                'jadwal_pelajaran' => [
                    'label' => 'Jadwal Pelajaran',
                    'icon' => 'bx bx-time',
                    'children' => [
                        'list_jadwal' => ['label' => 'List Semua Jadwal Pelajaran', 'route' => 'jadwal-pelajaran.index', 'icon' => 'bx bx-list-ul'],
                        'template_jadwal' => ['label' => 'Template Jadwal Pelajaran', 'route' => 'admin.jadwal-pelajaran.template', 'icon' => 'bx bx-table'],
                    ],
                ],
                'monitoring_nilai' => ['label' => 'Monitoring Nilai', 'route' => 'admin.grades.index', 'icon' => 'bx bx-notepad'],
                'monitoring_jurnal' => ['label' => 'Monitoring Jurnal', 'route' => 'admin.journals.index', 'icon' => 'bx bx-book-content'],
                'monitoring_absensi' => ['label' => 'Monitoring Absensi', 'route' => 'admin.attendances.index', 'icon' => 'bx bx-list-ul'],
            ],
        ],

        'raport' => [
            'label' => 'Raport',
            'icon' => 'bx bx-file',
            'active_patterns' => [
                'admin.report-cards.*',
                'admin.exports.*',
            ],
            'children' => [
                'preview_raport_arab' => ['label' => 'Preview Raport Arab', 'route' => 'admin.report-cards.index', 'icon' => 'bx bx-file'],
                'export_data' => ['label' => 'Export Data', 'route' => 'admin.exports.index', 'icon' => 'bx bx-download'],
            ],
        ],
    ],

    'wali_kelas' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],

        'data_santri_kelas' => ['label' => 'Data Santri Kelas', 'route' => 'wali_kelas.students.index', 'icon' => 'bx bxs-user-rectangle', 'active_patterns' => ['wali_kelas.students.*']],
        'jadwal_kelas' => ['label' => 'Jadwal Kelas', 'route' => 'jadwal-pelajaran.index', 'icon' => 'bx bx-time', 'active_patterns' => ['jadwal-pelajaran.*']],
        'absensi_santri' => ['label' => 'Absensi Santri', 'route' => 'homeroom.attendances.index', 'icon' => 'bx bx-list-ul', 'active_patterns' => ['homeroom.attendances.*']],

        'akademik_kelas' => [
            'label' => 'Akademik Kelas',
            'icon' => 'bx bx-book',
            'active_patterns' => [
                'homeroom.journals.*',
                'homeroom.attitudes.*',
                'homeroom.report-cards.*',
            ],
            'children' => [
                'jurnal_guru' => ['label' => 'Jurnal Guru', 'route' => 'homeroom.journals.index', 'icon' => 'bx bx-book-content', 'active_patterns' => ['homeroom.journals.*']],
                'nilai_sikap' => ['label' => 'Nilai Sikap', 'route' => 'homeroom.attitudes.index', 'icon' => 'bx bx-notepad', 'active_patterns' => ['homeroom.attitudes.*']],
                'raport_santri' => ['label' => 'Raport Santri', 'route' => 'homeroom.report-cards.index', 'icon' => 'bx bx-file', 'active_patterns' => ['homeroom.report-cards.*']],
            ],
        ],

        'laporan' => [
            'label' => 'Laporan',
            'icon' => 'bx bx-download',
            'active_patterns' => [
                'homeroom.exports.*',
            ],
            'children' => [
                'export_rekap' => ['label' => 'Export Rekap', 'route' => 'homeroom.exports.index', 'icon' => 'bx bx-download'],
            ],
        ],

        'profil' => ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'bx bx-user-circle'],
    ],

    'guru_fan' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'bx bx-home-circle'],

        'jadwal_mengajar' => ['label' => 'Jadwal Mengajar', 'route' => 'jadwal-pelajaran.index', 'icon' => 'bx bx-time', 'active_patterns' => ['jadwal-pelajaran.*']],
        'kelas_fan' => ['label' => 'Kelas & Fan/Mapel', 'route' => 'guru_fan.subjects.index', 'icon' => 'bx bx-book-open', 'active_patterns' => ['guru_fan.subjects.*']],
        'daftar_santri' => ['label' => 'Daftar Santri', 'route' => 'guru_fan.students.index', 'icon' => 'bx bxs-user-rectangle', 'active_patterns' => ['guru_fan.students.*']],

        'akademik' => [
            'label' => 'Akademik',
            'icon' => 'bx bx-book',
            'active_patterns' => [
                'teacher.journals.*',
                'teacher.grades.*',
                'teacher.attendances.*',
                'guru_fan.reports.*',
            ],
            'children' => [
                'jurnal_guru' => ['label' => 'Jurnal Guru', 'route' => 'teacher.journals.index', 'icon' => 'bx bx-book-content', 'active_patterns' => ['teacher.journals.*']],
                'submit_nilai' => ['label' => 'Submit Nilai', 'route' => 'teacher.grades.index', 'icon' => 'bx bx-edit', 'active_patterns' => ['teacher.grades.*']],
                'rekap_nilai' => ['label' => 'Rekap Nilai', 'route' => 'guru_fan.reports.index', 'icon' => 'bx bx-chart', 'active_patterns' => ['guru_fan.reports.*']],
            ],
        ],

        'laporan' => [
            'label' => 'Laporan',
            'icon' => 'bx bx-download',
            'active_patterns' => [
                'teacher.exports.*',
            ],
            'children' => [
                'export_nilai' => ['label' => 'Export Nilai', 'route' => 'teacher.exports.index', 'icon' => 'bx bx-download'],
            ],
        ],

        'profil' => ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'bx bx-user-circle'],
    ],

    'wali_santri' => [
        'dashboard' => ['label' => 'Dashboard', 'route' => 'guardian.dashboard', 'icon' => 'bx bx-home-circle'],
        'data_santri' => ['label' => 'Data Santri', 'route' => 'guardian.students.index', 'icon' => 'bx bxs-user-rectangle'],
        'absensi_santri' => ['label' => 'Absensi Santri', 'route' => 'guardian.attendances.index', 'icon' => 'bx bx-list-ul'],
        'raport_santri' => ['label' => 'Raport Santri', 'route' => 'wali_santri.reports.index', 'icon' => 'bx bx-file'],
        'download_raport' => ['label' => 'Download Raport', 'route' => 'wali_santri.downloads.index', 'icon' => 'bx bx-download'],
        'profil' => ['label' => 'Profil', 'route' => 'profile.edit', 'icon' => 'bx bx-user-circle'],
    ],
];
