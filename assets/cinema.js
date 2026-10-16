/*
 * The cinematic layer's moving parts: embers on the stage, a torch that
 * follows the pointer, page transitions where the browser has none, titles
 * and story text that write themselves in, choices on number keys, and the
 * battle effects app.js fires after each round. Purely presentational: it
 * never changes game state, and it runs after app.js's own ready handler.
 */

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(pointer: fine)').matches;

/* ---------- Embers ---------- */

const PALETTES = {
    ember: [[255, 176, 96], [255, 132, 52], [255, 214, 150]],
    battle: [[255, 112, 60], [255, 70, 48], [255, 168, 92]],
    blood: [[214, 38, 38], [160, 20, 26], [255, 90, 70]],
};

class Embers {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.mood = 'ember';
        this.parts = [];
        this.sprites = new Map();
        this.running = false;
        this.resize = this.resize.bind(this);
        this.frame = this.frame.bind(this);
        this.resize();
        window.addEventListener('resize', this.resize);
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.running = false;
            } else {
                this.start();
            }
        });
    }

    // A soft glowing dot per colour, drawn once and stamped every frame.
    sprite(rgb) {
        const key = rgb.join(',');
        if (!this.sprites.has(key)) {
            const s = document.createElement('canvas');
            s.width = s.height = 32;
            const g = s.getContext('2d');
            const grad = g.createRadialGradient(16, 16, 0, 16, 16, 16);
            grad.addColorStop(0, 'rgba(255,255,240,1)');
            grad.addColorStop(0.18, `rgba(${key},0.95)`);
            grad.addColorStop(0.45, `rgba(${key},0.28)`);
            grad.addColorStop(1, `rgba(${key},0)`);
            g.fillStyle = grad;
            g.fillRect(0, 0, 32, 32);
            this.sprites.set(key, s);
        }
        return this.sprites.get(key);
    }

    resize() {
        const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
        this.w = window.innerWidth;
        this.h = window.innerHeight;
        this.canvas.width = Math.round(this.w * dpr);
        this.canvas.height = Math.round(this.h * dpr);
        this.ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        this.target = Math.round(Math.min(80, (this.w * this.h) / 20000));
    }

    spawn(scatter) {
        const palette = PALETTES[this.mood];
        const blood = this.mood === 'blood';
        return {
            x: Math.random() * this.w,
            y: scatter ? Math.random() * this.h : this.h + 12,
            vx: (Math.random() - 0.5) * 0.25,
            vy: -(blood ? 0.12 : 0.22) - Math.random() * (blood ? 0.25 : 0.6),
            size: 4 + Math.random() * 9,
            alpha: 0.35 + Math.random() * 0.6,
            life: 0,
            max: 500 + Math.random() * 900,
            phase: Math.random() * Math.PI * 2,
            rgb: palette[Math.floor(Math.random() * palette.length)],
            drag: 1,
        };
    }

    setMood(mood) {
        if (!PALETTES[mood] || mood === this.mood) {
            return;
        }
        this.mood = mood;
        const palette = PALETTES[mood];
        this.parts.forEach((p) => { p.rgb = palette[Math.floor(Math.random() * palette.length)]; });
    }

    // A shower of sparks from one point (a killing blow, a victory).
    burst(x, y, count = 60, rgb = [255, 210, 130]) {
        for (let i = 0; i < count; i++) {
            const angle = Math.random() * Math.PI * 2;
            const speed = 1.5 + Math.random() * 6;
            this.parts.push({
                x, y,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed - 1.2,
                size: 4 + Math.random() * 7,
                alpha: 0.9,
                life: 0,
                max: 50 + Math.random() * 60,
                phase: Math.random() * 6,
                rgb,
                drag: 0.95,
                spark: true,
            });
        }
        this.start();
    }

    start() {
        if (this.running || document.hidden) {
            return;
        }
        this.running = true;
        requestAnimationFrame(this.frame);
    }

    frame(t) {
        if (!this.running) {
            return;
        }
        const { ctx } = this;
        ctx.clearRect(0, 0, this.w, this.h);
        ctx.globalCompositeOperation = 'lighter';

        const ambient = this.parts.filter((p) => !p.spark).length;
        const want = this.mood === 'battle' ? this.target * 1.5 : this.target;
        for (let i = ambient; i < want; i++) {
            this.parts.push(this.spawn(this.parts.length < want / 2));
        }

        this.parts = this.parts.filter((p) => {
            p.life++;
            p.vx *= p.drag;
            p.vy *= p.drag;
            p.x += p.vx + (p.spark ? 0 : Math.sin(p.life / 55 + p.phase) * 0.28);
            p.y += p.vy;
            if (p.life > p.max || p.y < -20 || p.x < -20 || p.x > this.w + 20) {
                return false;
            }
            const fade = Math.min(1, p.life / 60, (p.max - p.life) / 90);
            const flicker = 0.65 + 0.35 * Math.sin(t / 180 + p.phase * 7);
            ctx.globalAlpha = Math.max(0, p.alpha * fade * flicker);
            const s = p.size;
            ctx.drawImage(this.sprite(p.rgb), p.x - s / 2, p.y - s / 2, s, s);
            return true;
        });
        ctx.globalAlpha = 1;
        requestAnimationFrame(this.frame);
    }
}

