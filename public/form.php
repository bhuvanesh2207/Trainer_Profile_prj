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

    <!-- PDF Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

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
        }
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* A4 Paper Size for Preview */
        .a4-paper {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            position: relative;
        }

        /* Validation Styles */
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
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

        /* Print Styles - CRITICAL */
        @media print {
            @page { size: A4; margin: 0; }
            body { background: white; margin: 0; padding: 0; }
            
            /* Hide everything except the resume preview container */
            body > *:not(#preview-modal) { display: none !important; }
            
            /* Ensure modal is visible and reset styles */
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

            /* Hide modal controls (header/footer) */
            #preview-modal-header, #preview-modal-footer { display: none !important; }
            
            /* Reset the scroll container */
            #preview-scroll-container {
                overflow: visible !important;
                height: auto !important;
                padding: 0 !important;
                background: white !important;
            }

            /* Ensure the A4 paper fits perfectly */
            .a4-paper {
                box-shadow: none !important;
                margin: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
                transform: scale(1) !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

<!-- Top Login Button -->
<div class="absolute top-4 left-4 z-10">
    <a href="/trainer_profile/admin/login" class="flex items-center gap-2 px-4 py-2 border rounded-lg shadow-sm hover:bg-gray-50 transition-colors text-sm font-semibold" style="border-color:#5D1F2F; color:#5D1F2F;">
        <i data-lucide="lock" class="w-4 h-4"></i>
        Admin Login
    </a>
</div>

<!-- Global Alert Container -->
<div id="global-alert-container" class="fixed top-4 right-4 z-[100] space-y-2" style="max-width: 400px;">
    <!-- Alerts will be injected here -->
</div>

<div class="min-h-screen flex flex-col items-center py-12 px-4">
    
    <!-- Header -->
    <div class="text-center mb-10 max-w-xl">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Trainer Profile Builder</h1>
        <p class="text-gray-600 text-sm md:text-base">Create your professional profile in minutes.</p>
    </div>

    <!-- Multi-Step Form Container -->
    <div class="w-full max-w-4xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col min-h-[600px]">
        
        <!-- Progress Bar -->
        <div class="bg-gray-50 p-4 md:p-6 border-b border-gray-100">
            <div class="flex justify-between items-center relative max-w-2xl mx-auto">
                <!-- Line -->
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 -z-0 -translate-y-1/2"></div>
                <div id="progress-fill" class="absolute top-1/2 left-0 h-1 bg-resume-primary -z-0 -translate-y-1/2 transition-all duration-300 w-0"></div>

                <!-- Steps -->
                <div class="step-indicator relative z-10 bg-gray-50 px-1 md:px-2 flex flex-col items-center gap-1 active" data-step="1">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-resume-primary text-white flex items-center justify-center font-bold text-xs md:text-sm">1</div>
                    <span class="text-[10px] md:text-xs font-medium text-gray-600">Personal</span>
                </div>
                <div class="step-indicator relative z-10 bg-gray-50 px-1 md:px-2 flex flex-col items-center gap-1" data-step="2">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">2</div>
                    <span class="text-[10px] md:text-xs font-medium text-gray-400">Experience</span>
                </div>
                <div class="step-indicator relative z-10 bg-gray-50 px-1 md:px-2 flex flex-col items-center gap-1" data-step="3">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">3</div>
                    <span class="text-[10px] md:text-xs font-medium text-gray-400">Education</span>
                </div>
                <div class="step-indicator relative z-10 bg-gray-50 px-1 md:px-2 flex flex-col items-center gap-1" data-step="4">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">4</div>
                    <span class="text-[10px] md:text-xs font-medium text-gray-400">Skills</span>
                </div>
                <div class="step-indicator relative z-10 bg-gray-50 px-1 md:px-2 flex flex-col items-center gap-1" data-step="5">
                    <div class="w-7 h-7 md:w-8 md:h-8 rounded-full bg-gray-300 text-white flex items-center justify-center font-bold text-xs md:text-sm">5</div>
                    <span class="text-[10px] md:text-xs font-medium text-gray-400">Finish</span>
                </div>
            </div>
        </div>

        <!-- Form Steps Content -->
        <div class="flex-1 p-4 md:p-8 overflow-y-auto">
            <form id="main-form" novalidate>
                
                <!-- Step 1: Personal Info -->
                <div class="form-step block" id="step-1">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">
                        Personal Information
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="form-group">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="firstName"
                                id="firstName"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
                                    outline-none transition-colors"
                                required
                                data-validation="required"
                                autofocus
                            />
                            <div class="validation-message"></div>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                name="lastName"
                                id="lastName"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
                                    outline-none transition-colors"
                                required
                                data-validation="required"
                            />
                            <div class="validation-message"></div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Professional Title <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="title"
                            id="title"
                            placeholder="e.g. Senior Corporate Trainer"
                            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
                                    outline-none transition-colors"
                            required
                            data-validation="required"
                        />
                        <div class="validation-message"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="form-group">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="your.email@example.com"
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
                                    outline-none transition-colors"
                                required
                                data-validation="required|email"
                            />
                            <div class="validation-message"></div>
                        </div>

                        <div class="form-group">
                            <label class="block text-xs font-medium text-gray-700 mb-1">
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
                                class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
                                    outline-none transition-colors"
                                required
                                data-validation="required|phone"
                            />
                            <div class="validation-message"></div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Location
                        </label>
                        <input
                            type="text"
                            name="location"
                            id="location"
                            placeholder="City, State/Country"
                            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
                                    outline-none transition-colors"
                            data-validation="maxLength:100"
                        />
                        <div class="validation-message"></div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Professional Summary <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            name="summary"
                            id="summary"
                            rows="3"
                            placeholder="Briefly describe your experience and expertise..."
                            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm resize-none
                                    focus:ring-2 focus:ring-resume-primary focus:border-transparent
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
                        <label class="block text-xs font-medium text-gray-700 mb-1">
                            Profile Photo <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="file"
                            id="photo-upload"
                            accept="image/*"
                            class="block w-full text-sm text-gray-500
                                    file:mr-3 file:py-1.5 file:px-3
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-medium
                                    file:bg-resume-primary file:text-white
                                    hover:file:bg-opacity-90 cursor-pointer"
                            required
                            data-validation="required"
                        />
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
        <div class="p-4 md:p-6 border-t border-gray-100 bg-gray-50 flex flex-col sm:flex-row justify-between items-center gap-3">
            <button type="button" id="prev-btn" class="w-full sm:w-auto px-6 py-2 rounded-md bg-gray-200 text-gray-700 font-medium hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" disabled>Previous</button>
            <button type="button" id="next-btn" class="w-full sm:w-auto px-6 py-2 rounded-md bg-resume-primary text-white font-medium hover:bg-opacity-90 shadow-md flex items-center justify-center gap-2 transition-all">
                Next Step <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>
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
            <div id="resume-preview-container" class="a4-paper transform transition-transform origin-top scale-[0.55] sm:scale-75 md:scale-90 lg:scale-100">
                <!-- Content injected by JS -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div id="preview-modal-footer" class="px-4 md:px-6 py-3 md:py-4 border-t bg-white flex flex-wrap justify-end gap-2 md:gap-3">
            <button onclick="window.print()" class="px-4 md:px-6 py-2 rounded-md bg-resume-primary text-white text-sm md:text-base font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                <i data-lucide="printer" class="w-4 h-4"></i> Print
            </button>
            <button onclick="downloadPDF()" class="px-4 md:px-6 py-2 rounded-md bg-resume-primary text-white text-sm md:text-base font-medium hover:bg-opacity-90 shadow-md flex items-center gap-2 transition-all">
                <i data-lucide="download" class="w-4 h-4"></i> Download PDF
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
        fileType: (file, type) => !file || file.type.startsWith(type)
    },

    messages: {
        required: 'This field is required',
        email: 'Please enter a valid email address',
        phone: 'Please enter a valid phone number',
        minLength: 'Minimum {min} characters required',
        maxLength: 'Maximum {max} characters allowed',
        fileSize: 'File size must be less than {maxKB}KB',
        fileType: 'Invalid file type. Expected {type}'
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
    
    // Show welcome message
    setTimeout(() => {
        showAlert('Welcome! Fill out all required fields to create your professional profile.', 'info', 7000);
    }, 500);
});
</script>

<script src="assets/js/resume_layouts.js"></script>
<script src="assets/js/app.js"></script>

<!-- Initial icon render -->
<script>
    if (window.lucide) {
        lucide.createIcons();
    }
</script>
</body>
</html>