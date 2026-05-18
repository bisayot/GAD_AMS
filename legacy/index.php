<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/><?php 
require_once 'auth.php';

// If user is already logged in, redirect to their dashboard
if (is_logged_in()) {
    redirect_by_role($_SESSION['user_role']);
}
?>
<title>BSU GAD Office | Empowering Equity through Academic Excellence</title>
<!-- Fonts -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
<!-- Material Symbols -->
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">tailwind.config = {darkMode: "class", theme: {extend: {colors: {"on-secondary-fixed-variant": "#0e5138", "on-secondary-container": "#316e52", "inverse-on-surface": "#eff1f3", "on-primary": "#ffffff", "on-background": "#191c1e", "on-primary-fixed-variant": "#680eac", "surface-variant": "#e0e3e5", "primary-fixed-dim": "#deb7ff", "primary-container": "#7b2cbf", "inverse-surface": "#2d3133", background: "#f8f9fb", "on-primary-container": "#e4c2ff", "surface-bright": "#f8f9fb", "secondary-container": "#aeeecb", "outline-variant": "#cfc2d5", "on-secondary": "#ffffff", error: "#ba1a1a", "surface-container-highest": "#e0e3e5", "tertiary-container": "#755200", "primary-fixed": "#f1dbff", "on-surface": "#191c1e", "on-error-container": "#93000a", "tertiary-fixed": "#ffdea9", surface: "#f8f9fb", "error-container": "#ffdad6", "on-tertiary-container": "#ffc65c", "surface-container-low": "#f2f4f6", "on-tertiary-fixed-variant": "#5e4100", "surface-dim": "#d8dadc", "secondary-fixed-dim": "#95d4b3", primary: "#6100a4", "surface-container-high": "#e6e8ea", "surface-tint": "#8234c6", "on-surface-variant": "#4c4353", "on-primary-fixed": "#2d0050", "tertiary-fixed-dim": "#ffba27", "surface-container-lowest": "#ffffff", "on-error": "#ffffff", tertiary: "#573c00", secondary: "#2c694e", "on-tertiary": "#ffffff", "on-tertiary-fixed": "#271900", "inverse-primary": "#deb7ff", outline: "#7e7384", "on-secondary-fixed": "#002114", "surface-container": "#eceef0", "secondary-fixed": "#b1f0ce"}, fontFamily: {headline: ["Manrope"], body: ["Manrope"], label: ["Manrope"], display: "Manrope"}, borderRadius: {DEFAULT: "0.125rem", lg: "0.25rem", xl: "0.5rem", full: "0.75rem"}}}};</script>
<style>
      .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
      }
      .glass-effect {
        backdrop-filter: blur(16px);
        background: rgba(255, 255, 255, 0.8);
      }
      .ambient-shadow {
        box-shadow: 0 8px 24px rgba(26, 28, 29, 0.04);
      }
      .hero-gradient {
        background: linear-gradient(135deg, rgba(66, 43, 104, 0.95) 0%, rgba(90, 66, 129, 0.8) 100%);
      }
    </style>
    <!-- SweetAlert2 CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      // Display alert if one was set in session
      document.addEventListener('DOMContentLoaded', function() {
        <?php 
        if (isset($_SESSION['alert']) && !empty($_SESSION['alert'])): 
            $alert = $_SESSION['alert'];
            $type = $alert['type'] ?? 'info';
            $title = $alert['title'] ?? '';
            $message = $alert['message'] ?? '';
        ?>
        Swal.fire({
          icon: '<?php echo htmlspecialchars($type); ?>',
          title: '<?php echo htmlspecialchars($title); ?>',
          text: '<?php echo htmlspecialchars($message); ?>',
          timer: 2000,
          timerProgressBar: true,
          showConfirmButton: false,
          allowOutsideClick: true,
          allowEscapeKey: true
        });
        <?php 
        unset($_SESSION['alert']);
        endif; 
        ?>
      });
    </script>
</head>
<body class="bg-background text-on-surface font-body selection:bg-primary-fixed selection:text-on-primary-fixed">

<?php include 'header.php'; ?>

