import * as THREE from 'three';
import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
import { EffectComposer } from 'three/addons/postprocessing/EffectComposer.js';
import { RenderPass } from 'three/addons/postprocessing/RenderPass.js';
import { UnrealBloomPass } from 'three/addons/postprocessing/UnrealBloomPass.js';

let scene, camera, renderer, controls, composer;
let currentModel = null;
let mixer = null;
let clock = new THREE.Clock();
let autoRotate = false;
let animationPlaying = false;
let animationStartedByAudio = false;
let spotLight, spotLightOn = false, spotLightIntensity = 3;
let loadRequestId = 0;
let audio = null;
let audioPlaying = false;
let currentProductId = null;

const audioMap = {
    1: 'audio/vinyl-demo.mp3',
    2: 'audio/speaker-demo.mp3',
    3: 'audio/microphone-demo.mp3',
};

function init() {
    const container = document.getElementById('threejs-container');
    if (!container) return;

    const width  = container.clientWidth;
    const height = container.clientHeight || 500;

    scene = new THREE.Scene();

    camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
    camera.position.set(0, 1.5, 4);

    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setSize(width, height);
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.shadowMap.enabled = true;
    container.appendChild(renderer.domElement);

    composer = new EffectComposer(renderer);
    composer.addPass(new RenderPass(scene, camera));
    composer.addPass(new UnrealBloomPass(
        new THREE.Vector2(width, height),
        0.4,    // strength
        0.5,    // radius
        0.85    // threshold
    ));
    window.composer = composer;

    scene.add(new THREE.AmbientLight(0xffffff, 0.5));

    const dirLight = new THREE.DirectionalLight(0xffffff, 1.2);
    dirLight.position.set(5, 10, 7.5);
    dirLight.castShadow = true;
    scene.add(dirLight);

    spotLight = new THREE.SpotLight(0xfff4e0, 0);
    spotLight.position.set(0, 5, 3);
    spotLight.angle = Math.PI / 6;
    spotLight.penumbra = 0.3;
    spotLight.decay = 1;
    spotLight.distance = 25;
    spotLight.castShadow = true;
    spotLight.target.position.set(0, 0, 0);
    scene.add(spotLight);
    scene.add(spotLight.target);

    controls = new OrbitControls(camera, renderer.domElement);
    controls.enableDamping = true;
    controls.target.set(0, 0, 0);
    controls.update();

    window.addEventListener('resize', onResize);

    animate();
}

function onResize() {
    const container = document.getElementById('threejs-container');
    if (!container || !renderer) return;
    if (!container.clientWidth || !container.clientHeight) return;
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
    composer.setSize(container.clientWidth, container.clientHeight);
}
window.resizeViewer = onResize;

function animate() {
    requestAnimationFrame(animate);
    const delta = clock.getDelta();

    if (mixer) mixer.update(delta);
    if (autoRotate && currentModel) currentModel.rotation.y += 0.008;

    controls.update();
    composer.render();
}

function disposeModel(model) {
    const resources = new Set();
    model.traverse((child) => {
        if (child.isMesh) {
            resources.add(child.geometry);
            (Array.isArray(child.material) ? child.material : [child.material])
                .forEach(m => {
                    resources.add(m);
                    Object.values(m).forEach(value => {
                        if (value?.isTexture) resources.add(value);
                    });
                });
        }
    });
    resources.forEach(resource => resource.dispose());
}

function resetSoundButton() {
    const btn = document.querySelector("button[onclick='playSound()']");
    if (btn) {
        btn.textContent = 'Play Sound';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-secondary');
    }
}

function resetAnimationButton() {
    const btn = document.querySelector("button[onclick='playAnimation()']");
    if (btn) {
        btn.textContent = 'Play Animation';
        btn.classList.remove('btn-success');
        btn.classList.add('btn-secondary');
    }
}

