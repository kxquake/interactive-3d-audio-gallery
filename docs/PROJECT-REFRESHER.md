# JB Productions: project refresher

## The short version

JB Productions is a browser-based 3D gallery for a small collection of audio-themed
objects. You modelled a vinyl record, a pair of speakers and a microphone/stand
in Blender, then presented their exported geometry and animations through Three.js.
A PHP/SQLite backend supplies their titles and descriptions. The interface includes
a carousel, a model viewer, audio controls and an About page.

It is a demonstration gallery, not an actual shop or client commission.
The supplied archive is the completed
JB Productions version; the previously inspected public `kxquake/3dapp` repository
contained an earlier, incomplete V Studios version.

## What the application actually does

1. Home shows a manually navigated carousel of three model previews. Arrow buttons,
   keyboard arrows, dot buttons and touch swipes change the active slide.
2. Selecting a product opens a shared Three.js viewer. GLTFLoader fetches its GLB;
   the scene is scaled and centred to fit the camera.
3. OrbitControls supports dragging and zooming. Additional controls toggle wireframe,
   continuous rotation, exported animation, camera presets and a spotlight.
4. Play Sound uses that model's original MP3 sample. When animation is not already
   running, it starts the existing animation alongside the sound. Sound finishing
   or pausing stops animation started by that sound action, not a separately started
   animation. This is coordination, not precise musical synchronisation.
5. PHP serves product data from SQLite. If that API request fails or returns no
   products, the browser tries the static seed JSON for the same metadata.
6. About is fetched on demand into the existing page rather than requiring a full
   navigation/reload.

There are no user accounts, uploads, search, purchases, payments, audio recording,
audio analysis, procedural music generation or password-vault features.

## Stack and responsibilities

| Part | Technology | Responsibility |
| --- | --- | --- |
| Models | Blender 5.1, `.blend` sources | Geometry, UVs/materials, keyframes and shape keys |
| Delivery format | Binary glTF 2.0 (`.glb`) | Bundles model data, textures and animation for the browser |
| Graphics | Three.js 0.160.0 | WebGL renderer, lights, camera, mesh display and animation mixer |
| Interaction | OrbitControls and plain JavaScript | Camera movement, model selection and control state |
| Post-processing | EffectComposer, RenderPass, UnrealBloomPass | Bloom/highlight effects after scene rendering |
| Layout | HTML/CSS, Bootstrap 5.3.2, Font Awesome 6.4.0 | Responsive layout, navbar, cards and icons |
| UI/data | jQuery 3.7.1 | AJAX requests, text updates and section transitions |
| Backend | PHP front controller and catalogue controller | Routes explicit read-only JSON actions |
| Storage | SQLite through PDO | Three catalogue rows, initialised from JSON |

This is a lightweight MVC-style PHP application plus a JavaScript viewer, not a
React application or a full framework-based service. PHP's view partial is mostly
HTML. jQuery remains because the project already uses it; the cleanup did not
introduce an npm build system or replace its UI architecture.

## Request and rendering flow

```text
Browser -> index.php -> index.html + CSS/JS/CDN libraries
        -> apiGetProducts -> Controller -> Model/PDO -> SQLite catalogue
        -> static data.json if the metadata API fails
Selection -> hardcoded model ID map -> GLTFLoader -> Three.js scene
Play Sound -> hardcoded audio ID map -> HTML Audio + existing AnimationMixer
About -> application/view/viewAbout.php -> injected About HTML
```

`application/model/data.json` is the initial catalogue. SQLite uses snake_case
column names, while the JSON uses camelCase. `normaliseKeys()` converts either
response to one frontend shape. Titles/descriptions are inserted with `.text()`,
not interpreted as product-provided HTML.

The existing hardcoded model/audio maps mean adding a fourth product requires
changing the viewer and navigation as well as JSON. The JSON's `gallery` arrays
are retained metadata, not a currently implemented secondary thumbnail gallery.

## The three models

**Vinyl record:** based on cylinder modelling, an inset/extruded centre opening
and a UV-mapped label. The export contains two animation clips. The original GLB,
Blender source and artwork are restored byte-for-byte. The GLB embeds its artwork;
the Blender source still references missing external photographs. Editing those
materials may require locating the original images; no substitute was packed in.

**Speakers:** two bevelled cabinets with driver cones and dust caps. Procedural
Blender materials were baked to textures for the GLB. Shape keys supply cone
movement. The export contains 12 clips. Both the original GLB and Blender source
are restored byte-for-byte, including their original texture data and references.

**Microphone/stand:** assembled from sphere/cylinder-based parts, with a wireframe
modifier for the grille and an adjustment animation. The export contains one clip.
Both its GLB and Blender source are unchanged.

The original About page described particular spin speeds and tilt angles. Those
claims are not required to understand the implementation and have not been
independently measured during this cleanup; do not present them as new test results.

## What this demonstrates to employers

- Turning editable 3D assets into browser-ready exports, including the practical
  difference between Blender shader nodes and baked/exportable materials.
- Using a rendering loop, a camera, lights, animation clips and post-processing.
- Managing asynchronous loads and UI state: a late response must not replace or
  damage the state of a newer selection.
- Separating catalogue data access from its API/controller and presentation.
- Building a responsive interactive interface without requiring a large framework.
- Maintaining reproducibility: include source assets, relative resource paths,
  documented setup and focused regression tests.

Do not describe it as production-scale, an e-commerce platform, an audio engine,
or an advanced REST API. It is a compact graphics/full-stack coursework project.

## A 30-second interview explanation

“For my Web 3D Applications coursework, I built an audio-themed gallery with three
models I created in Blender. I exported them as GLBs and used Three.js for camera
controls, lighting, wireframe and animation playback. A small PHP/PDO backend
serves their descriptions from SQLite. The main integration work was preserving
materials and animation during export, and keeping asynchronous model loading,
audio and UI state consistent when the user changes models.”

Only use the first-person wording after reviewing the code and confirming it
accurately reflects your own contribution and the code you can explain.

## Useful technical answers to prepare

**Why GLB?** One binary file per product contains its required model buffers and
exported images. That reduces broken external-asset paths compared with managing
many loose export files. It does not automatically preserve every Blender material.

**How do animations run?** GLTFLoader provides animation clips. AnimationMixer
creates/actions those clips and advances them using the render loop's elapsed time.
Auto-rotation is a separate, simple transform applied in the render loop.

**What went wrong with rapid switching?** The original had a request ID guard,
but removed a globally selected loader before checking it, and left error callbacks
unguarded. Each request now owns its overlay and checks whether it is still current.
Discarded models and their resources are disposed rather than added to the scene.

**What did the backend cleanup change?** Public `apiSetup` and browser-triggered
reseeding were removed. An empty database initialises itself; existing rows are
not replaced on each visit. Only two read actions are routed, and malformed IDs,
wrong methods and missing products receive appropriate status codes.

**What are the tradeoffs?** External CDN availability, sizeable original models,
simple hardcoded catalogue mappings, and a continuously running render loop. The
demo has no authentication because it serves public catalogue data, not private
user data. There is no claim of offline support or exhaustive browser coverage.

## Publication and future work

The three original recordings remain unchanged at your request. Confirm their
redistribution permissions before making those files public; the credits file
keeps the recorded source links but does not claim licence clearance.

Optional future work is your decision: model optimisation, broader browser testing,
a verified media licence set, deployment, dependency upgrades, pausing rendering
when hidden, more data-driven asset selection, or a more sophisticated audio system.
None was added as a speculative feature in this cleanup.