<main class="pt-16">
<!-- Hero Section -->
<section class="relative h-[870px] flex items-center overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="BSU Campus" class="w-full h-full object-cover" data-alt="Modern university campus architecture with lush landscaping and academic buildings under a clear blue sky during golden hour" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDDOUMOuclAu-k7ZxqNy3VQ_eDix1HnIcagflCDc85LJXkGDsEM5Sgxa1CvVgzh4iIY7RfckhFP5FUlNeIR8z1t-R4G1waaca3q4vj-AWYjSYAkEnLBIirE9M2KG1AdckM8_KSH-clmPRC3Ea785_TWAHJVJ9KoM8v8HnM92z_mtbcezKmziJLx7Ddd2dTFzS1SGWsFgoBnStRBIP_ld3N7yVjn7AMHrnecgHjXnzb_IXG_mEhYnk_1e1NPEJX_eVTZERULs__o4bVw"/>
<div class="absolute inset-0 hero-gradient"></div>
</div>
<div class="relative z-10 max-w-screen-2xl mx-auto px-12 w-full">
<div class="max-w-3xl space-y-8">
<span class="inline-block px-4 py-1.5 rounded-full bg-secondary-container text-on-secondary-container font-label text-xs font-bold tracking-[0.2em] uppercase">
                        Center of Excellence
                    </span>
<h1 class="text-7xl font-headline font-extrabold text-on-primary tracking-tight leading-[1.1]">
                        Gender and <br/>Development Office
                    </h1>
<p class="text-xl text-on-primary-container font-body leading-relaxed max-w-xl opacity-90">
                        Empowering equity through academic excellence. We facilitate the mainstreaming of gender perspectives in all university development processes.
                    </p>
<div class="flex gap-4 pt-4">
<button class="bg-secondary text-on-secondary px-8 py-4 rounded-full font-headline font-bold text-lg hover:scale-95 transition-transform ambient-shadow">
                            Explore Resources
                        </button>
<button class="bg-white/10 backdrop-blur-md border border-white/20 text-white px-8 py-4 rounded-full font-headline font-bold text-lg hover:bg-white/20 transition-all">
                            Our Initiatives
                        </button>
</div>
</div>
</div>
</section>
<!-- Vision & Mission Section -->
<section class="py-24 bg-background px-12">
<div class="max-w-screen-2xl mx-auto grid md:grid-cols-2 gap-16 items-start">
<div class="space-y-12">
<div class="space-y-6">
<div class="flex items-center gap-4">
<div class="w-12 h-1 bg-secondary"></div>
<span class="font-label text-sm font-bold uppercase tracking-widest text-secondary">Our Purpose</span>
</div>
<h2 class="text-5xl font-headline font-extrabold text-primary">Vision &amp; Mission</h2>
</div>
<div class="p-10 rounded-xl bg-surface-container-lowest ambient-shadow border-l-4 border-primary">
<h3 class="text-2xl font-headline font-bold mb-4 text-primary">Vision</h3>
<p class="text-lg text-on-surface-variant leading-relaxed font-body">
                            A premier university delivering world-class education that is gender-responsive, inclusive, and empowering for all members of the academic community and its stakeholders.
                        </p>
</div>
</div>
<div class="mt-20 md:mt-0 space-y-8">
<div class="p-10 rounded-xl bg-surface-container-low border-l-4 border-secondary">
<h3 class="text-2xl font-headline font-bold mb-4 text-on-secondary-fixed-variant">Mission</h3>
<p class="text-lg text-on-surface-variant leading-relaxed font-body">
                            To integrate gender and development principles into the university's core functions—instruction, research, extension, and production—fostering an environment free from discrimination and bias.
                        </p>
</div>
<div class="grid grid-cols-2 gap-6">
<div class="bg-primary-container/10 p-8 rounded-xl border border-primary/5">
<span class="material-symbols-outlined text-primary text-4xl mb-4" data-icon="diversity_3">diversity_3</span>
<h4 class="font-headline font-bold text-primary">Inclusivity</h4>
<p class="text-sm text-on-surface-variant mt-2">Creating space for every voice within our academic halls.</p>
</div>
<div class="bg-secondary-container/10 p-8 rounded-xl border border-secondary/5">
<span class="material-symbols-outlined text-secondary text-4xl mb-4" data-icon="gavel">gavel</span>
<h4 class="font-headline font-bold text-on-secondary-fixed-variant">Equity</h4>
<p class="text-sm text-on-surface-variant mt-2">Ensuring fair access to opportunities and resources for all.</p>
</div>
</div>
</div>
</div>
</section>
<!-- GAD Corner / Resources (Bento Grid) -->
<section class="py-24 bg-surface-container-low px-12">
<div class="max-w-screen-2xl mx-auto">
<div class="flex justify-between items-end mb-16">
<div class="max-w-2xl">
<h2 class="text-5xl font-headline font-extrabold text-primary mb-6">GAD Corner</h2>
<p class="text-lg text-on-surface-variant font-body">Access institutional reports, training modules, and support services designed to foster a gender-sensitive university environment.</p>
</div>
<a class="flex items-center gap-2 text-primary font-bold hover:underline" href="#">
                        View All Resources 
                        <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Feature Card 1 -->
