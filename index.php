<?php
$pageName   = "Home";
$isHomePage = true;
include_once("front/header.php");
?>

<!-- ═══════════════════ HERO ═══════════════════ -->
<section class="ft-hero" aria-label="Hero">
  <div class="ft-hero-bg"></div>
  <div class="ft-hero-grid"></div>
  <div class="ft-hero-inner">

    <!-- Left: Copy -->
    <div class="ft-hero-copy">
      <div class="ft-hero-trust">
        <span class="ft-hero-trust-dot"></span>
        Trusted &amp; Licensed Banking Infrastructure
      </div>
      <h1 class="ft-hero-title">
        Banking Designed<br>for the <span class="grad">Modern World</span>
      </h1>
      <p class="ft-hero-sub">
        Move money instantly, manage multi-currency accounts, issue virtual cards,
        and track every dollar — all in one beautifully secure platform.
      </p>
      <div class="ft-hero-actions">
        <a href="<?= $web_url ?>/signup/verify-registration.php" class="ft-btn-hero-primary">
          <i class="ri-bank-card-line"></i> Open Account Free
        </a>
        <a href="<?= $web_url ?>/login.php" class="ft-btn-hero-secondary">
          <i class="ri-login-box-line"></i> Sign In
        </a>
      </div>
      <div class="ft-hero-stats">
        <div class="ft-hero-stat">
          <div class="ft-hero-stat-val"><span data-target="4.8" data-suffix="M+">0</span></div>
          <div class="ft-hero-stat-label">Active Users</div>
        </div>
        <div class="ft-hero-stat">
          <div class="ft-hero-stat-val"><span data-target="150" data-suffix="+">0</span></div>
          <div class="ft-hero-stat-label">Countries Served</div>
        </div>
        <div class="ft-hero-stat">
          <div class="ft-hero-stat-val"><span data-target="99.9" data-suffix="%">0</span></div>
          <div class="ft-hero-stat-label">Uptime SLA</div>
        </div>
      </div>
    </div>

    <!-- Right: Phone Mockup -->
    <div class="ft-hero-visual" aria-hidden="true">

      <!-- Floating card 1: instant transfer -->
      <div class="ft-hero-float-card ft-hero-float-card-1">
        <div class="ft-float-icon"><i class="ri-flashlight-line" style="color:#f59e0b;"></i></div>
        <div class="ft-float-label">Instant Transfer</div>
        <div class="ft-float-val">$2,500.00</div>
        <div class="ft-float-sub">↑ Sent in 0.3s</div>
      </div>

      <!-- Phone -->
      <div class="ft-phone-mockup">
        <div class="ft-phone-screen">
          <div class="ft-phone-notch"></div>
          <div class="ft-phone-balance-label">Total Balance</div>
          <div class="ft-phone-balance">$24,835.60</div>
          <div class="ft-phone-balance-sub">↑ +3.2% this month</div>

          <div class="ft-phone-card">
            <div class="ft-phone-card-label">Virtual Debit Card</div>
            <div class="ft-phone-card-num">•••• •••• •••• 4291</div>
            <div class="ft-phone-card-row">
              <span>J. MORGAN</span><span>12/28</span>
            </div>
          </div>

          <div class="ft-phone-txns">
            <div class="ft-phone-txn-title">Recent Transactions</div>
            <div class="ft-phone-txn">
              <div class="ft-phone-txn-icon" style="background:rgba(99,102,241,0.2);display:flex;align-items:center;justify-content:center;"><i class="ri-shopping-cart-line" style="color:#6366f1;font-size:11px;"></i></div>
              <div class="ft-phone-txn-info">
                <div class="ft-phone-txn-name">Amazon</div>
                <div class="ft-phone-txn-date">Today, 9:41 AM</div>
              </div>
              <div class="ft-phone-txn-amt neg">−$89.99</div>
            </div>
            <div class="ft-phone-txn">
              <div class="ft-phone-txn-icon" style="background:rgba(16,185,129,0.2);display:flex;align-items:center;justify-content:center;"><i class="ri-send-plane-fill" style="color:#10b981;font-size:11px;"></i></div>
              <div class="ft-phone-txn-info">
                <div class="ft-phone-txn-name">Wire Received</div>
                <div class="ft-phone-txn-date">Yesterday</div>
              </div>
              <div class="ft-phone-txn-amt pos">+$5,000</div>
            </div>
            <div class="ft-phone-txn">
              <div class="ft-phone-txn-icon" style="background:rgba(249,115,22,0.2);display:flex;align-items:center;justify-content:center;"><i class="ri-cup-line" style="color:#f97316;font-size:11px;"></i></div>
              <div class="ft-phone-txn-info">
                <div class="ft-phone-txn-name">Starbucks</div>
                <div class="ft-phone-txn-date">Dec 19</div>
              </div>
              <div class="ft-phone-txn-amt neg">−$6.50</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Floating card 2: savings -->
      <div class="ft-hero-float-card ft-hero-float-card-2">
        <div class="ft-float-icon"><i class="ri-safe-2-line" style="color:#10b981;"></i></div>
        <div class="ft-float-label">Savings Vault</div>
        <div class="ft-float-val">$12,200</div>
        <div class="ft-float-sub">4.5% APY</div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════ TRUST BAR ═══════════════════ -->
