<?php

?>



<h3>3D Models</h3>

<p>Three original models were created in Blender 5.1, all themed around music production
and studio audio equipment. Each was UV-unwrapped, textured, animated, and exported as a
binary <code>.glb</code> file for Three.js to load in the browser. For every model the sound
effect plays in synchronisation with the animation, and the animation stops when the audio
ends.</p>

<h4>Vinyl Record</h4>
<p>Modelled from a cylinder primitive with 64 vertices. The top face was inset and the
resulting inner ring was extruded to form the centre hole. A circular label texture was
UV-projected onto the top face using "Project from View (Bounds)" so the artwork sits
perfectly flat. The label artwork was sourced from Unsplash (copyright-free). The spinning
animation rotates the record on its Y-axis at 33⅓ RPM, with a reverse cycle baked into the
same animation clip rather than using two separate animations. The accompanying audio is a
copyright-free jazz sample.</p>

<div class="row g-3 my-3">
    <div class="col-md-6">
        <img src="images/about/vinyl-blender.jpg" class="img-fluid rounded" alt="Vinyl record in Blender">
        <p class="text-center small mt-2 text-white">Vinyl record in Blender viewport (solid shading)</p>
    </div>
    <div class="col-md-6">
        <img src="images/about/vinyl-rendered.jpg" class="img-fluid rounded" alt="Vinyl record final render">
        <p class="text-center small mt-2 text-white">Final rendered output with label texture</p>
    </div>
</div>

<h4>Bookshelf Speakers</h4>
<p>A pair of cabinets each built from a cube primitive with bevelled edges. The driver
cones were created from a cylinder, with an inset face pulled inward to form the cone
shape, plus a dust-cap dome on top. The custom side pattern was created with a procedural
Wave Texture and Colour Ramp inside Blender's shader graph, then baked to a 1024 by 1024
PNG so the procedural pattern would survive the GLB export. Shape keys drive the bass-pulse
animation on both woofer cones to mimic the in-out movement of a real speaker driver.</p>

<div class="row g-3 my-3">
    <div class="col-md-6">
        <img src="images/about/speakers-blender.jpg" class="img-fluid rounded" alt="Speakers in Blender">
        <p class="text-center small mt-2 text-white">Speaker pair in Blender, cone detail visible</p>
    </div>
    <div class="col-md-6">
        <img src="images/about/speakers-shader.jpg" class="img-fluid rounded" alt="Speaker shader graph">
        <p class="text-center small mt-2 text-white">Wave Texture + Colour Ramp shader graph (pre-bake)</p>
    </div>
</div>

<h4>Microphone (with stand)</h4>
<p>The mic capsule was built from a sphere with manual edge-loop reduction at the top to
flatten it slightly. The grille uses a wireframe modifier on a simplified sphere to give
the perforated look without the polygon cost of modelling each hole individually. The boom
arm and base were built from cylinder primitives joined with subdivision-surface modifiers
for smooth rounded joints. The animation tilts the microphone 30 degrees back and forth on
its stand to simulate adjustment during a recording session, with the reverse motion built
into the same animation cycle.</p>

<div class="row g-3 my-3">
    <div class="col-md-6">
        <img src="images/about/microphone-blender.jpg" class="img-fluid rounded" alt="Microphone in Blender">
        <p class="text-center small mt-2 text-white">Microphone assembly in Blender</p>
    </div>
    <div class="col-md-6">
        <img src="images/about/microphone-modifier.jpg" class="img-fluid rounded" alt="Wireframe modifier on grille">
        <p class="text-center small mt-2 text-white">Wireframe modifier producing the grille pattern</p>
    </div>
</div>

<hr>



<h3>Wireframe</h3>

<p>Wireframe views of each model showing the underlying mesh topology. Lower polygon counts
were prioritised wherever the silhouette allowed it, particularly on the speaker cabinets
and the microphone boom arm, so the GLB files stay small enough to load quickly in the
browser. Subdivision surface modifiers were applied non-destructively where smoother curves
were needed (microphone capsule, dust caps).</p>

<div class="row g-3 my-3">
    <div class="col-md-4">
        <img src="images/about/vinyl-wireframe.jpg" class="img-fluid rounded" alt="Vinyl wireframe">
        <p class="text-center small mt-2 text-white">Vinyl Record</p>
    </div>
    <div class="col-md-4">
        <img src="images/about/speakers-wireframe.jpg" class="img-fluid rounded" alt="Speakers wireframe">
        <p class="text-center small mt-2 text-white">Bookshelf Speakers</p>
    </div>
    <div class="col-md-4">
        <img src="images/about/microphone-wireframe.jpg" class="img-fluid rounded" alt="Microphone wireframe">
        <p class="text-center small mt-2 text-white">Microphone &amp; Stand</p>
    </div>
