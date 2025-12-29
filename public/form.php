<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainer Profile Builder</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&family=Merriweather:wght@300;400;700&family=Open+Sans:wght@400;600&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#ec1d25',
                        secondary: '#ffe500',
                        resume: {
                            primary: '#ec1d25',
                            accent: '#9d4edd',
                            blue: '#1e40af',
                            slate: '#334155'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        serif: ['Merriweather', 'serif'],
                        mono: ['Roboto', 'sans-serif'],
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'float': 'float 6s ease-in-out infinite',
                        'gradient': 'gradient 8s ease infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                        gradient: {
                            '0%, 100%': { backgroundPosition: '0% 50%' },
                            '50%': { backgroundPosition: '100% 50%' },
                        }
                    }
                }
            }
        }   </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* A4 Paper Size for Preview */
        .a4-paper {
            width: 210mm;
            height: 297mm;
            background: white;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
        }

        /* Print Styles */
        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                background: white;

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
        }

        /* Validation Styles */
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
        }

        .input-error:focus {
            border-color: #000000 !important;
            box-shadow: 0 0 0 1px #000000 !important;
        }

        .input-success {
            border-color: #10b981 !important;
        }

        /* Shake Animation for Errors */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .shake {
            animation: shake 0.4s ease-in-out;
        }

        /* Fade In Animation for Messages */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</head>
<body class="font-sans antialiased m-0">

<!-- Header -->
<header class="relative w-full overflow-hidden mb-12 m-0">

    <!-- RED STRIP -->
    <div class="w-full bg-red-700 shadow-2xl">
        <div class="relative flex items-center justify-center h-20 pr-14 md:pr-24">

            <!-- Centered Title -->
            <h1 class="pl-6 md:pl-0 text-2xl md:text-5xl font-extrabold tracking-widest text-yellow-300 uppercase">
                Trainee Profile Builder
            </h1>


            <!-- ✅ FIXED ADMIN LOGIN -->
            <a
                href="/trainer_profile/admin/login"
                class="absolute right-3 md:right-6 flex items-center gap-2
                       px-3 md:px-5 py-2 rounded-full
                       text-sm font-bold text-black shadow-xl
                       bg-gradient-to-r from-yellow-400 to-yellow-300
                       hover:scale-105 hover:shadow-2xl transition-all duration-300"
                title="Admin Login"
            >
                <!-- Person icon (always visible) -->
                <i data-lucide="user" class="w-5 h-5"></i>

                <!-- Text hidden on mobile -->
                <span class="hidden md:inline">Admin Login</span>
            </a>

        </div>
    </div>

    <!-- YELLOW STRIP -->
    <div class="w-full bg-yellow-400 py-4 shadow-xl">
        <p class="text-sm md:text-base font-semibold tracking-wide text-black text-center">
            Create Your Professional Trainer Profile In Minutes
        </p>
    </div>

</header>