<div class="ft-trust-bar" aria-label="Trusted by">
  <div class="ft-trust-inner">
    <span class="ft-trust-label">Trusted partners &amp; regulators</span>
    <div class="ft-trust-logos">
      <img src="<?= $web_url ?>/front/images/clients/1.png" alt="Partner 1" />
      <img src="<?= $web_url ?>/front/images/clients/2.png" alt="Partner 2" />
      <img src="<?= $web_url ?>/front/images/clients/4.png" alt="Partner 3" />
      <img src="<?= $web_url ?>/front/images/clients/5.png" alt="Partner 4" />
    </div>
    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0">
      <span style="font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.1em;">FDIC Insured</span>
      <span style="width:1px;height:20px;background:var(--border)"></span>
      <span style="font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.1em;">256-bit SSL</span>
      <span style="width:1px;height:20px;background:var(--border)"></span>
      <span style="font-size:.72rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.1em;">PCI DSS Level 1</span>
    </div>
  </div>
</div>

<!-- ═══════════════════ FEATURES ═══════════════════ -->
<section class="ft-features-bg" aria-labelledby="features-heading">
  <div class="ft-section">
    <div class="ft-section-header">
      <div class="ft-badge ft-reveal">Everything You Need</div>
      <h2 class="ft-heading ft-reveal ft-reveal-delay-1" id="features-heading">
        Premium Features, <span class="grad">Zero Compromises</span>
      </h2>
      <p class="ft-subheading ft-reveal ft-reveal-delay-2">
        One platform. Eight powerful capabilities built for individuals and businesses who demand the best.
      </p>
    </div>
    <div class="ft-features-grid">

      <div class="ft-feature-card ft-reveal">
        <div class="ft-feature-icon"><i class="ri-flashlight-line" style="color:#6366f1"></i></div>
        <h3 class="ft-feature-title">Instant Transfers</h3>
        <p class="ft-feature-text">Send money domestically or internationally in seconds with zero hidden fees. Real-time confirmation every time.</p>
      </div>

      <div class="ft-feature-card ft-reveal ft-reveal-delay-1">
        <div class="ft-feature-icon cyan"><i class="ri-exchange-dollar-line" style="color:#06b6d4"></i></div>
        <h3 class="ft-feature-title">Multi-Currency Accounts</h3>
        <p class="ft-feature-text">Hold, convert, and transact in 30+ currencies at interbank exchange rates. No markup, no surprises.</p>
      </div>

      <div class="ft-feature-card ft-reveal ft-reveal-delay-2">
        <div class="ft-feature-icon violet"><i class="ri-bank-card-2-line" style="color:#8b5cf6"></i></div>
        <h3 class="ft-feature-title">Virtual &amp; Physical Cards</h3>
        <p class="ft-feature-text">Issue unlimited virtual cards instantly. Order premium metal cards with custom spending limits and instant freeze.</p>
      </div>

      <div class="ft-feature-card ft-reveal ft-reveal-delay-3">
        <div class="ft-feature-icon green"><i class="ri-pie-chart-2-line" style="color:#10b981"></i></div>
        <h3 class="ft-feature-title">Spending Analytics</h3>
        <p class="ft-feature-text">AI-powered insights categorize every transaction automatically. See exactly where your money goes.</p>
      </div>

      <div class="ft-feature-card ft-reveal">
        <div class="ft-feature-icon orange"><i class="ri-wallet-3-line" style="color:#f97316"></i></div>
        <h3 class="ft-feature-title">Smart Budgeting</h3>
        <p class="ft-feature-text">Set intelligent budgets that adapt to your spending. Get nudges before you overspend — not after.</p>
      </div>

      <div class="ft-feature-card ft-reveal ft-reveal-delay-1">
        <div class="ft-feature-icon gold"><i class="ri-bit-coin-line" style="color:#eab308"></i></div>
        <h3 class="ft-feature-title">Crypto &amp; Investments</h3>
        <p class="ft-feature-text">Buy, hold, and sell Bitcoin, Ethereum, and top altcoins directly from your banking dashboard.</p>
      </div>

      <div class="ft-feature-card ft-reveal ft-reveal-delay-2">
        <div class="ft-feature-icon pink"><i class="ri-global-line" style="color:#ec4899"></i></div>
        <h3 class="ft-feature-title">International Payments</h3>
        <p class="ft-feature-text">Wire funds to 150+ countries using SWIFT and SEPA. Track every payment in real time end-to-end.</p>
      </div>

      <div class="ft-feature-card ft-reveal ft-reveal-delay-3">
        <div class="ft-feature-icon cyan"><i class="ri-safe-2-line" style="color:#06b6d4"></i></div>
        <h3 class="ft-feature-title">Savings Vaults</h3>
        <p class="ft-feature-text">Earn up to 4.5% APY on automated savings. Round-up rules and recurring rules make saving effortless.</p>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════ SOCIAL PROOF BAR ═══════════════════ -->
