<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: /trainer_profile/admin/login");
    exit;
}
require '../api/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Merriweather:wght@300;400;700&family=Open+Sans:wght@400;600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        resume: {
                            primary: '#5D1F2F',
                            accent: '#9d4edd',
                            blue: '#1e40af',
                            slate: '#334155'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'serif'],
                        mono: ['Roboto', 'sans-serif'],
                    }
                }
            }
        };
    </script>
    <style>
        /* A4 Paper for Preview (screen) */
        .a4-paper {
            width: 794px;           /* ~210mm at 96dpi */
            min-height: 1123px;     /* ~297mm */
            background: white;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* PRINT STYLES (from your first snippet, adapted) */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                background: white;
                margin: 0;
                padding: 0;
            }

            /* Hide everything except the preview modal */
            body > *:not(#preview-modal) {
                display: none !important;
            }

            #preview-modal {
                position: absolute !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: auto !important;
                background: white !important;
                z-index: 9999 !important;
                display: block !important;
                overflow: visible !important;
            }

            /* Hide modal header/footer in print */
            #preview-modal-header,
            #preview-modal-footer {
                display: none !important;
            }

            #preview-scroll-container {
                overflow: visible !important;
                height: auto !important;
                padding: 0 !important;
                background: white !important;
            }

            /* Make the resume full A4, remove scaling and shadow */
            .a4-paper {
                box-shadow: none !important;
                margin: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                min-height: 297mm !important;
                transform: none !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Set the PDF filename */
            @page {
                prince-pdf-title: attr(data-pdf-filename);
            }
            
            body::after {
                content: attr(data-pdf-filename);
                display: none;
            }
        }
        
        /* Loading animation */
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center sticky top-0 z-10">
        <h1 class="text-xl font-bold text-resume-primary flex items-center gap-2">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i> Admin Dashboard
        </h1>
        <div class="flex items-center gap-4">
            <span class="text-sm font-semibold text-black">Welcome, Admin</span>
            <form action="/trainer_profile/admin/logout" method="POST">
                <button type="submit" class="flex items-center gap-2 px-3 py-1.5 border rounded text-sm font-semibold hover:bg-gray-50" style="border-color:#5D1F2F; color:#5D1F2F;">
                    <span class="material-icons text-base">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto p-6">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h2 class="text-lg font-semibold text-gray-800">Trainer Profiles</h2>
            <div class="relative w-full md:w-auto">
                <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base">search</span>
                <input
                    type="text"
                    id="search-box"
                    placeholder="Search profiles..."
                    class="pl-9 pr-4 py-2 border rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-resume-primary w-full md:w-64">
            </div>
        </div>

        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full text-left">
                <thead class="bg-gray-50 border-b">
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-3 text-sm font-extrabold text-resume-primary uppercase">Name</th>
                        <th class="px-6 py-3 text-sm font-extrabold text-resume-primary uppercase">Role</th>
                        <th class="px-6 py-3 text-sm font-extrabold text-resume-primary uppercase">Email</th>
                        <th class="px-6 py-3 text-sm font-extrabold text-resume-primary uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200" id="trainer-table">
                    <!-- Dynamic Data Injected Here -->
                </tbody>
            </table>
        </div>
    </main>

    <!-- Preview Modal -->
    <div id="preview-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
        <div class="bg-white w-full h-full md:w-[95%] md:h-[95%] md:rounded-xl shadow-2xl flex flex-col overflow-hidden relative">
            <div id="preview-modal-header" class="px-6 py-4 border-b flex flex-col md:flex-row justify-between items-center bg-white z-10 gap-4">
                <h3 class="font-bold text-lg text-gray-800">Profile Preview</h3>

                <div class="flex items-center gap-2">
                    <button onclick="closePreview()" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-full">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
            <div id="preview-scroll-container" class="flex-1 overflow-auto bg-gray-200 p-2 sm:p-4 md:p-8 flex justify-center">
                <div id="resume-preview-container" class="a4-paper transform transition-transform origin-top scale-[0.55] sm:scale-75 md:scale-90 lg:scale-100"></div>
            </div>
            <div id="preview-modal-footer" class="px-6 py-4 border-t bg-white flex flex-wrap justify-end gap-3">
                <!-- Print button -->
                <button id="admin-print-btn" type="button" class="w-full sm:w-auto px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center justify-center gap-2 transition-all">
                    <span class="material-icons text-base">print</span>
                    Print / Save as PDF
                </button>
            </div>
        </div>
    </div>

    <script>
        // ============================
        // Resume Layouts Renderer
        // ============================
        window.resumeLayouts = {
            nl2br: function (str) {
                return (str || "").replace(/\n/g, "<br>");
            },

            buildPhotoHtml: function (photoUrl, initials, shape) {
                const shapeClass = shape === 'circle' ? 'rounded-full' : 'rounded-md';

                if (photoUrl && photoUrl !== 'null' && photoUrl !== '') {
                    return `
                        <div class="w-24 h-24 overflow-hidden ${shapeClass} border-4 border-white/60 shadow-md flex-shrink-0">
                            <img src="${photoUrl}" class="w-full h-full object-cover" />
                        </div>
                    `;
                } else {
                    return `
                        <div class="w-24 h-24 ${shapeClass} bg-white/20 border-4 border-white/60 flex items-center justify-center text-2xl font-bold uppercase flex-shrink-0">
                            ${initials || "?"}
                        </div>
                    `;
                }
            },

            renderExperience: function (experiences) {
                if (!experiences || !experiences.length) {
                    return `<p class="text-xs text-gray-500">No experience added.</p>`;
                }

                return experiences.map(exp => {
                    const position = exp.position || '';
                    const company = exp.company || '';
                    const location = exp.location || '';
                    const startDate = exp.start_date || exp.startDate || '';
                    const endDate = exp.end_date || exp.endDate || '';
                    const description = exp.description || '';

                    const dateStr = [startDate, endDate || "Present"].filter(Boolean).join(" - ");
                    const companyLine = [company, location].filter(Boolean).join(" • ");

                    return `
                        <div class="mb-3">
                            <div class="flex justify-between text-xs font-semibold text-gray-800">
                                <span>${position}</span>
                                <span class="text-gray-500">${dateStr}</span>
                            </div>
                            <div class="text-xs text-gray-600">${companyLine}</div>
                            ${description ? `<p class="text-xs text-gray-700 mt-1">${this.nl2br(description)}</p>` : ""}
                        </div>
                    `;
                }).join("");
            },

            renderEducation: function (education) {
                if (!education || !education.length) {
                    return `<p class="text-xs text-gray-500">No education added.</p>`;
                }

                return education.map(ed => {
                    const degree = ed.degree || '';
                    const institution = ed.institution || '';
                    const location = ed.location || '';
                    const startYear = ed.start_year || ed.startYear || '';
                    const endYear = ed.end_year || ed.endYear || '';
                    const details = ed.details || '';

                    const years = [startYear, endYear].filter(Boolean).join(" - ");
                    const instLine = [institution, location].filter(Boolean).join(" • ");

                    return `
                        <div class="mb-3">
                            <div class="flex justify-between text-xs font-semibold text-gray-800">
                                <span>${degree}</span>
                                <span class="text-gray-500">${years}</span>
                            </div>
                            <div class="text-xs text-gray-600">${instLine}</div>
                            ${details ? `<p class="text-xs text-gray-700 mt-1">${this.nl2br(details)}</p>` : ""}
                        </div>
                    `;
                }).join("");
            },

            renderSkills: function (skills) {
                if (!skills || !skills.length) {
                    return `<p class="text-xs text-gray-500">No skills added.</p>`;
                }

                return `
                    <ul class="space-y-1">
                        ${skills.map(s => {
                            const name = s.name || s.skill_name || '';
                            const level = s.level || s.skill_level || '';
                            return `<li class="flex justify-between text-xs">
                                <span>${name}</span>
                                <span class="text-gray-500">${level}</span>
                            </li>`;
                        }).join("")}
                    </ul>
                `;
            },

            renderLanguages: function (languages) {
                if (!languages || !languages.length) {
                    return `<p class="text-xs text-gray-500">No languages added.</p>`;
                }

                return `
                    <ul class="space-y-1">
                        ${languages.map(l => {
                            const name = l.name || l.language_name || '';
                            const level = l.level || l.language_level || '';
                            return `<li class="flex justify-between text-xs">
                                <span>${name}</span>
                                <span class="text-gray-500">${level}</span>
                            </li>`;
                        }).join("")}
                    </ul>
                `;
            },

            renderAchievements: function (achievements) {
                if (!achievements || !achievements.length) {
                    return `<p class="text-xs text-gray-500">No achievements added.</p>`;
                }

                return `
                    <div class="space-y-1 text-xs text-gray-700">
                        ${achievements.map(a => {
                            const text = typeof a === 'string' ? a : (a.text || '');
                            return `
                                <div class="flex items-start gap-1">
                                    <span class="mt-[3px] w-1.5 h-1.5 rounded-full bg-gray-700 flex-shrink-0"></span>
                                    <span>${this.nl2br(text)}</span>
                                </div>
                            `;
                        }).join("")}
                    </div>
                `;
            },

            generateLayout1: function (trainer, fontClass, photoShape, photoUrl) {
                const fullName = `${trainer.first_name || ''} ${trainer.last_name || ''}`.trim();
                const initials = (trainer.first_name || '').charAt(0) + (trainer.last_name || '').charAt(0);

                return `
                    <div class="w-full h-full flex flex-col ${fontClass}">
                        <div class="bg-resume-primary text-white px-8 py-6 flex items-center gap-6">
                            ${this.buildPhotoHtml(photoUrl, initials, photoShape)}
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold tracking-wide">${fullName || "Your Name"}</h1>
                                <p class="text-sm opacity-90 mt-1">${trainer.title || ""}</p>
                                <div class="flex flex-wrap gap-3 text-xs mt-3 opacity-90">
                                    ${trainer.email ? `<div class="flex items-center gap-1"><i data-lucide="mail" class="w-3 h-3"></i><span>${trainer.email}</span></div>` : ""}
                                    ${trainer.phone ? `<div class="flex items-center gap-1"><i data-lucide="phone" class="w-3 h-3"></i><span>${trainer.phone}</span></div>` : ""}
                                    ${trainer.location ? `<div class="flex items-center gap-1"><i data-lucide="map-pin" class="w-3 h-3"></i><span>${trainer.location}</span></div>` : ""}
                                </div>
                            </div>
                        </div>

                        <div class="flex-1 px-8 py-6 bg-white text-gray-800 text-sm">
                            <div class="grid grid-cols-3 gap-6 h-full">
                                <div class="col-span-2 space-y-4">
                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Profile</h2>
                                        <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                                        <p class="text-xs text-gray-700 leading-relaxed">${this.nl2br(trainer.summary || "")}</p>
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Experience</h2>
                                        <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                                        ${this.renderExperience(trainer.experience || [])}
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Achievements</h2>
                                        <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                                        ${this.renderAchievements(trainer.achievements || [])}
                                    </section>
                                </div>

                                <div class="space-y-4">
                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Education</h2>
                                        <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                                        ${this.renderEducation(trainer.education || [])}
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Skills</h2>
                                        <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                                        ${this.renderSkills(trainer.skills || [])}
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Languages</h2>
                                        <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                                        ${this.renderLanguages(trainer.languages || [])}
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            },

            generateLayout2: function (trainer, fontClass, photoShape, photoUrl) {
                const fullName = `${trainer.first_name || ''} ${trainer.last_name || ''}`.trim();
                const initials = (trainer.first_name || '').charAt(0) + (trainer.last_name || '').charAt(0);

                return `
                    <div class="w-full h-full flex ${fontClass}">
                        <!-- Sidebar -->
                        <div class="w-[32%] bg-resume-blue text-white px-6 py-8 flex flex-col gap-6">
                            <div class="flex flex-col items-center text-center gap-2">
                                ${this.buildPhotoHtml(photoUrl, initials, photoShape)}
                                <h1 class="text-lg font-semibold mt-3">${fullName || "Your Name"}</h1>
                                <p class="text-xs opacity-90">${trainer.title || ""}</p>
                            </div>

                            <div class="text-xs space-y-3">
                                <h2 class="uppercase text-[10px] tracking-widest font-semibold opacity-80">Contact</h2>
                                <div class="space-y-1.5">
                                    ${trainer.email ? `<div class="flex items-center gap-2"><i data-lucide="mail" class="w-3 h-3"></i><span class="break-all">${trainer.email}</span></div>` : ""}
                                    ${trainer.phone ? `<div class="flex items-center gap-2"><i data-lucide="phone" class="w-3 h-3"></i><span>${trainer.phone}</span></div>` : ""}
                                    ${trainer.location ? `<div class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3 h-3"></i><span>${trainer.location}</span></div>` : ""}
                                </div>
                            </div>

                            <div class="text-xs space-y-3">
                                <h2 class="uppercase text-[10px] tracking-widest font-semibold opacity-80">Skills</h2>
                                ${this.renderSkills(trainer.skills || [])}
                            </div>

                            <div class="text-xs space-y-3">
                                <h2 class="uppercase text-[10px] tracking-widest font-semibold opacity-80">Languages</h2>
                                ${this.renderLanguages(trainer.languages || [])}
                            </div>
                        </div>

                        <!-- Main content -->
                        <div class="flex-1 bg-white px-8 py-8 text-sm text-gray-800 space-y-5">
                            <section>
                                <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Profile</h2>
                                <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                                <p class="text-xs text-gray-700 leading-relaxed">${this.nl2br(trainer.summary || "")}</p>
                            </section>

                            <section>
                                <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Experience</h2>
                                <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                                ${this.renderExperience(trainer.experience || [])}
                            </section>

                            <section>
                                <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Education</h2>
                                <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                                ${this.renderEducation(trainer.education || [])}
                            </section>

                            <section>
                                <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Achievements</h2>
                                <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                                ${this.renderAchievements(trainer.achievements || [])}
                            </section>
                        </div>
                    </div>
                `;
            },

            generateLayout3: function (trainer, fontClass, photoShape, photoUrl) {
                const fullName = `${trainer.first_name || ''} ${trainer.last_name || ''}`.trim();
                const initials = (trainer.first_name || '').charAt(0) + (trainer.last_name || '').charAt(0);

                const shapeClass = photoShape === 'circle' ? 'rounded-full' : 'rounded-md';
                const photoBlock = (photoUrl && photoUrl !== 'null' && photoUrl !== '') ?
                    `<img src="${photoUrl}" class="w-20 h-20 object-cover ${shapeClass} border border-gray-300" />` :
                    `<div class="w-20 h-20 ${shapeClass} bg-gray-200 border border-gray-300 flex items-center justify-center text-xl font-bold uppercase text-gray-600">${initials || "?"}</div>`;

                return `
                    <div class="w-full h-full flex flex-col ${fontClass}">
                        <div class="px-8 py-6 bg-gray-50 border-b flex items-center gap-6">
                            ${photoBlock}
                            <div class="flex-1">
                                <h1 class="text-2xl font-bold text-gray-900">${fullName || "Your Name"}</h1>
                                <p class="text-sm text-gray-700 mt-1">${trainer.title || ""}</p>
                            </div>
                            <div class="text-xs text-gray-600 space-y-1">
                                ${trainer.email ? `<div class="flex items-center gap-2"><i data-lucide="mail" class="w-3 h-3"></i><span class="break-all">${trainer.email}</span></div>` : ""}
                                ${trainer.phone ? `<div class="flex items-center gap-2"><i data-lucide="phone" class="w-3 h-3"></i><span>${trainer.phone}</span></div>` : ""}
                                ${trainer.location ? `<div class="flex items-center gap-2"><i data-lucide="map-pin" class="w-3 h-3"></i><span>${trainer.location}</span></div>` : ""}
                            </div>
                        </div>

                        <div class="flex-1 px-8 py-6 bg-white text-gray-800 text-sm">
                            <section class="mb-4">
                                <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Profile</h2>
                                <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                                <p class="text-xs text-gray-700 leading-relaxed">${this.nl2br(trainer.summary || "")}</p>
                            </section>

                            <div class="grid grid-cols-3 gap-6">
                                <div class="col-span-2 space-y-4">
                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Experience</h2>
                                        <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                                        ${this.renderExperience(trainer.experience || [])}
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Achievements</h2>
                                        <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                                        ${this.renderAchievements(trainer.achievements || [])}
                                    </section>
                                </div>

                                <div class="space-y-4">
                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Education</h2>
                                        <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                                        ${this.renderEducation(trainer.education || [])}
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Skills</h2>
                                        <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                                        ${this.renderSkills(trainer.skills || [])}
                                    </section>

                                    <section>
                                        <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Languages</h2>
                                        <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                                        ${this.renderLanguages(trainer.languages || [])}
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        };

        // Global current trainer
        window.currentTrainer = null;
        window.currentFileName = 'trainer_profile';

        function closePreview() {
            $('#preview-modal').addClass('hidden');
        }

        function loadTrainers(query = '') {
            $.ajax({
                url: '../api/search_trainers.php',
                type: 'GET',
                data: { search: query },
                success: function (data) {
                    $('#trainer-table').html(data);
                }
            });
        }

        $('#search-box').on('input', function () {
            loadTrainers($(this).val());
        });

        loadTrainers();

        function viewTrainer(id) {
            $.ajax({
                url: '../api/search_trainers.php',
                type: 'GET',
                data: { view_id: id },
                dataType: 'json',
                success: function (trainer) {
                    if (!trainer) {
                        alert('Trainer not found!');
                        return;
                    }

                    window.currentTrainer = trainer;
                    
                    // Generate filename from first name
                    const firstName = trainer.first_name || '';
                    if (firstName) {
                        // Clean filename: remove special characters, keep only letters, numbers, underscores, hyphens
                        const cleanName = firstName.replace(/[^a-zA-Z0-9_\-]/g, '_').toLowerCase();
                        window.currentFileName = cleanName + '_profile';
                    } else {
                        window.currentFileName = 'trainer_profile';
                    }

                    const savedTemplate = trainer.template || '1';
                    const savedFont = trainer.font || 'font-sans';
                    const savedPhotoShape = trainer.photo_shape || 'circle';
                    const photoUrl = trainer.photo ? '/trainer_profile/uploads/' + trainer.photo : null;

                    renderTrainerPreview(trainer, savedTemplate, savedFont, savedPhotoShape, photoUrl);

                    $('#preview-modal').removeClass('hidden');
                },
                error: function (xhr, status, error) {
                    console.error('Error loading trainer:', error);
                    alert('Error loading trainer data. Please try again.');
                }
            });
        }

        function renderTrainerPreview(trainer, template, fontClass, photoShape, photoUrl) {
            let html = '';

            switch (template) {
                case '2':
                    html = resumeLayouts.generateLayout2(trainer, fontClass, photoShape, photoUrl);
                    break;
                case '3':
                    html = resumeLayouts.generateLayout3(trainer, fontClass, photoShape, photoUrl);
                    break;
                case '1':
                default:
                    html = resumeLayouts.generateLayout1(trainer, fontClass, photoShape, photoUrl);
                    break;
            }

            $('#resume-preview-container').html(html);
            if (window.lucide) lucide.createIcons();
        }

        // PRINT HANDLER with filename support
        document.addEventListener('DOMContentLoaded', function () {
            const printBtn = document.getElementById('admin-print-btn');
            if (!printBtn) return;

            printBtn.addEventListener('click', function () {
                if (!window.currentTrainer) {
                    alert('No trainer profile loaded.');
                    return;
                }
                
                // Store original button content
                const originalHTML = printBtn.innerHTML;
                printBtn.innerHTML = '<span class="material-icons text-base animate-spin">sync</span> Preparing...';
                printBtn.disabled = true;
                
                // Small delay to ensure UI updates
                setTimeout(() => {
                    try {
                        // Set document title to desired filename
                        const originalTitle = document.title;
                        document.title = window.currentFileName;
                        
                        // Add a data attribute to body for print filename
                        document.body.setAttribute('data-pdf-filename', window.currentFileName);
                        
                        // Add print-specific CSS for filename
                        const printStyle = document.createElement('style');
                        printStyle.id = 'print-filename-style';
                        printStyle.textContent = `
                            @media print {
                                @page {
                                    prince-pdf-title: "${window.currentFileName}";
                                }
                                body::after {
                                    content: "${window.currentFileName}";
                                    display: none;
                                }
                            }
                        `;
                        document.head.appendChild(printStyle);
                        
                        // Trigger print
                        window.print();
                        
                        // Clean up after printing
                        setTimeout(() => {
                            document.title = originalTitle;
                            document.body.removeAttribute('data-pdf-filename');
                            const styleEl = document.getElementById('print-filename-style');
                            if (styleEl) {
                                styleEl.remove();
                            }
                        }, 100);
                        
                    } catch (err) {
                        console.error('Print error:', err);
                        alert('Failed to open print dialog. Please use Ctrl+P or Cmd+P instead.');
                    } finally {
                        // Restore button
                        printBtn.innerHTML = originalHTML;
                        printBtn.disabled = false;
                    }
                }, 100);
            });
        });
        
        // Alternative print method using iframe for better filename control
        function printWithCustomFilename() {
            if (!window.currentTrainer) {
                alert('No trainer profile loaded.');
                return;
            }
            
            const printBtn = document.getElementById('admin-print-btn');
            const originalHTML = printBtn.innerHTML;
            printBtn.innerHTML = '<span class="material-icons text-base animate-spin">sync</span> Preparing...';
            printBtn.disabled = true;
            
            setTimeout(() => {
                try {
                    const resumeContent = document.getElementById('resume-preview-container').innerHTML;
                    const iframe = document.createElement('iframe');
                    iframe.style.position = 'absolute';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = 'none';
                    document.body.appendChild(iframe);
                    
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    iframeDoc.open();
                    iframeDoc.write(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>${window.currentFileName}</title>
                            <style>
                                @page {
                                    size: A4;
                                    margin: 0;
                                }
                                body {
                                    margin: 0;
                                    padding: 0;
                                }
                                .a4-paper {
                                    width: 210mm;
                                    height: 297mm;
                                    background: white;
                                    margin: 0;
                                    overflow: hidden;
                                    -webkit-print-color-adjust: exact;
                                    print-color-adjust: exact;
                                }
                                ${document.querySelector('style').textContent}
                            </style>
                        </head>
                        <body> 
                            <div class="a4-paper">
                                ${resumeContent}
                            </div>
                            <script>
                                window.onload = function() {
                                    window.print();
                                    setTimeout(function() {
                                        window.parent.postMessage('printComplete', '*');
                                    }, 100);
                                };
                            <\/script>
                        </body>
                        </html>
                    `);
                    iframeDoc.close();
                    // Listen for print completion
                    window.addEventListener('message', function handler(event) {
                        if (event.data === 'printComplete') {
                            document.body.removeChild(iframe);
                            window.removeEventListener('message', handler);
                        }
                    });
                    
                } catch (err) {
                    console.error('Print error:', err);
                    alert('Failed to generate print preview.');
                } finally {
                    printBtn.innerHTML = originalHTML;
                    printBtn.disabled = false;
                }
            }, 100);
        }
    </script>
    <script>
        if (window.lucide) lucide.createIcons();
    </script>
</body>
</html>