<!-- MAIN CENTER WRAPPER -->
<div class="w-full flex justify-center px-4 mb-16">
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col min-h-[600px]">

        
        <!-- Progress Bar -->
       <div class="bg-[#fee452ff] p-4 md:p-6 border-b border-gray-100">


            <div class="flex justify-between items-center relative max-w-2xl mx-auto">
                <!-- Line -->
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-0 -translate-y-1/2"></div>
                <div id="progress-fill" class="absolute top-1/2 left-0 h-1 bg-resume-primary -z-0 -translate-y-1/2 transition-all duration-300 w-0"></div>

                <!-- Steps -->
                <div class="step-indicator relative z-10 bg-[#fee452ff] px-1 md:px-2 flex flex-col items-center gap-1 active" data-step="1">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-resume-primary text-white flex items-center justify-center font-bold text-xs md:text-sm">1</div>
                    <span class="text-[10px] md:text-xs font-medium text-black">Personal</span>
                </div>
                <div class="step-indicator relative z-10 bg-[#fee452ff] px-1 md:px-2 flex flex-col items-center gap-1" data-step="2">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">2</div>
                    <span class="text-[10px] md:text-xs font-medium text-black">Experience</span>
                </div>
                <div class="step-indicator relative z-10 bg-[#fee452ff] px-1 md:px-2 flex flex-col items-center gap-1" data-step="3">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">3</div>
                    <span class="text-[10px] md:text-xs font-medium text-black">Education</span>
                </div>
                <div class="step-indicator relative z-10 bg-[#fee452ff] px-1 md:px-2 flex flex-col items-center gap-1" data-step="4">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">4</div>
                    <span class="text-[10px] md:text-xs font-medium text-black">Skills</span>
                </div>
                <div class="step-indicator relative z-10 bg-[#fee452ff] px-1 md:px-2 flex flex-col items-center gap-1" data-step="5">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">5</div>
                    <span class="text-[1px] md:text-xs font-medium text-black">Finish</span>
                </div>
            </div>
        </div>

        <!-- Form Steps Content -->
        <div class="flex-1 p-4 md:p-8 overflow-y-auto">
            <form id="main-form" novalidate>
                
                <!-- Step 1: Personal Info -->
                <div class="form-step block" id="step-1">
                    <h2 class="text-2xl font-bold text-black mb-4">
                        Personal Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="form-group">
                            <label class="block text-ls font-medium text-black mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input
                        type="text"
                        name="firstName"
                        id="firstName"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                        required
                        data-validation="required"
                    />
                            <div class="validation-message"></div>
                        </div>

                        <div class="form-group">
                            <label class="block text-ls font-medium text-black mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="lastName"
                                id="lastName"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                                required
                                data-validation="required"
                            />
                            <div class="validation-message"></div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                            <label class="block text-ls font-medium text-black mb-1">
                            Professional Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            placeholder="e.g. Senior Corporate Trainer"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                            required
                            data-validation="required"
                        />
                        <div class="validation-message"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="form-group">
                            <label class="block text-ls font-medium text-black mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="your.email@example.com"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                                required
                                data-validation="required|email"
                            />
                            <div class="validation-message"></div>
                        </div>

                        <div class="form-group">
                            <label class="block text-ls font-medium text-black mb-1">
                                Phone <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="tel"
                                name="phone"
                                id="phone"
                                placeholder="10-digit mobile number"
                                maxlength="10"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                                required
                                data-validation="required|phone"
                            />
                            <div class="validation-message"></div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                            <label class="block text-ls font-medium text-black mb-1">
                            Location
                        </label>
                        <input
                            type="text"
                            name="location"
                            id="location"
                            placeholder="City, State/Country"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                            data-validation="maxLength:100"
                        />
                        <div class="validation-message"></div>
                    </div>

                    <div class="form-group mb-4">
                            <label class="block text-ls font-medium text-black mb-1">
                            Professional Summary <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="summary"
                            id="summary"
                            rows="3"
                            placeholder="Briefly describe your experience and expertise..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-base
                            focus:border-black focus:ring-0
                            outline-none transition-colors"
                            required
                            data-validation="required"
                        ></textarea>
                        <div class="flex justify-between items-center mt-1">
                            <div class="validation-message"></div>
                            <span class="text-xs text-gray-500" id="summary-counter">0 / 500</span>
                        </div>
                    </div>

                    <div class="form-group">
                            <label class="block text-ls font-medium text-black mb-1">
                            Profile Photo <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="file"
                            id="photo-upload"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                            class="block w-full text-sm text-gray-500
                                    file:mr-3 file:py-1.5 file:px-3
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-medium
                                    file:bg-resume-primary file:text-white
                                    hover:file:bg-opacity-90 cursor-pointer"
                            required
                        />
                        <div class="validation-message"></div>
                    </div>
                </div>

                <!-- Step 2: Experience -->
                <div class="form-step hidden" id="step-2">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Work Experience</h2>
                    <div id="experience-container" class="space-y-6">
                        <!-- Dynamic Items will be added here -->
                    </div>
                    <button type="button" onclick="addExperience()" class="mt-4 px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Experience
                    </button>
                    <div id="experience-validation" class="validation-message mt-4"></div>
                </div>

                <!-- Step 3: Education -->
                <div class="form-step hidden" id="step-3">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Education</h2>
                    <div id="education-container" class="space-y-6">
                        <!-- Dynamic Items will be added here -->
                    </div>
                    <button type="button" onclick="addEducation()" class="mt-4 px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Education
                    </button>
                    <div id="education-validation" class="validation-message mt-4"></div>
                </div>

                <!-- Step 4: Skills & Languages -->
                <div class="form-step hidden" id="step-4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Skills & Languages</h2>
                    
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Skills</h3>
                        <div id="skills-container" class="space-y-3"></div>
                        <button type="button" onclick="addSkill()" class="mt-2 px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Skill
                        </button>
                        <div id="skills-validation" class="validation-message mt-2"></div>
                    </div>

                    <hr class="border-gray-200 my-6">

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-700 mb-3">Languages</h3>
                        <div id="languages-container" class="space-y-3"></div>
                        <button type="button" onclick="addLanguage()" class="mt-2 px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add Language
                        </button>
                        <div id="languages-validation" class="validation-message mt-2"></div>
                    </div>
                </div>

                <!-- Step 5: Achievements & Finish -->
                <div class="form-step hidden" id="step-5">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Achievements</h2>
                    
                    <div id="achievements-container" class="space-y-3 mb-8"></div>
                    <button type="button" onclick="addAchievement()" class="mt-2 text-sm text-resume-primary font-medium hover:underline flex items-center gap-1">
                        <i data-lucide="plus" class="w-3 h-3"></i> Add Achievement
                    </button>

                    <div class="mt-10 bg-blue-50 p-6 rounded-lg border border-blue-100">
                        <h3 class="text-lg font-bold text-blue-800 mb-2">Ready to Preview?</h3>
                        <p class="text-blue-600 text-sm">You've completed all sections. Click "Preview &amp; Submit" to generate your resume, choose a template, and download it.</p>
                    </div>
                </div>

            </form>
        </div>

        <!-- Footer Navigation -->
       <div class="p-4 md:p-6 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-3">
        <button type="button" id="prev-btn" 
            class="w-full sm:w-auto px-6 py-3 rounded-lg border-2 border-transparent text-red-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center justify-center gap-2 hover:border-red-600" 
            disabled>
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Previous
        </button>

        <button type="button" id="next-btn" 
            class="w-full sm:w-auto px-6 py-3 rounded-lg border-2 border-transparent text-red-600 flex items-center justify-center gap-2 transition-all hover:border-red-600"> 
            Next Step <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
        </div >
    </div>
    </div>

    </div>