<div class="ft-proof-bar" aria-label="Key metrics">
  <div class="ft-proof-inner">
    <div class="ft-proof-item ft-reveal">
      <div class="ft-proof-val"><span data-target="4800000" data-suffix="+" data-prefix="">0</span></div>
      <div class="ft-proof-label">Happy Customers</div>
    </div>
    <div class="ft-proof-item ft-reveal ft-reveal-delay-1">
      <div class="ft-proof-val">$<span data-target="28" data-suffix="B+">0</span></div>
      <div class="ft-proof-label">Processed Monthly</div>
    </div>
    <div class="ft-proof-item ft-reveal ft-reveal-delay-2">
      <div class="ft-proof-val"><span data-target="150" data-suffix="+">0</span></div>
      <div class="ft-proof-label">Countries Supported</div>
    </div>
    <div class="ft-proof-item ft-reveal ft-reveal-delay-3">
      <div class="ft-proof-val"><span data-target="4.9" data-suffix="/5">0</span></div>
      <div class="ft-proof-label">App Store Rating</div>
    </div>
    <div class="ft-proof-item ft-reveal ft-reveal-delay-4">
      <div class="ft-proof-val"><span data-target="99.9" data-suffix="%">0</span></div>
      <div class="ft-proof-label">Uptime SLA</div>
    </div>
  </div>
</div>

