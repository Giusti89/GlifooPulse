@php
    $yaAcepto = request()->cookie('cookies_ok') === '1';
@endphp

@if (! $yaAcepto)
    <div id="glifoo-cookie-banner"
         style="position: fixed; bottom: 0; left: 0; right: 0; z-index: 99999;
                padding: 1rem 1.25rem; background: rgba(17, 24, 39, 0.96);
                backdrop-filter: blur(8px); border-top: 1px solid rgba(255,255,255,0.08);
                box-shadow: 0 -6px 24px rgba(0,0,0,0.35);
                display: flex; flex-direction: column; gap: 0.75rem;
                font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
                font-size: 0.875rem; color: #e5e7eb; line-height: 1.5;">
        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                 fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                <path d="M12 2a10 10 0 1 0 10 10 4 4 0 0 1-5-5 4 4 0 0 1-5-5"></path>
                <path d="M8.5 8.5v.01"></path>
                <path d="M16 12v.01"></path>
                <path d="M12 16v.01"></path>
            </svg>
            <p style="margin: 0; flex: 1;">
                Usamos cookies para medir el tráfico y mejorar tu experiencia.
                <a href="{{ url('/terminos') }}" target="_blank"
                   style="color: #f59e0b; text-decoration: underline;">
                    Más información
                </a>
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
            <button type="button" id="glifoo-cookie-accept"
                    style="background: #f59e0b; color: #111827; padding: 0.55rem 1.25rem;
                           border: none; border-radius: 0.5rem; font-weight: 600;
                           cursor: pointer; font-size: 0.875rem; transition: background 0.2s;">
                Aceptar
            </button>
        </div>
    </div>

    <script>
        (function() {
            function aceptar() {
                var expira = new Date();
                expira.setFullYear(expira.getFullYear() + 1);
                document.cookie = "cookies_ok=1; expires=" + expira.toUTCString() + "; path=/; SameSite=Lax";
                var banner = document.getElementById('glifoo-cookie-banner');
                if (banner) banner.style.display = 'none';
            }

            var btn = document.getElementById('glifoo-cookie-accept');
            if (btn) {
                btn.addEventListener('click', aceptar);
            }
        })();
    </script>
@endif