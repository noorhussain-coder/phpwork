<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CyberGuard – Secure Your World</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        body {
            background: #0c0f17;
            color: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(6px);
        }

        /* ✅ HERO SECTION */
        .hero {
            padding: 130px 0;
            background: radial-gradient(circle at top, #1a2332, #0c0f17);
            text-align: center;
            position: relative;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }
        .cyber-btn {
            padding: 12px 35px;
            background: #15d1f5;
            color: #000;
            border-radius: 50px;
            font-weight: 600;
            transition: .3s;
        }
        .cyber-btn:hover {
            box-shadow: 0 0 20px #15d1f5;
            transform: translateY(-3px);
            color: #000;
        }

        /* ✅ FEATURE CARDS */
        .feature-card {
            background: #151925;
            border: 1px solid #1d2333;
            border-radius: 12px;
            padding: 25px;
            height: 100%;
            transition: .3s;
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(21,209,245,0.2);
        }
        .feature-icon {
            font-size: 40px;
            margin-bottom: 15px;
            color: #15d1f5;
        }

        /* ✅ PRICING */
        .price-box {
            background: #111520;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #1d2333;
            transition: .3s;
        }
        .price-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(21,209,245,0.25);
        }
        .price-num {
            font-size: 45px;
            font-weight: 700;
            color: #15d1f5;
        }

        /* ✅ TESTIMONIAL */
        .test-box {
            background: #151925;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid #1d2333;
        }

        footer {
            background: #080b12;
            padding: 25px;
            text-align: center;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<!-- ✅ NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark py-3">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">CyberGuard</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="nav" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto gap-3">
                <li><a class="nav-link" href="#home">Home</a></li>
                <li><a class="nav-link" href="#features">Solutions</a></li>
                <li><a class="nav-link" href="#pricing">Plans</a></li>
                <li><a class="nav-link" href="#testimonials">Reviews</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- ✅ HERO SECTION -->
<section id="home" class="hero" data-aos="fade-up">
    <div class="container">
        <h1>Next-Generation Cybersecurity Protection</h1>
        <p class="mt-3 fs-5 text-light">AI-powered defense systems built to secure your digital world.</p>
        <a href="#" class="cyber-btn mt-4 d-inline-block">Get Protection</a>
    </div>
</section>

<!-- ✅ FEATURES -->
<section id="features" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-up">Core Security Solutions</h2>

        <div class="row g-4">

            <div class="col-md-3" data-aos="zoom-in">
                <div class="feature-card">
                    <div class="feature-icon">🛡️</div>
                    <h5 class="fw-bold">Threat Shield AI</h5>
                    <p>Automatically blocks attacks using machine learning.</p>
                </div>
            </div>

            <div class="col-md-3" data-aos="zoom-in" data-aos-delay="150">
                <div class="feature-card">
                    <div class="feature-icon">🚨</div>
                    <h5 class="fw-bold">Live Breach Alerts</h5>
                    <p>Instant notifications for unusual activity.</p>
                </div>
            </div>

            <div class="col-md-3" data-aos="zoom-in" data-aos-delay="250">
                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h5 class="fw-bold">Vulnerability Scan</h5>
                    <p>Identify weaknesses before attackers find them.</p>
                </div>
            </div>

            <div class="col-md-3" data-aos="zoom-in" data-aos-delay="350">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h5 class="fw-bold">Cyber Awareness</h5>
                    <p>Training programs that upgrade your security skills.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ✅ PRICING -->
<section id="pricing" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-up">Choose Your Plan</h2>

        <div class="row g-4">

            <div class="col-md-4" data-aos="fade-up">
                <div class="price-box">
                    <h4 class="fw-bold">Starter</h4>
                    <div class="price-num">$49</div>
                    <p class="text-secondary">per month</p>
                    <ul class="list-unstyled mt-3">
                        <li>✔ Basic Threat Monitoring</li>
                        <li>✔ Email Security</li>
                        <li>✔ Monthly Scan</li>
                    </ul>
                    <button class="cyber-btn mt-2">Buy Now</button>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="price-box">
                    <h4 class="fw-bold">Professional</h4>
                    <div class="price-num">$149</div>
                    <p class="text-secondary">per month</p>
                    <ul class="list-unstyled mt-3">
                        <li>✔ Real-Time Protection</li>
                        <li>✔ Vulnerability Scan</li>
                        <li>✔ 24/7 Support</li>
                    </ul>
                    <button class="cyber-btn mt-2">Buy Now</button>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="price-box">
                    <h4 class="fw-bold">Enterprise</h4>
                    <div class="price-num">$299</div>
                    <p class="text-secondary">per month</p>
                    <ul class="list-unstyled mt-3">
                        <li>✔ Full Cyber Suite</li>
                        <li>✔ Dedicated Analyst</li>
                        <li>✔ Incident Response Team</li>
                    </ul>
                    <button class="cyber-btn mt-2">Buy Now</button>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ✅ TESTIMONIALS -->
<section id="testimonials" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5" data-aos="fade-up">User Feedback</h2>

        <div class="row g-4">

            <div class="col-md-4" data-aos="fade-right">
                <div class="test-box">
                    <p>"Outstanding protection! My business feels safer than ever."</p>
                    <h6>- Alex Carter</h6>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up">
                <div class="test-box">
                    <p>"Their AI detection is truly next-gen. Amazing results."</p>
                    <h6>- Mia Rodriguez</h6>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-left">
                <div class="test-box">
                    <p>"Fast support team and very reliable service."</p>
                    <h6>- Ethan Hill</h6>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ✅ FOOTER -->
<footer>
    <p>© 2025 CyberGuard — All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script>
    AOS.init();
</script>

</body>
</html>