let embers = null;

function initStage() {
    const canvas = document.querySelector('.stage__embers');
    if (canvas && !reduceMotion && canvas.getContext) {
        embers = new Embers(canvas);
        embers.start();
    }

    // The torch follows the pointer, a frame at a time.
    const torch = document.querySelector('.stage__torch');
    if (torch && finePointer && !reduceMotion) {
        let x = window.innerWidth / 2;
        let y = window.innerHeight * 0.2;
        let queued = false;
        window.addEventListener('pointermove', (e) => {
            x = e.clientX;
            y = e.clientY;
            if (!queued) {
                queued = true;
                requestAnimationFrame(() => {
                    torch.style.setProperty('--tx', `${x}px`);
                    torch.style.setProperty('--ty', `${y}px`);
                    queued = false;
                });
            }
        }, { passive: true });
    }
}

/* ---------- Leaving a page ---------- */

const crossDocumentTransitions = 'onpageswap' in window;

function isPlainNavigation(e, a) {
    if (!a || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
        return false;
    }
    const href = a.getAttribute('href');
    if (!href || href.startsWith('#') || href.startsWith('javascript:') || a.target || a.hasAttribute('download') || a.dataset.noTransition !== undefined) {
        return false;
    }
    const here = window.location;
    return a.origin === here.origin
        && !(a.pathname === here.pathname && a.search === here.search && a.hash);
}

function navigate(href, delay) {
    if (!crossDocumentTransitions && !reduceMotion) {
        document.documentElement.classList.add('is-leaving');
        delay = Math.max(delay, 240);
    }
    window.setTimeout(() => { window.location.href = href; }, reduceMotion ? 0 : delay);
}

function initTransitions() {
    // Back/forward cache: come back to a clean page, not a half-faded one.
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            document.documentElement.classList.remove('is-leaving');
            document.querySelectorAll('.is-choosing, .is-chosen').forEach((el) => el.classList.remove('is-choosing', 'is-chosen'));
        }
    });

    if (crossDocumentTransitions || reduceMotion) {
        return;
    }
    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!isPlainNavigation(e, a) || a.closest('.choice-list')) {
            return;
        }
        e.preventDefault();
        navigate(a.href, 0);
    });
}

/* ---------- Titles and reveals ---------- */

// Splits a title into words that rise one after another.
function splitTitles() {
    document.querySelectorAll('[data-split]').forEach((el) => {
        const text = el.textContent.trim();
        el.setAttribute('aria-label', text);
        el.textContent = '';
        text.split(/\s+/).forEach((word, i, all) => {
            const span = document.createElement('span');
            span.className = 'split-word';
            span.setAttribute('aria-hidden', 'true');
            span.style.setProperty('--i', i);
            span.textContent = i < all.length - 1 ? `${word} ` : word;
            el.appendChild(span);
        });
    });
}

