
function nl2br(str) {
    return (str || "").replace(/\n/g, "<br>");
}

// ---------- Render helpers ----------
function renderExperience(experiences) {
    if (!experiences || !experiences.length) {
        return `<p class="text-xs text-gray-500">No experience added.</p>`;
    }
    return experiences
        .map((exp) => {
            const dateStr = [exp.startDate, exp.endDate || "Present"]
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
            const years = [ed.startYear, ed.endYear]
                .filter(Boolean)
                .join(" - ");
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
                <span class="text-gray-500">${s.level}</span>
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
                <span class="text-gray-500">${l.level}</span>
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
        <ul class="list-disc ml-4 space-y-1 text-xs text-gray-700">
            ${achievements
                .map((a) => `<li>${nl2br(a)}</li>`)
                .join("")}
        </ul>
    `;
}

function buildPhotoHtml(initials, photoUrl, shape) {
    const shapeClass =
        shape === "circle" ? "rounded-full" : "rounded-md";

    if (photoUrl) {
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
}

// ---------- Layout functions ----------
function generateLayout1(data, fontClass = 'font-sans', photoShape = 'circle', photoUrl = null) {
    const fullName =
        `${data.first_name || data.firstName || ""} ${data.last_name || data.lastName || ""}`.trim() || "Your Name";
    const initials =
        (data.first_name?.[0] || data.firstName?.[0] || "") + (data.last_name?.[0] || data.lastName?.[0] || "");
    const summaryHtml = nl2br(data.summary || "");

    return `
        <div class="w-full h-full flex flex-col ${fontClass}">
            <div class="bg-resume-primary text-white px-8 py-6 flex items-center gap-6">
                ${buildPhotoHtml(initials, photoUrl, photoShape)}
                <div class="flex-1">
                    <h1 class="text-2xl font-bold tracking-wide">${fullName}</h1>
                    <p class="text-sm opacity-90 mt-1">${data.title || ""}</p>
                    <div class="flex flex-wrap gap-3 text-xs mt-3 opacity-90">
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

            <div class="flex-1 px-8 py-6 bg-white text-gray-800 text-sm">
                <div class="grid grid-cols-3 gap-6 h-full">
                    <div class="col-span-2 space-y-4">
                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Profile</h2>
                            <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                            <p class="text-xs text-gray-700 leading-relaxed">${summaryHtml}</p>
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Experience</h2>
                            <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                            ${renderExperience(data.experience)}
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Achievements</h2>
                            <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                            ${renderAchievements(data.achievements)}
                        </section>
                    </div>

                    <div class="space-y-4">
                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Education</h2>
                            <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                            ${renderEducation(data.education)}
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Skills</h2>
                            <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                            ${renderSkills(data.skills)}
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-primary uppercase mb-1">Languages</h2>
                            <div class="h-0.5 w-10 bg-resume-primary mb-2"></div>
                            ${renderLanguages(data.languages)}
                        </section>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function generateLayout2(data, fontClass = 'font-sans', photoShape = 'circle', photoUrl = null) {
    const fullName =
        `${data.first_name || data.firstName || ""} ${data.last_name || data.lastName || ""}`.trim() || "Your Name";
    const initials =
        (data.first_name?.[0] || data.firstName?.[0] || "") + (data.last_name?.[0] || data.lastName?.[0] || "");
    const summaryHtml = nl2br(data.summary || "");

    return `
        <div class="w-full h-full flex ${fontClass}">
            <!-- Sidebar -->
            <div class="w-[32%] bg-resume-blue text-white px-6 py-8 flex flex-col gap-6">
                <div class="flex flex-col items-center text-center gap-2">
                    ${buildPhotoHtml(initials, photoUrl, photoShape)}
                    <h1 class="text-lg font-semibold mt-3">${fullName}</h1>
                    <p class="text-xs opacity-90">${data.title || ""}</p>
                </div>

                <div class="text-xs space-y-3">
                    <h2 class="uppercase text-[10px] tracking-widest font-semibold opacity-80">Contact</h2>
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

                <div class="text-xs space-y-3">
                    <h2 class="uppercase text-[10px] tracking-widest font-semibold opacity-80">Skills</h2>
                    ${renderSkills(data.skills)}
                </div>

                <div class="text-xs space-y-3">
                    <h2 class="uppercase text-[10px] tracking-widest font-semibold opacity-80">Languages</h2>
                    ${renderLanguages(data.languages)}
                </div>
            </div>

            <!-- Main content -->
            <div class="flex-1 bg-white px-8 py-8 text-sm text-gray-800 space-y-5">
                <section>
                    <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Profile</h2>
                    <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                    <p class="text-xs text-gray-700 leading-relaxed">${summaryHtml}</p>
                </section>

                <section>
                    <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Experience</h2>
                    <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                    ${renderExperience(data.experience)}
                </section>

                <section>
                    <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Education</h2>
                    <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                    ${renderEducation(data.education)}
                </section>

                <section>
                    <h2 class="text-xs font-semibold tracking-widest text-resume-blue uppercase mb-1">Achievements</h2>
                    <div class="h-0.5 w-10 bg-resume-blue mb-2"></div>
                    ${renderAchievements(data.achievements)}
                </section>
            </div>
        </div>
    `;
}

function generateLayout3(data, fontClass = 'font-sans', photoShape = 'circle', photoUrl = null) {
    const fullName =
        `${data.first_name || data.firstName || ""} ${data.last_name || data.lastName || ""}`.trim() || "Your Name";
    const initials =
        (data.first_name?.[0] || data.firstName?.[0] || "") + (data.last_name?.[0] || data.lastName?.[0] || "");
    const summaryHtml = nl2br(data.summary || "");

    const shapeClass =
        photoShape === "circle" ? "rounded-full" : "rounded-md";

    const photoBlock = photoUrl
        ? `<img src="${photoUrl}" class="w-20 h-20 object-cover ${shapeClass} border border-gray-300" />`
        : `<div class="w-20 h-20 ${shapeClass} bg-gray-200 border border-gray-300 flex items-center justify-center text-xl font-bold uppercase text-gray-600">
                ${initials || "?"}
            </div>`;

    return `
        <div class="w-full h-full flex flex-col ${fontClass}">
            <div class="px-8 py-6 bg-gray-50 border-b flex items-center gap-6">
                ${photoBlock}
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-900">${fullName}</h1>
                    <p class="text-sm text-gray-700 mt-1">${data.title || ""}</p>
                </div>
                <div class="text-xs text-gray-600 space-y-1">
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

            <div class="flex-1 px-8 py-6 bg-white text-gray-800 text-sm">
                <section class="mb-4">
                    <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Profile</h2>
                    <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                    <p class="text-xs text-gray-700 leading-relaxed">${summaryHtml}</p>
                </section>

                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-2 space-y-4">
                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Experience</h2>
                            <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                            ${renderExperience(data.experience)}
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Achievements</h2>
                            <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                            ${renderAchievements(data.achievements)}
                        </section>
                    </div>

                    <div class="space-y-4">
                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Education</h2>
                            <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                            ${renderEducation(data.education)}
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Skills</h2>
                            <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                            ${renderSkills(data.skills)}
                        </section>

                        <section>
                            <h2 class="text-xs font-semibold tracking-widest text-resume-slate uppercase mb-1">Languages</h2>
                            <div class="h-[2px] w-10 bg-resume-slate mb-2"></div>
                            ${renderLanguages(data.languages)}
                        </section>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Export globally
window.resumeLayouts = {
    generateLayout1,
    generateLayout2,
    generateLayout3
};
