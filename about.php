<?php
/**
 * NOVA Creative Digital Agency
 * About Us Page (about.php)
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/helpers.php';

$pageTitle = 'About Us — The Story Behind NOVA®';
$pageDesc = 'Discover our philosophy, core values, visionary leadership team, and the collaborative process that turns ambitious ideas into digital benchmarks.';

require_once __DIR__ . '/includes/header.php';
?>

<!-- About Hero Section -->
<section class="py-24 bg-primary-dark border-b border-[#222222] relative overflow-hidden">
    <div class="absolute -right-20 top-0 w-96 h-96 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-4xl">
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-mono uppercase tracking-wider text-accent bg-accent/10 border border-accent/20 mb-6">
                OUR STORY &amp; VISION
            </span>
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-heading font-black text-white tracking-tight leading-[1.08] mb-8">
                We craft digital identities that define the next era of business.
            </h1>
            <p class="text-xl sm:text-2xl text-offwhite/70 font-light leading-relaxed max-w-3xl">
                Founded with a singular conviction: ambitious brands deserve digital experiences that reflect their highest ambitions, engineered without compromise.
            </p>
        </div>
    </div>
</section>

<!-- Company Story & Vision/Mission -->
<section class="py-24 bg-[#141414]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-start">
            <div class="lg:col-span-5 sticky top-28">
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">ORIGIN STORY</span>
                <h2 class="text-3xl sm:text-4xl font-heading font-extrabold text-white leading-tight mb-6">
                    Redefining what a creative agency can be.
                </h2>
                <div class="p-6 rounded-2xl bg-primary-dark border border-[#2B2B2B] space-y-4">
                    <div>
                        <span class="font-mono text-xs text-muted-gray uppercase block mb-1">FOUNDED IN</span>
                        <span class="font-heading font-bold text-white text-lg">2019 — Jakarta &amp; Distributed</span>
                    </div>
                    <div>
                        <span class="font-mono text-xs text-muted-gray uppercase block mb-1">SPECIALIZATION</span>
                        <span class="font-heading font-bold text-white text-lg">Brand Identity, UI/UX, &amp; Web Engineering</span>
                    </div>
                    <div>
                        <span class="font-mono text-xs text-muted-gray uppercase block mb-1">CLIENT GLOBAL REACH</span>
                        <span class="font-heading font-bold text-white text-lg">Southeast Asia, Europe, North America</span>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-12 text-offwhite/80 leading-relaxed font-light text-lg">
                <div>
                    <h3 class="text-2xl font-heading font-bold text-white mb-4">The Need for Craft in a Standardized World</h3>
                    <p class="mb-4">
                        In an era saturated with cookie-cutter website builders and templated UI kits, true distinction has become the scarcest commodity. NOVA was built to counter digital indifference.
                    </p>
                    <p>
                        We operate as an agile creative studio combining the strategic acumen of a global consultancy with the experimental daring of an independent boutique. Every project we touch is treated as a piece of digital architecture — designed to endure, scale, and convert.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-[#262626]">
                    <div class="p-8 rounded-xl bg-primary-dark border border-[#2A2A2A]">
                        <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center text-accent mb-4">
                            <i data-lucide="eye" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-heading font-bold text-white text-xl mb-3">Our Vision</h4>
                        <p class="text-sm text-offwhite/70 leading-relaxed">
                            To be the premier creative catalyst for transformative brands worldwide, setting the standard for digital design and engineering excellence.
                        </p>
                    </div>

                    <div class="p-8 rounded-xl bg-primary-dark border border-[#2A2A2A]">
                        <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center text-accent mb-4">
                            <i data-lucide="target" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-heading font-bold text-white text-xl mb-3">Our Mission</h4>
                        <p class="text-sm text-offwhite/70 leading-relaxed">
                            To empower ambitious entrepreneurs and enterprises with uncompromising visual identities, intuitive product interfaces, and resilient digital architectures.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section (PRD 4.2) -->
<section class="py-24 bg-primary-dark border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-20">
            <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">OUR CORE VALUES</span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                Principles that Guide Every Decision.
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Value 1: Creativity -->
            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent transition-colors">
                <span class="font-mono text-xs text-accent font-bold">01 // VALUE</span>
                <h3 class="text-2xl font-heading font-bold text-white mt-4 mb-3">Creativity</h3>
                <p class="font-mono text-xs text-accent mb-4">Thinking beyond boundaries.</p>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    We reject safe defaults and formulaic approaches. We seek inventive visual languages and distinct narrative angles that make our clients impossible to ignore.
                </p>
            </div>

            <!-- Value 2: Innovation -->
            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent transition-colors">
                <span class="font-mono text-xs text-accent font-bold">02 // VALUE</span>
                <h3 class="text-2xl font-heading font-bold text-white mt-4 mb-3">Innovation</h3>
                <p class="font-mono text-xs text-accent mb-4">Embracing new possibilities.</p>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Technology never stands still, and neither do we. We constantly explore emerging web capabilities, interactive frameworks, and algorithmic design workflows.
                </p>
            </div>

            <!-- Value 3: Collaboration -->
            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent transition-colors">
                <span class="font-mono text-xs text-accent font-bold">03 // VALUE</span>
                <h3 class="text-2xl font-heading font-bold text-white mt-4 mb-3">Collaboration</h3>
                <p class="font-mono text-xs text-accent mb-4">Creating better things together.</p>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    The best digital work emerges from mutual respect and unfiltered dialogue. We treat our clients as intellectual partners, co-creating solutions shoulder to shoulder.
                </p>
            </div>

            <!-- Value 4: Excellence -->
            <div class="p-8 rounded-2xl bg-[#171717] border border-[#2B2B2B] hover:border-accent transition-colors">
                <span class="font-mono text-xs text-accent font-bold">04 // VALUE</span>
                <h3 class="text-2xl font-heading font-bold text-white mt-4 mb-3">Excellence</h3>
                <p class="font-mono text-xs text-accent mb-4">Delivering quality in every detail.</p>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Excellence is not an accident; it is the sum of disciplined choices. From semantic markup to micro-typography, we sweat the details that others overlook.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Proven Company Statistics (PRD 4.2) -->
<section class="py-20 bg-[#161616] border-t border-b border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <div class="font-heading text-4xl sm:text-5xl font-extrabold text-accent">8+</div>
                <div class="font-mono text-xs text-white uppercase mt-2 font-bold tracking-wider">Years of Track Record</div>
                <p class="text-xs text-offwhite/50 mt-1 font-light">Established since 2019</p>
            </div>
            <div>
                <div class="font-heading text-4xl sm:text-5xl font-extrabold text-accent">120+</div>
                <div class="font-mono text-xs text-white uppercase mt-2 font-bold tracking-wider">Projects Shipped</div>
                <p class="text-xs text-offwhite/50 mt-1 font-light">Across 14 global markets</p>
            </div>
            <div>
                <div class="font-heading text-4xl sm:text-5xl font-extrabold text-accent">98%</div>
                <div class="font-mono text-xs text-white uppercase mt-2 font-bold tracking-wider">Client Retention &amp; NPS</div>
                <p class="text-xs text-offwhite/50 mt-1 font-light">Direct client survey ratings</p>
            </div>
            <div>
                <div class="font-heading text-4xl sm:text-5xl font-extrabold text-accent">15+</div>
                <div class="font-mono text-xs text-white uppercase mt-2 font-bold tracking-wider">Design Awards</div>
                <p class="text-xs text-offwhite/50 mt-1 font-light">Awwwards, FWA, CSSDA honors</p>
            </div>
        </div>
    </div>
</section>

<!-- Team Member Profiles (PRD 4.2) -->
<section class="py-24 bg-primary-dark">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16">
            <div>
                <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">LEADERSHIP &amp; CRAFT</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                    The Minds Behind NOVA.
                </h2>
            </div>
            <a href="<?= url('careers.php') ?>" class="mt-4 md:mt-0 font-mono text-xs text-accent hover:text-white transition-colors flex items-center gap-1.5 font-bold">
                <span>JOIN OUR GROWING TEAM</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Team 1 -->
            <div class="p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B] flex flex-col">
                <div class="aspect-square rounded-xl bg-charcoal border border-[#333333] mb-6 overflow-hidden flex items-center justify-center">
                    <img src="<?= asset('images/avatar-1.svg') ?>" alt="Alex Nova" class="w-24 h-24">
                </div>
                <h3 class="font-heading font-bold text-white text-xl">Alex Nova</h3>
                <span class="font-mono text-xs text-accent block mb-3">Founder &amp; Creative Director</span>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    12+ years directing global design systems and brand identity initiatives for tech unicorns and cultural institutions.
                </p>
            </div>

            <!-- Team 2 -->
            <div class="p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B] flex flex-col">
                <div class="aspect-square rounded-xl bg-charcoal border border-[#333333] mb-6 overflow-hidden flex items-center justify-center">
                    <img src="<?= asset('images/avatar-2.svg') ?>" alt="Elena Rostova" class="w-24 h-24">
                </div>
                <h3 class="font-heading font-bold text-white text-xl">Elena Rostova</h3>
                <span class="font-mono text-xs text-accent block mb-3">Head of Product Design</span>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Specialist in complex SaaS workflows, consumer fintech applications, and modular enterprise design tokens.
                </p>
            </div>

            <!-- Team 3 -->
            <div class="p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B] flex flex-col">
                <div class="aspect-square rounded-xl bg-charcoal border border-[#333333] mb-6 overflow-hidden flex items-center justify-center">
                    <img src="<?= asset('images/avatar-3.svg') ?>" alt="Marcus Vance" class="w-24 h-24">
                </div>
                <h3 class="font-heading font-bold text-white text-xl">Marcus Vance</h3>
                <span class="font-mono text-xs text-accent block mb-3">Technical Director &amp; Engineering Lead</span>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Full-stack web architect obsessive about sub-second load times, headless CMS engineering, and WebGL experiences.
                </p>
            </div>

            <!-- Team 4 -->
            <div class="p-6 rounded-2xl bg-[#171717] border border-[#2B2B2B] flex flex-col">
                <div class="aspect-square rounded-xl bg-charcoal border border-[#333333] mb-6 overflow-hidden flex items-center justify-center">
                    <div class="w-24 h-24 rounded-full bg-accent/20 border border-accent/40 flex items-center justify-center text-accent font-heading font-bold text-2xl">
                        SW
                    </div>
                </div>
                <h3 class="font-heading font-bold text-white text-xl">Sarah Wijaya</h3>
                <span class="font-mono text-xs text-accent block mb-3">Head of Brand Strategy</span>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Brand storyteller and positioning strategist helping high-growth startups craft magnetic market narratives.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Working Philosophy & 4-Step Process (PRD 4.2) -->
<section class="py-24 bg-[#141414] border-t border-[#222222]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mb-16">
            <span class="font-mono text-xs text-accent uppercase tracking-widest block mb-3">HOW WE WORK</span>
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-heading font-extrabold text-white tracking-tight">
                Our 4-Stage Collaboration Framework.
            </h2>
            <p class="mt-4 text-offwhite/70 font-light text-base">
                A proven, transparent methodology designed to eliminate surprises and maximize measurable output.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B] relative">
                <span class="font-mono text-3xl font-black text-accent/30 block mb-4">01</span>
                <h3 class="text-xl font-heading font-bold text-white mb-2">Discovery &amp; Research</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Deep stakeholder interviews, category benchmarking, user persona analysis, and technical requirement audits.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B] relative">
                <span class="font-mono text-3xl font-black text-accent/30 block mb-4">02</span>
                <h3 class="text-xl font-heading font-bold text-white mb-2">Strategy &amp; Concept</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Information architecture, moodboards, creative direction exploration, and wireframe prototypes for alignment.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B] relative">
                <span class="font-mono text-3xl font-black text-accent/30 block mb-4">03</span>
                <h3 class="text-xl font-heading font-bold text-white mb-2">Design &amp; Execution</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    High-fidelity visual design, interactive component token systems, and clean semantic code implementation.
                </p>
            </div>

            <div class="p-8 rounded-2xl bg-primary-dark border border-[#2B2B2B] relative">
                <span class="font-mono text-3xl font-black text-accent/30 block mb-4">04</span>
                <h3 class="text-xl font-heading font-bold text-white mb-2">Launch &amp; Growth</h3>
                <p class="text-sm text-offwhite/70 font-light leading-relaxed">
                    Rigorous QA across all viewports, Lighthouse performance tuning, secure deployment, and ongoing analytics monitoring.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Call to action block -->
<?php require_once __DIR__ . '/includes/cta-banner.php'; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