<!-- ═══════════════════ APP SHOWCASE ═══════════════════ -->
<section aria-labelledby="showcase-heading">
  <div class="ft-section">
    <div class="ft-showcase">

      <!-- Visual phones -->
      <div class="ft-showcase-phones ft-reveal" aria-hidden="true">
        <div class="ft-showcase-phone ft-showcase-phone-1">
          <div class="ft-showcase-screen">
            <div style="font-size:.55rem;color:rgba(255,255,255,.35);margin-bottom:8px;text-transform:uppercase;letter-spacing:.1em">Analytics</div>
            <div style="font-size:1.1rem;font-weight:800;color:#fff;margin-bottom:2px">$8,420</div>
            <div style="font-size:.55rem;color:#10b981;margin-bottom:12px">↑ 12% vs last month</div>
            <!-- mini bar chart -->
            <div style="display:flex;align-items:flex-end;gap:4px;height:60px;margin-bottom:10px">
              <?php
              $bars = [40,65,45,80,55,90,70,85,60,95,75,100];
              $colors = ['rgba(99,102,241,0.4)','rgba(99,102,241,0.5)','rgba(99,102,241,0.4)','rgba(99,102,241,0.6)','rgba(99,102,241,0.45)','rgba(99,102,241,0.7)','rgba(99,102,241,0.55)','rgba(139,92,246,0.7)','rgba(99,102,241,0.5)','rgba(6,182,212,0.8)','rgba(99,102,241,0.65)','rgba(99,102,241,1)'];
              foreach ($bars as $i => $h):
              ?>
              <div style="flex:1;height:<?= $h ?>%;background:<?= $colors[$i] ?>;border-radius:3px 3px 0 0"></div>
              <?php endforeach; ?>
            </div>
            <div style="font-size:.5rem;color:rgba(255,255,255,.25);display:flex;justify-content:space-between">
              <span>Jan</span><span>Jun</span><span>Dec</span>
            </div>
          </div>
        </div>
        <div class="ft-showcase-phone ft-showcase-phone-2">
          <div class="ft-showcase-screen">
            <div style="font-size:.55rem;color:rgba(255,255,255,.35);margin-bottom:8px;text-transform:uppercase;letter-spacing:.1em">My Cards</div>
            <div style="border-radius:10px;padding:10px;background:linear-gradient(135deg,#6366f1,#8b5cf6);margin-bottom:8px;position:relative;overflow:hidden">
              <div style="position:absolute;inset:0;background:linear-gradient(135deg,rgba(255,255,255,.15),transparent)"></div>
              <div style="font-size:.45rem;color:rgba(255,255,255,.7);letter-spacing:.1em;margin-bottom:6px">VIRTUAL CARD</div>
              <div style="font-size:.65rem;color:rgba(255,255,255,.9);font-family:monospace;letter-spacing:.1em">•••• •••• •••• 4291</div>
              <div style="display:flex;justify-content:space-between;margin-top:8px">
                <span style="font-size:.4rem;color:rgba(255,255,255,.7)">J. MORGAN</span>
                <span style="font-size:.4rem;color:rgba(255,255,255,.7)">12/28</span>
              </div>
            </div>
            <div style="font-size:.5rem;color:rgba(255,255,255,.4);margin-bottom:6px">Spending limit</div>
            <div style="height:4px;background:rgba(255,255,255,.1);border-radius:2px;overflow:hidden">
              <div style="width:62%;height:100%;background:linear-gradient(90deg,#6366f1,#06b6d4);border-radius:2px"></div>
            </div>
            <div style="display:flex;justify-content:space-between;margin-top:4px">
              <span style="font-size:.45rem;color:rgba(255,255,255,.4)">$3,100 used</span>
              <span style="font-size:.45rem;color:rgba(255,255,255,.4)">$5,000 limit</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Text content -->
      <div class="ft-showcase-content ft-reveal ft-reveal-delay-1">
        <div class="ft-badge">App Experience</div>
        <h2 class="ft-heading" id="showcase-heading">
          Your Finances, <span class="grad">At a Glance</span>
        </h2>
        <p class="ft-subheading" style="margin-bottom:0">
          A beautiful dashboard that gives you complete visibility and control.
          Designed to feel fast, smart, and completely effortless.
        </p>
        <ul class="ft-showcase-list">
          <li>
            <span class="icon"><i class="ri-pie-chart-2-line" style="color:#6366f1;"></i></span>
            <div>
              <strong>Real-time Spending Charts</strong>
              <span>Interactive category breakdown updated every transaction</span>
            </div>
          </li>
          <li>
            <span class="icon"><i class="ri-bank-card-line" style="color:#8b5cf6;"></i></span>
            <div>
              <strong>Card Management</strong>
              <span>Freeze, unfreeze, set limits, and create virtual cards in seconds</span>
            </div>
          </li>
          <li>
            <span class="icon"><i class="ri-notification-3-line" style="color:#06b6d4;"></i></span>
            <div>
              <strong>Instant Notifications</strong>
              <span>Push alerts for every transaction the moment it happens</span>
            </div>
          </li>
          <li>
            <span class="icon"><i class="ri-smartphone-line" style="color:#10b981;"></i></span>
            <div>
              <strong>Apple Pay &amp; Google Pay</strong>
              <span>Tap to pay anywhere in the world with your digital wallet</span>
            </div>
          </li>
        </ul>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════ SECURITY ═══════════════════ -->