<div class="md:col-span-2 bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col md:flex-row ambient-shadow hover:-translate-y-1 transition-transform">
<div class="md:w-1/2 p-10 flex flex-col justify-between">
<div>
<span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-6 inline-block">Service</span>
<h3 class="text-3xl font-headline font-bold text-primary mb-4">Gender-responsive Services</h3>
<p class="text-on-surface-variant leading-relaxed">Counseling, support groups, and administrative assistance for gender-related concerns within the BSU community.</p>
</div>
<button class="mt-8 flex items-center gap-2 text-primary font-bold group">
                                Learn More <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform" data-icon="chevron_right">chevron_right</span>
</button>
</div>
<div class="md:w-1/2 h-64 md:h-auto">
<img alt="Counseling Session" class="w-full h-full object-cover" data-alt="Professional academic counseling setting with warm natural light and focused, supportive atmosphere" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAWfT6cC60lgVDULdoogzB-MqXy2W26P0mWQRwWZlGkE923q72CL7VsdlXrQ3ONVRtPpIAdz0loaZqNv752I6T4tWMpiGjfjFZ5fW24Sfoci_ohBW1qEVV9NE620yhOZ-daOtQ5_Ke3-ynCEfXJtIFHBYzVbaaW-eQSwnmTd5KrJTtgawPgSP7WFqL0f1fSXoc59OanXgqSLeqWF3vqqVO6Hx8TiUmjgINFqNzFOmzF5FN5tO8vU23t87uu5FIfJZCCqJ0rgoBGvnpJ"/>
</div>
</div>
<!-- Feature Card 2 -->
<div class="bg-primary text-on-primary p-10 rounded-xl ambient-shadow flex flex-col justify-between">
<span class="material-symbols-outlined text-5xl opacity-50" data-icon="menu_book" data-weight="fill">menu_book</span>
<div>
<h3 class="text-2xl font-headline font-bold mb-4">Training Modules</h3>
<p class="opacity-80 mb-6">Explore our curated curriculum on gender mainstreaming and sensitivity training.</p>
<button class="w-full py-3 bg-white/10 hover:bg-white/20 rounded-full font-bold transition-all border border-white/20">Access Modules</button>
</div>
</div>
<!-- Feature Card 3 -->
<div class="bg-surface-container-lowest p-10 rounded-xl ambient-shadow flex flex-col justify-between border-t-4 border-secondary">
<div>
<span class="material-symbols-outlined text-secondary text-4xl mb-4" data-icon="description">description</span>
<h3 class="text-2xl font-headline font-bold text-primary mb-4">Institutional Reports</h3>
<p class="text-on-surface-variant">Annual GAD accomplishment reports and strategic plans for the university.</p>
</div>
<div class="mt-8 space-y-3">
<a class="flex items-center justify-between p-3 rounded-lg hover:bg-surface-container-low transition-colors text-sm font-semibold border border-transparent hover:border-outline-variant/20" href="#">
                                2023 Annual Report <span class="material-symbols-outlined text-xs" data-icon="download">download</span>
</a>
<a class="flex items-center justify-between p-3 rounded-lg hover:bg-surface-container-low transition-colors text-sm font-semibold border border-transparent hover:border-outline-variant/20" href="#">
                                Strategic Plan 2024-2028 <span class="material-symbols-outlined text-xs" data-icon="download">download</span>