</div>

<!-- PREVIEW MODAL -->
<div id="preview-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center">
    <div class="bg-white w-full h-full md:w-[95%] md:h-[95%] md:rounded-xl shadow-2xl flex flex-col overflow-hidden relative">
        
        <!-- Modal Header -->
        <div id="preview-modal-header" class="px-4 md:px-6 py-3 md:py-4 border-b flex flex-col md:flex-row justify-between items-center bg-white z-10 gap-3 md:gap-4">
            <h3 class="font-bold text-lg text-gray-800">Profile Preview</h3>
            
            <!-- Customization Toolbar -->
            <div class="flex flex-wrap items-center gap-3 md:gap-4 text-xs md:text-sm bg-gray-50 p-2 rounded-lg border border-gray-200 max-w-full">
                <!-- Layout Selector -->
                <div class="flex items-center gap-1 md:gap-2">
                    <span class="text-gray-500 font-medium whitespace-nowrap">Layout:</span>
                    <select id="layout-selector" class="border rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-resume-primary text-xs md:text-sm">
                        <option value="1">Layout 1 (Burgundy)</option>
                        <option value="2">Layout 2 (Blue Sidebar)</option>
                        <option value="3">Layout 3 (Classic Top)</option>
                    </select>
                </div>

                <!-- Font Selector -->
                <div class="flex items-center gap-1 md:gap-2">
                    <span class="text-gray-500 font-medium whitespace-nowrap">Font:</span>
                    <select id="font-selector" class="border rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-resume-primary text-xs md:text-sm">
                        <option value="font-sans">Inter (Sans)</option>
                        <option value="font-serif">Merriweather (Serif)</option>
                        <option value="font-mono">Roboto (Modern)</option>
                    </select>
                </div>

                <!-- Photo Shape -->
                <div class="flex items-center gap-1 md:gap-2">
                    <span class="text-gray-500 font-medium whitespace-nowrap">Photo:</span>
                    <div class="flex bg-white rounded border border-gray-200 overflow-hidden text-xs">
                        <button type="button" class="px-2 md:px-3 py-1 hover:bg-gray-100 font-medium border-r" onclick="setPhotoShape('square')">Square</button>
                        <button type="button" class="px-2 md:px-3 py-1 hover:bg-gray-100 font-medium" onclick="setPhotoShape('circle')">Circle</button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="closePreview()" class="p-2 text-gray-500 hover:text-gray-800 hover:bg-gray-100 rounded-full">
                    <i data-lucide="x" class="w-5 h-5 md:w-6 md:h-6"></i>
                </button>
            </div>
        </div>

        <!-- Modal Content (Scrollable) -->
        <div id="preview-scroll-container" class="flex-1 overflow-auto bg-gray-200 p-2 sm:p-4 md:p-8 flex justify-center">
            <!-- THE RESUME CONTAINER (A4) -->
            <div id="resume-preview-container" class="a4-paper">
                <!-- Content injected by JS -->
            </div>
        </div> 

        <!-- Modal Footer -->
        <div id="preview-modal-footer" class="px-4 md:px-6 py-3 md:py-4 border-t bg-white flex flex-wrap justify-end gap-2 md:gap-3">
            <button id="print-btn" type="button" class="w-full sm:w-auto px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center justify-center gap-2 transition-all">
                <i data-lucide="printer" class="w-4 h-4"></i> Print/Download
            </button>

            <button onclick="submitProfile()" class="px-4 md:px-6 py-2 rounded-md bg-resume-primary text-white text-sm md:text-base font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                <i data-lucide="check" class="w-4 h-4"></i> Submit Profile
            </button>
        </div>
    </div>