<section class="ft-security-bg" aria-labelledby="security-heading">
  <div class="ft-section">
    <div class="ft-security-grid">

      <!-- Visual -->
      <div class="ft-security-visual ft-reveal" aria-hidden="true">
        <div class="ft-security-shield"><i class="ri-shield-check-fill" style="color:#10b981;font-size:1.8rem;"></i></div>
        <div>
          <div style="font-size:1.1rem;font-weight:800;margin-bottom:6px">Bank-Grade Security</div>
          <div style="font-size:.82rem;color:var(--text-secondary)">Your money and data are protected by multiple layers of military-grade encryption and real-time fraud detection.</div>
        </div>
        <div class="ft-security-badges">
          <div class="ft-security-badge">
            <span class="ft-security-badge-icon"><i class="ri-lock-password-line" style="color:#6366f1;"></i></span>
            <div>
              <strong>256-bit AES Encryption</strong>
              <span>All data encrypted in transit and at rest</span>
            </div>
          </div>
          <div class="ft-security-badge">
            <span class="ft-security-badge-icon"><i class="ri-fingerprint-line" style="color:#06b6d4;"></i></span>
            <div>
              <strong>Biometric Authentication</strong>
              <span>Face ID, fingerprint, and PIN protection</span>
            </div>
          </div>
          <div class="ft-security-badge">
            <span class="ft-security-badge-icon"><i class="ri-robot-line" style="color:#f59e0b;"></i></span>
            <div>
              <strong>AI Fraud Detection</strong>
              <span>24/7 monitoring flags suspicious activity instantly</span>
            </div>
          </div>
          <div class="ft-security-badge">
            <span class="ft-security-badge-icon"><i class="ri-bank-line" style="color:#10b981;"></i></span>
            <div>
              <strong>FDIC Insured up to $250,000</strong>
              <span>Your deposits are federally protected</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Text -->
      <div class="ft-reveal ft-reveal-delay-1">
        <div class="ft-badge">Security &amp; Trust</div>
        <h2 class="ft-heading" id="security-heading">
          Security That <span class="grad">Never Sleeps</span>
        </h2>
        <p class="ft-subheading" style="margin-bottom:0">
          We take the security of your financial life seriously. Our multi-layered
          protection system works around the clock so you never have to worry.
        </p>
        <div class="ft-security-items">
          <div class="ft-security-item">
            <span class="icon ri-shield-check-line"></span>
            <div><strong>Fraud Protection</strong><span>Zero liability on unauthorized transactions</span></div>
          </div>
          <div class="ft-security-item">
            <span class="icon ri-eye-off-line"></span>
            <div><strong>Privacy First</strong><span>We never sell your financial data</span></div>
          </div>
          <div class="ft-security-item">
            <span class="icon ri-lock-password-line"></span>
            <div><strong>2FA / MFA</strong><span>Multi-factor auth on every login</span></div>
          </div>
          <div class="ft-security-item">
            <span class="icon ri-time-line"></span>
            <div><strong>Real-time Alerts</strong><span>Instant notification on every transaction</span></div>
          </div>
          <div class="ft-security-item">
            <span class="icon ri-global-line"></span>
            <div><strong>PCI DSS Level 1</strong><span>Highest payment security standard</span></div>
          </div>
          <div class="ft-security-item">
            <span class="icon ri-bank-line"></span>
            <div><strong>Regulatory Compliance</strong><span>Licensed and regulated in all markets</span></div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════ TESTIMONIALS ═══════════════════ -->
