<?php
// password_breach_checker.php
// Local password strength check + HaveIBeenPwned breach lookup (k-anonymity method)

function check_strength($password) {
    $length = strlen($password);
    $score = 0;
    if ($length >= 8) $score++;
    if (preg_match('/[A-Z]/', $password)) $score++;
    if (preg_match('/[a-z]/', $password)) $score++;
    if (preg_match('/[0-9]/', $password)) $score++;
    if (preg_match('/[^A-Za-z0-9]/', $password)) $score++;
    return $score;
}

function strength_label($score) {
    return match ($score) {
        0,1 => ['Weak', 'bad'],
        2 => ['Fair', 'warn'],
        3,4 => ['Strong', 'good'],
        5 => ['Very Strong', 'good'],
        default => ['Unknown', 'muted'],
    };
}

function hibp_check($password) {
    // Calculate SHA1 hash of password
    $sha1 = strtoupper(sha1($password));
    $prefix = substr($sha1, 0, 5);
    $suffix = substr($sha1, 5);
    $url = "https://api.pwnedpasswords.com/range/" . $prefix;

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "PHP Password Breach Checker");
    $resp = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http != 200 || !$resp) {
        return ['error' => "HIBP request failed (HTTP $http)."];
    }

    // Search suffix in response
    $lines = explode("\n", $resp);
    foreach ($lines as $line) {
        [$hashSuffix, $count] = array_pad(explode(':', trim($line)), 2, null);
        if (strcasecmp($hashSuffix, $suffix) == 0) {
            return ['found' => true, 'count' => (int)$count];
        }
    }
    return ['found' => false];
}

$result = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');
    if ($password === '') {
        $error = "Please enter a password.";
    } else {
        $strength_score = check_strength($password);
        [$strength_label, $class] = strength_label($strength_score);
        $hibp = hibp_check($password);

        $result = [
            'password' => $password,
            'strength_score' => $strength_score,
            'strength_label' => $strength_label,
            'strength_class' => $class,
            'hibp' => $hibp
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Password Breach Checker (PHP)</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./style.css">
<style>
body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Arial; background:#f8fafc; color:#111; padding:24px; }
.card { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.06); padding:20px; max-width:700px; margin:0 auto; }
h1 { font-size:20px; margin-bottom:8px; }
input[type="password"] { width:100%; padding:10px 12px; border-radius:8px; border:1px solid #cbd5e1; font-size:16px; margin-bottom:12px; }
button { padding:10px 18px; background:#2563eb; color:#fff; border:0; border-radius:8px; cursor:pointer; font-size:15px; }
.good { background:#ecfdf5; color:#065f46; padding:6px 10px; border-radius:8px; display:inline-block; }
.bad { background:#fee2e2; color:#991b1b; padding:6px 10px; border-radius:8px; display:inline-block; }
.warn { background:#fff7ed; color:#92400e; padding:6px 10px; border-radius:8px; display:inline-block; }
.muted { color:#64748b; font-size:14px; }
pre { background:#0f172a; color:#e2e8f0; padding:12px; border-radius:8px; overflow:auto; font-size:13px; }
.error { background:#fee2e2; color:#7f1d1d; padding:10px; border-radius:8px; margin-bottom:10px; }
</style>
</head>
<body>
      <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="#home">
        <div class="logo">CS</div>
        <div class="brand-text">
          <h1>Cyber Security Awareness Portal </h1>
          <p class="muted">Awareness Portal</p>

        </div>
      </a>
      <nav class="main-nav">
        <ul>
           <li><a href="index.php">Home</a></li>
          <li><a href="#tools">Tools</a></li>
          <li><a href="#quiz">Quizzes</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </nav>
    </div>
  </header>
<div class="card">
    <h1>🔐 Password Strength & Breach Checker</h1>
    <p class="muted">Check if your password is strong and whether it appears in known data breaches (using Have I Been Pwned API).</p>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="password" name="password" placeholder="Enter your password..." required>
        <button type="submit">Check Password</button>
    </form>

    <?php if ($result): ?>
        <h3>Results:</h3>
        <p><strong>Strength:</strong> <span class="<?= $result['strength_class'] ?>"><?= htmlspecialchars($result['strength_label']) ?></span></p>

        <?php if (isset($result['hibp']['error'])): ?>
            <div class="error"><?= htmlspecialchars($result['hibp']['error']) ?></div>
        <?php elseif ($result['hibp']['found']): ?>
            <p><span class="bad">⚠️ This password has appeared in <?= number_format($result['hibp']['count']) ?> breaches!</span><br>
            You should <strong>never use</strong> this password anywhere.</p>
        <?php else: ?>
            <p><span class="good">✅ Good news!</span> This password was <strong>not found</strong> in known breaches.</p>
        <?php endif; ?>

        <details>
            <summary class="muted">Show SHA1 hash used for anonymous lookup</summary>
            <pre><?= htmlspecialchars(strtoupper(sha1($result['password']))) ?></pre>
        </details>
    <?php endif; ?>

    <hr style="margin-top:20px;">
    <p class="muted">Note: Your password is <strong>never sent</strong> to any server — only the first 5 characters of its SHA-1 hash are used for the breach check (k-Anonymity model).</p>
</div>
</body>
</html>
