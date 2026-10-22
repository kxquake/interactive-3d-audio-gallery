// Dependency-free checks for the viewer's asynchronous state and UI fixes.
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const root = path.join(__dirname, '..');
const read = file => fs.readFileSync(path.join(root, file), 'utf8');

function viewer() {
    const overlays = [], requests = [];
    const status = { textContent: '' };
    const container = {
        clientWidth: 800, clientHeight: 600, style: {},
        appendChild(node) { overlays.push(node); },
    };
    const ctx = {
        console, THREE: { Clock: class {}, Color: class {} },
        GLTFLoader: class {
            load(url, success, progress, failure) {
                requests.push({ url, success, progress, failure });
            }
        },
        document: {
            getElementById(id) {
                return id === 'threejs-container' ? container : id === 'audio-status' ? status :
                    overlays.find(node => node.id === id) || null;
            },
            createElement() {
                const node = { style: {}, textContent: '', setAttribute() {} };
                node.remove = () => {
                    const index = overlays.indexOf(node);
                    if (index !== -1) overlays.splice(index, 1);
                };
                return node;
            },
            querySelector() { return null; },
        },
    };
    ctx.window = ctx;
    vm.createContext(ctx);
    vm.runInContext(read('js/main.js').replace(/^import .*;\r?$/gm, '') + `
        globalThis.check = {
            loadModel, onResize, disposeModel,
            prepare() {
                scene = {};
                renderer = { setSize() {} };
                camera = { updateProjectionMatrix() {} };
                composer = { setSize() { globalThis.composerResizes++; } };
            },
            pendingAudio(value) { audio = value; currentModel = null; },
            model(value) { currentModel = value; currentProductId = 1; },
            audioState() { return audioPlaying; },
        };
    `, ctx);
    return { ctx, overlays, requests, container, status };
}

async function main() {
    {
        const { ctx } = viewer();
        assert.doesNotThrow(() => ctx.setCamera('front'));
        assert.doesNotThrow(() => ctx.toggleLight('spot light'));
        ctx.composerResizes = 0;
        ctx.check.prepare();
        ctx.check.onResize();
        assert.equal(ctx.composerResizes, 1);
    }
    {
        const { ctx, container } = viewer();
        ctx.composerResizes = 0;
        ctx.check.prepare();
        container.clientHeight = 0;
        ctx.check.onResize();
        assert.equal(ctx.composerResizes, 0);
    }
    {
        const { ctx, overlays, requests } = viewer();
        ctx.check.prepare();
        let pauses = 0;
        ctx.check.pendingAudio({ pause() { pauses++; } });
        ctx.check.loadModel(1);
        ctx.check.loadModel(2);
        assert.equal(pauses, 1);
        assert.equal(overlays.length, 1);
        const current = overlays[0];
        requests[0].failure(new Error('deliberate stale failure'));
        assert.equal(current.textContent, 'Loading model...');
        let disposed = 0;
        requests[0].success({ scene: { traverse() { disposed++; } } });
        assert.equal(disposed, 1);
        assert.equal(overlays[0], current);
        requests[1].progress({ loaded: 10, total: 0 });
        assert.equal(current.textContent, 'Loading model...');
    }
    {
        const { ctx } = viewer();
        let disposed = 0;
        const texture = { isTexture: true, dispose() { disposed++; } };
        const material = { map: texture, dispose() { disposed++; }, wireframe: false };
        const geometry = { dispose() { disposed++; } };
        const model = { traverse(fn) {
            fn({ isMesh: true, material, geometry });
            fn({ isMesh: true, material, geometry });
        } };
        ctx.check.model(model);
        ctx.toggleWireframe();
        assert.equal(material.wireframe, true);
        ctx.check.disposeModel(model);
        assert.equal(disposed, 3);
    }
    {
        const { ctx, status } = viewer();
        ctx.check.model({ userData: {} });
        ctx.Audio = class {
            addEventListener() {}
            pause() {}
            play() { return Promise.reject(new Error('deliberate media failure')); }
        };
        await ctx.playSound();
        assert.equal(ctx.check.audioState(), false);
        assert.match(status.textContent, /could not play/);
    }
    {
        let attempts = 0;
        const ctx = {
            console, scrollTo() {},
            $() { return { stop() { return this; }, hide() {}, fadeIn() {}, html() {} }; },
        };
        ctx.window = ctx;
        ctx.$.get = () => {
            attempts++;
            return { done() { return this; }, fail(fn) { fn(); return this; } };
        };
        vm.createContext(ctx);
        vm.runInContext(read('js/ui.js'), ctx);
        ctx.showSection('about');
        ctx.showSection('about');
        assert.equal(attempts, 2);
    }
    {
        const texts = {};
        const ctx = { console, document: {},
            $(selector) { return { ready() {}, text(value) { texts[selector] = value; } }; },
        };
        ctx.window = ctx;
        ctx.$.getJSON = () => ({
            done(fn) { fn({ products: [{ id: 3, title: 'Microphone' }] }); return this; },
            fail() { return this; },
        });
        vm.createContext(ctx);
        vm.runInContext(read('js/getJsonData.js'), ctx);
        ctx.populateProductInfo(3);
        assert.equal(ctx.currentProductId, 3);
        vm.runInContext('fallbackToStaticJson()', ctx);
        assert.equal(texts['#product-title'], 'Microphone');
    }
    console.log('PASS: 7 regression groups (guards, resize, load races/audio cleanup, shared resources, audio failure, About retry, JSON fallback).');
}
main().catch(error => { console.error(error); process.exitCode = 1; });
