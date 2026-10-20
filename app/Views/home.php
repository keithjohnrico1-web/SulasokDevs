<!DOCTYPE html>
<html>
<head>
    <title>Sun Son Solar</title>
    <link rel="stylesheet" href="<?= base_url('css/index.css') ?>"">
</head>
<body>

<nav>
    <div><img src="<?= base_url('logo.jpg') ?>" alt="Sun Son Solar Logo"><span class="brand">Sun Son Solar</span></div>
    <div>
        <a href="#services">Services</a><a href="#about">About</a><a href="#testimonials">Testimonials</a>
        <a href="#faq">FAQ</a><a href="#news">News</a><a href="#contact">Contact</a><a href="#login">Login</a>
    </div>
</nav>

<div class="hero">
    <h1>Power Your Future with Solar</h1>
    <p>Clean, affordable energy for your home and business.</p>
    <a class="cta" href="href="<?= site_url('register') ?>"">Request a Free Quote</a>
    <a class="cta" href="href="<?= site_url('register') ?>"">Schedule a Service</a>
</div>

<section id="about">
    <h2>About Sun Son Solar</h2>
    <p style="text-align:center">Founded by Katherine and Santino "Sonny" Sinagaraw, Sun Son Solar started in Pasig City. We sell solar panels, inverters, batteries, racking and mounting, and wires, and we help you every step of the way.</p>
    <div class="cards">
        <div class="card"><h3>Cost Savings</h3>Lower your monthly electricity bill and get your investment back over time.</div>
        <div class="card"><h3>Sustainability</h3>Cut your carbon footprint with clean energy from the sun.</div>
        <div class="card"><h3>Energy Independence</h3>Depend less on the grid and be ready when the power goes out.</div>
    </div>
</section>

<section id="services" class="alt"><div>
    <h2>Our Services</h2>
    <div class="cards">
        <div class="card"><h3>Consultation</h3>We check your energy needs and suggest the right solar setup.</div>
        <div class="card"><h3>Installation</h3>Our technicians install your system safely and cleanly.</div>
        <div class="card"><h3>Maintenance</h3>Regular check-ups, repair and monitoring keep your system running well.</div>
    </div>
    <p style="text-align:center">We also offer designing and permitting.</p>
</div></section>

<section id="testimonials">
    <h2>What Our Customers Say</h2>
    <div class="cards">
        <div class="card">"My electric bill dropped a lot after installation. The team was on time and professional." <br><b>- Francis Lequin</b></div>
        <div class="card">"Easy to book a schedule online and the technician arrived as planned." <br><b>- Elbertson Suela</b></div>
        <div class="card">"Great service from consultation to maintenance." <br><b>- Norvin Mina</b></div>
    </div>
    <p style="text-align:center;font-size:12px;color:#999">(Sample testimonials - replace with real customer stories.)</p>
</section>

<section id="login" class="alt"><div>
    <h2>Login</h2>
    <form class="login-box" action="<?= site_url('login') ?>" method="POST">
        <?= csrf_field() ?>
        <select name="type"><option value="customer">Customer</option><option value="employee">Employee</option></select>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p style="text-align:center;font-size:13px">New customer? <a href="<?= site_url('register') ?>" style="color:#ff9900">Create an account</a></p>
    </form>
</div></section>

<section id="faq">
    <h2>Frequently Asked Questions</h2>
    <details><summary>How do I schedule a service?</summary>Create a customer account, log in, and choose a service and date in My Schedule.</details>
    <details><summary>Can I change or cancel my schedule?</summary>Yes. You can reschedule from your dashboard, and cancel while the request is still pending.</details>
    <details><summary>Do you help with permits?</summary>Yes. Permitting is one of our services.</details>
    <details><summary>How do I contact support?</summary>Log in and send a ticket from the Support tab, or use the contact details below.</details>
</section>

<section id="news" class="alt"><div>
    <h2>News &amp; Updates</h2>
    <div class="cards">
        <div class="card"><h3>Sun Son Solar opens in Pasig City</h3>Our first office is now open to customers.</div>
        <div class="card"><h3>Why go solar?</h3>A short look at the savings and benefits of solar power.</div>
    </div>
    <p style="text-align:center;font-size:12px;color:#999">(Sample posts - replace with real updates.)</p>
</div></section>

<footer id="contact">
    <p><b>Sun Son Solar</b> - Pasig City</p>
    <p>Phone: 09291230983 | Email: katherine.sinagaraw@sunsonsolar.com</p>
    <p><a href="#">Facebook</a><a href="#">Instagram</a><a href="#">LinkedIn</a></p>
    <p><a href="#">Privacy Policy</a><a href="#">Terms of Service</a></p>
    <p>&copy; 2026 Sun Son Solar. All rights reserved.</p>
</footer>

</body>
</html>