<section aria-labelledby="testimonials-heading">
  <div class="ft-section">
    <div class="ft-section-header">
      <div class="ft-badge ft-reveal">Customer Stories</div>
      <h2 class="ft-heading ft-reveal ft-reveal-delay-1" id="testimonials-heading">
        Loved by <span class="grad">Millions Worldwide</span>
      </h2>
      <p class="ft-subheading ft-reveal ft-reveal-delay-2">
        Don't just take our word for it — here's what our customers say.
      </p>
    </div>
    <div class="ft-testimonials-grid">

      <div class="ft-testimonial-card ft-reveal">
        <div class="ft-stars">★★★★★</div>
        <p class="ft-testimonial-text">"I've tried every banking app out there. Nothing comes close to this. The instant transfers and spending analytics alone are worth it — it's like having a personal CFO in your pocket."</p>
        <div class="ft-testimonial-author">
          <div class="ft-author-avatar" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">SM</div>
          <div>
            <div class="ft-author-name">Sarah Mitchell</div>
            <div class="ft-author-role">Freelance Designer, New York</div>
          </div>
        </div>
      </div>

      <div class="ft-testimonial-card ft-reveal ft-reveal-delay-1">
        <div class="ft-stars">★★★★★</div>
        <p class="ft-testimonial-text">"Switched our entire business banking here. The multi-currency support saved us thousands in conversion fees every month. Customer support is genuinely world-class."</p>
        <div class="ft-testimonial-author">
          <div class="ft-author-avatar" style="background:linear-gradient(135deg,#06b6d4,#6366f1)">JK</div>
          <div>
            <div class="ft-author-name">James Kowalski</div>
            <div class="ft-author-role">CEO, Apex Logistics Ltd.</div>
          </div>
        </div>
      </div>

      <div class="ft-testimonial-card ft-reveal ft-reveal-delay-2">
        <div class="ft-stars">★★★★★</div>
        <p class="ft-testimonial-text">"The security features give me complete peace of mind. I travel internationally and the instant card freeze feature has literally saved me from fraud twice already."</p>
        <div class="ft-testimonial-author">
          <div class="ft-author-avatar" style="background:linear-gradient(135deg,#10b981,#06b6d4)">AL</div>
          <div>
            <div class="ft-author-name">Amara Levi</div>
            <div class="ft-author-role">Digital Nomad &amp; Investor</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════ PRICING ═══════════════════ -->