</a>
</div>
</div>
<!-- Feature Card 4 -->
<div class="md:col-span-2 bg-gradient-to-br from-primary-container to-primary p-12 rounded-xl text-on-primary relative overflow-hidden flex items-center">
<div class="relative z-10 max-w-lg">
<h3 class="text-3xl font-headline font-extrabold mb-4">Need Immediate Assistance?</h3>
<p class="text-on-primary-container text-lg mb-8">Our dedicated team is ready to support you with any gender-related grievances or information requests.</p>
<button class="bg-secondary text-on-secondary px-10 py-4 rounded-full font-bold shadow-xl hover:scale-105 transition-all">Contact GAD Desk</button>
</div>
<span class="material-symbols-outlined absolute -right-12 -bottom-12 text-[20rem] opacity-5 pointer-events-none" data-icon="shield_with_heart" data-weight="fill">shield_with_heart</span>
</div>
</div>
</div>
</section>
<!-- Institutional Impact -->
<section class="py-24 px-12 overflow-hidden">
<div class="max-w-screen-2xl mx-auto flex flex-col md:flex-row items-center gap-20">
<div class="md:w-1/2 relative">
<div class="absolute -top-10 -left-10 w-64 h-64 bg-secondary/10 rounded-full blur-3xl"></div>
<img alt="Graduation Celebration" class="rounded-2xl relative z-10 ambient-shadow" data-alt="Group of diverse university students in academic regalia celebrating graduation in a classic campus setting with stone columns" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDh0KBhsxdP0gE0QZYEWZIY0FELpV7ApFuXzbZyo45dKpDNcR6NsmKAQxFhRJClYYOXRxhHzpEiDCMwF7SLpbJZ1n6s2qQ1KJpd0RWypvHsul9lOK93s5NkoNIe3tHD33Og52rmgHUvePUbeHk92Ng4u6PNu6COLoyhJxHRpBS9dwlfY3T9eE0YsKtVEf6lWVRlnEevVu54yIeW54fp4i-cGRoSl55Lwy6WDpAGcfbo3NQbMNgCW0s83PUH1Y82bO0NBsw4rwLcMvG-"/>
<div class="absolute -bottom-8 -right-8 bg-white p-8 rounded-xl ambient-shadow z-20">
<div class="text-4xl font-headline font-extrabold text-primary">94%</div>
<div class="text-sm font-bold text-on-surface-variant uppercase tracking-tighter">Community Satisfaction</div>
</div>
</div>
<div class="md:w-1/2 space-y-8">
<h2 class="text-5xl font-headline font-extrabold text-primary">Institutional Impact</h2>
<p class="text-xl text-on-surface-variant leading-relaxed">
                        Since its inception, the GAD Office has been pivotal in reshaping the university's landscape, ensuring that policies and practices reflect the changing dynamics of gender roles in society.
                    </p>