// Content below the fold rises into view as it's scrolled to. Content
// already on screen has its own entrance in CSS, so it isn't touched.
function initReveals() {
    if (reduceMotion || !('IntersectionObserver' in window)) {
        return;
    }
    const candidates = document.querySelectorAll('.help-section, .faq-item, .help-group__title, .home-card, .merchant-panel');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px' });
    candidates.forEach((el) => {
        if (el.getBoundingClientRect().top > window.innerHeight) {
            el.setAttribute('data-reveal', '');
            observer.observe(el);
        }
    });
}

// Light that follows the pointer across glowing cards, and cards that lean.
function initLight() {
    if (!finePointer || reduceMotion) {
        return;
    }
    document.querySelectorAll('.home-card').forEach((card) => card.classList.add('has-glow', 'tilt'));

    document.addEventListener('pointermove', (e) => {
        const el = e.target.closest && e.target.closest('.has-glow');
        if (!el) {
            return;
        }
        const r = el.getBoundingClientRect();
        const px = (e.clientX - r.left) / r.width;
        const py = (e.clientY - r.top) / r.height;
        el.style.setProperty('--mx', `${px * 100}%`);
        el.style.setProperty('--my', `${py * 100}%`);
        if (el.classList.contains('tilt')) {
            el.classList.add('is-tilting');
            el.style.setProperty('--rx', `${(0.5 - py) * 9}deg`);
            el.style.setProperty('--ry', `${(px - 0.5) * 11}deg`);
        }
    }, { passive: true });

    document.querySelectorAll('.tilt').forEach((el) => {
        el.addEventListener('pointerleave', () => {
            el.classList.remove('is-tilting');
            el.style.setProperty('--rx', '0deg');
            el.style.setProperty('--ry', '0deg');
        });
    });
}

/* ---------- The story ---------- */

const storyState = { pending: false, finish: () => {} };

function storyKey() {
    const adventure = document.getElementById('adventure');
    const paragraph = document.getElementById('paragraph');
    return adventure && paragraph ? `cyoa:seen:${adventure.textContent.trim()}:${paragraph.textContent.trim()}` : null;
}

// Wraps each word of the story in a span that inks in after the one before.
// Screen readers get the plain text; the animated copy is hidden from them.
function inkStory(box) {
    let count = 0;
    const words = [];
    box.querySelectorAll('p').forEach((p, index) => {
        const text = p.textContent;
        if (!text.trim()) {
            return;
        }
        const plain = document.createElement('span');
        plain.className = 'sr-only';
        plain.textContent = text;
        const shown = document.createElement('span');
        shown.setAttribute('aria-hidden', 'true');

        let rest = text.replace(/^\s+/, '');
        if (index === 0 && /^\p{L}/u.test(rest)) {
            const cap = document.createElement('span');
            cap.className = 'dropcap';
            const glyph = document.createElement('span');
            glyph.className = 'dropcap__glyph';
            glyph.textContent = rest.charAt(0);
            cap.appendChild(glyph);
            shown.appendChild(cap);
            rest = rest.slice(1);
            box.classList.add('has-dropcap');
        }

        rest.split(/(\s+)/).forEach((token) => {
            if (!token) {
                return;
            }
            if (/^\s+$/.test(token)) {
                shown.appendChild(document.createTextNode(token));
                return;
            }
            const word = document.createElement('span');
            word.className = 'ink';
            word.textContent = token;
            shown.appendChild(word);
            words.push(word);
            count++;
        });
        p.replaceChildren(plain, shown);
    });

    // About two and a half seconds whatever the length, within reason.
    const step = Math.max(12, Math.min(38, 2500 / Math.max(count, 1)));
    words.forEach((word, i) => { word.style.animationDelay = `${Math.round(300 + i * step)}ms`; });
    return 300 + count * step + 700;
}