</div>

<!-- Validation & App Script -->
<script>
// ==========================================
// VALIDATION SYSTEM (GLOBAL)
// ==========================================

const Validator = {
    rules: {
        required: (value) => {
            if (value === null || value === undefined) return false;
            if (value instanceof File) return true;
            return String(value).trim() !== '';
        },
        email: (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value),
        phone: (value) => {
            const digits = String(value || '').replace(/\D/g, '');
            return /^[\d\s\-\+\(\)]+$/.test(value) && digits.length >= 10;
        },
        minLength: (value, min) => String(value || '').length >= Number(min),
        maxLength: (value, max) => String(value || '').length <= Number(max),
        fileSize: (file, maxKB) => !file || file.size <= maxKB * 1024,
        fileType: (file, types) => {
            if (!file) return true;
            const allowedTypes = types.split(',');
            return allowedTypes.some(type => file.type === type || file.type.startsWith(type));
        },
        dateGreaterThan: (value, otherFieldId) => {
            if (!value) return true;
            const otherInput = document.getElementById(otherFieldId);
            if (!otherInput || !otherInput.value) return true;
            return new Date(value) > new Date(otherInput.value);
        },
        yearGreaterThan: (value, otherFieldId) => {
            if (!value) return true;
            const otherInput = document.getElementById(otherFieldId);
            if (!otherInput || !otherInput.value) return true;
            return parseInt(value) > parseInt(otherInput.value);
        }
    },

    messages: {
        required: 'This field is required',
        email: 'Please enter a valid email address',
        phone: 'Please enter a valid phone number',
        minLength: 'Minimum {min} characters required',
        maxLength: 'Maximum {max} characters allowed',
        fileSize: 'File size must be less than {maxKB}KB',
        fileType: 'Invalid file type. Only image files (JPG, PNG, GIF) are allowed',
        dateGreaterThan: 'End date must be after start date',
        yearGreaterThan: 'End year must be after start year'
    },

    validate(input) {
        const validations = input.dataset.validation;
        if (!validations) return { valid: true };

        const rules = validations.split('|').filter(Boolean);
        const value = input.type === 'file' ? (input.files[0] || null) : input.value;

        for (let rule of rules) {
            const [ruleName, ...params] = rule.split(':');
            if (!ruleName || !this.rules[ruleName]) continue;

            const ruleParams = params.length ? params[0].split(',') : [];
            const isValid = this.rules[ruleName](value, ...ruleParams);

            if (!isValid) {
                let message = this.messages[ruleName] || 'Invalid value';
                if (ruleParams.length) {
                    message = message.replace('{min}', ruleParams[0]);
                    message = message.replace('{max}', ruleParams[0]);
                    message = message.replace('{maxKB}', ruleParams[0]);
                    message = message.replace('{type}', ruleParams[0]);
                }
                return { valid: false, message };
            }
        }

        return { valid: true };
    },

    showError(input, message) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;

        input.classList.add('input-error');
        input.classList.remove('input-success');

        const validationDiv = formGroup.querySelector('.validation-message');
        if (validationDiv) {
            validationDiv.innerHTML = `
                <div class="flex items-start gap-2 mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-md p-2 fade-in">
                    <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span>${message}</span>
                </div>
            `;
            if (window.lucide) lucide.createIcons();
        }

        input.classList.add('shake');
        setTimeout(() => input.classList.remove('shake'), 400);
    },

    showSuccess(input) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;

        input.classList.remove('input-error');
        input.classList.add('input-success');

        const validationDiv = formGroup.querySelector('.validation-message');
        if (validationDiv) {
            validationDiv.innerHTML = '';
        }
    },

    clearValidation(input) {
        const formGroup = input.closest('.form-group');
        if (!formGroup) return;

        input.classList.remove('input-error', 'input-success');
        const validationDiv = formGroup.querySelector('.validation-message');
        if (validationDiv) {
            validationDiv.innerHTML = '';
        }
    }
};