</div>

<p class="text-center small mt-2 text-white">The same wireframe view is available live inside the 3D viewer by clicking the <strong>Toggle Wireframe</strong> button on any model.</p>

<hr>



<h3>Technologies Used</h3>

<div class="row g-4 my-3">
    <div class="col-md-6">
        <h5>Front end</h5>
        <ul>
            <li>HTML5 and CSS3</li>
            <li>Bootstrap 5.3.2 (grid system, navbar, cards, dropdown)</li>
            <li>jQuery 3.7.1 (AJAX, DOM rendering, fade animations)</li>
            <li>Font Awesome 6.4 (carousel arrows, CTAs)</li>
            <li>Custom CSS carousel (no Bootstrap carousel dependency)</li>
        </ul>

        <h5 class="mt-4">3D / Graphics</h5>
        <ul>
            <li>Three.js 0.160 (loaded as ES6 modules via import map)</li>
            <li>GLTFLoader (glTF 2.0 binary model loading)</li>
            <li>OrbitControls (mouse drag and zoom)</li>
            <li>EffectComposer with UnrealBloomPass (post-processing)</li>
            <li>Blender 5.1 (modelling, texturing, animation, GLB export)</li>
        </ul>
    </div>

    <div class="col-md-6">
        <h5>Back end</h5>
        <ul>
            <li>PHP 8.2 (custom front controller and JSON API endpoints)</li>
            <li>SQLite 3 via PDO (zero-configuration relational store)</li>
            <li>MVC architecture (separate model / controller / view layers)</li>
            <li>Custom action-based router (<code>index.php?action=...</code>)</li>
        </ul>

        <h5 class="mt-4">Tooling and hosting</h5>
        <ul>
            <li>Visual Studio Code with Live Server and PHP Intelephense</li>
            <li>XAMPP (Apache 2.4 + PHP 8.2 + SQLite) for local development</li>
            <li>WinSCP / SFTP for deployment to a web server</li>
        </ul>
    </div>
</div>

<hr>



<h3>Features</h3>

<div class="row g-4 my-3">
    <div class="col-md-6">
        <h5>Interface and navigation</h5>
        <ul>
            <li>Single-page application with three fade-transitioned sections
                (Home, Products, About)</li>
            <li>Custom CSS carousel on the home page with autoplay-free manual navigation,
                left/right arrow buttons, dot indicators, full keyboard arrow-key support,
                and touch swipe gestures on mobile</li>
            <li>"3D Models" dropdown in the navbar for quick jumps directly to any model</li>
            <li>Click-through CTAs ("View in 3D") on every carousel slide</li>
            <li>Fully responsive layout from mobile (320px) up to desktop, using Bootstrap's
                fluid grid system</li>
        </ul>

        <h5 class="mt-4">3D Viewer</h5>
        <ul>
            <li>Real-time GLB loading with progress indicator and load-race protection
                (rapid model swaps cannot leave half-loaded geometry in the scene)</li>
            <li>Wireframe toggle (live mesh visualisation)</li>
            <li>Auto-rotate toggle (continuous Y-axis spin)</li>
            <li>Play / pause animation with state-aware button styling</li>
            <li>OrbitControls for click-and-drag camera rotation, scroll-to-zoom</li>
            <li>Three camera presets (Front, Top, Hero) triggered via JavaScript</li>
            <li>Toggleable spot light plus 0-2 intensity slider</li>
            <li>UnrealBloomPass post-processing for stylised highlights</li>
        </ul>
    </div>

    <div class="col-md-6">
        <h5>Audio and animation</h5>
        <ul>
            <li>Per-model demo audio (vinyl jazz, speaker beat, microphone test)</li>
            <li>Audio-synced animation: pressing Play Sound starts both the audio and the
                model's animation</li>
            <li>Both buttons reflect each other's state, stopping one stops the other</li>
            <li>Cache-busted audio URLs so swapping product mid-session loads the correct
                file every time</li>
            <li>Reverse-motion built into a single animation clip rather than two
                separate clips (vinyl spin, microphone tilt)</li>
        </ul>

        <h5 class="mt-4">Data and backend</h5>
        <ul>
            <li>MVC architecture with Model / Controller / View separation</li>
            <li>Two read-only JSON API endpoints: <code>apiGetProducts</code>,
                <code>apiGetProduct</code></li>
            <li>SQLite database auto-created and auto-seeded from <code>data.json</code>
                on the first catalogue API request, without resetting existing rows</li>
            <li>Front end normalises snake-case (DB) and camel-case (JSON) keys
                transparently</li>
            <li>Graceful fallback to static JSON if the API is unavailable, so the page
                can still load catalogue data over HTTP if PHP is unavailable</li>
            <li>About page content lazy-loaded on demand via <code>$.get()</code> from a
                server-rendered PHP partial</li>
        </ul>
    </div>
