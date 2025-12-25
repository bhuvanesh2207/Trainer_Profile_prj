document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("main-form");
  const steps = Array.from(document.querySelectorAll(".form-step"));
  const totalSteps = steps.length;

  const nextBtn = document.getElementById("next-btn");
  const prevBtn = document.getElementById("prev-btn");
  const progressFill = document.getElementById("progress-fill");
  const stepIndicators = document.querySelectorAll(".step-indicator");

  const layoutSelector = document.getElementById("layout-selector");
  const fontSelector = document.getElementById("font-selector");

  const experienceContainer = document.getElementById("experience-container");
  const educationContainer = document.getElementById("education-container");
  const skillsContainer = document.getElementById("skills-container");
  const languagesContainer = document.getElementById("languages-container");
  const achievementsContainer = document.getElementById("achievements-container");

  const photoUpload = document.getElementById("photo-upload");
  const resumePreviewContainer = document.getElementById("resume-preview-container");

  let currentStep = 1;
  let currentLayout = layoutSelector ? layoutSelector.value : "1";
  let currentFontClass = fontSelector ? fontSelector.value : "font-sans";
  let currentPhotoShape = "circle";
  let photoDataUrl = null;

  let experienceCount = 0;
  let educationCount = 0;
  let skillCount = 0;
  let languageCount = 0;
  let achievementCount = 0;

  // ---------- PHOTO UPLOAD HANDLING ----------

  function handlePhotoUpload(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Validate file type
    const result = Validator.validate(e.target);
    if (!result.valid) {
      Validator.showError(e.target, result.message);
      // Clear the file input
      e.target.value = '';
      return;
    } else {
      Validator.showSuccess(e.target);
    }

    const reader = new FileReader();
    reader.onload = () => {
      photoDataUrl = reader.result;
      const previewEl = document.getElementById("photo-preview");
      if (previewEl) {
        previewEl.innerHTML = `<img src="${photoDataUrl}" class="w-full h-full object-cover" alt="Profile Photo">`;
      }
    };
    reader.readAsDataURL(file);
  }

  // ---------- AUTOFOCUS HANDLING ----------

  function autoFocusCurrentStep(skipScroll = false) {
    setTimeout(() => {
      const currentStepEl = steps[currentStep - 1];
      if (!currentStepEl) return;

      const focusableSelectors = [
        'input[type="text"]:not([disabled]):not([readonly])',
        'input[type="email"]:not([disabled]):not([readonly])',
        'input[type="tel"]:not([disabled]):not([readonly])',
        'input[type="date"]:not([disabled]):not([readonly])',
        'textarea:not([disabled]):not([readonly])',
        'input:not([type="hidden"]):not([type="file"]):not([type="checkbox"]):not([type="radio"]):not([disabled]):not([readonly])',
        'select:not([disabled])',
      ];

      let firstFocusable = null;

      for (const selector of focusableSelectors) {
        const elements = currentStepEl.querySelectorAll(selector);
        for (const el of elements) {
          if (el.offsetParent !== null && !el.closest('.hidden')) {
            firstFocusable = el;
            break;
          }
        }
        if (firstFocusable) break;
      }

      if (firstFocusable) {
        firstFocusable.focus();

        if (!skipScroll) {
          setTimeout(() => {
            firstFocusable.scrollIntoView({
              behavior: 'smooth',
              block: 'center'
            });
          }, 50);
        }

        firstFocusable.classList.add('ring-2', 'ring-resume-primary');
        setTimeout(() => {
          firstFocusable.classList.remove('ring-2', 'ring-resume-primary');
        }, 800);
      }
    }, 150);
  }

  // ---------- STEP HANDLING ----------

  function setStep(step) {
    currentStep = step;

    steps.forEach((el, idx) => {
      const isActive = idx === currentStep - 1;
      el.classList.toggle("hidden", !isActive);
      el.classList.toggle("block", isActive);
    });

    prevBtn.disabled = currentStep === 1;

    nextBtn.innerHTML =
      currentStep === totalSteps
        ? 'Preview & Submit <i data-lucide="arrow-right" class="w-4 h-4"></i>'
        : 'Next Step <i data-lucide="arrow-right" class="w-4 h-4"></i>';

    const percent =
      totalSteps > 1 ? ((currentStep - 1) / (totalSteps - 1)) * 100 : 0;
    progressFill.style.width = percent + "%";

    stepIndicators.forEach((indicator) => {
      const stepNum = parseInt(indicator.dataset.step, 10);
      const circle = indicator.querySelector("div.w-8, div.w-7"); // support w-7 md:w-8
      const label = indicator.querySelector("span");

      if (!circle || !label) return;

      const isPast = stepNum < currentStep;
      const isCurrent = stepNum === currentStep;

      if (isPast || isCurrent) {
        circle.classList.remove("bg-gray-300");
        circle.classList.add("bg-resume-primary");

        label.classList.remove("text-gray-400");
        label.classList.add("text-gray-600");
      } else {
        circle.classList.add("bg-gray-300");
        circle.classList.remove("bg-resume-primary");

        label.classList.add("text-gray-400");
        label.classList.remove("text-gray-600");
      }
    });

    if (window.lucide) {
      lucide.createIcons();
    }

    autoFocusCurrentStep();
  }

  console.log('Setting up Next button event listener');
  if (nextBtn) {
    console.log('Next button found:', nextBtn);
    nextBtn.addEventListener("click", () => {
      console.log('Next button clicked');
      if (!window.validateCurrentStep()) {
        console.log('Validation failed, not proceeding');
        return;
      }

      if (currentStep < totalSteps) {
        console.log('Moving to next step:', currentStep + 1);
        setStep(currentStep + 1);
      } else {
        console.log('Opening preview');
        openPreview();
      }
    });
  } else {
    console.error('Next button not found!');
  }

  prevBtn.addEventListener("click", () => {
    if (currentStep > 1) {
      setStep(currentStep - 1);
    }
  });

  if (photoUpload) {
    photoUpload.addEventListener("change", handlePhotoUpload);
  }

  // ---------- KEYBOARD NAVIGATION ----------

  document.addEventListener('keydown', function (e) {
    if (e.altKey) {
      if (e.key === 'ArrowRight' && currentStep < totalSteps) {
        e.preventDefault();
        if (window.validateCurrentStep()) {
          setStep(currentStep + 1);
        }
      } else if (e.key === 'ArrowLeft' && currentStep > 1) {
        e.preventDefault();
        setStep(currentStep - 1);
      }
    }

    if (e.key === 'Enter' && e.target.matches('input:not([type="submit"]):not([type="button"])')) {
      e.preventDefault();

      // Handle file input: trigger file picker
      if (e.target.type === 'file') {
        e.target.click();
        return;
      }

      // Handle date input: trigger date picker
      if (e.target.type === 'date') {
        // Date inputs open picker automatically on focus/click, but we can ensure it's focused
        e.target.showPicker && e.target.showPicker();
        return;
      }

      const currentStepEl = steps[currentStep - 1];
      if (!currentStepEl) return;

      const focusableSelector = 'input:not([type="hidden"]):not([type="file"]):not([disabled]), textarea:not([disabled]), select:not([disabled])';
      const focusables = Array.from(currentStepEl.querySelectorAll(focusableSelector)).filter(el => el.offsetParent !== null);
      const currentIndex = focusables.indexOf(e.target);

      if (currentIndex > -1 && currentIndex < focusables.length - 1) {
        focusables[currentIndex + 1].focus();
      } else if (currentIndex === focusables.length - 1) {
        nextBtn.click();
      }
    }
  });

  // ---------- DYNAMIC SECTIONS ----------

  window.addExperience = function (skipAlert = false) {
    experienceCount++;
    const wrapper = document.createElement("div");
    wrapper.className =
      "experience-item border border-gray-200 rounded-lg p-4 relative bg-gray-50";
    wrapper.innerHTML = `
      <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-red-500" onclick="removeExperience(this)">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
      <div class="grid md:grid-cols-2 gap-4 mb-3">
        <div class="form-group">
          <label class="block text-xs font-medium text-gray-700 mb-1">
            Position <span class="text-red-500">*</span>
          </label>
          <input 
            data-field="position" 
            type="text" 
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors" 
            required
            data-validation="required"
            placeholder="e.g. Senior Trainer">
          <div class="validation-message"></div>
        </div>
        <div class="form-group">
          <label class="block text-xs font-medium text-gray-700 mb-1">
            Company <span class="text-red-500">*</span>
          </label>
          <input 
            data-field="company" 
            type="text" 
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors" 
            required
            data-validation="required"
            placeholder="e.g. ABC Corporation">
          <div class="validation-message"></div>
        </div>
      </div>
      <div class="grid md:grid-cols-2 gap-4 mb-3">
        <div class="form-group">
          <label class="block text-xs font-medium text-gray-700 mb-1">Location <span class="text-red-500">*</span></label>
          <input 
            data-field="location" 
            type="text" 
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors"
            required
            data-validation="required"
            placeholder="e.g. New York, NY">
          <div class="validation-message"></div>
        </div>
        <div class="grid grid-cols-2 gap-2">
          <div class="form-group">
            <label class="block text-xs font-medium text-gray-700 mb-1">
              Start Date <span class="text-red-500">*</span>
            </label>
            <input 
              data-field="startDate" 
              type="date" 
              class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors"
              required
              data-validation="required">
            <div class="validation-message"></div>
          </div>
          <div class="form-group">
            <label class="block text-xs font-medium text-gray-700 mb-1">End Date <span class="text-red-500">*</span></label>
            <input
              data-field="endDate"
              type="date"
              class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors"
              required
              data-validation="required|dateGreaterThan:startDate">
            <div class="validation-message"></div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="block text-xs font-medium text-gray-700 mb-1">
          Description <span class="text-red-500">*</span>
        </label>
        <textarea 
          data-field="description" 
          rows="3" 
          class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm resize-none focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors" 
          placeholder="Key responsibilities, achievements, results..."
          required
          data-validation="required"></textarea>
        <div class="validation-message"></div>
      </div>
    `;
    experienceContainer.appendChild(wrapper);

    setupDynamicValidation(wrapper);

    if (window.lucide) lucide.createIcons();

    if (!skipAlert) {
      showAlert("Experience section added successfully!", "success", 3000);
      const firstInput = wrapper.querySelector('input[data-field="position"]');
      if (firstInput) {
        setTimeout(() => {
          firstInput.focus();
          firstInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  };

  window.removeExperience = function (btn) {
    if (confirm("Are you sure you want to remove this experience?")) {
      btn.closest(".experience-item").remove();
      showAlert("Experience removed", "info", 2000);
    }
  };

  window.addEducation = function (skipAlert = false) {
    educationCount++;

    const wrapper = document.createElement("div");
    wrapper.className =
      "education-item border border-gray-200 rounded-lg p-4 relative bg-gray-50";

    wrapper.innerHTML = `
      <button type="button"
        class="absolute top-2 right-2 text-gray-400 hover:text-red-500"
        onclick="removeEducation(this)">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>

      <div class="grid md:grid-cols-2 gap-4 mb-3">
        <div class="form-group">
          <label class="block text-xs font-medium text-gray-700 mb-1">
            Degree / Course <span class="text-red-500">*</span>
          </label>
          <input
            data-field="degree"
            type="text"
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                   focus:ring-2 focus:ring-resume-primary focus:border-transparent
                   outline-none transition-colors"
            required
            data-validation="required|minLength:2|maxLength:100"
            placeholder="e.g. Master of Education">
          <div class="validation-message"></div>
        </div>

        <div class="form-group">
          <label class="block text-xs font-medium text-gray-700 mb-1">
            Institution <span class="text-red-500">*</span>
          </label>
          <input
            data-field="institution"
            type="text"
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                   focus:ring-2 focus:ring-resume-primary focus:border-transparent
                   outline-none transition-colors"
            required
            data-validation="required|minLength:2|maxLength:100"
            placeholder="e.g. University of XYZ">
          <div class="validation-message"></div>
        </div>
      </div>

      <div class="grid md:grid-cols-2 gap-4 mb-3">
        <div class="form-group">
          <label class="block text-xs font-medium text-gray-700 mb-1">
            Location <span class="text-red-500">*</span>
          </label>
          <input
            data-field="location"
            type="text"
            class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                   focus:ring-2 focus:ring-resume-primary focus:border-transparent
                   outline-none transition-colors"
            required
            data-validation="required"
            placeholder="e.g. Boston, MA">
          <div class="validation-message"></div>
        </div>

        <div class="grid grid-cols-2 gap-2">
          <div class="form-group">
            <label class="block text-xs font-medium text-gray-700 mb-1">
              Start Year <span class="text-red-500">*</span>
            </label>
            <input
              data-field="startYear"
              type="text"
              maxlength="4"
              inputmode="numeric"
              class="year-input w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                     focus:ring-2 focus:ring-resume-primary focus:border-transparent
                     outline-none transition-colors"
              required
              placeholder="2018">
            <div class="validation-message"></div>
          </div>

          <div class="form-group">
            <label class="block text-xs font-medium text-gray-700 mb-1">
              End Year <span class="text-red-500">*</span>
            </label>
            <input
              data-field="endYear"
              type="text"
              maxlength="4"
              inputmode="numeric"
              class="year-input w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                     focus:ring-2 focus:ring-resume-primary focus:border-transparent
                     outline-none transition-colors"
              required
              data-validation="required|yearGreaterThan:startYear"
              placeholder="2020">
            <div class="validation-message"></div>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="block text-xs font-medium text-gray-700 mb-1">
          Details
        </label>
        <textarea
          data-field="details"
          rows="2"
          class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm
                 resize-none focus:ring-2 focus:ring-resume-primary
                 focus:border-transparent outline-none transition-colors"
          placeholder="Key subjects, honors, projects..."
          data-validation="maxLength:300"></textarea>
        <div class="validation-message"></div>
      </div>
    `;

    educationContainer.appendChild(wrapper);

    setupDynamicValidation(wrapper);

    if (window.lucide) lucide.createIcons();

    if (!skipAlert) {
      showAlert("Education section added successfully!", "success", 3000);
      const firstInput = wrapper.querySelector('input[data-field="degree"]');
      if (firstInput) {
        setTimeout(() => {
          firstInput.focus();
          firstInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  };

  window.removeEducation = function (btn) {
    if (confirm("Are you sure you want to remove this education entry?")) {
      btn.closest(".education-item").remove();
      showAlert("Education removed", "info", 2000);
    }
  };

  window.addSkill = function (skipAlert = false) {
    skillCount++;
    const wrapper = document.createElement("div");
    wrapper.className = "skill-item flex items-center gap-3";
    wrapper.innerHTML = `
      <div class="flex-1 form-group">
        <input 
          data-field="skillName" 
          type="text" 
          placeholder="e.g. Instructional Design" 
          class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors"
          required
          data-validation="required|maxLength:30">
        <div class="validation-message"></div>
      </div>
      <select 
        data-field="skillLevel" 
        class="px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none">
        <option value="Beginner">Beginner</option>
        <option value="Intermediate" selected>Intermediate</option>
        <option value="Advanced">Advanced</option>
        <option value="Expert">Expert</option>
      </select>
      <button type="button" class="text-gray-400 hover:text-red-500 transition-colors" onclick="removeSkill(this)">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    `;
    skillsContainer.appendChild(wrapper);

    setupDynamicValidation(wrapper);

    if (window.lucide) lucide.createIcons();

    const skillsValidation = document.getElementById("skills-validation");
    if (skillsValidation) skillsValidation.innerHTML = "";

    if (!skipAlert) {
      const skillInput = wrapper.querySelector('input[data-field="skillName"]');
      if (skillInput) {
        setTimeout(() => {
          skillInput.focus();
          skillInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  };

  window.removeSkill = function (btn) {
    btn.closest(".skill-item").remove();
  };

  window.addLanguage = function (skipAlert = false) {
    languageCount++;
    const wrapper = document.createElement("div");
    wrapper.className = "language-item flex items-center gap-3";
    wrapper.innerHTML = `
      <div class="flex-1 form-group">
        <input 
          data-field="languageName" 
          type="text" 
          placeholder="e.g. English" 
          class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors"
          required
          data-validation="required|maxLength:30">
        <div class="validation-message"></div>
      </div>
      <select 
        data-field="languageLevel" 
        class="px-2 py-1.5 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none">
        <option value="Basic">Basic</option>
        <option value="Conversational">Conversational</option>
        <option value="Fluent" selected>Fluent</option>
        <option value="Native">Native</option>
      </select>
      <button type="button" class="text-gray-400 hover:text-red-500 transition-colors" onclick="removeLanguage(this)">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    `;
    languagesContainer.appendChild(wrapper);

    setupDynamicValidation(wrapper);

    if (window.lucide) lucide.createIcons();

    const languagesValidation = document.getElementById("languages-validation");
    if (languagesValidation) languagesValidation.innerHTML = "";

    if (!skipAlert) {
      const langInput = wrapper.querySelector('input[data-field="languageName"]');
      if (langInput) {
        setTimeout(() => {
          langInput.focus();
          langInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  };

  window.removeLanguage = function (btn) {
    btn.closest(".language-item").remove();
  };

  window.addAchievement = function (skipAlert = false) {
    achievementCount++;
    const wrapper = document.createElement("div");
    wrapper.className = "achievement-item flex gap-3 items-start";
    wrapper.innerHTML = `
      <span class="mt-2 text-gray-400">•</span>
      <div class="flex-1 form-group">
        <textarea 
          data-field="achievementText" 
          rows="2" 
          class="w-full px-2 py-1.5 border border-gray-300 rounded-md text-sm resize-none focus:ring-2 focus:ring-resume-primary focus:border-transparent outline-none transition-colors" 
          placeholder="e.g. Delivered 200+ hours of leadership training with 95% positive feedback."
          required
          data-validation="required|maxLength:500"></textarea>
        <div class="validation-message"></div>
      </div>
      <button type="button" class="mt-1 text-gray-400 hover:text-red-500 transition-colors" onclick="removeAchievement(this)">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    `;
    achievementsContainer.appendChild(wrapper);

    setupDynamicValidation(wrapper);

    if (window.lucide) lucide.createIcons();

    if (!skipAlert) {
      const achievementInput = wrapper.querySelector('textarea[data-field="achievementText"]');
      if (achievementInput) {
        setTimeout(() => {
          achievementInput.focus();
          achievementInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
      }
    }
  };

  window.removeAchievement = function (btn) {
    if (confirm("Remove this achievement?")) {
      btn.closest(".achievement-item").remove();
    }
  };

  // Setup validation for dynamically added fields
  function setupDynamicValidation(container) {
    const inputs = container.querySelectorAll("[data-validation]");

    inputs.forEach((input) => {
      input.addEventListener("blur", function () {
        const result = Validator.validate(this);
        const isRequired =
          this.hasAttribute("required") ||
          (this.dataset.validation || "").includes("required");

        if (!result.valid && (this.value.trim() !== "" || isRequired)) {
          Validator.showError(this, result.message);
        } else if (this.value.trim() !== "") {
          Validator.showSuccess(this);
        }
      });

      input.addEventListener("focus", function () {
        if (this.classList.contains("input-error")) {
          Validator.clearValidation(this);
        }
      });

      input.addEventListener("input", function () {
        if (this.value.length > 2 && !this.classList.contains("input-error")) {
          const result = Validator.validate(this);
          if (result.valid) {
            Validator.showSuccess(this);
          }
        }
      });
    });
  }

  // ---------- EXTEND VALIDATOR (YEAR RULE) ----------

  if (window.Validator) {
    Validator.rules.year = (value) => {
      if (!value) return true;
      const year = parseInt(value, 10);
      const currentYear = new Date().getFullYear();
      return (
        /^\d{4}$/.test(value) && year >= 1950 && year <= currentYear + 10
      );
    };

    Validator.messages.year =
      "Please enter a valid year (1950-present)";

    Validator.rules.dateGreaterThan = (value, param) => {
      if (!value) return true;
      const startDateValue = document.querySelector(`[data-field="${param}"]`)?.value;
      if (!startDateValue) return true;
      return new Date(value) >= new Date(startDateValue);
    };

    Validator.messages.dateGreaterThan =
      "End date must be on or after start date";

    Validator.rules.yearGreaterThan = (value, param) => {
      if (!value) return true;
      const startYearValue = document.querySelector(`[data-field="${param}"]`)?.value;
      if (!startYearValue) return true;
      const endYear = parseInt(value, 10);
      const startYear = parseInt(startYearValue, 10);
      return endYear >= startYear;
    };

    Validator.messages.yearGreaterThan =
      "End year must be on or after start year";
  }

  // ---------- ENHANCED STEP VALIDATION ----------

  window.validateCurrentStep = function () {
    const currentStepEl = document.querySelector(".form-step:not(.hidden)");
    if (!currentStepEl) return true;

    console.log('Validating current step:', currentStepEl.id);

    const inputs = currentStepEl.querySelectorAll("[data-validation]");
    let isValid = true;
    let firstInvalidInput = null;

    inputs.forEach((input) => {
      // Skip file inputs as they are handled separately
      if (input.type === "file") return;

      const result = Validator.validate(input);
      const isRequired =
        input.hasAttribute("required") ||
        (input.dataset.validation || "").includes("required");

      const hasValue = input.value.trim() !== "";

      console.log(`Input ${input.id || input.name}: valid=${result.valid}, hasValue=${hasValue}, isRequired=${isRequired}, message=${result.message || 'none'}`);

      if (!result.valid && (hasValue || isRequired)) {
        Validator.showError(input, result.message);
        isValid = false;
        if (!firstInvalidInput) firstInvalidInput = input;
      } else if (hasValue) {
        Validator.showSuccess(input);
      }
    });

    console.log('Overall isValid:', isValid);

    const stepId = currentStepEl.id;

    // Special check for photo in step 1
    if (stepId === "step-1") {
      console.log('Validating step-1: photo check');
      const photoInput = document.getElementById("photo-upload");
      console.log('Photo input found:', !!photoInput);
      if (photoInput) {
        console.log('Photo files length:', photoInput.files.length);
        console.log('Photo files:', photoInput.files);
      }
      if (photoInput && photoInput.files.length === 0) {
        console.log('Photo validation failed: no file uploaded');
        Validator.showError(photoInput, "Profile photo is required");
        isValid = false;
        if (!firstInvalidInput) firstInvalidInput = photoInput;
      } else if (photoInput) {
        console.log('Photo validation passed');
        Validator.showSuccess(photoInput);
      } else {
        console.log('Photo input not found');
      }
    }

    if (stepId === "step-2") {
      const experiences = document.querySelectorAll(
        "#experience-container .experience-item"
      );
      const validationDiv = document.getElementById("experience-validation");

      if (experiences.length === 0) {
        validationDiv.innerHTML = `
          <div class="flex items-start gap-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md p-3 fade-in">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
            <span><strong>Recommendation:</strong> Add at least one work experience to strengthen your profile.</span>
          </div>
        `;
        if (window.lucide) lucide.createIcons();
      } else {
        validationDiv.innerHTML = "";

        experiences.forEach((exp) => {
          const expInputs = exp.querySelectorAll("[data-validation]");
          expInputs.forEach((input) => {
            const result = Validator.validate(input);
            const isRequired =
              input.hasAttribute("required") ||
              (input.dataset.validation || "").includes("required");

            if (!result.valid && (input.value.trim() !== "" || isRequired)) {
              Validator.showError(input, result.message);
              isValid = false;
              if (!firstInvalidInput) firstInvalidInput = input;
            }
          });
        });
      }
    }

    if (stepId === "step-3") {
      const educations = document.querySelectorAll(
        "#education-container .education-item"
      );
      const validationDiv = document.getElementById("education-validation");

      if (educations.length === 0) {
        validationDiv.innerHTML = `
          <div class="flex items-start gap-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md p-3 fade-in">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
            <span><strong>Recommendation:</strong> Add at least one education entry to complete your profile.</span>
          </div>
        `;
        if (window.lucide) lucide.createIcons();
      } else {
        validationDiv.innerHTML = "";

        educations.forEach((edu) => {
          const eduInputs = edu.querySelectorAll("[data-validation]");
          eduInputs.forEach((input) => {
            const result = Validator.validate(input);
            const isRequired =
              input.hasAttribute("required") ||
              (input.dataset.validation || "").includes("required");

            if (!result.valid && (input.value.trim() !== "" || isRequired)) {
              Validator.showError(input, result.message);
              isValid = false;
              if (!firstInvalidInput) firstInvalidInput = input;
            }
          });
        });
      }
    }

    if (stepId === "step-4") {
      const skills = document.querySelectorAll(
        "#skills-container .skill-item"
      );
      const validationDiv = document.getElementById("skills-validation");

      if (skills.length < 3) {
        validationDiv.innerHTML = `
          <div class="flex items-start gap-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-md p-3 fade-in">
            <i data-lucide="alert-triangle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
            <span><strong>Recommendation:</strong> Add at least 3-5 skills to showcase your expertise.</span>
          </div>
        `;
        if (window.lucide) lucide.createIcons();
      } else {
        validationDiv.innerHTML = "";

        skills.forEach((skill) => {
          const skillInput = skill.querySelector("[data-validation]");
          if (skillInput) {
            const result = Validator.validate(skillInput);
            const isRequired =
              skillInput.hasAttribute("required") ||
              (skillInput.dataset.validation || "").includes("required");

            if (!result.valid && (skillInput.value.trim() !== "" || isRequired)) {
              Validator.showError(skillInput, result.message);
              isValid = false;
              if (!firstInvalidInput) firstInvalidInput = skillInput;
            }
          }
        });
      }
    }

    if (!isValid && firstInvalidInput) {
      firstInvalidInput.scrollIntoView({
        behavior: "smooth",
        block: "center",
      });
      setTimeout(() => firstInvalidInput.focus(), 300);
      showAlert("Please fix the errors before proceeding", "error", 4000);
    }

    return isValid;
  };

  // ---------- COLLECT FORM DATA ----------

  function collectFormData() {
    const data = {
      first_name: (document.getElementById("firstName").value || "").trim(),
      last_name: (document.getElementById("lastName").value || "").trim(),
      title: (document.getElementById("title").value || "").trim(),
      email: (document.getElementById("email").value || "").trim(),
      phone: (document.getElementById("phone").value || "").trim(),
      location: (document.getElementById("location").value || "").trim(),
      summary: (document.getElementById("summary").value || "").trim(),
    };

    data.experience = [];
    document.querySelectorAll(".experience-item").forEach((item) => {
      data.experience.push({
        position:
          item.querySelector('[data-field="position"]')?.value.trim() || "",
        company:
          item.querySelector('[data-field="company"]')?.value.trim() || "",
        location:
          item.querySelector('[data-field="location"]')?.value.trim() || "",
        start_date:
          item.querySelector('[data-field="startDate"]')?.value.trim() || "",
        end_date:
          item.querySelector('[data-field="endDate"]')?.value.trim() || "",
        description:
          item.querySelector('[data-field="description"]')?.value.trim() ||
          "",
      });
    });

    data.education = [];
    document.querySelectorAll(".education-item").forEach((item) => {
      data.education.push({
        degree:
          item.querySelector('[data-field="degree"]')?.value.trim() || "",
        institution:
          item.querySelector('[data-field="institution"]')?.value.trim() || "",
        location:
          item.querySelector('[data-field="location"]')?.value.trim() || "",
        start_year:
          item.querySelector('[data-field="startYear"]')?.value.trim() || "",
        end_year:
          item.querySelector('[data-field="endYear"]')?.value.trim() || "",
        details:
          item.querySelector('[data-field="details"]')?.value.trim() || "",
      });
    });

    data.skills = [];
    document.querySelectorAll(".skill-item").forEach((item) => {
      const name =
        item.querySelector('[data-field="skillName"]')?.value.trim();
      const level = item.querySelector('[data-field="skillLevel"]')?.value;
      if (name) {
        data.skills.push({ name, level });
      }
    });

    data.languages = [];
    document.querySelectorAll(".language-item").forEach((item) => {
      const name =
        item.querySelector('[data-field="languageName"]')?.value.trim();
      const level =
        item.querySelector('[data-field="languageLevel"]')?.value.trim();
      if (name) {
        data.languages.push({ name, level });
      }
    });

    data.achievements = [];
    document.querySelectorAll(".achievement-item").forEach((item) => {
      const text =
        item.querySelector('[data-field="achievementText"]')?.value.trim();
      if (text) {
        data.achievements.push(text);
      }
    });

    return data;
  }

  function nl2br(str) {
    return (str || "").replace(/\n/g, "<br>");
  }

  // ---------- PREVIEW LAYOUTS ----------

  function renderExperience(experiences) {
    if (!experiences || !experiences.length) {
      return `<p class="text-xs text-gray-500">No experience added.</p>`;
    }
    return experiences
      .map((exp) => {
        const dateStr = [exp.start_date, exp.end_date || "Present"]
          .filter(Boolean)
          .join(" - ");
        const companyLine = [exp.company, exp.location]
          .filter(Boolean)
          .join(" • ");
        return `
          <div class="mb-3">
            <div class="flex justify-between text-xs font-semibold text-gray-800">
              <span>${exp.position || ""}</span>
              <span class="text-gray-500">${dateStr}</span>
            </div>
            <div class="text-xs text-gray-600">${companyLine}</div>
            ${
              exp.description
                ? `<p class="text-xs text-gray-700 mt-1">${nl2br(
                    exp.description
                  )}</p>`
                : ""
            }
          </div>
        `;
      })
      .join("");
  }

  function renderEducation(education) {
    if (!education || !education.length) {
      return `<p class="text-xs text-gray-500">No education added.</p>`;
    }
    return education
      .map((ed) => {
        const years = [ed.start_year, ed.end_year].filter(Boolean).join(" - ");
        const instLine = [ed.institution, ed.location]
          .filter(Boolean)
          .join(" • ");
        return `
          <div class="mb-3">
            <div class="flex justify-between text-xs font-semibold text-gray-800">
              <span>${ed.degree || ""}</span>
              <span class="text-gray-500">${years}</span>
            </div>
            <div class="text-xs text-gray-600">${instLine}</div>
            ${
              ed.details
                ? `<p class="text-xs text-gray-700 mt-1">${nl2br(
                    ed.details
                  )}</p>`
                : ""
            }
          </div>
        `;
      })
      .join("");
  }

  function renderSkills(skills) {
    if (!skills || !skills.length) {
      return `<p class="text-xs text-gray-500">No skills added.</p>`;
    }
    return `
      <ul class="space-y-1">
        ${skills
          .map(
            (s) => `
          <li class="flex justify-between text-xs">
            <span>${s.name}</span>
            <span class="text-black">${s.level}</span>
          </li>
        `
          )
          .join("")}
      </ul>
    `;
  }

  function renderLanguages(languages) {
    if (!languages || !languages.length) {
      return `<p class="text-xs text-gray-500">No languages added.</p>`;
    }
    return `
      <ul class="space-y-1">
        ${languages
          .map(
            (l) => `
          <li class="flex justify-between text-xs">
            <span>${l.name}</span>
            <span class="text-black">${l.level}</span>
          </li>
        `
          )
          .join("")}
      </ul>
    `;
  }

 function renderAchievements(achievements) {
  if (!achievements || !achievements.length) {
    return `<p class="text-xs text-gray-500">No achievements added.</p>`;
  }

  return `
    <div class="space-y-1 text-xs text-gray-700">
      ${achievements.map(a => `
        <div class="flex items-start gap-1">
          <span class="mt-[3px] w-1.5 h-1.5 rounded-full bg-gray-700 flex-shrink-0"></span>
          <span>${nl2br(a)}</span>
        </div>
      `).join("")}
    </div>
  `;
}

  function buildPhotoHtml(initials) {
    const shapeClass =
      currentPhotoShape === "circle" ? "rounded-full" : "rounded-md";

    if (photoDataUrl) {
      return `
        <div class="w-24 h-24 overflow-hidden ${shapeClass} border-4 border-white/60 shadow-md flex-shrink-0">
          <img src="${photoDataUrl}" class="w-full h-full object-cover" />
        </div>
      `;
    } else {
      return `
        <div class="w-24 h-24 ${shapeClass} bg-white/20 border-4 border-white/60 flex items-center justify-center text-2xl font-bold uppercase flex-shrink-0">
          ${initials || "?"}
        </div>
      `;
    }
  }

  function generateLayout1(data) {
    const fullName =
      `${data.first_name || ""} ${data.last_name || ""}`.trim();
    const initials =
      (data.first_name?.[0] || "") + (data.last_name?.[0] || "");
    const summaryHtml = nl2br(data.summary || "");

    return `
      <div class="w-full h-full flex flex-col ${currentFontClass}">
        <div class="bg-resume-primary text-white px-6 md:px-8 py-4 md:py-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6">
          ${buildPhotoHtml(initials)}
          <div class="flex-1">
            <h1 class="text-xl md:text-2xl font-bold tracking-wide">${fullName}</h1>
            <p class="text-xs md:text-sm opacity-90 mt-1">${data.title || ""}</p>
            <div class="flex flex-wrap gap-3 text-[11px] md:text-xs mt-3 opacity-90">
              ${
                data.email
                  ? `<div class="flex items-center gap-1">
                      <i data-lucide="mail" class="w-3 h-3"></i>
                      <span>${data.email}</span>
                    </div>`
                  : ""
              }
              ${
                data.phone
                  ? `<div class="flex items-center gap-1">
                      <i data-lucide="phone" class="w-3 h-3"></i>
                      <span>${data.phone}</span>
                    </div>`
                  : ""
              }
              ${
                data.location
                  ? `<div class="flex items-center gap-1">
                      <i data-lucide="map-pin" class="w-3 h-3"></i>
                      <span>${data.location}</span>
                    </div>`
                  : ""
              }
            </div>
          </div>
        </div>

        <div class="flex-1 px-6 md:px-8 py-4 md:py-6 bg-white text-gray-800 text-sm">
          <div class="grid grid-cols-3 gap-4 md:gap-6 h-full">
            <div class="col-span-3 md:col-span-2 space-y-4">
              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Profile</h2>
                <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                <p class="text-[11px] md:text-xs text-gray-700 leading-relaxed">${summaryHtml}</p>
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Experience</h2>
                <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                ${renderExperience(data.experience)}
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Achievements</h2>
                <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                ${renderAchievements(data.achievements)}
              </section>
            </div>

            <div class="col-span-3 md:col-span-1 space-y-4">
              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Education</h2>
                <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                ${renderEducation(data.education)}
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Skills</h2>
                <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                ${renderSkills(data.skills)}
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Languages</h2>
                <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                ${renderLanguages(data.languages)}
              </section>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function generateLayout2(data) {
    const fullName =
      `${data.first_name || ""} ${data.last_name || ""}`.trim() ;
    const initials =
      (data.first_name?.[0] || "") + (data.last_name?.[0] || "");
    const summaryHtml = nl2br(data.summary || "");

    return `
      <div class="w-full h-full flex ${currentFontClass}">
        <!-- Sidebar -->
        <div class="w-[32%] bg-resume-blue text-white px-4 md:px-6 py-6 md:py-8 flex flex-col gap-5 md:gap-6">
          <div class="flex flex-col items-center text-center gap-2">
            ${buildPhotoHtml(initials)}
            <h1 class="text-base md:text-lg font-semibold mt-3">${fullName}</h1>
            <p class="text-[11px] md:text-xs opacity-90">${data.title || ""}</p>
          </div>

          <div class="text-[11px] md:text-xs space-y-3">
            <h2 class="uppercase text-[9px] md:text-[10px] tracking-widest font-semibold opacity-80">Contact</h2>
            <div class="space-y-1.5">
              ${
                data.email
                  ? `<div class="flex items-center gap-2">
                      <i data-lucide="mail" class="w-3 h-3"></i>
                      <span class="break-all">${data.email}</span>
                    </div>`
                  : ""
              }
              ${
                data.phone
                  ? `<div class="flex items-center gap-2">
                      <i data-lucide="phone" class="w-3 h-3"></i>
                      <span>${data.phone}</span>
                    </div>`
                  : ""
              }
              ${
                data.location
                  ? `<div class="flex items-center gap-2">
                      <i data-lucide="map-pin" class="w-3 h-3"></i>
                      <span>${data.location}</span>
                    </div>`
                  : ""
              }
            </div>
          </div>

          <div class="text-[11px] md:text-xs space-y-3">
            <h2 class="uppercase text-[9px] md:text-[10px] tracking-widest font-semibold opacity-80">Skills</h2>
            ${renderSkills(data.skills)}
          </div>

          <div class="text-[11px] md:text-xs space-y-3">
            <h2 class="uppercase text-[9px] md:text-[10px] tracking-widest font-semibold opacity-80">Languages</h2>
            ${renderLanguages(data.languages)}
          </div>
        </div>

        <!-- Main content -->
        <div class="flex-1 bg-white px-6 md:px-8 py-6 md:py-8 text-[11px] md:text-sm text-gray-800 space-y-4 md:space-y-5">
          <section>
            <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Profile</h2>
            <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
            <p class="text-[11px] md:text-xs text-gray-700 leading-relaxed">${summaryHtml}</p>
          </section>

          <section>
            <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Experience</h2>
            <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
            ${renderExperience(data.experience)}
          </section>

          <section>
            <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Education</h2>
            <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
            ${renderEducation(data.education)}
          </section>

          <section>
            <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Achievements</h2>
            <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
            ${renderAchievements(data.achievements)}
          </section>
        </div>
      </div>
    `;
  }

  function generateLayout3(data) {
    const fullName =
      `${data.first_name || ""} ${data.last_name || ""}`.trim();
    const initials =
      (data.first_name?.[0] || "") + (data.last_name?.[0] || "");
    const summaryHtml = nl2br(data.summary || "");

    const shapeClass =
      currentPhotoShape === "circle" ? "rounded-full" : "rounded-md";

    const photoBlock = photoDataUrl
      ? `<img src="${photoDataUrl}" class="w-16 h-16 md:w-20 md:h-20 object-cover ${shapeClass} border border-gray-300" />`
      : `<div class="w-16 h-16 md:w-20 md:h-20 ${shapeClass} bg-gray-200 border border-gray-300 flex items-center justify-center text-lg md:text-xl font-bold uppercase text-gray-600">
            ${initials || "?"}
         </div>`;

    return `
      <div class="w-full h-full flex flex-col ${currentFontClass}">
        <div class="px-6 md:px-8 py-4 md:py-6 bg-gray-50 border-b flex flex-col sm:flex-row items-start sm:items-center gap-4 md:gap-6">
          ${photoBlock}
          <div class="flex-1">
            <h1 class="text-xl md:text-2xl font-bold text-gray-900">${fullName}</h1>
            <p class="text-xs md:text-sm text-gray-700 mt-1">${data.title || ""}</p>
          </div>
          <div class="text-[11px] md:text-xs text-gray-600 space-y-1">
            ${
              data.email
                ? `<div class="flex items-center gap-2">
                    <i data-lucide="mail" class="w-3 h-3"></i>
                    <span class="break-all">${data.email}</span>
                  </div>`
                : ""
            }
            ${
              data.phone
                ? `<div class="flex items-center gap-2">
                    <i data-lucide="phone" class="w-3 h-3"></i>
                    <span>${data.phone}</span>
                  </div>`
                : ""
            }
            ${
              data.location
                ? `<div class="flex items-center gap-2">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    <span>${data.location}</span>
                  </div>`
                : ""
            }
          </div>
        </div>

        <div class="flex-1 px-6 md:px-8 py-4 md:py-6 bg-white text-gray-800 text-[11px] md:text-sm">
          <section class="mb-4">
            <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Profile</h2>
            <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
            <p class="text-[11px] md:text-xs text-gray-700 leading-relaxed">${summaryHtml}</p>
          </section>

          <div class="grid grid-cols-3 gap-4 md:gap-6">
            <div class="col-span-3 md:col-span-2 space-y-4">
              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Experience</h2>
                <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                ${renderExperience(data.experience)}
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Achievements</h2>
                <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                ${renderAchievements(data.achievements)}
              </section>
            </div>

            <div class="col-span-3 md:col-span-1 space-y-4">
              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Education</h2>
                <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                ${renderEducation(data.education)}
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Skills</h2>
                <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                ${renderSkills(data.skills)}
              </section>

              <section>
                <h2 class="text-[10px] md:text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Languages</h2>
                <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                ${renderLanguages(data.languages)}
              </section>
            </div>
          </div>
        </div>
      </div>
    `;
  }

  function generateResumePreview() {
    const data = collectFormData();
    let html = "";

    switch (currentLayout) {
      case "2":
        html = generateLayout2(data);
        break;
      case "3":
        html = generateLayout3(data);
        break;
      case "1":
      default:
        html = generateLayout1(data);
        break;
    }

    resumePreviewContainer.innerHTML = html;

    if (window.lucide) {
      lucide.createIcons();
    }
  }

  // ---------- GLOBAL FUNCTIONS FOR INLINE HANDLERS ----------

  window.openPreview = function () {
    const modal = document.getElementById("preview-modal");
    modal.classList.remove("hidden");
    generateResumePreview();
  };

  window.closePreview = function () {
    const modal = document.getElementById("preview-modal");
    modal.classList.add("hidden");
  };

  window.setPhotoShape = function (shape) {
    currentPhotoShape = shape === "square" ? "square" : "circle";
    generateResumePreview();
  };



  // ---------- SUBMIT PROFILE ----------

  window.submitProfile = function () {
    const data = collectFormData();
    const formData = new FormData();

    Object.keys(data).forEach((key) => {
      if (typeof data[key] === "object") {
        formData.append(key, JSON.stringify(data[key]));
      } else {
        formData.append(key, data[key]);
      }
    });

    formData.append("template", currentLayout);
    formData.append("font", currentFontClass);
    formData.append("photoShape", currentPhotoShape);

    const fileInput = document.getElementById("photo-upload");
    if (fileInput.files[0]) {
      formData.append("photo", fileInput.files[0]);
    }

    fetch("api/save_trainer.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((response) => {
        if (response.status === "success") {
          window.location.href = `/trainer_profile/success.php?id=${response.id}`;
        } else {
          alert("❌ Failed to save profile: " + response.message);
        }
      })
      .catch((err) => {
        console.error(err);
        alert("❌ Server error");
      });
  };

  // ---------- LAYOUT & FONT SELECTORS ----------

  if (layoutSelector) {
    layoutSelector.addEventListener("change", (e) => {
      currentLayout = e.target.value;
      generateResumePreview();
    });
  }

  if (fontSelector) {
    fontSelector.addEventListener("change", (e) => {
      currentFontClass = e.target.value;
      generateResumePreview();
    });
  }


  // ---------- INITIALIZE ----------

  if (experienceContainer) window.addExperience(true);
  if (educationContainer) window.addEducation(true);
  if (skillsContainer) window.addSkill(true);
  if (languagesContainer) window.addLanguage(true);
  if (achievementsContainer) window.addAchievement(true);

  setStep(1);

  if (window.lucide) {
    lucide.createIcons();
  }
});

// ---------- YEAR INPUT HANDLING (OUTSIDE DOMContentLoaded) ----------

document.addEventListener("input", function (e) {
  if (e.target.classList.contains("year-input")) {
    e.target.value = e.target.value.replace(/\D/g, "");

    if (e.target.value.length > 4) {
      e.target.value = e.target.value.slice(0, 4);
    }
  }
});