// Global alert function
function showAlert(message, type = 'info', duration = 5000) {
    const container = document.getElementById('global-alert-container');
    
    const colors = {
        success: 'bg-green-50 border-green-200 text-green-800',
        error: 'bg-red-50 border-red-200 text-red-800',
        warning: 'bg-yellow-50 border-yellow-200 text-yellow-800',
        info: 'bg-blue-50 border-blue-200 text-blue-800'
    };

    const icons = {
        success: 'check-circle',
        error: 'x-circle',
        warning: 'alert-triangle',
        info: 'info'
    };

    const alertId = 'alert-' + Date.now();
    const alertHTML = `
        <div id="${alertId}" class="flex items-start gap-3 p-4 rounded-lg border shadow-lg ${colors[type]} fade-in">
            <i data-lucide="${icons[type]}" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-current opacity-70 hover:opacity-100">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', alertHTML);
    if (window.lucide) lucide.createIcons();

    if (duration > 0) {
        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                alert.style.transition = 'all 0.3s ease-out';
                setTimeout(() => alert.remove(), 300);
            }
        }, duration);
    }
}

// Function to get trainer's first name
function getTrainerFirstName() {
    const firstNameInput = document.getElementById('firstName');
    if (firstNameInput && firstNameInput.value.trim()) {
        // Clean the filename: remove special characters and spaces
        const firstName = firstNameInput.value.trim();
        return firstName.replace(/[^a-zA-Z0-9_\-]/g, '_').toLowerCase();
    }
    return 'trainer_profile';
}

// Print functionality with custom filename
function setupPrintFunctionality() {
    const printBtn = document.getElementById('print-btn');
    if (!printBtn) return;

    printBtn.addEventListener('click', function() {
        const originalHTML = printBtn.innerHTML;
        printBtn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Preparing...';
        printBtn.disabled = true;
        
        if (window.lucide) lucide.createIcons();

        // Small delay to ensure UI updates
        setTimeout(() => {
            try {
                // Ensure all icons are rendered
                if (window.lucide) {
                    lucide.createIcons();
                }
                
                // Get trainer's first name for filename
                const firstName = getTrainerFirstName();
                
                // Create a custom print event to set filename
                const beforePrint = () => {
                    // Add custom CSS for print filename
                    const style = document.createElement('style');
                    style.id = 'print-filename-style';
                    style.textContent = `
                        @media print {
                            @page {
                                prince-pdf-title: "${firstName}_Profile";
                            }
                        }
                    `;
                    document.head.appendChild(style);
                };
                
                const afterPrint = () => {
                    // Clean up
                    const styleEl = document.getElementById('print-filename-style');
                    if (styleEl) {
                        styleEl.remove();
                    }
                };
                
                // Add event listeners for print
                window.addEventListener('beforeprint', beforePrint);
                window.addEventListener('afterprint', afterPrint);
                
                // For browsers that support the title attribute in print dialog
                // We'll use a small hack to set the document title temporarily
                const originalTitle = document.title;
                document.title = `${firstName.charAt(0).toUpperCase() + firstName.slice(1)}_Profile`;
                
                // Trigger the browser's print dialog
                window.print();
                
                // Restore original title
                document.title = originalTitle;
                
                // Remove event listeners
                window.removeEventListener('beforeprint', beforePrint);
                window.removeEventListener('afterprint', afterPrint);
                
               
                
            } catch (err) {
                console.error('Print error:', err);
                showAlert('Failed to open print dialog. Please use Ctrl+P or Cmd+P instead.', 'error', 5000);
            } finally {
                printBtn.innerHTML = originalHTML;
                printBtn.disabled = false;
                if (window.lucide) lucide.createIcons();
            }
        }, 100);
    });
}

// Alternative approach: Using iframe for better filename control
function printWithFilename() {
    const firstName = getTrainerFirstName();
    const resumeContainer = document.getElementById('resume-preview-container');
    
    if (!resumeContainer) {
        showAlert('No resume content found.', 'error', 3000);
        return;
    }
    
    // Create a temporary iframe
    const iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);
    
    // Write the resume content to iframe
    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
    iframeDoc.open();
    iframeDoc.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>${firstName}_profile</title>
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
                ${resumeContainer.innerHTML}
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
}

// Live validation setup for static fields
function setupLiveValidation() {
    const inputs = document.querySelectorAll('[data-validation]');
    
    inputs.forEach(input => {
        // Validate on blur
        input.addEventListener('blur', function() {
            const result = Validator.validate(this);
            const isRequired = this.hasAttribute('required') || (this.dataset.validation || '').includes('required');

            if (!result.valid && (this.value.trim() !== '' || isRequired)) {
                Validator.showError(this, result.message);
            } else if (this.value.trim() !== '') {
                Validator.showSuccess(this);
            }
        });

        // Clear error on focus
        input.addEventListener('focus', function() {
            if (this.classList.contains('input-error')) {
                Validator.clearValidation(this);
            }
        });

        // Real-time validation for certain fields
        if (input.type === 'email' || input.type === 'tel') {
            input.addEventListener('input', function() {
                if (this.value.length > 3) {
                    const result = Validator.validate(this);
                    if (result.valid && this.value.trim() !== '') {
                        Validator.showSuccess(this);
                    }
                }
            });
        }
    });

    // Character counter for textarea
    const summary = document.getElementById('summary');
    if (summary) {
        summary.addEventListener('input', function() {
            const counter = document.getElementById('summary-counter');
            if (counter) {
                const length = this.value.length;
                counter.textContent = `${length} / 500`;
                counter.className = length > 500 ? 'text-xs text-red-500 font-medium' : 'text-xs text-gray-500';
            }
        });
    }
}

// Initialize validation when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    setupLiveValidation();
    setupPrintFunctionality();
});
</script>

<script src="/trainer_profile/assets/js/resume_layouts.js"></script>
<script src="/trainer_profile/assets/js/app.js"></script>

<!-- Initial icon render -->
<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>

<script>
    window.addEventListener('load', () => {
        window.scrollTo({ top: 0, behavior: 'instant' });
    });
</script>

</body>
</html>t>

</body>
</html>