</div>

<hr>



<h3>Testing</h3>

<p>Testing was conducted throughout development using 'developer self-testing', which is a common
methodology where the developer systematically exercises the application against documented
expected outcomes. The testing covered three areas: functional correctness of every
interactive element, backend verification of the MVC/AJAX/SQLite flow, and responsive
layout across viewport sizes using Chrome DevTools device emulation.</p>

<p>Where testing revealed a defect, the fix was applied and the behaviour re-tested before
moving on. Two defects found during this process directly changed the codebase:</p>

<ul>
    <li>
        <strong>Product description not updating on model switch.</strong>
        While testing the model selector, switching between Vinyl, Speakers, and Microphone
        left the title and description showing the previous product's data. The root cause
        was that <code>populateProductInfo()</code> was only called inside the GLTFLoader
        <code>onLoad</code> callback, meaning it never fired for models that failed to load,
        and was delayed for those that loaded slowly. Fixed by calling
        <code>populateProductInfo(id)</code> immediately inside <code>window.loadProduct</code>
        before the loader even starts, so the UI always updates on click regardless of load
        outcome.
    </li>
    <li>
        <strong>Stale audio playing after switching products.</strong>
        Testing audio playback after switching from Speakers to Vinyl revealed the browser
        was still playing the speaker audio file. The <code>Audio</code> object was being
        retained across model swaps. Fixed by appending a <code>?v=Date.now()</code>
        cache-busting query string so each play request forces a fresh fetch of the correct
        file.
    </li>
    <li>
        <strong>Half-loaded models accumulating in the scene.</strong>
        Rapidly clicking between model buttons during load testing produced stacked geometry
        in the Three.js scene because each GLTFLoader callback added its model on arrival,
        regardless of whether a newer request had already started. Fixed by introducing an
        incrementing <code>loadRequestId</code> counter - any callback whose ID does not
        match the latest request disposes of its geometry and returns early.
    </li>
    <li>
        <strong>Vinyl GLB file size causing slow initial load.</strong>
        The vinyl record model exported at 8.3 MB, noticeably slow on first load. Reviewed
        the Blender export settings and confirmed the large size is due to the embedded
        label texture resolution. Mitigated by showing a percentage progress indicator
        inside the viewer while the model streams, so the user receives feedback rather
        than a blank black canvas.
    </li>
</ul>

<p>The functional test matrix below documents the full set of tests run against the final
build.</p>

<ul>
    <li><strong>Functional testing</strong>: every button, control, and interactive
        element was exercised with a documented expected outcome and the actual result
        recorded. The matrix below summarises the results.</li>
    <li><strong>Backend verification</strong>: the AJAX network traffic and PHP endpoint responses were inspected via Chrome DevTools and direct
        URL hits to confirm the MVC flow is actually serving data from the database
        rather than from hardcoded values.</li>
</ul>



<hr>



<h3>Functional Testing</h3>

<p>The matrix below lists every interactive feature, the expected behaviour, and the
observed result. All tests passed on the final check.</p>