function loadModel(id) {
    const modelMap = {
        1: 'models/product1.glb',
        2: 'models/product2.glb',
        3: 'models/product3.glb'
    };

    const path = modelMap[id];
    if (!path) return;

    currentProductId = id;
    if (!renderer) init();

    scene.background = new THREE.Color(0x3D3D3D);

    window.stopProductAudio();
    if (mixer) {
        mixer.stopAllAction();
        mixer.uncacheRoot(currentModel);
    }
    if (currentModel) {
        scene.remove(currentModel);
        disposeModel(currentModel);
        currentModel = null;
    }
    mixer = null;
    animationPlaying = false;
    resetAnimationButton();

    const container = document.getElementById('threejs-container');
    document.getElementById('model-loader')?.remove();
    const loader_div = document.createElement('div');
    loader_div.id = 'model-loader';
    loader_div.setAttribute('role', 'status');
    loader_div.textContent = 'Loading model...';
    loader_div.style.cssText = `
        position:absolute; top:50%; left:50%;
        transform:translate(-50%,-50%);
        color:white; font-size:1rem; z-index:10;
    `;
    container.style.position = 'relative';
    container.appendChild(loader_div);

    // user can spam-click model buttons before the previous load finishes.
    // ignore any callback that isn't from the latest request.
    const thisLoadId = ++loadRequestId;

    const loader = new GLTFLoader();
    loader.load(
        path,

        (gltf) => {
            if (thisLoadId !== loadRequestId) {
                disposeModel(gltf.scene);
                return;
            }
            loader_div.remove();

            currentModel = gltf.scene;

            // normalise scale so any model fits the viewer regardless of blender export size
            const box = new THREE.Box3().setFromObject(currentModel);
            const centre = box.getCenter(new THREE.Vector3());
            const size   = box.getSize(new THREE.Vector3());
            const maxDim = Math.max(size.x, size.y, size.z);
            const scale  = maxDim > 0 ? 2 / maxDim : 1;
            currentModel.scale.setScalar(scale);
            currentModel.position.sub(centre.multiplyScalar(scale));

            scene.add(currentModel);

            if (gltf.animations && gltf.animations.length > 0) {
                mixer = new THREE.AnimationMixer(currentModel);
                currentModel.userData.animations = gltf.animations;
            }

            if (window.populateProductInfo) {
                window.populateProductInfo(id);
            }
        },

        (xhr) => {
            if (thisLoadId !== loadRequestId) return;
            loader_div.textContent = xhr.total > 0
                ? `Loading... ${Math.round((xhr.loaded / xhr.total) * 100)}%`
                : 'Loading model...';
        },

        (error) => {
            if (thisLoadId !== loadRequestId) return;
            console.error('GLTFLoader error:', error);
            loader_div.textContent = 'Could not load model. Select it again to retry.';
        }
    );
}

window.loadProduct = function(id) {
    if (window.showSection) window.showSection('products');
    if (window.populateProductInfo) window.populateProductInfo(id);
    loadModel(id);
};

window.toggleWireframe = function() {
    if (!currentModel) return;
    const materials = new Set();
    currentModel.traverse((child) => {
        if (child.isMesh) {
            const mats = Array.isArray(child.material) ? child.material : [child.material];
            mats.forEach(m => materials.add(m));
        }
    });
    materials.forEach(m => { m.wireframe = !m.wireframe; });
};

window.toggleRotate = function() {
    autoRotate = !autoRotate;
};

window.playAnimation = function() {
    if (!mixer || !currentModel?.userData?.animations?.length) return;

    animationStartedByAudio = false;
    animationPlaying = !animationPlaying;

    currentModel.userData.animations.forEach(clip => {
        const action = mixer.clipAction(clip);
        if (animationPlaying) {
            action.reset();
            action.setLoop(THREE.LoopRepeat);
            action.clampWhenFinished = false;
            action.play();
        } else {
            action.stop();
        }
    });

    const btn = document.querySelector("button[onclick='playAnimation()']");
    if (btn) {
        btn.textContent = animationPlaying ? 'Animation ON' : 'Play Animation';
        btn.classList.toggle('btn-secondary', !animationPlaying);
        btn.classList.toggle('btn-success', animationPlaying);
    }
};

