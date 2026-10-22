# JB Productions: publication change record

Updated 22 October 2026. Baseline: the supplied original project's working files,
not the earlier public repository. All work is in a separate publication copy.
The original archive and review extraction are untouched. This record supersedes
the earlier cleanup report.

## Restored in response to your feedback

- All three GLBs and all three Blender source files are restored byte-for-byte.
  The earlier vinyl artwork replacement and Blender resaves are fully undone.
- Original product thumbnails, speaker texture, nine modelling screenshots and
  catalogue JSON are restored byte-for-byte, including the original descriptions.
- Removed the cleanup-generated `textures/vinyl_label.png` and `.svg`. These were
  replacement assets, not original files. The original artwork is present again.
- All three original MP3s remain byte-for-byte unchanged. No audio replacement,
  trimming, transcoding or generation was performed.
- Restored the original long About page: modelling explanations, features, test
  table, references, images and layout, rather than the shortened rewritten page.
- Removed Statement of Originality, Use of AI During Development, academic
  identifiers and institution-specific deployment wording, as requested.
- Both footers use the JB Productions / 3D Audio Gallery project identity.
- Original carousel, branding, dark palette, viewer, model materials, animations,
  control arrangement, camera presets and light values are retained. No redesign,
  remodelling, material replacement or dependency upgrade remains.

The original vinyl Blender file still references missing external photographs.
Its GLB embeds the artwork and renders without those loose images. Editing the
source materials may require locating the original photographs. This limitation
is documented instead of silently substituting artwork. Original packed texture
references in the speaker Blender file are also retained without resaving.

## Necessary application fixes retained

### `index.php`

- Replaced dynamic method routing with two explicit read actions: `apiGetProducts`
  and `apiGetProduct`. Public database setup is no longer accessible.
- Unknown/array-valued actions return JSON 404 without reflecting submitted text.
  Non-GET API requests return 405 and `Allow: GET`.
- JSON responses declare charset and `X-Content-Type-Options: nosniff`.
- Unexpected API exceptions return generic 500 errors; details are logged rather
  than exposing database paths/exception messages. Normal HTML serving is unchanged.

### `application/controller/controller.php`

- Removed public `apiSetup()`.
- Product IDs must be canonical positive integers. Missing, array, fractional,
  mixed-text, negative, zero and leading-zero IDs return 400.
- Valid but absent IDs return 404 instead of a successful response with an error
  object. The original prepared database lookup remains.

### `application/model/model.php`

- Empty databases initialise themselves; normal visits no longer replace rows.
  The original database path and seven-column schema remain.
- Seeding uses a transaction and `INSERT`, not `INSERT OR REPLACE`. Failures roll
  back. Invalid/missing JSON raises an exception instead of reporting success.
- The front controller, not the PDO constructor, owns HTTP error responses.

### `js/getJsonData.js`

- Removed the browser's setup/reset request on every visit.
- API/static-fallback data use one normalisation/update path. Selections made before
  metadata arrives now receive the correct description from either data source.
- Invalid/empty fallback arrays are rejected; displayed product strings use `.text()`.
- Removed dead `renderGallery()` markup for nonexistent `#gallery-row`. The actual
  original home carousel remains.

### `js/main.js`

- Resize updates the renderer, camera and bloom composer. Hidden zero-sized
  containers are ignored; navigation can resize after the viewer becomes visible.
- Each load owns its overlay. Success/progress/error callbacks check the active
  request ID before changing UI. Stale loads cannot remove a newer loader, replace
  its model or display an old failure; stale assets are disposed.
- Zero/unknown progress totals and zero model dimensions no longer produce invalid
  percentages/scales. Load errors explain how to retry.
- Deduplicated geometry/material/texture disposal; old animation mixer bindings are
  released. Shared materials toggle wireframe once rather than cancelling themselves.
- Model changes stop old/pending sound even before loading completes and reset
  controls. Removed the deferred 50 ms load call that left a stale-state gap.
- Camera/light controls safely no-op before initialisation. Intensity must be finite
  and stays within the original slider range and original light scaling.
- Audio playback is awaited; failures reset false Sound ON state and show a retry
  message. Late play promises/ended events verify the current Audio instance.
- Paused/replaced pending samples cannot restart animation after a late resolution.
  Sound stops only animation that it started, not independently started animation.
- Removed timestamp cache-busting: each model session already selects a new Audio
  object. Original MP3 mappings and volume 0.3 remain unchanged.
- Shared sound-stop handling runs on model switches and when leaving the viewer.
  No beat detection, audio-reactive geometry or new audio feature was added.

### `js/ui.js`

- Restricts navigation to existing sections; clears queued fades and resizes the
  viewer after fade-in. Leaving the viewer stops sound.
- About is marked loaded only after success. A loading flag prevents duplicate
  requests; failure allows retry. Removed an obsolete hosting-specific error hint.

### `js/carousel.js` and `index.html`

- Enter/Space activate focused slides. Only the active slide is in the tab order;
  inactive slides expose `aria-hidden` and dots expose `aria-current`.