<div class="table-responsive my-3">
    <table class="table table-dark table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Feature tested</th>
                <th>Expected outcome</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>T01</td>
                <td>Home page loads</td>
                <td>JB Productions home with carousel appears, no console errors</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T02</td>
                <td>Carousel next arrow</td>
                <td>Slide advances by 1, dot indicator updates</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T03</td>
                <td>Carousel previous arrow</td>
                <td>Slide goes back by 1, wraps around from slide 1</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T04</td>
                <td>Carousel dot navigation</td>
                <td>Clicking any dot jumps directly to that slide</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T05</td>
                <td>Carousel keyboard navigation</td>
                <td>Left/right arrow keys move carousel when home is visible</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T06</td>
                <td>Carousel touch swipe (mobile)</td>
                <td>Swipe left advances, swipe right goes back</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T07</td>
                <td>Carousel slide click</td>
                <td>Clicking any slide loads that product in the 3D viewer</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T08</td>
                <td>3D Models dropdown</td>
                <td>Dropdown opens, all 3 items load the correct model</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T09</td>
                <td>Load Vinyl Record</td>
                <td>product1.glb appears, title and description update from DB</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T10</td>
                <td>Load Speakers</td>
                <td>product2.glb appears with baked side texture intact</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T11</td>
                <td>Load Microphone</td>
                <td>product3.glb appears with grille modifier visible</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T12</td>
                <td>Toggle Wireframe</td>
                <td>All meshes switch to wireframe, second click restores solid</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T13</td>
                <td>Auto-Rotate</td>
                <td>Model spins continuously on Y-axis, toggle stops it</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T14</td>
                <td>Play Animation</td>
                <td>Model animation plays in a loop, button turns green and shows "Animation ON"</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T15</td>
                <td>Play Sound</td>
                <td>Correct audio file plays for current product, animation starts in sync</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T16</td>
                <td>Audio-animation sync</td>
                <td>Audio ending stops animation, button states reset</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T17</td>
                <td>Camera Front preset</td>
                <td>Camera repositions to (0, 1.5, 4) looking at origin</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T18</td>
                <td>Camera Top preset</td>
                <td>Camera repositions to top-down view</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T19</td>
                <td>Camera Hero preset</td>
                <td>Camera repositions to angled hero shot</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T20</td>
                <td>OrbitControls drag</td>
                <td>Mouse drag rotates camera around the model</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T21</td>
                <td>OrbitControls zoom</td>
                <td>Mouse wheel zooms camera in and out smoothly</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T22</td>
                <td>Spot Light toggle</td>
                <td>Spot light turns on with warm 0xfff4e0 colour, second click off</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T23</td>
                <td>Light intensity slider</td>
                <td>Spot light brightness scales 0 to 6 as slider moves 0 to 2</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T24</td>
                <td>Section nav (Home / About)</td>
                <td>Section switches with fade, scroll resets to top</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T25</td>
                <td>About page loads on demand</td>
                <td>First click on About fetches viewAbout.php via AJAX, populates content</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T26</td>
                <td>Database initialisation</td>
                <td>Creates table and seeds 3 rows from data.json on the first catalogue request</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T27</td>
                <td>API: apiGetProducts</td>
                <td>Returns full array of 3 products from SQLite as JSON</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T28</td>
                <td>API: apiGetProduct?id=2</td>
                <td>Returns single product object for Speakers</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T29</td>
                <td>API: unknown action</td>
                <td>Returns HTTP 404 with JSON error body</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T30</td>
                <td>Static JSON fallback</td>
                <td>With API offline, gallery still populates from data.json</td>
                <td><span>Pass</span></td>
            </tr>
            <tr>
                <td>T31</td>
                <td>Rapid model swap</td>
                <td>Clicking products fast does not stack half-loaded models in scene</td>
                <td><span>Pass</span></td>
            </tr>

        </tbody>
    </table>
</div>

<h5 class="mt-4">Issues discovered through testing and fixed</h5>
<p>Two issues surfaced during the testing pass and were fixed before submission:</p>
<ul>
    <li><strong>Stale audio after model swap.</strong> Initially, swapping models mid-playback
        sometimes played the previous product's audio because the browser cached the
        <code>Audio</code> object. Fixed by appending a cache-busting <code>?v=Date.now()</code>
        query string to the audio URL on every play.</li>
    <li><strong>Half-loaded models stacking in the scene.</strong> Rapidly clicking different
        model buttons before each load completed left orphan meshes in the Three.js scene
        graph. Fixed by introducing an incrementing <code>loadRequestId</code> so callbacks
        from any superseded load dispose of their geometry and return early.</li>
</ul>

<hr>



<h3>Testing Evidence</h3>

<p>Screenshots evidencing key tests from the matrix above. All images were captured on the
final build.</p>

<h5 class="mt-4">Desktop view</h5>
<div class="row g-3 my-3">
    <div class="col-md-6">
        <img src="images/about/test-desktop-home.jpg" class="img-fluid rounded" alt="Desktop home page">
        <p class="text-center small mt-2 text-white">Home page carousel on desktop (1920x1080)</p>
    </div>
    <div class="col-md-6">
        <img src="images/about/test-desktop-viewer.jpg" class="img-fluid rounded" alt="Desktop 3D viewer">
        <p class="text-center small mt-2 text-white">3D viewer with controls on desktop</p>
    </div>
</div>

