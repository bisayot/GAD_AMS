<?php
require_once 'database.php';
require_once 'auth.php';

// If user is already logged in, redirect to their dashboard
if (is_logged_in()) {
    redirect_by_role($_SESSION['user_role']);
}

// Session is already started by auth.php
$registerError = '';
$registerSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $universityId = trim($_POST['university_id'] ?? '');
    $department = trim($_POST['department'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (!$fullname || !$universityId || !$department || !$email || !$password || !$confirmPassword) {
        $registerError = 'Please fill in all required fields.';
    } elseif ($password !== $confirmPassword) {
        $registerError = 'Passwords do not match.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registerError = 'Please provide a valid email address.';
    } else {
        // Ensure uniqueness for email/username
        $username = strtolower(str_replace(' ', '_', explode('@', $email)[0]));
        $role = 'college';

        $stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1');
        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()) {
            $registerError = 'A user with that email or username already exists.';
            $stmt->close();
        } else {
            $stmt->close();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmtInsert = $mysqli->prepare('INSERT INTO users (username, email, password, role, full_name, student_id, college, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
            if (!$stmtInsert) {
                die('Prepare failed: ' . $mysqli->error);
            }
            $stmtInsert->bind_param('sssssss', $username, $email, $hashedPassword, $role, $fullname, $universityId, $department);
            if ($stmtInsert->execute()) {
                $registerSuccess = 'Account created successfully. Please log in.';
            } else {
                $registerError = 'Unable to create account. Please try again later.';
            }
            $stmtInsert->close();
        }
    }
}
?>

<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Sign Up | BSU GAD Portal</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "surface-container": "#eceef0",
                        "on-tertiary-fixed-variant": "#5e4100",
                        "error-container": "#ffdad6",
                        primary: "#6100a4",
                        "secondary-fixed": "#b1f0ce",
                        "on-error": "#ffffff",
                        "secondary-container": "#aeeecb",
                        "on-tertiary-container": "#ffc65c",
                        "on-background": "#191c1e",
                        outline: "#7e7384",
                        "surface-container-low": "#f2f4f6",
                        "on-tertiary-fixed": "#271900",
                        "tertiary-container": "#755200",
                        "secondary-fixed-dim": "#95d4b3",
                        "primary-container": "#7b2cbf",
                        "surface-container-high": "#e6e8ea",
                        "surface-bright": "#f8f9fb",
                        "on-secondary-fixed-variant": "#0e5138",
                        "surface-variant": "#e0e3e5",
                        "on-error-container": "#93000a",
                        "on-tertiary": "#ffffff",
                        "on-secondary-container": "#316e52",
                        "inverse-on-surface": "#eff1f3",
                        "surface-container-highest": "#e0e3e5",
                        "on-primary-fixed-variant": "#680eac",
                        "surface-tint": "#8234c6",
                        "on-primary-fixed": "#2d0050",
                        "on-primary": "#ffffff",
                        surface: "#f8f9fb",
                        "surface-dim": "#d8dadc",
                        "outline-variant": "#cfc2d5",
                        "primary-fixed-dim": "#deb7ff",
                        "on-surface-variant": "#4c4353",
                        "on-secondary": "#ffffff",
                        secondary: "#2c694e",
                        "primary-fixed": "#f1dbff",
                        "on-primary-container": "#e4c2ff",
                        error: "#ba1a1a",
                        tertiary: "#573c00",
                        "on-secondary-fixed": "#002114",
                        "tertiary-fixed-dim": "#ffba27",
                        background: "#f8f9fb",
                        "surface-container-lowest": "#ffffff",
                        "inverse-primary": "#deb7ff",
                        "tertiary-fixed": "#ffdea9",
                        "inverse-surface": "#2d3133",
                        "on-surface": "#191c1e"
                    },
                    fontFamily: {
                        headline: ["Manrope"],
                        body: ["Manrope"],
                        label: ["Manrope"],
                        display: "Manrope"
                    },
                    borderRadius: {
                        DEFAULT: "0.125rem",
                        lg: "0.25rem",
                        xl: "0.5rem",
                        full: "0.75rem"
                    }
                }
            }
        };
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .ambient-shadow {
            box-shadow: 0 8px 24px rgba(26, 28, 29, 0.04);
        }
    </style>