window.setCamera = function(preset) {
    if (!camera || !controls) return;
    const positions = {
        front: [0, 1.5, 4],
        top:   [0, 5, 0.5],
        hero:  [1, -2, 2]
    };
    const pos = positions[preset] || positions.front;
    camera.position.set(...pos);
    controls.target.set(0, 0, 0);
    controls.update();
};

window.toggleLight = function(type) {
    if (type !== 'spot light' || !spotLight) return;
    spotLightOn = !spotLightOn;
    spotLight.intensity = spotLightOn ? spotLightIntensity : 0;

    const btn = document.querySelector("button[onclick=\"toggleLight('spot light')\"]");
    if (btn) {
        btn.textContent = spotLightOn ? 'Spot Light ON' : 'Spot Light';
        btn.classList.toggle('btn-warning', !spotLightOn);
        btn.classList.toggle('btn-success', spotLightOn);
    }
};

window.setLightIntensity = function(value) {
    // slider runs 0-2, scale to a useful spotlight intensity range
    const intensity = Number(value);
    if (!Number.isFinite(intensity)) return;
    spotLightIntensity = Math.max(0, Math.min(2, intensity)) * 3;
    if (spotLightOn) spotLight.intensity = spotLightIntensity;
};

function stopAnimationFromAudio() {
    if (!animationStartedByAudio) return;
    animationStartedByAudio = false;
    if (mixer && currentModel?.userData?.animations?.length) {
        animationPlaying = false;
        currentModel.userData.animations.forEach(clip => mixer.clipAction(clip).stop());
        resetAnimationButton();
    }
}

window.stopProductAudio = function() {
    if (audio) audio.pause();
    audio = null;
    audioPlaying = false;
    resetSoundButton();
    stopAnimationFromAudio();
    animationStartedByAudio = false;
};

window.playSound = async function() {
    if (!currentModel || !audioMap[currentProductId]) return;
    if (!audio) {
        audio = new Audio(audioMap[currentProductId]);
        audio.volume = 0.3;
        const clip = audio;
        audio.addEventListener('ended', () => {
            if (audio !== clip) return;
            audioPlaying = false;
            resetSoundButton();
            stopAnimationFromAudio();
        });
    }

    audioPlaying = !audioPlaying;

    if (audioPlaying) {
        const clip = audio;
        try {
            await clip.play();
        } catch (error) {
            if (audio !== clip) return;
            window.stopProductAudio();
            document.getElementById('audio-status').textContent = 'Audio could not play. Please try again.';
            return;
        }
        if (audio !== clip || !audioPlaying) {
            clip.pause();
            return;
        }
        document.getElementById('audio-status').textContent = '';

        // Start the existing clip alongside audio; this is not beat analysis.
        if (mixer && currentModel?.userData?.animations?.length && !animationPlaying) {
            animationPlaying = true;
            animationStartedByAudio = true;
            currentModel.userData.animations.forEach(clip => {
                const action = mixer.clipAction(clip);
                action.reset();
                action.setLoop(THREE.LoopRepeat);
                action.clampWhenFinished = false;
                action.play();
            });
            const animBtn = document.querySelector("button[onclick='playAnimation()']");
            if (animBtn) {
                animBtn.textContent = 'Animation ON';
                animBtn.classList.remove('btn-secondary');
                animBtn.classList.add('btn-success');
            }
        }
    } else {
        audio.pause();
        stopAnimationFromAudio();
    }

    const btn = document.querySelector("button[onclick='playSound()']");
    if (btn) {
        btn.textContent = audioPlaying ? 'Sound ON' : 'Play Sound';
        btn.classList.toggle('btn-secondary', !audioPlaying);
        btn.classList.toggle('btn-success', audioPlaying);
    }
};