<section class="ft-features-bg" aria-labelledby="pricing-heading">
  <div class="ft-section">
    <div class="ft-section-header">
      <div class="ft-badge ft-reveal">Plans &amp; Pricing</div>
      <h2 class="ft-heading ft-reveal ft-reveal-delay-1" id="pricing-heading">
        Simple, <span class="grad">Transparent Pricing</span>
      </h2>
      <p class="ft-subheading ft-reveal ft-reveal-delay-2">
        No hidden fees. No surprises. Pick the plan that fits your life — upgrade or downgrade anytime.
      </p>
    </div>
    <div class="ft-pricing-grid">

      <!-- Standard -->
      <div class="ft-price-card ft-reveal">
        <div class="ft-price-plan">Personal</div>
        <div class="ft-price-name">Standard</div>
        <div class="ft-price-amount">
          <span class="ft-price-currency">$</span>
          <span class="ft-price-value">0</span>
        </div>
        <div class="ft-price-desc">Free forever · No credit card needed</div>
        <div class="ft-price-divider"></div>
        <ul class="ft-price-features">
          <li><i class="ri-checkbox-circle-line"></i> Free virtual debit card</li>
          <li><i class="ri-checkbox-circle-line"></i> Up to $500/mo transfers</li>
          <li><i class="ri-checkbox-circle-line"></i> Basic spending analytics</li>
          <li><i class="ri-checkbox-circle-line"></i> 2 currency accounts</li>
          <li class="muted"><i class="ri-close-circle-line"></i> ATM withdrawals</li>
          <li class="muted"><i class="ri-close-circle-line"></i> Priority support</li>
        </ul>
        <a href="<?= $web_url ?>/signup/verify-registration.php" class="ft-price-btn">Get Started Free</a>
      </div>

      <!-- Premium (featured) -->
      <div class="ft-price-card featured ft-reveal ft-reveal-delay-1">
        <div class="ft-price-popular">Most Popular</div>
        <div class="ft-price-plan">Personal</div>
        <div class="ft-price-name">Premium</div>
        <div class="ft-price-amount">
          <span class="ft-price-currency">$</span>
          <span class="ft-price-value" style="background:linear-gradient(135deg,#6366f1,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">9</span>
          <span class="ft-price-period">/mo</span>
        </div>
        <div class="ft-price-desc">Everything you need to manage money like a pro</div>
        <div class="ft-price-divider"></div>
        <ul class="ft-price-features">
          <li><i class="ri-checkbox-circle-line"></i> Physical + 5 virtual cards</li>
          <li><i class="ri-checkbox-circle-line"></i> Unlimited transfers</li>
          <li><i class="ri-checkbox-circle-line"></i> Advanced analytics + budgets</li>
          <li><i class="ri-checkbox-circle-line"></i> 10 currency accounts</li>
          <li><i class="ri-checkbox-circle-line"></i> 3 free ATM withdrawals/mo</li>
          <li><i class="ri-checkbox-circle-line"></i> Priority 24/7 support</li>
        </ul>
        <a href="<?= $web_url ?>/signup/verify-registration.php" class="ft-price-btn featured-btn">Open Premium Account</a>
      </div>

      <!-- Metal -->
      <div class="ft-price-card ft-reveal ft-reveal-delay-2">
        <div class="ft-price-plan">Personal</div>
        <div class="ft-price-name">Metal</div>
        <div class="ft-price-amount">
          <span class="ft-price-currency">$</span>
          <span class="ft-price-value">19</span>
          <span class="ft-price-period">/mo</span>
        </div>
        <div class="ft-price-desc">Ultra-premium for power users and frequent travelers</div>
        <div class="ft-price-divider"></div>
        <ul class="ft-price-features">
          <li><i class="ri-checkbox-circle-line"></i> Metal debit card</li>
          <li><i class="ri-checkbox-circle-line"></i> Unlimited global transfers</li>
          <li><i class="ri-checkbox-circle-line"></i> Crypto + investment access</li>
          <li><i class="ri-checkbox-circle-line"></i> Unlimited currencies</li>
          <li><i class="ri-checkbox-circle-line"></i> Unlimited ATM withdrawals</li>
          <li><i class="ri-checkbox-circle-line"></i> Dedicated account manager</li>
        </ul>
        <a href="<?= $web_url ?>/signup/verify-registration.php" class="ft-price-btn">Get Metal Card</a>
      </div>

      <!-- Business -->
      <div class="ft-price-card ft-reveal ft-reveal-delay-3">
        <div class="ft-price-plan">Business</div>
        <div class="ft-price-name">Business</div>
        <div class="ft-price-amount">
          <span class="ft-price-currency">$</span>
          <span class="ft-price-value">29</span>
          <span class="ft-price-period">/mo</span>
        </div>
        <div class="ft-price-desc">Scale your business with powerful banking infrastructure</div>
        <div class="ft-price-divider"></div>
        <ul class="ft-price-features">
          <li><i class="ri-checkbox-circle-line"></i> Multi-user team access</li>
          <li><i class="ri-checkbox-circle-line"></i> Bulk payment tools</li>
          <li><i class="ri-checkbox-circle-line"></i> Expense management</li>
          <li><i class="ri-checkbox-circle-line"></i> API access &amp; webhooks</li>
          <li><i class="ri-checkbox-circle-line"></i> Accounting integrations</li>
          <li><i class="ri-checkbox-circle-line"></i> Dedicated onboarding</li>
        </ul>
        <a href="<?= $web_url ?>/signup/verify-registration.php" class="ft-price-btn">Start Business Account</a>
      </div>

    </div>
  </div>
</section>