function initStory() {
    const box = document.getElementById('paragraphText');
    if (!box) {
        return;
    }
    const body = document.body;
    const key = storyKey();
    let seen = false;
    try {
        seen = key !== null && window.sessionStorage.getItem(key) === '1';
        if (key) {
            window.sessionStorage.setItem(key, '1');
        }
    } catch (e) {
        seen = false;
    }

    // Mid-battle, after buying something, or for reduced motion: the same
    // illuminated page, but no retelling.
    if (reduceMotion || seen || document.getElementById('battleEnemy')) {
        try {
            inkStory(box);
        } catch (e) {
            // The plain drop cap in CSS still stands in.
        }
        box.classList.add('is-revealed');
        body.classList.add('story-ready');
        return;
    }

    body.classList.add('story-pending');
    let timer = 0;
    const finish = () => {
        if (!storyState.pending) {
            return;
        }
        storyState.pending = false;
        window.clearTimeout(timer);
        box.classList.remove('is-revealing');
        box.classList.add('is-revealed');
        body.classList.remove('story-pending');
        body.classList.add('story-ready');
    };
    storyState.pending = true;
    storyState.finish = finish;

    try {
        const duration = inkStory(box);
        box.classList.add('is-revealing');
        timer = window.setTimeout(finish, duration);
    } catch (e) {
        finish();
        return;
    }

    document.querySelector('.story-card')?.addEventListener('click', finish);
}

function initChoices() {
    const list = document.getElementById('paragraphDirections');
    if (!list) {
        return;
    }

    const choose = (a) => {
        const wrapper = a.closest('.paragraphDirectionsEquipment');
        list.classList.add('is-choosing');
        wrapper?.classList.add('is-chosen');
        embers?.burst(a.getBoundingClientRect().right - 30, a.getBoundingClientRect().top + a.offsetHeight / 2, 24);
        navigate(a.href, 280);
    };

    list.addEventListener('click', (e) => {
        const a = e.target.closest('a.choice-btn');
        if (!a || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
            return;
        }
        e.preventDefault();
        choose(a);
    });

    // 1-9 picks a path; any of them first finishes the telling.
    document.addEventListener('keydown', (e) => {
        if (e.metaKey || e.ctrlKey || e.altKey || /^(INPUT|TEXTAREA|SELECT)$/.test(e.target.tagName)) {
            return;
        }
        if (storyState.pending && (e.key === ' ' || e.key === 'Enter' || /^[1-9]$/.test(e.key))) {
            e.preventDefault();
            storyState.finish();
            return;
        }
        if (!/^[1-9]$/.test(e.key) || list.classList.contains('hidden') || list.classList.contains('is-choosing')) {
            return;
        }
        const visible = [...list.querySelectorAll('a.choice-btn')].filter((a) => a.offsetParent !== null);
        const a = visible[Number(e.key) - 1];
        if (a) {
            e.preventDefault();
            choose(a);
        }
    });
}

/* ---------- The hero's condition and the mood of the scene ---------- */

function initVitals() {
    const stamina = document.querySelector('.stat[data-vital="stamina"]');
    const value = document.getElementById('gamestamina');
    if (stamina && value) {
        const start = parseFloat(stamina.dataset.start);
        const now = parseFloat(value.textContent);
        if (start > 0 && now > 0 && now / start <= 0.3) {
            stamina.classList.add('is-critical');
            document.body.classList.add('is-wounded');
        }
    }

    if (document.querySelector('#paragraphEnemy .encounter, #battleEnemy')) {
        document.body.classList.add('mood-battle');
        embers?.setMood('battle');
    }

    const gameover = document.getElementById('gameover');
    if (gameover && !gameover.classList.contains('hidden')) {
        document.body.classList.add('is-dead');
        document.body.classList.remove('is-wounded', 'mood-battle');
        embers?.setMood('blood');
    }
}

// Dice shown on screen tumble before they land on what was rolled.
function rollDice() {
    document.querySelectorAll('[data-roll]').forEach((el) => {
        const final = el.textContent.trim();
        if (reduceMotion || !/^\d+$/.test(final)) {
            return;
        }
        let ticks = 0;
        const spin = window.setInterval(() => {
            ticks++;
            if (ticks > 14) {
                window.clearInterval(spin);
                el.textContent = final;
                el.classList.add('stat-flash-up');
                return;
            }
            el.textContent = String(2 + Math.floor(Math.random() * 11));
        }, 55);
    });
}

/* ---------- Effects for battle rounds ---------- */

function transient(el, className, ms) {
    if (!el) {
        return;
    }
    el.classList.remove(className);
    void el.offsetWidth; // restart the animation
    el.classList.add(className);
    window.setTimeout(() => el.classList.remove(className), ms);
}

