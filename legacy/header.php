<!-- TopAppBar -->
<nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-md shadow-sm dark:shadow-none">
<div class="flex justify-between items-center w-full px-8 py-4 max-w-screen-2xl mx-auto">
<div class="text-xl font-bold tracking-tighter text-purple-900 dark:text-purple-100 uppercase font-headline">
                BSU GAD Office
            </div>

<div class="hidden md:flex items-center gap-8 font-manrope tracking-tight font-semibold">
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$nav_items = [
    ['href' => 'index.php', 'label' => 'Home'],
    ['href' => 'about_us.php', 'label' => 'About Us'],
    ['href' => 'mandates.php', 'label' => 'Resources'],
    ['href' => 'gad_corner.php', 'label' => 'GAD Corner'],
    ['href' => 'contact.php', 'label' => 'Contact']
];

foreach ($nav_items as $item):
    $is_active = $current_page === $item['href'];
    $active_class = $is_active ? 'text-purple-700 dark:text-purple-400 font-bold border-b-2 border-purple-600 pb-1' : 'text-slate-600 dark:text-slate-400 hover:text-purple-600';
?>
    <a class="<?php echo $active_class; ?> transition-colors" href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a>
<?php endforeach; ?>
</div>
<div class="flex items-center gap-4">
<div class="relative hidden lg:block">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm" data-icon="search">search</span>
<input class="bg-surface-container-low border-none rounded-sm pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary w-64 transition-all" placeholder="Search resources..." type="text"/>
</div>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
if ($currentPage === 'login.php') {
    // On login page, show sign-up CTA
    echo '<a href="register.php" class="bg-[#422b68] text-white px-5 py-2 rounded-full font-label text-sm font-semibold tracking-wide hover:opacity-90 transition-all active:scale-95 duration-200 inline-block text-center">Sign Up</a>';
} else {
    // On any other page, show login CTA
    echo '<span class="text-xs uppercase tracking-widest text-slate-400 font-label">Already have an account?</span><a href="login.php" class="bg-gradient-to-br from-primary to-primary-container text-on-primary px-6 py-2 rounded-full font-label text-sm font-semibold hover:scale-95 duration-200 transition-all uppercase tracking-wider inline-block text-center">Portal Login</a>';
}
?>

</div>
</div>
</nav>