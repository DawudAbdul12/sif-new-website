<footer>
  <div class="container">
    <div class="foot-grid">
      <div class="foot-brand">
        <img src="https://sifinghana.org/backend/images/uploads/logo/sif-logo-new.png" alt="The Social Investment Fund" data-fallback>
        <p>A pro-poor institution of the Government of Ghana, mobilising resources and delivering inclusive economic and social development programmes that improve livelihoods across all sixteen regions.</p>
        <div class="foot-social">
          <a href="https://www.facebook.com/profile.php?id=100066870500808" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 22v-8h2.7l.4-3.1h-3.1V9c0-.9.25-1.5 1.55-1.5H17V4.7C16.6 4.65 15.5 4.5 14.2 4.5c-2.6 0-4.4 1.6-4.4 4.5v2.4H7v3.1h2.8V22h3.7Z"/></svg></a>
          <a href="https://www.instagram.com/sifghana/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="3.6"/><circle cx="17" cy="7" r="1"/></svg></a>
          <a href="https://www.youtube.com/@sifinghana" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="6" width="18" height="12" rx="4"/><path d="M10.5 9.5 15 12l-4.5 2.5Z" fill="currentColor" stroke="none"/></svg></a>
          <a href="https://www.linkedin.com/in/sifinghana/" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 8.5H4.1V20h2.84V8.5ZM5.52 4a1.65 1.65 0 1 0 0 3.3 1.65 1.65 0 0 0 0-3.3ZM20 13.4c0-3-1.6-4.4-3.74-4.4a3.23 3.23 0 0 0-2.93 1.6V8.5h-2.84c.04.8 0 11.5 0 11.5h2.84v-6.4c0-.34.02-.68.12-.93.27-.68.9-1.4 1.94-1.4 1.37 0 1.92 1.04 1.92 2.57V20H20v-6.6Z"/></svg></a>
        </div>
      </div>

      <div class="foot-col">
        <h5>About</h5>
        <ul>
          <li><a href="{{ route('about') }}">About SIF</a></li>
          <li><a href="{{ route('about') }}#mvv">Mission, Vision &amp; Values</a></li>
          <li><a href="{{ route('board') }}">Board of Directors</a></li>
          <li><a href="{{ route('leadership') }}">Leadership</a></li>
          <li><a href="{{ route('departments') }}">Departments &amp; Units</a></li>
          <li><a href="{{ route('departments') }}#zones">Zonal Offices</a></li>
        </ul>
      </div>

      <div class="foot-col">
        <h5>Projects</h5>
        <ul>
          <li><a href="{{ route('projects') }}">All Projects</a></li>
          <li><a href="{{ route('projects') }}?status=ongoing">Active Projects</a></li>
          <li><a href="{{ route('projects') }}?status=completed">Completed Projects</a></li>
          <li><a href="{{ route('projects') }}#map">Project Map</a></li>
        </ul>
      </div>

      <div class="foot-col">
        <h5>Resources</h5>
        <ul>
          <li><a href="{{ route('resources') }}#reports">Annual Reports</a></li>
          <li><a href="{{ route('resources') }}#publications">Publications</a></li>
          <li><a href="{{ route('resources') }}#procurement">Procurement Notices</a></li>
          <li><a href="{{ route('news') }}">News &amp; Media</a></li>
          <li><a href="{{ route('resources') }}#faq">FAQ</a></li>
        </ul>
      </div>

      <div class="foot-col">
        <h5>Public Information</h5>
        <ul>
          <li><a href="{{ route('contact') }}">Contact Us</a></li>
          <li><a href="{{ route('complaint') }}">Submit a Complaint</a></li>
          <li><a href="tel:0800600555">Toll-Free: 0800 600 555</a></li>
          <li><a href="https://api.whatsapp.com/send?phone=233203918036" target="_blank" rel="noopener">WhatsApp</a></li>
          <li><a href="{{ route('sitemap') }}">Sitemap</a></li>
        </ul>
      </div>
    </div>

    <div class="foot-bottom">
      <span>&copy; <span id="year"></span> The Social Investment Fund, Ghana. All rights reserved. · Digital Address: GL-090-8073</span>
      <div class="foot-legal">
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <a href="{{ route('accessibility') }}">Accessibility Statement</a>
        <a href="{{ route('terms') }}">Terms of Use</a>
      </div>
      <div class="partners-mini">
        <img src="https://sifinghana.org/backend/images/uploads/gov-logo.png1732797769.png" alt="Government of Ghana" data-fallback loading="lazy">
        <img src="https://sifinghana.org/backend/images/uploads/download%20%281%29.png1732798233.webp1738336933.webp" alt="AfDB" data-fallback loading="lazy">
        <img src="https://sifinghana.org/backend/images/uploads/undp-logo-png_seeklogo-322648.png1732798147.webp1738336912.webp" alt="UNDP" data-fallback loading="lazy">
      </div>
    </div>
  </div>
</footer>

<div class="cookie-banner" id="cookieBanner">
  <p><strong style="color:var(--forest);font-family:var(--font-head);">This site uses cookies.</strong><br>We use essential cookies to keep this site secure and remember your accessibility preferences. We do not sell personal data.</p>
  <div class="ck-actions">
    <button type="button" id="cookieAccept" class="btn btn-primary btn-sm">Accept</button>
    <button type="button" id="cookieDecline" class="btn btn-outline btn-sm">Decline</button>
  </div>
</div>
