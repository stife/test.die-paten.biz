<?php
// Session starten, falls noch nicht aktiv – mit sicheren Cookie-Parametern
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime'  => 86400,            // 30 Tage
        'path'      => '/',
        'domain'    => $_SERVER['HTTP_HOST'],
        'secure'    => true,
        'httponly'  => true,
        'samesite'  => 'Strict'
    ]);
    session_start();
    $sessionStarted = true;
} else {
    $sessionStarted = false;
}

// Falls die Session nicht gerade neu gestartet wurde, Variablen einbinden
if (!$sessionStarted) {
    include_once 'include/variablen.php';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        section.cont {
            display: none;
        }
    </style>
    <!-- Debug-Tool (nur in der Entwicklung nutzen) -- >
    <script src="https://cdn.jsdelivr.net/npm/eruda"></script>
    <script>eruda.init();</script>
    <!---->
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['email'])): ?>
            <div class="user-info">
                Eingeloggt als: <?= htmlspecialchars($_SESSION['email']) ?>
            </div>
        <?php endif; ?>

        <?php if ($zeige_header): ?>
            <header>
                <div class="header_content">
                    <h1><?= $titel ?></h1>
                    <?php include 'include/navigation.php'; ?>
                </div>
            </header>
        <?php endif; ?>

        <main>
            <!-- Home -->
            <section class="cont" id="home">
                <h2><?= $title_home ?></h2>
                <div class="home-content">
                    <?php include $content_home; ?>
                </div>
            </section>
            
            <!-- Expresso -->
            <section class="cont" id="expresso">
                <h2><?= $title_expresso ?></h2>
                <div class="expresso-content">
                    <?php include $expresso; ?>
                </div>
            </section>
            
            <!-- Aufgabenliste -->
            <section class="cont" id="aufgaben">
                <h2><?= $title_aufgabenliste ?></h2>
                <div class="aufgaben-content">
                    <?php include $content_aufgabenliste; ?>
                </div>
            </section>
            
            <!-- IP-Adresse -->
            <section class="cont" id="ipadresse">
                <h2>IP - Adresse</h2>
                <?php include 'include/ipadresse/index.php'; ?>
            </section>
            
            <!-- Login -->
            <section class="cont" id="login">
                <div class="login-content">
                    <?php include $content_login; ?>
                </div>
            </section>
            
            <!-- Logout -->
            <section class="cont" id="logout">
                <h2><?= $title_logout ?></h2>
                <a href="logout.php"></a>
            </section>
            
            <!-- BunkerTV -->
            <?php if ($zeige_bunkertv): ?>
                <section class="cont" id="bunkertv">
                    <h2><?= $title_bunkertv ?></h2>
                    <div class="bunkertv-content">
                        <?php include $bunkertv; ?>
                    </div>
                </section>
            <?php endif; ?>
            
            <!-- Countdown -->
            <?php if ($zeige_countdown): ?>
                <section class="cont" id="countdown">
                    <div class="countdown-content">
                        <?php include $countdown; ?>
                    </div>
                </section>
            <?php endif; ?>
        </main>

        <footer>
            <?php include "content/footer.php"; ?>
        </footer>
    </div>

    <script>
        // Dropdown-Funktionen
        function toggleDropdown(event) {
            event.preventDefault();
            const dropdown = event.target.nextElementSibling;
            if (dropdown) {
                const isVisible = dropdown.style.display === "block";
                closeAllDropdowns();
                dropdown.style.display = isVisible ? "none" : "block";
            }
        }

        function closeAllDropdowns() {
            document.querySelectorAll('.dropdown-content').forEach(dropdown => {
                dropdown.style.display = "none";
            });
        }

        // Funktion zum Anzeigen eines bestimmten Abschnitts
        function zeigeAbschnitt(abschnittId) {
            document.querySelectorAll('section.cont').forEach(section => {
                section.style.display = 'none';
            });
            const target = document.getElementById(abschnittId);
            if (target) {
                target.style.display = 'flex';
                target.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Initialisierung nach DOM-Load
        document.addEventListener('DOMContentLoaded', function() {
            // Navigation: Aktiven Link anhand des URL-Hash setzen
            const currentHash = window.location.hash.substring(1);
            document.querySelectorAll('nav ul li a').forEach(link => {
                const linkHash = link.getAttribute('href').substring(1);
                link.classList.toggle('active', linkHash === currentHash);
            });

            // Navigation: Klick-Events für Listenelemente mit data-href und aktiver Klasse
            document.querySelectorAll('nav ul li').forEach(li => {
                li.addEventListener('click', function(event) {
                    event.preventDefault();
                    const href = li.getAttribute('data-href');
                    if (href) {
                        window.location.href = href;
                    }
                    // Aktiven Link setzen
                    li.querySelectorAll('a').forEach(a => {
                        a.classList.add('active');
                    });
                });
            });

            // Sticky Buttons: Buttons unterhalb des Headers "kleben" beim Scrollen
            const buttonsCategory = document.querySelector('.buttonsCategory');
            const header = document.querySelector('header');
            if (buttonsCategory && header) {
                const headerHeight = header.offsetHeight;
                window.addEventListener('scroll', () => {
                    buttonsCategory.classList.toggle('sticky', window.scrollY >= headerHeight);
                });
            }

            // Dynamisches Anpassen des margin-top von main
            const main = document.querySelector('main');
            if (header && main) {
                main.style.marginTop = header.offsetHeight + 'px';
            }

            // Anzeige des Abschnitts anhand des URL-Hash oder Standard "home"
            const hash = window.location.hash.slice(1) || 'home';
            zeigeAbschnitt(hash);
        });

        // Schließt Dropdowns, wenn außerhalb geklickt wird
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.dropdown')) {
                closeAllDropdowns();
            }
        });
    </script>
</body>
</html>