(function () {
    const cfg = window.klinikaTheme || {};
    const restUrl = cfg.restUrl || '/wp-json/klinika/v1/';

    const closeDropdowns = () => {
        document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
        document.querySelectorAll('[data-dropdown-icon]').forEach((i) => i.classList.remove('rotate-180'));
        document.querySelectorAll('[data-dropdown-btn]').forEach((b) => b.setAttribute('aria-expanded', 'false'));
        document.querySelectorAll('.nav-chevron').forEach((b) => b.setAttribute('aria-expanded', 'false'));
    };

    document.querySelectorAll('[data-dropdown]').forEach((dropdown) => {
        const btn = dropdown.querySelector('[data-dropdown-btn]');
        const menu = dropdown.querySelector('[data-dropdown-menu]');
        const icon = dropdown.querySelector('[data-dropdown-icon]');
        const chevron = dropdown.querySelector('.nav-chevron');
        if (!btn || !menu) return;

        const toggle = (e) => {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = !menu.classList.contains('hidden');
            closeDropdowns();
            if (!isOpen) {
                menu.classList.remove('hidden');
                if (icon) icon.classList.add('rotate-180');
                btn.setAttribute('aria-expanded', 'true');
                if (chevron) chevron.setAttribute('aria-expanded', 'true');
            }
        };

        if (chevron) chevron.addEventListener('click', toggle);
        else btn.addEventListener('click', toggle);
    });

    document.addEventListener('click', closeDropdowns);

    const menuBtn = document.querySelector('[data-menu-btn]');
    const nav = document.querySelector('[data-nav]');
    const navList = document.querySelector('[data-nav-list]');
    const lockScroll = (lock) => {
        document.documentElement.style.overflow = lock ? 'hidden' : '';
        document.body.style.overflow = lock ? 'hidden' : '';
    };
    if (menuBtn && nav) {
        menuBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = nav.classList.toggle('is-open');
            if (navList) navList.classList.toggle('is-open', isOpen);
            menuBtn.classList.toggle('is-open', isOpen);
            menuBtn.setAttribute('aria-expanded', String(isOpen));
            document.body.classList.toggle('is-nav-open', isOpen);
            lockScroll(isOpen);
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                nav.classList.remove('is-open');
                if (navList) navList.classList.remove('is-open');
                menuBtn.classList.remove('is-open');
                menuBtn.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('is-nav-open');
                lockScroll(false);
            }
        });
    }

    /* ——— Booking modal ——— */
    const modal = document.querySelector('[data-booking-modal]');
    const dialog = modal && modal.querySelector('[data-booking-dialog]');
    const state = { service: '', doctor: '', date: '', time: '', year: 0, month: 0 };

    const showStep = (name) => {
        if (!modal) return;
        modal.querySelectorAll('[data-booking-step]').forEach((el) => {
            el.classList.toggle('hidden', el.getAttribute('data-booking-step') !== name);
        });
        if (name === 'calendar' && dialog) {
            dialog.classList.add('is-calendar');
            renderCalendar();
        } else if (dialog) {
            dialog.classList.remove('is-calendar');
        }
        if (name === 'time') {
            const view = modal.querySelector('[data-booking-date-view]');
            if (view) view.textContent = state.date || '';
            const submit = modal.querySelector('[data-booking-submit]');
            if (submit) submit.disabled = !state.time;
        }
    };

    const openModal = () => {
        if (!modal) return;
        modal.classList.add('is-open');
        showStep('form');
        lockScroll(true);
    };
    const closeModal = () => {
        if (!modal) return;
        modal.classList.remove('is-open');
        lockScroll(false);
        state.service = '';
        state.doctor = '';
        state.date = '';
        state.time = '';
        showStep('form');
    };

    document.querySelectorAll('[data-booking-open]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal();
        });
    });
    document.querySelectorAll('[data-booking-close]').forEach((btn) => btn.addEventListener('click', closeModal));
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        modal.querySelectorAll('[data-booking-dropdown]').forEach((dd) => {
            const btn = dd.querySelector('[data-booking-select-btn]');
            const menu = dd.querySelector('[data-booking-select-menu]');
            const label = dd.querySelector('[data-booking-select-label]');
            const hidden = dd.querySelector('input[type="hidden"]');
            if (!btn || !menu) return;
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const open = menu.classList.contains('hidden');
                modal.querySelectorAll('[data-booking-select-menu]').forEach((m) => m.classList.add('hidden'));
                modal.querySelectorAll('[data-booking-dropdown]').forEach((d) => d.classList.remove('is-open'));
                if (open) {
                    menu.classList.remove('hidden');
                    dd.classList.add('is-open');
                }
            });
            menu.querySelectorAll('button[data-value]').forEach((opt) => {
                opt.addEventListener('click', () => {
                    const val = opt.getAttribute('data-value') || '';
                    if (label) label.textContent = val;
                    if (hidden) hidden.value = val;
                    if (dd.getAttribute('data-booking-dropdown') === 'service') state.service = val;
                    if (dd.getAttribute('data-booking-dropdown') === 'doctor') state.doctor = val;
                    menu.classList.add('hidden');
                    dd.classList.remove('is-open');
                    opt.classList.add('is-active');
                    menu.querySelectorAll('button').forEach((b) => {
                        if (b !== opt) b.classList.remove('is-active');
                    });
                });
            });
        });

        document.addEventListener('click', () => {
            modal.querySelectorAll('[data-booking-select-menu]').forEach((m) => m.classList.add('hidden'));
            modal.querySelectorAll('[data-booking-dropdown]').forEach((d) => d.classList.remove('is-open'));
        });

        const now = new Date();
        state.year = now.getFullYear();
        state.month = now.getMonth();

        const monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

        function renderCalendar() {
            const grid = modal.querySelector('[data-cal-grid]');
            const label = modal.querySelector('[data-cal-label]');
            const nextBtn = modal.querySelector('[data-booking-next="time"]');
            if (!grid || !label) return;
            label.textContent = monthNames[state.month] + ' ' + state.year;
            grid.innerHTML = '';
            const first = new Date(state.year, state.month, 1);
            let start = (first.getDay() + 6) % 7;
            const daysInMonth = new Date(state.year, state.month + 1, 0).getDate();
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let i = 0; i < start; i++) {
                const empty = document.createElement('span');
                grid.appendChild(empty);
            }
            for (let d = 1; d <= daysInMonth; d++) {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'booking-cal-day';
                btn.textContent = String(d);
                const dateObj = new Date(state.year, state.month, d);
                const iso = [
                    state.year,
                    String(state.month + 1).padStart(2, '0'),
                    String(d).padStart(2, '0'),
                ].join('-');
                if (dateObj < today) {
                    btn.classList.add('is-past');
                    btn.disabled = true;
                } else {
                    btn.classList.add('is-available');
                    btn.addEventListener('click', () => {
                        grid.querySelectorAll('.booking-cal-day').forEach((el) => el.classList.remove('is-selected'));
                        btn.classList.add('is-selected');
                        state.date = iso;
                        if (nextBtn) nextBtn.disabled = false;
                    });
                }
                if (state.date === iso) btn.classList.add('is-selected');
                grid.appendChild(btn);
            }
            if (nextBtn) nextBtn.disabled = !state.date;
        }

        const calPrev = modal.querySelector('[data-cal-prev]');
        const calNext = modal.querySelector('[data-cal-next]');
        if (calPrev) {
            calPrev.addEventListener('click', () => {
                state.month -= 1;
                if (state.month < 0) {
                    state.month = 11;
                    state.year -= 1;
                }
                renderCalendar();
            });
        }
        if (calNext) {
            calNext.addEventListener('click', () => {
                state.month += 1;
                if (state.month > 11) {
                    state.month = 0;
                    state.year += 1;
                }
                renderCalendar();
            });
        }

        modal.querySelectorAll('[data-booking-next]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const step = btn.getAttribute('data-booking-next');
                if (step === 'calendar') {
                    const name = (modal.querySelector('[data-booking-name]') || {}).value || '';
                    const phone = (modal.querySelector('[data-booking-phone]') || {}).value || '';
                    if (!name.trim() || !phone.trim()) {
                        alert('Укажите имя и телефон');
                        return;
                    }
                }
                if (step === 'time' && !state.date) return;
                showStep(step);
            });
        });

        modal.querySelectorAll('[data-booking-back]').forEach((btn) => {
            btn.addEventListener('click', () => showStep(btn.getAttribute('data-booking-back')));
        });

        modal.querySelectorAll('[data-time]').forEach((btn) => {
            btn.addEventListener('click', () => {
                modal.querySelectorAll('[data-time]').forEach((el) => el.classList.remove('is-selected'));
                btn.classList.add('is-selected');
                state.time = btn.getAttribute('data-time') || '';
                const submit = modal.querySelector('[data-booking-submit]');
                if (submit) submit.disabled = !state.time;
            });
        });

        const submitBtn = modal.querySelector('[data-booking-submit]');
        if (submitBtn) {
            submitBtn.addEventListener('click', async () => {
                const name = (modal.querySelector('[data-booking-name]') || {}).value || '';
                const phone = (modal.querySelector('[data-booking-phone]') || {}).value || '';
                submitBtn.disabled = true;
                try {
                    const res = await fetch(restUrl + 'booking', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            name: name.trim(),
                            phone: phone.trim(),
                            service: state.service,
                            doctor: state.doctor,
                            date: state.date,
                            time: state.time,
                        }),
                    });
                    if (!res.ok) throw new Error('fail');
                    showStep('success');
                } catch (err) {
                    alert('Не удалось отправить заявку. Попробуйте ещё раз.');
                    submitBtn.disabled = false;
                }
            });
        }
    }

    /* ——— Gallery mobile slider ——— */
    const galleryItems = document.querySelectorAll('[data-gallery-item]');
    let galleryIndex = 0;
    const showGallery = (index) => {
        if (window.innerWidth >= 640 || !galleryItems.length) return;
        galleryIndex = (index + galleryItems.length) % galleryItems.length;
        galleryItems.forEach((el, i) => {
            el.classList.toggle('hidden', i !== galleryIndex);
        });
    };
    const prevG = document.querySelector('[data-gallery-prev]');
    const nextG = document.querySelector('[data-gallery-next]');
    if (prevG) prevG.addEventListener('click', () => showGallery(galleryIndex - 1));
    if (nextG) nextG.addEventListener('click', () => showGallery(galleryIndex + 1));

    /* ——— Specialists filter ——— */
    const filterRoot = document.querySelector('[data-specialisty-filter]');
    if (filterRoot) {
        const toggle = filterRoot.querySelector('[data-specialisty-filter-toggle]');
        const list = filterRoot.querySelector('[data-specialisty-filter-list]');
        const label = filterRoot.querySelector('[data-specialisty-filter-label]');
        const icon = filterRoot.querySelector('[data-specialisty-filter-icon]');
        const backdrop = filterRoot.querySelector('[data-specialisty-backdrop]');

        const closeFilter = () => {
            if (list) list.classList.add('hidden');
            if (backdrop) backdrop.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        };
        const openFilter = () => {
            if (list) list.classList.remove('hidden');
            if (backdrop) backdrop.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
        };

        if (toggle) {
            toggle.addEventListener('click', (e) => {
                e.stopPropagation();
                if (list && list.classList.contains('hidden')) openFilter();
                else closeFilter();
            });
        }
        if (backdrop) backdrop.addEventListener('click', closeFilter);

        filterRoot.querySelectorAll('[data-specialty]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const slug = btn.getAttribute('data-specialty') || 'all';
                const text = btn.getAttribute('data-label') || btn.textContent;
                if (label) label.textContent = text;
                filterRoot.setAttribute('data-active-specialty', slug);
                applyDoctorFilters();
                closeFilter();
            });
        });
        document.addEventListener('click', (e) => {
            if (!filterRoot.contains(e.target)) closeFilter();
        });
    }

    const applyDoctorFilters = () => {
        const root = document.querySelector('[data-specialisty-filter]');
        const slug = (root && root.getAttribute('data-active-specialty')) || 'all';
        const homeOnly = !!(document.querySelector('[data-home-filter]') || {}).checked;
        document.querySelectorAll('[data-doctor-card]').forEach((card) => {
            const specs = (card.getAttribute('data-specialty') || 'all').split(',');
            const homeOk = !homeOnly || card.getAttribute('data-home') === '1';
            const show = (slug === 'all' || specs.includes(slug)) && homeOk;
            card.classList.toggle('hidden', !show);
        });
    };
    const homeFilter = document.querySelector('[data-home-filter]');
    if (homeFilter) homeFilter.addEventListener('change', applyDoctorFilters);

    /* ——— Price filters ——— */
    const priceFilters = document.querySelector('[data-price-filters]');
    if (priceFilters) {
        const apply = () => {
            const min = parseInt((priceFilters.querySelector('[data-price-min]') || {}).value || '0', 10) || 0;
            const max = parseInt((priceFilters.querySelector('[data-price-max]') || {}).value || '999999', 10) || 999999;
            const group = (priceFilters.querySelector('[data-price-group]') || {}).value || 'all';
            document.querySelectorAll('[data-price-group-block]').forEach((block) => {
                const g = block.getAttribute('data-price-group-block');
                block.classList.toggle('hidden', group !== 'all' && group !== g);
            });
            document.querySelectorAll('[data-price-row]').forEach((row) => {
                const val = parseInt(row.getAttribute('data-price-value') || '0', 10) || 0;
                const g = row.getAttribute('data-price-group') || '';
                const inGroup = group === 'all' || group === g;
                row.classList.toggle('hidden', !(inGroup && val >= min && val <= max));
            });
        };
        priceFilters.querySelectorAll('input, select').forEach((el) => el.addEventListener('input', apply));
        priceFilters.querySelectorAll('select').forEach((el) => el.addEventListener('change', apply));
    }

    /* ——— Cookie + a11y + CTA analytics ——— */
    const cookieBanner = document.querySelector('[data-cookie-banner]');
    if (cookieBanner) {
        if (!localStorage.getItem('klinika_cookie_ok')) {
            cookieBanner.classList.remove('hidden');
        }
        const accept = cookieBanner.querySelector('[data-cookie-accept]');
        if (accept) {
            accept.addEventListener('click', () => {
                localStorage.setItem('klinika_cookie_ok', '1');
                cookieBanner.classList.add('hidden');
            });
        }
    }

    let fontScale = parseFloat(localStorage.getItem('klinika_font_scale') || '1');
    const applyFont = () => {
        document.documentElement.style.setProperty('--klinika-font-scale', String(fontScale));
        document.documentElement.style.fontSize = (16 * fontScale) + 'px';
    };
    applyFont();
    document.querySelectorAll('[data-a11y-font]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dir = btn.getAttribute('data-a11y-font');
            fontScale = dir === 'up' ? Math.min(1.35, fontScale + 0.1) : Math.max(0.9, fontScale - 0.1);
            localStorage.setItem('klinika_font_scale', String(fontScale));
            applyFont();
        });
    });
    const contrastBtn = document.querySelector('[data-a11y-contrast]');
    if (contrastBtn) {
        if (localStorage.getItem('klinika_contrast') === '1') {
            document.documentElement.classList.add('klinika-high-contrast');
        }
        contrastBtn.addEventListener('click', () => {
            const on = document.documentElement.classList.toggle('klinika-high-contrast');
            localStorage.setItem('klinika_contrast', on ? '1' : '0');
        });
    }

    const trackCta = (key) => {
        if (!key) return;
        fetch(restUrl + 'cta', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cta: key }),
        }).catch(() => {});
    };
    document.querySelectorAll('[data-cta]').forEach((el) => {
        el.addEventListener('click', () => trackCta(el.getAttribute('data-cta')));
    });

    /* Prefill doctor/service from CTA */
    document.querySelectorAll('[data-booking-open]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const doc = btn.getAttribute('data-booking-doctor') || '';
            const svc = btn.getAttribute('data-booking-service') || '';
            if (!modal) return;
            if (doc) {
                state.doctor = doc;
                const dd = modal.querySelector('[data-booking-dropdown="doctor"]');
                if (dd) {
                    const labelEl = dd.querySelector('[data-booking-select-label]');
                    const hidden = dd.querySelector('input[type="hidden"]');
                    if (labelEl) labelEl.textContent = doc;
                    if (hidden) hidden.value = doc;
                }
            }
            if (svc) {
                state.service = svc;
                const dd = modal.querySelector('[data-booking-dropdown="service"]');
                if (dd) {
                    const labelEl = dd.querySelector('[data-booking-select-label]');
                    const hidden = dd.querySelector('input[type="hidden"]');
                    if (labelEl) labelEl.textContent = svc;
                    if (hidden) hidden.value = svc;
                }
            }
        });
    });

    /* Reveal + header shadow */
    const revealEls = document.querySelectorAll('[data-reveal]');
    if (revealEls.length && 'IntersectionObserver' in window) {
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        revealEls.forEach((el) => io.observe(el));
    } else {
        revealEls.forEach((el) => el.classList.add('is-visible'));
    }

    const siteHeader = document.querySelector('[data-site-header]');
    if (siteHeader) {
        const onScroll = () => siteHeader.classList.toggle('is-scrolled', window.scrollY > 12);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    document.querySelectorAll('main:not([id])').forEach((main) => {
        main.id = 'main-content';
    });
})();
