<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CyberEz - Bootstrap UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .hero {
        background: linear-gradient(90deg, #0b1223 0%, #0f1b2b 100%);
        color: #fff;
        padding: 6rem 0;
      }
      .feature-card { min-height: 150px; }
      .testimonial { background: #f8f9fa; padding: 3rem 0; }
      .footer { background:#0b1223; color: #cfd8e3; padding: 3rem 0; }
      .nav-brand { font-weight:700; letter-spacing: .2px; }
      @media (max-width: 767px) {
        .hero { padding: 3rem 0; }
      }
    </style>
  </head>
  <body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <a class="navbar-brand nav-brand" href="#">CyberEz</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="#services">Our Service</a></li>
            <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
            <li class="nav-item"><a class="nav-link" href="#pricing">Pricing</a></li>
            <li class="nav-item"><a class="nav-link" href="#testimonial">Testimonial</a></li>
            <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
          </ul>
          <div class="d-flex ms-3">
            <a class="btn btn-outline-light me-2" href="#">FAQ</a>
            <a class="btn btn-primary" href="#">Purchase</a>
          </div>
        </div>
      </div>
    </nav>

    <header class="hero">
      <div class="container">
        <div class="row align-items-center">
    <div class="col-lg-6">
  <h1 class="display-4 fw-bold mb-3">
    Empowering <span class="text-primary">You</span><br>
    in the <span class="text-info">Digital Age</span>
  </h1>
  <p class="lead text-light opacity-75 mb-4">
    Take control of your cybersecurity journey with cutting-edge tools, 
    expert guidance, and 24/7 protection — all tailored to your business.
  </p>
  <div class="d-flex flex-wrap gap-3">
    <a href="#pricing" class="btn btn-primary btn-lg shadow">
      <i class="bi bi-lightning-charge-fill me-1"></i> Get Started
    </a>
    <a href="#services" class="btn btn-outline-light btn-lg">
      <i class="bi bi-grid me-1"></i> Explore Services
    </a>
  </div>
</div>

          <div class="col-lg-6 text-center mt-4 mt-lg-0">
            <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=600&q=80" class="img-fluid rounded" alt="Cyber Security Hero">
          </div>
        </div>
      </div>
    </header>

    <section id="features" class="py-5">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4">
            <h3>FEATURE POINT</h3>
            <h4>Key Service Features</h4>
            <p>Protecting You — Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum congue metus quis accumsan euismod.</p>
          </div>
          <div class="col-lg-8">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="card p-3 feature-card h-100">
                  <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=600&q=80" class="card-img-top rounded" alt="Feature 1">
                  <h5>Customized Security Solutions</h5>
                  <p class="mb-0">Lorem ipsum dolor sit amet.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 feature-card h-100">
                  <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=600&q=80" class="card-img-top rounded" alt="Feature 2">
                  <h5>24/7 Incident Response</h5>
                  <p class="mb-0">Lorem ipsum dolor sit amet.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 feature-card h-100">
                  <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=600&q=80" class="card-img-top rounded" alt="Feature 3">
                  <h5>Vulnerability Assessment</h5>
                  <p class="mb-0">Lorem ipsum dolor sit amet.</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="card p-3 feature-card h-100">
                  <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981c?auto=format&fit=crop&w=600&q=80" class="card-img-top rounded" alt="Feature 4">
                  <h5>User Training Programs</h5>
                  <p class="mb-0">Lorem ipsum dolor sit amet.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="about" class="py-5 bg-light">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6">
            <h3>OUR GOALS</h3>
            <h4>Securing Your Digital World Together</h4>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum congue metus quis accumsan euismod.</p>
            <ul>
              <li><strong>Mission Statement</strong> — Lorem ipsum dolor sit amet.</li>
              <li><strong>Key Objectives</strong> — Lorem ipsum dolor sit amet.</li>
              <li><strong>Client-Centric Approach</strong> — Vestibulum congue metus quis accumsan euismod.</li>
            </ul>
          </div>
          <div class="col-lg-6 text-center">
            <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=700&q=80" class="img-fluid rounded" alt="About Image">
          </div>
        </div>
      </div>
    </section>

    <section class="py-5 team">
      <div class="container">
        <h3>Team Members</h3>
        <div class="row g-4">
          <div class="col-md-3">
            <div class="card text-center p-3">
              <img src="https://randomuser.me/api/portraits/women/44.jpg" class="rounded-circle mx-auto d-block" alt="Team 1" width="120" height="120"/>
              <h6>Miss Sammy Feeney</h6>
              <p class="small text-muted">Investor Metrics Executive</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center p-3">
              <img src="https://randomuser.me/api/portraits/women/68.jpg" class="rounded-circle mx-auto d-block" alt="Team 2" width="120" height="120"/>
              <h6>Regina Weissnat</h6>
              <p class="small text-muted">Regional Branding Consultant</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center p-3">
              <img src="https://randomuser.me/api/portraits/men/32.jpg" class="rounded-circle mx-auto d-block" alt="Team 3" width="120" height="120"/>
              <h6>Rosemary Mante</h6>
              <p class="small text-muted">Human Integration Agent</p>
            </div>
          </div>
          <div class="col-md-3">
            <div class="card text-center p-3">
              <img src="https://randomuser.me/api/portraits/women/12.jpg" class="rounded-circle mx-auto d-block" alt="Team 4" width="120" height="120"/>
              <h6>Marianne Bode</h6>
              <p class="small text-muted">Product Intranet Agent</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer id="contact" class="footer">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <h5>CyberEz</h5>
            <p>Morbi sit amet neque tortor. Lorem ipsum dolor sit amet.</p>
          </div>
          <div class="col-md-2">
            <h6>Quick Links</h6>
            <ul class="list-unstyled">
              <li><a class="text-decoration-none text-muted" href="#services">Our Service</a></li>
              <li><a class="text-decoration-none text-muted" href="#about">About Us</a></li>
              <li><a class="text-decoration-none text-muted" href="#pricing">Pricing</a></li>
              <li><a class="text-decoration-none text-muted" href="#testimonial">Testimonial</a></li>
            </ul>
          </div>
          <div class="col-md-3">
            <h6>Contact</h6>
            <p class="mb-1">hello@website.com</p>
            <p class="mb-1">838 Cantt Sialkot, ENG</p>
            <p class="mb-0">+02 5421234560</p>
          </div>
          <div class="col-md-3">
            <h6>Newsletter</h6>
            <p>Subscribe</p>
            <form class="d-flex" onsubmit="event.preventDefault();alert('Subscribed — demo');">
              <input class="form-control me-2" placeholder="Email address">
              <button class="btn btn-primary">Subscribe</button>
            </form>
          </div>
        </div>
        <div class="text-center mt-4 small">&copy; <span id="year"></span> CyberEz. All rights reserved.</div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>document.getElementById('year').textContent = new Date().getFullYear();</script>
  </body>
</html>