<div class="grid grid-cols-2 gap-8 pt-4">
<div class="space-y-2">
<div class="text-3xl font-headline font-bold text-secondary">500+</div>
<div class="text-sm font-label font-bold text-on-surface-variant uppercase">Staff Trained Yearly</div>
</div>
<div class="space-y-2">
<div class="text-3xl font-headline font-bold text-secondary">25+</div>
<div class="text-sm font-label font-bold text-on-surface-variant uppercase">Active Initiatives</div>
</div>
<div class="space-y-2">
<div class="text-3xl font-headline font-bold text-secondary">100%</div>
<div class="text-sm font-label font-bold text-on-surface-variant uppercase">Policy Compliance</div>
</div>
<div class="space-y-2">
<div class="text-3xl font-headline font-bold text-secondary">12</div>
<div class="text-sm font-label font-bold text-on-surface-variant uppercase">Partner Agencies</div>
</div>
</div>
</div>
</div>
</section>
<!-- Announcements / Updates -->
<section class="py-24 bg-surface px-12">
<div class="max-w-screen-2xl mx-auto">
<div class="flex items-center justify-between mb-16">
<h2 class="text-4xl font-headline font-extrabold text-primary">Latest Updates</h2>
<button class="text-primary font-bold border-b-2 border-primary/20 hover:border-primary transition-all pb-1">All News</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
<div class="group cursor-pointer">
<div class="aspect-video rounded-xl overflow-hidden mb-6">
<img alt="Workshop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Academic workshop session with participants engaging in collaborative discussion in a modern classroom" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBGonyIq11H0YrKS7sHEaTR-bjeFNyTROAaqsERXHB9Z79MJ5ag2Fs6GJzPbstjh1ympo-Le226brKiUfws4O4BwAiNG0_kKviSSnZmNNcfQdGjF-QNCrhPBYIyJgPdBKNMurFj0Xy8tmR0LUDlZHFirkM9K0NGK0ypZxQt1jz5AKQEZ0rOnFrrBk_8mflk0H72-NscfpMg0SyhdZ24Ldm-1YKhcRHLGohuiuIBaHPfyfHk84gvEXzFLq4Ja1zA8JYOzkKRCJ9IPFKi"/>
</div>
<span class="text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2 block">Event • Oct 24, 2024</span>
<h3 class="text-xl font-headline font-bold text-primary group-hover:text-secondary transition-colors mb-3">Annual Gender Sensitivity Training for New Faculty</h3>
<p class="text-on-surface-variant line-clamp-2">The upcoming training sessions are designed to equip new academic staff with essential gender mainstreaming tools...</p>
</div>
<div class="group cursor-pointer">
<div class="aspect-video rounded-xl overflow-hidden mb-6">
<img alt="Students in Library" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Students of diverse backgrounds working together in a high-tech university library lounge" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCV4wDTnp7iABt-Xvr5EChTtGUouHrLJR0eW_Fb6DylccaUSNN-qB0xfKGpcwYuLUrDIonm5kt6cbbhYEXXQ8YSmkhWfx8SRv2f6Y8k0_Nidaig5ODfsxujeaWVwxpilgpkIOpzV5qZ703-2ZnkRbmx9-Wi7RU_w8vmKm8T1cmfW0SbC4GZkIOX3K0BPOWSVRHykkvUzeLhCLtjGdjDhH9oHOBXjlYUYw2OtmatQC1nvVCsBvAp00SuDWp3kK5L87uWuS1t_ZSsxn6n"/>
</div>
<span class="text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2 block">Announcement • Oct 15, 2024</span>
<h3 class="text-xl font-headline font-bold text-primary group-hover:text-secondary transition-colors mb-3">Launch of the 2024 Student Wellness Survey</h3>
<p class="text-on-surface-variant line-clamp-2">Participate in our comprehensive survey to help us better understand the needs of our diverse student body...</p>
</div>
<div class="group cursor-pointer">
<div class="aspect-video rounded-xl overflow-hidden mb-6">
<img alt="Legal Documents" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Detailed view of official academic documents and a professional pen on a wooden desk with soft focus background" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCWwpnHzXN7HQGd8e-Sj3uqxgW4Spw_svqxT7HfmG3iO5gyjDLmt9KcRjH79IiGVtSaLUiA0LCxIW3-8yCs0iUtNfwPdpfZwwi6bC_nMo0o9ktJoIZPkzE2ba_0tCki9tIxXo8Uv_HHUhAf3gOoIkhxo3NTfP0gY5sIQDiCDHqqB5jhHZROubJ1i0CmprL2uQzFqEeQwnCtgE-USUV2ZPv5rhUpAivz_pVzmKsDZbBtlf2bIR5Zv7qdBM7S-OAkDyH5LvE2-l4i1nFv"/>
</div>
<span class="text-xs font-bold text-secondary uppercase tracking-[0.2em] mb-2 block">Policy • Oct 02, 2024</span>
<h3 class="text-xl font-headline font-bold text-primary group-hover:text-secondary transition-colors mb-3">Updated Guidelines on Anti-Sexual Harassment</h3>
<p class="text-on-surface-variant line-clamp-2">The University Board has approved significant revisions to the ASH policies to enhance protection for the entire community...</p>
</div>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950">
<div class="flex flex-col md:flex-row justify-between items-center w-full px-12 py-10 gap-6 max-w-screen-2xl mx-auto">
<div class="flex flex-col gap-2">
<span class="font-manrope font-extrabold text-purple-900 dark:text-purple-100 text-lg uppercase tracking-wider">BSU GAD Office</span>
<p class="font-inter text-sm leading-relaxed text-slate-500 dark:text-slate-400 max-w-md">
                    © 2024 Benguet State University Gender and Development Office. All rights reserved.
                </p>
</div>
<div class="flex flex-wrap justify-center gap-8 font-inter text-sm leading-relaxed">
<a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">Privacy Policy</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">Terms of Service</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">University Portal</a>
<a class="text-slate-500 dark:text-slate-400 hover:text-purple-700 dark:hover:text-purple-400 underline transition-all opacity-80 hover:opacity-100" href="#">Contact Directory</a>
</div>
<div class="flex gap-4">
<a class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-purple-900 hover:bg-primary hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-xl" data-icon="facebook">social_leaderboard</span>
</a>
<a class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-purple-900 hover:bg-primary hover:text-white transition-all" href="#">
<span class="material-symbols-outlined text-xl" data-icon="mail">mail</span>
</a>
</div>
</div>
</footer>
</body></html>