<h5 class="mt-4">Mobile / responsive view</h5>
<div class="row g-3 my-3">
    <div class="col-md-4">
        <img src="images/about/test-mobile-home.jpg" class="img-fluid rounded" alt="Mobile home">
        <p class="text-center small mt-2 text-white">Home page on mobile (iPhone 12 width)</p>
    </div>
    <div class="col-md-4">
        <img src="images/about/test-mobile-viewer.jpg" class="img-fluid rounded" alt="Mobile viewer">
        <p class="text-center small mt-2 text-white">3D viewer adapted for mobile</p>
    </div>
    <div class="col-md-4">
        <img src="images/about/test-tablet.jpg" class="img-fluid rounded" alt="Tablet view">
        <p class="text-center small mt-2 text-white">Tablet view (iPad portrait)</p>
    </div>
</div>

<h5 class="mt-4">MVC / AJAX evidence</h5>
<p>The screenshots below evidence that data is flowing through the full MVC stack
(SQLite database to PHP controller to JSON to AJAX consumer) rather than from hardcoded
HTML.</p>

<div class="row g-3 my-3">
    <div class="col-md-6">
        <img src="images/about/test-ajax-network.jpg" class="img-fluid rounded" alt="DevTools network">
        <p class="text-center small mt-2 text-white">
            Chrome DevTools Network tab showing the AJAX call to
            <code>index.php?action=apiGetProducts</code>
        </p>
    </div>
    <div class="col-md-6">
        <img src="images/about/test-ajax-response.jpg" class="img-fluid rounded" alt="JSON response">
        <p class="text-center small mt-2 text-white">
            JSON response body returned by the controller, populated from SQLite
        </p>
    </div>
</div>


    <div class="col-md-6">
        <img src="images/about/test-api-direct.jpg" class="img-fluid rounded" alt="API direct hit">
        <p class="text-center small mt-2 text-white">
            Direct browser hit to
            <code>index.php?action=apiGetProducts</code> showing raw JSON output
        </p>
    </div>
</div>

<h5 class="mt-4">Browser console</h5>
<div class="row g-3 my-3">
    <div class="col-md-6">
        <img src="images/about/test-console-clean.jpg" class="img-fluid rounded" alt="Clean console">
        <p class="text-center small mt-2 text-white">
            Refreshed Chrome regression check: no uncaught page errors during the tested interactions
        </p>
    </div>
    <div class="col-md-6">
        <img src="images/about/test-cross-browser.jpg" class="img-fluid rounded" alt="Cross-browser">
        <p class="text-center small mt-2 text-white">
            Browser verification of the restored gallery
        </p>
    </div>
</div>






<h3>References</h3>

<ul>
    <li>Three.js Documentation. Available at:
        <a href="https://threejs.org/docs/" target="_blank" rel="noopener">
            https://threejs.org/docs/
        </a>
    </li>
    <li>Bootstrap 5 Documentation. Available at:
        <a href="https://getbootstrap.com" target="_blank" rel="noopener">
            https://getbootstrap.com
        </a>
    </li>
    <li>Creating CSS carousels. Available at:
        <a href="https://developer.mozilla.org/en-US/docs/Web/CSS/Guides/Overflow/Carousels"
           target="_blank" rel="noopener">
            developer.mozilla.org/en-US/docs/Web/CSS/Guides/Overflow/Carousels
        </a>
    </li>
    <li>Vinyl cover art (Unsplash). Available at:
        <a href="https://unsplash.com/s/photos/jazz" target="_blank" rel="noopener">
            https://unsplash.com/s/photos/jazz
        </a>
    </li>
    <li>Vinyl audio sample. Available at:
        <a href="https://www.youtube.com/watch?v=-omxLHMXjVo" target="_blank" rel="noopener">
            youtube.com/watch?v=-omxLHMXjVo
        </a>
    </li>
    <li>Microphone audio sample. Available at:
        <a href="https://www.youtube.com/watch?v=utqJPBSDjYI" target="_blank" rel="noopener">
            youtube.com/watch?v=utqJPBSDjYI
        </a>
    </li>
    <li>Speaker audio sample. Available at:
        <a href="https://www.youtube.com/watch?v=VZJhkt2rVVM" target="_blank" rel="noopener">
            youtube.com/watch?v=VZJhkt2rVVM
        </a>
    </li>
    <li>Blender Foundation Manual. Available at:
        <a href="https://docs.blender.org" target="_blank" rel="noopener">
            https://docs.blender.org
        </a>
    </li>
    <li>SQLite Consortium (2026) SQLite Documentation. Available at:
        <a href="https://sqlite.org/docs.html" target="_blank" rel="noopener">
            https://sqlite.org/docs.html
        </a>
    </li>
</ul>

<hr>


<p class="text-center text-muted small mt-4 mb-3">
    JB Productions &middot; 3D Audio Gallery
</p>

<hr>