- Invalid indices are ignored. Existing arrows, dots, swipes and no-autoplay
  behaviour remain. Added navbar labels and focusable labelled slide roles.
- Added an empty live audio-status element for playback failures; replaced the
  academic footer. Existing controls, sections, imports and CDN versions remain.

### `css/style.css`

- Footer flows after content rather than covering controls on narrow screens.
- Fixed unreadable dark card labels/subtitles and added a visible slide focus outline.
  No overall palette or layout redesign.

### `application/view/viewAbout.php`

Beyond the requested removals, corrected descriptions made stale by necessary
fixes: two read-only routes instead of public setup; seeding on first catalogue
request without resetting rows; HTTP JSON fallback instead of claiming the full
app works by double-clicking HTML. The setup test row describes automatic
initialisation. Refreshed screenshot captions do not claim a new OperaGX test or
present a test summary as DevTools. Original test-table entries remain historical
project documentation, not a claim every item was rerun during cleanup.

## Screenshot changes to remove academic identifiers

All nine original modelling screenshots remain unchanged. Two safe historical
diagnostic crops also remain unchanged: `test-ajax-network.jpg` and
`test-api-direct.jpg`.

Eight `images/about/` images contained the old academic footer and are refreshed:

- `test-desktop-home.jpg`, `test-desktop-viewer.jpg`: restored app in Chrome.
- `test-mobile-home.jpg`, `test-mobile-viewer.jpg`: 390-pixel Chrome viewport.
- `test-tablet.jpg`: 1024-pixel Chrome viewport.
- `test-cross-browser.jpg`: current Chrome capture with a generic browser-verification
  caption, not presented as a new OperaGX test.
- `test-ajax-response.jpg`: actual current HTTP 200 catalogue JSON displayed as
  browser-rendered diagnostic output, not a simulated DevTools interface.
- `test-console-clean.jpg`: browser-rendered summary of the interactions actually
  checked and zero uncaught page errors, explicitly labelled as a test summary.

The three `docs/*.png` screenshots are also refreshed with the original artwork.
No modelling image, product thumbnail or embedded model texture was retouched.

## Documentation, checks and packaging

- `README.md`: overview, features, stack versions, PHP/PDO SQLite setup, architecture,
  file map, database lifecycle, two API routes/status codes, tests and media caveats.
- `docs/PROJECT-REFRESHER.md`: behaviour, stack responsibilities, model details,
  request flow, interview refresher, limitations and optional future work.
- `docs/MEDIA-CREDITS.md`: original audio URLs, artwork provenance gaps and library
  credits. No blanket licence was chosen or permission invented.
- `docs/CHANGES.md`: this record, including the reverted excessive changes.
- `.gitignore`: generated databases/sidecars, caches, local configuration, logs,
  editor files and Blender backups.
- `tests/viewer-regression.cjs`: seven standalone Node assertion groups; no new
  testing dependency. Covers pre-init controls, resizing, stale loading/audio,
  shared resources/wireframe, audio failures, About retries and JSON fallback.
- `tests/check_api.py`: Python standard-library HTTP tests in an isolated temporary
  project, not the live database. Covers fresh seeding, routing/method/ID errors,
  row preservation and generic seed-error responses/recovery. SQLite connections
  are explicitly closed for Windows temporary-directory cleanup.
- Excluded embedded `.git`, generated `db/products.db` and the academic
  `submission.html` wrapper from the ZIP. The running preview may create its own
  ignored database; runtime databases are excluded when packaging.
- Reset only the publication preview's generated catalogue database so its rows
  reflect the restored original JSON. The original archive's database is untouched.
- No commit, push, deployment or remote-history rewrite was performed.

## Verification and remaining limitations

Checks target the restored copy: PHP/JavaScript syntax, seven Node regression
groups, isolated PHP HTTP tests, real Chrome rendering and visual screenshot
review. Browser checks exercise all three original models, camera/wireframe/light
controls, animation/audio play/pause, rapid model selection, About, keyboard
activation and responsive layouts. No uncaught page errors were recorded.
Desktop 1366-pixel, mobile 390-pixel and tablet 1024-pixel captures were refreshed;
an additional 320-pixel width check found no horizontal document overflow.

Byte comparisons verify all original GLBs, Blender sources, MP3s, thumbnails,
texture, modelling screenshots and catalogue JSON. Local resource references,
requested text removals, original archive integrity and packaged ZIP bytes are
checked. These are scoped tests, not exhaustive device/browser certification,
a formal security audit or media licence clearance.

Original artwork and recordings remain as requested, but the supplied credits
do not establish their redistribution permissions. Confirm those before public
redistribution. Attribution alone is not permission evidence. Use maintained PHP
hosting, HTTPS and production settings for a public demo; the local development
server is not production hosting. Original CDN dependence, large models, hardcoded
product mappings and an always-running render loop remain optional future work.
No framework, build system, application dependency or speculative feature was added.