<!-- ═══════════════════ FAQ ═══════════════════ -->
<section class="ft-faq-bg" aria-labelledby="faq-heading">
  <div class="ft-section">
    <div class="ft-faq-grid">

      <div class="ft-faq-left ft-reveal">
        <div class="ft-badge" style="margin-bottom:16px">FAQ</div>
        <h2 id="faq-heading">Got Questions?<br>We've Got Answers.</h2>
        <p>Can't find what you're looking for? Reach out to our support team — we respond within minutes.</p>
        <a href="<?= $web_url ?>/p/contact.php" class="ft-faq-contact">
          <i class="ri-customer-service-2-line"></i> Talk to Support
        </a>
      </div>

      <div class="ft-accordion ft-reveal ft-reveal-delay-1" role="list">

        <div class="ft-accordion-item open" role="listitem">
          <button class="ft-accordion-header" aria-expanded="true">
            <span class="ft-accordion-q">Is my money safe and insured?</span>
            <span class="ft-accordion-icon"><i class="ri-add-line"></i></span>
          </button>
          <div class="ft-accordion-body" role="region">
            <p>Yes. All deposits are FDIC insured up to $250,000 per depositor. We use 256-bit AES encryption, multi-factor authentication, and real-time fraud monitoring to protect every account 24/7. Your funds are held at licensed banking institutions.</p>
          </div>
        </div>

        <div class="ft-accordion-item" role="listitem">
          <button class="ft-accordion-header" aria-expanded="false">
            <span class="ft-accordion-q">What are the international transfer fees?</span>
            <span class="ft-accordion-icon"><i class="ri-add-line"></i></span>
          </button>
          <div class="ft-accordion-body" role="region">
            <p>Standard accounts get up to $500/month in free transfers. Premium and Metal accounts have unlimited transfers with no fees. Business accounts include bulk payment tools. Exchange rates are at the real interbank rate — we never add a markup.</p>
          </div>
        </div>

        <div class="ft-accordion-item" role="listitem">
          <button class="ft-accordion-header" aria-expanded="false">
            <span class="ft-accordion-q">How do virtual cards work?</span>
            <span class="ft-accordion-icon"><i class="ri-add-line"></i></span>
          </button>
          <div class="ft-accordion-body" role="region">
            <p>Virtual cards are generated instantly from your dashboard. Each card has a unique 16-digit number, CVV, and expiry. You can create cards for subscriptions, freeze them, set spending limits, or delete them in seconds — ideal for online shopping security.</p>
          </div>
        </div>

        <div class="ft-accordion-item" role="listitem">
          <button class="ft-accordion-header" aria-expanded="false">
            <span class="ft-accordion-q">Which countries are supported?</span>
            <span class="ft-accordion-icon"><i class="ri-add-line"></i></span>
          </button>
          <div class="ft-accordion-body" role="region">
            <p>We currently support customers in 150+ countries for international transfers and payments. Full account opening is available to residents in the United States, UK, EU, Canada, and Australia with more regions being added regularly.</p>
          </div>
        </div>

        <div class="ft-accordion-item" role="listitem">
          <button class="ft-accordion-header" aria-expanded="false">
            <span class="ft-accordion-q">Can I open a business account?</span>
            <span class="ft-accordion-icon"><i class="ri-add-line"></i></span>
          </button>
          <div class="ft-accordion-body" role="region">
            <p>Absolutely. Our Business plan supports LLCs, corporations, sole proprietors, and freelancers. You get multi-user access, expense cards for your team, bulk payments, accounting integrations, API access, and a dedicated onboarding specialist.</p>
          </div>
        </div>

        <div class="ft-accordion-item" role="listitem">
          <button class="ft-accordion-header" aria-expanded="false">
            <span class="ft-accordion-q">How quickly can I open an account?</span>
            <span class="ft-accordion-icon"><i class="ri-add-line"></i></span>
          </button>
          <div class="ft-accordion-body" role="region">
            <p>Most accounts are approved in under 3 minutes. You'll need a valid government-issued ID and a selfie for verification. Once approved, your virtual card is ready to use immediately while your physical card ships within 3–5 business days.</p>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- ═══════════════════ FINAL CTA ═══════════════════ -->
<section class="ft-cta-section" aria-labelledby="cta-heading">
  <div class="ft-cta-inner">
    <div class="ft-badge ft-reveal" style="margin:0 auto 20px">Get Started Today</div>
    <h2 class="ft-reveal ft-reveal-delay-1" id="cta-heading">
      Take Control of<br><span class="text-grad">Your Money</span>
    </h2>
    <p class="ft-reveal ft-reveal-delay-2">
      Join millions of people who trust <?= htmlspecialchars($pageTitle) ?> to manage,
      grow, and protect their finances. Open your account in minutes — free forever.
    </p>
    <div class="ft-cta-actions ft-reveal ft-reveal-delay-3">
      <a href="<?= $web_url ?>/signup/verify-registration.php" class="ft-btn-hero-primary">
        <i class="ri-bank-card-line"></i> Open Free Account
      </a>
      <a href="<?= $web_url ?>/login.php" class="ft-btn-hero-secondary">
        <i class="ri-login-box-line"></i> Sign In
      </a>
    </div>
  </div>
</section>

<?php include_once("front/footer.php"); ?>