</head>

<body class="bg-background font-body text-on-surface">

    <?php include 'header.php'; ?>

    <main class="min-h-screen pt-24 pb-16 px-4 flex flex-col items-center justify-center">
        <!-- Asymmetric Bento-style Layout -->
        <div class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
            <!-- Branding & Info Column (The Academic Curator Style) -->
            <div class="md:col-span-5 flex flex-col gap-10 pr-0 md:pr-12">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 bg-secondary-container/20 text-on-secondary-container px-3 py-1 rounded-full">
                        <span class="material-symbols-outlined text-[18px]">verified_user</span>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em] font-label">Official Registration</span>
                    </div>
                    <h1 class="text-5xl font-extrabold font-headline tracking-tighter text-primary leading-tight">
                        Empowering Equality through <span class="text-secondary italic">Scholarly Action.</span>
                    </h1>
                    <p class="text-on-surface-variant text-lg leading-relaxed max-w-md">
                        Join the Benguet State University Gender and Development portal. Register to access academic resources, track seminars, and contribute to our inclusive community.
                    </p>
                </div>
                <div class="relative w-full aspect-square rounded-xl overflow-hidden shadow-2xl">
                    <img alt="Academic Building" class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-700" data-alt="Modern university campus architecture with purple floral landscaping under a bright clear sky, clean aesthetic, academic atmosphere" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8nYYL7wB7f7aO6xD9YmqF4a5EEvWxuG3gjap1VnrmGH6QoUtPdArd-LTbR6PAmLIf1R6P4DDC-2s_ZM1ncYyMn1i5wbipjXlYbmzfUM9NGZ8sEh9affvurdentdsie0DQNMXroezFpxyGlz_a0TkDVXF4F7T-9Y5dOjyOWhwggU1Wd2aUR3QcpIg4azf8r6vcJ-8yZCqmyID0XMuhcJ7iibjgZO2qVBoKJ_IBmUSgxB7wsD_3wjdtCnZxOfIEJQouT_McUGz5E8v3" />
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6">
                        <p class="text-white text-sm font-label uppercase tracking-widest opacity-80 mb-2">Heritage &amp; Excellence</p>
                        <p class="text-white font-headline font-bold text-xl">Serving the Highlands since 1916.</p>
                    </div>
                </div>
            </div>
            <!-- Registration Form Canvas -->
            <div class="md:col-span-7 bg-surface-container-lowest rounded-xl p-8 md:p-12 shadow-[0_8px_24px_rgba(26,28,29,0.04)]">
                <div class="mb-10">
                    <h2 class="text-2xl font-bold font-headline text-on-surface mb-2">Create Academic Profile</h2>
                    <p class="text-on-surface-variant text-sm">Please provide your institutional credentials to begin.</p>
                </div>
                <form class="space-y-8" method="POST" action="">
                    <?php if ($registerError): ?>
                        <div class="rounded-md bg-red-100 border border-red-200 text-red-700 px-4 py-3 text-sm"><?php echo htmlspecialchars($registerError); ?></div>
                    <?php endif; ?>
                    <?php if ($registerSuccess): ?>
                        <div class="rounded-md bg-green-100 border border-green-200 text-green-700 px-4 py-3 text-sm"><?php echo htmlspecialchars($registerSuccess); ?></div>
                    <?php endif; ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Full Name -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500" for="fullname">Full Name</label>
                            <input class="bg-surface-container-low border-none rounded-sm px-4 py-3 text-on-surface focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 placeholder:text-slate-400" id="fullname" name="fullname" placeholder="Dela Cruz, Juan A." type="text" />
                        </div>
                        <!-- University ID -->
                        <div class="flex flex-col gap-2">
                            <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500" for="university_id">University ID</label>
                            <input class="bg-surface-container-low border-none rounded-sm px-4 py-3 text-on-surface focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 placeholder:text-slate-400" id="university_id" name="university_id" placeholder="2024-XXXX" type="text" />
                        </div>
                        <!-- Department -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500" for="department">Department / Office</label>
                            <select class="bg-surface-container-low border-none rounded-sm px-4 py-3 text-on-surface focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 appearance-none" id="department" name="department">
                                <option disabled="" selected="" value="">Select your academic unit</option>
                                <option value="cas">College of Arts and Sciences</option>
                                <option value="ca">College of Agriculture</option>
                                <option value="cte">College of Teacher Education</option>
                                <option value="cen">College of Engineering</option>
                                <option value="admin">Administrative Office</option>
                            </select>
                        </div>
                        <!-- Email Address -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500" for="email">Institutional Email</label>
                            <input class="bg-surface-container-low border-none rounded-sm px-4 py-3 text-on-surface focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 placeholder:text-slate-400" id="email" name="email" placeholder="username@bsu.edu.ph" type="email" required />
                        </div>
                        <!-- Password -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500" for="password">Password</label>
                            <input class="bg-surface-container-low border-none rounded-sm px-4 py-3 text-on-surface focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 placeholder:text-slate-400" id="password" name="password" placeholder="Enter password" type="password" required />
                        </div>
                        <!-- Confirm Password -->
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-xs uppercase tracking-widest font-label font-bold text-slate-500" for="confirm_password">Confirm Password</label>
                            <input class="bg-surface-container-low border-none rounded-sm px-4 py-3 text-on-surface focus:ring-0 focus:bg-surface-bright focus:border-b-2 focus:border-primary transition-all duration-200 placeholder:text-slate-400" id="confirm_password" name="confirm_password" placeholder="Confirm password" type="password" required />
                        </div>
                    </div>
                    <!-- Terms & Agreement -->
                    <div class="flex items-start gap-3 pt-4">
                        <div class="flex items-center h-5">
                            <input class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary/20" id="terms" name="terms" type="checkbox" />
                        </div>
                        <div class="text-xs text-on-surface-variant leading-relaxed">
                            I agree to the <a class="text-primary font-bold hover:underline" href="#">Data Privacy Policy</a> of Benguet State University and consent to the collection and processing of my institutional data for academic purposes.
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="flex flex-col gap-4 pt-4">
                        <button class="w-full bg-gradient-to-br from-primary to-primary-container text-white py-4 rounded-full font-headline font-bold text-sm tracking-widest uppercase hover:opacity-90 transition-all duration-300 shadow-lg shadow-primary/20 flex items-center justify-center gap-2" type="submit">
                            Initialize Registration
                            <span class="material-symbols-outlined text-[18px]">arrow_right_alt</span>
                        </button>
                        <p class="text-center text-[10px] text-slate-400 font-label tracking-widest uppercase">
                            Security managed by BSU ICT Management Office
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <!-- Footer - Shell Visibility Hierarchy Applied -->
    <footer class="w-full py-12 bg-slate-50 border-t border-slate-200/10">
        <div class="flex flex-col items-center justify-center gap-6 px-4">
            <div class="flex gap-8">
                <a class="text-xs uppercase tracking-widest font-label text-slate-400 hover:text-primary transition-colors" href="#">Privacy Policy</a>
                <a class="text-xs uppercase tracking-widest font-label text-slate-400 hover:text-primary transition-colors" href="#">Terms of Service</a>
                <a class="text-xs uppercase tracking-widest font-label text-slate-400 hover:text-primary transition-colors" href="#">University Portal</a>
            </div>
            <p class="text-[10px] uppercase tracking-widest font-label text-slate-400 text-center">
                © 2024 Benguet State University Gender and Development Office
            </p>
        </div>
    </footer>
</body>

</html>