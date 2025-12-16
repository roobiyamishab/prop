<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AIPropMatch - AI-Powered Real Estate Intelligence</title>
  <link rel="stylesheet" href="{{ asset('assets/style.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <div id="app">
    
    <div id="landing-page" class="page active">
      <nav class="landing-nav">
        <div class="nav-container">
          <div class="logo">AIPropMatch</div>
          <div class="nav-actions">
           <a class="btn-secondary" href="{{ route('login') }}" >
    Login
</a>

<a class="btn-primary" href="{{ route('register') }}" >
    Sign Up
</a>



          </div>
        </div>
      </nav>

      <section class="hero">
        <div class="hero-content">
          <h1 class="hero-title">AIPropMatch — Where Real Estate Meets AI</h1>
          <p class="hero-subtitle">Transforming property discovery into property intelligence.</p>
          <div class="hero-actions">
            <a class="btn-large btn-primary" href="signup.html">
    Buy Property
</a>

<a class="btn-large btn-secondary" href="signup.html">
    List Property
</a>

          </div>
        </div>
      </section>

      <section class="features">
        <div class="container">
          <div class="features-grid">
            <div class="feature-card">
              <div class="feature-icon">🤖</div>
              <h3>AI Matching Engine</h3>
              <p>Our advanced AI analyzes your preferences and matches you with the perfect properties in real-time.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">📊</div>
              <h3>ISS Score</h3>
              <p>Investment Suitability Score helps you make informed decisions with data-driven insights.</p>
            </div>
            <div class="feature-card">
              <div class="feature-icon">✓</div>
              <h3>Trusted Listings</h3>
              <p>Verified properties with complete documentation and transparent pricing.</p>
            </div>
          </div>
        </div>
      </section>

      <footer class="landing-footer">
        <div class="container">
          <div class="footer-content">
            <div class="footer-section">
              <h4>Contact Us</h4>
              <p>Email: contact@aipropmatch.com</p>
              <p>Phone: +91 1234567890</p>
            </div>
            <div class="footer-section">
              <h4>Quick Links</h4>
              <p>About Us</p>
              <p>Privacy Policy</p>
              <p>Terms of Service</p>
            </div>
            <div class="footer-section">
              <h4>Connect</h4>
              <p>Scan QR Code</p>
              <div class="qr-placeholder">qrco.de/aipropmatch</div>
            </div>
          </div>
          <div class="footer-bottom">
            <p>&copy; 2025 AIPropMatch. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  </div>

 <script src="{{ asset('assets/script.js') }}"></script>
</body>
</html>