function overlay(className, ms) {
    const el = document.createElement('div');
    el.className = className;
    el.setAttribute('aria-hidden', 'true');
    document.body.appendChild(el);
    window.setTimeout(() => el.remove(), ms);
    return el;
}

export function banner(kicker, title, sub, variant) {
    if (reduceMotion) {
        return;
    }
    const el = overlay(`cine-banner${variant ? ` cine-banner--${variant}` : ''}`, variant === 'victory' ? 2700 : 1600);
    [['kicker', kicker], ['title', title], ['sub', sub]].forEach(([part, text]) => {
        if (text) {
            const span = document.createElement(part === 'sub' ? 'p' : 'span');
            span.className = `cine-banner__${part}`;
            span.textContent = text;
            el.appendChild(span);
        }
    });
}

function floatNumber(target, amount, kind) {
    if (!target || reduceMotion || !amount) {
        return;
    }
    const r = target.getBoundingClientRect();
    const el = overlay(`float-num float-num--${kind}`, 1300);
    el.textContent = amount > 0 ? `+${amount}` : `−${Math.abs(amount)}`;
    el.style.left = `${r.left + r.width / 2}px`;
    el.style.top = `${r.top + r.height / 2}px`;
}

function slash(target) {
    if (!target || reduceMotion) {
        return;
    }
    const el = document.createElement('span');
    el.className = 'cine-slash';
    el.setAttribute('aria-hidden', 'true');
    target.appendChild(el);
    window.setTimeout(() => el.remove(), 600);
}

/**
 * Called by app.js once per page load of a battle, after comparing this
 * round's numbers with the last ones it saw.
 */
export function battleRound({ round, playerDelta = 0, enemyDelta = 0, luckDelta = 0, enemyName = '', enemyDown = false, heroDown = false, first = false }) {
    if (reduceMotion) {
        return;
    }
    const shell = document.querySelector('.game-shell');
    const enemyCard = document.querySelector('.fighter--enemy');
    const playerCard = document.querySelector('.fighter--player');

    if (heroDown) {
        overlay('cine-flash cine-flash--damage', 800);
        return;
    }

    if (enemyDown) {
        overlay('cine-flash cine-flash--strike', 800);
        window.setTimeout(() => {
            banner('Enemy defeated', 'Victory', enemyName ? `${enemyName} falls.` : '', 'victory');
            embers?.burst(window.innerWidth / 2, window.innerHeight / 2, 90);
        }, 120);
        return;
    }

    banner(first ? 'The fight begins' : 'Round', first ? enemyName || 'Battle' : String(round));

    if (enemyDelta < 0) {
        window.setTimeout(() => {
            slash(enemyCard);
            transient(enemyCard, 'is-struck', 600);
            overlay('cine-flash cine-flash--strike', 700);
            floatNumber(document.getElementById('enemystamina'), enemyDelta, 'strike');
            if (enemyCard) {
                const r = enemyCard.getBoundingClientRect();
                embers?.burst(r.left + r.width / 2, r.top + r.height / 2, 30);
            }
        }, 450);
    }

    if (playerDelta < 0) {
        window.setTimeout(() => {
            overlay('cine-flash cine-flash--damage', 700);
            transient(shell, 'is-shaking', 500);
            transient(playerCard, 'is-struck', 600);
            floatNumber(document.getElementById('battleplayerstamina'), playerDelta, 'damage');
        }, enemyDelta < 0 ? 900 : 450);
    } else if (playerDelta > 0) {
        overlay('cine-flash cine-flash--heal', 700);
        floatNumber(document.getElementById('battleplayerstamina'), playerDelta, 'heal');
    }

    if (luckDelta !== 0) {
        window.setTimeout(() => floatNumber(document.getElementById('gameluck'), luckDelta, luckDelta > 0 ? 'heal' : 'damage'), 1100);
    }
}

/* ---------- Start ---------- */

export function initCinema() {
    initStage();
    initTransitions();
    splitTitles();
    initReveals();
    initLight();
    initStory();
    initChoices();
    initVitals();
    rollDice();
}
