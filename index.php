

<!-- https://urlhaus.abuse.ch/url/3698164/ -->
 <!-- ethical-points-competitive-fluid.trycloudflare.com -->
<?php
//  $allowed_roles=['user'];

// use PhpParser\Node\Expr\Isset_;
// $allowed_roles=['user'];
// include('../configs/auth.php');
$allowed_roles = ['user']; 
include('./configs/auth.php'); 
// include('./configs/Session.php');
// include('./configs/auth.php');
  
//   if (isset($_SESSION['username'])) {
//     echo $_SESSION['username'];
// } else {
//     echo "No session found — please login first.";
// }
  
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cyber Security Awareness Portal   </title>
  <link rel="stylesheet" href="./style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.php">
        <div class="logo">CS</div>
        <div class="brand-text">
          <h1>Cyber Security Awareness Portal </h1>
          <p class="muted">Awareness Portal     </p>
          <p class="muted"><?php   if (isset($_SESSION['username'])) {
    echo $_SESSION['username'];
}?>   </p>

        </div>
      </a>
      <nav class="main-nav">
        <ul>
           <li><a href="index.php">Home</a></li>
          <li><a href="#tools">Tools</a></li>
          <li><a href="#quiz">Quizzes</a></li>
          <li><a href="#contact">Contact</a></li>
          <li><a href="#contact">Dashboard</a></li>
        
          <li><a href="./layout/awarness_page.php">awarness</a></li>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){
       echo (' <li><a href="./cloudnary/upload2.php">upload-video</a></li>'.
         ' <li><a href="./cloudnary/Edit_Video.php">edit-video</a></li>');
          }?>
          <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){
       echo '<li><a href="./layout/role.php"> role</a></li>';
          }?>
    
          <!-- <li><a href="./layout/update_role.php">edit role</a></li> -->
          
          <!-- <li><a href="./auth/login.php">login</a></li> -->
          <!-- <li><a href="./auth/regiseter.php">register</a></li> -->
<?php   if (isset($_SESSION['username'])) {
 echo  ' <li><a href="./auth/logout.php">logout</a></li> ';
}else{
  echo  '  <li><a href="./auth/login.php">login</a></li> ';
}   

?>

          
        </ul>
      </nav>
    </div>
  </header>
  <main id="home" class="container">
    <section class="head card">
      <h2>Learn, Stay Safe, and Spread Cyber Awareness</h2>
      <p class="muted">Understand tools, take quizzes, and protect yourself online. This site is built using only HTML and CSS.</p>
      <div class="head-cta">
        <a href="#tools" class="btn">Explore Tools</a>
        <a href="#quiz" class="btn outline">Take Quiz</a>
      </div>
    </section>
    <section id="tools" class="card section">
      <div class="section-header">
        <a href="./layout/user_dashboard.php">user</a>
        <a href="./layout/admin_dashboard.php">admin</a>
        <a href="./cloudnary/upload.php">upload</a>
        <a href="./cloudnary/upload2.php">upload2</a>
        <a href="ui.php">ui</a>
        <a href="ui2.php">ui2</a>
        <h3>Cyber Security Tools Overview</h3>
        <p class="muted">Explore key tools used by professionals for ethical hacking and awareness.</p>
      </div>

      <div class="cards">
         <a href="url.php" class="simple-card"><h4> Url</h4><p>Log monitoring and analytics for cyber defense.</p></a>
        <a href="ssl.php" class="simple-card"><h4> ssl </h4><p>Wi-Fi network auditing suite for wireless testing.</p></a>
       <a href="ip_lookup.php" class="simple-card"><h4> ip_lookup</h4><p>Vulnerability scanner for identifying weaknesses.</p></a>
        <a href="domain_info_tool.php" class="simple-card"><h4> domain_info_tool</h4><p>Network intrusion detection and prevention system.</p></a>
        <a href="password_breach_checker.php" class="simple-card"><h4> password_breach_checker</h4><p>Password cracking and strength auditing tool.</p></a>
        <a href="#" class="simple-card"><h4> Kali Linux 🐧</h4><p>Linux distro for security professionals and learners.</p></a>
        <a href="#" class="simple-card"><h4> Burp Suite</h4><p>Web vulnerability testing and security assessment tool.</p></a>
        <a href="#" class="simple-card"><h4> Metasploit</h4><p>Framework for authorized penetration testing.</p></a>
        <a href="#" class="simple-card"><h4> Nmap</h4><p>Network discovery and security auditing tool.</p></a>
        <a href="#" class="simple-card"><h4> Wireshark</h4><p>Network traffic analyzer for deep inspection.</p></a>
      </div>
    </section>

    <section id="quiz" class="card section">
    <a href="#"><h3>Quick Awareness Quiz</h3></a>
      <ol class="quiz-list">
        <li>Should you share your password with anyone?</li>
        <li>What should you do if you receive a suspicious email?</li>
        <li>Why is two-factor authentication important?</li>
      </ol>
    </section>

    <section id="contact" class="card section">
      <h3>Contact Us</h3>
      <p class="muted"><a href="#">Have suggestions or feedback? Get in touch below.</a></p>

      <div class="contact-grid">
        <div>
          <h4>Email</h4>
          <p>Solangi5426@gmail.com</p>
        </div>
        <div>
          <h4>Workshops</h4>
          <p>Join our online awareness sessions for cyber safety tips.</p>
        </div>
      </div>
    </section>

    <footer class="site-footer">
      <p> Cyber Security Awareness Portal</p>
      <p class="muted small">For educational use only. Practice ethical security!</p>
    </footer>
  </main>
</body>
</html>

