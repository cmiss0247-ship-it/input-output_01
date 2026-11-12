
// ===== Cookie 網頁寵物 =====

const cookie = document.getElementById("cookie-pet");
const cookieImage = document.getElementById("cookie-image");
const speech = document.querySelector(".cookie-speech");

// 圖片位置
const images = {
    normal: "./images/cookie/cookie_blink.gif",
    blink: "./images/cookie/cookie_blink.gif",
    tongue: "./images/cookie/cookie_tongue.gif",
    rest: "./images/cookie/cookie_rest.png"
};

// Cookie 的對話
const messages = [
    "汪！今天也要加油喔！",
    "Cookie 想吃餅乾 🍪",
    "今天的月亮好漂亮！",
    "摸摸我嘛～",
    "寫程式累了就休息一下！",
    "汪汪！我在巡邏！",
    "主人，我想睡覺了 💤",
    "今天也要開開心心！"
];

// ===== 基本狀態 =====
let x = 20;
let direction = 1;
let walking = true;
let resting = false;
let restUntil = 0;
let lastTime = 0;
let lastImage = "";
let speechTimer = null;
let expressionTimer = null;

// 每秒移動 48px
const speed = 48;

// ===== 切換圖片 =====
function changeImage(src) {
    if (lastImage !== src) {
        cookieImage.src = src;
        lastImage = src;
    }
}

// 圖片載入失敗時，顯示警告
cookieImage.addEventListener("error", () => {
    console.warn("Cookie 圖片載入失敗：", cookieImage.src);
});

// ===== 說話 =====
function say(message) {
    speech.textContent = message;
    speech.hidden = false;

    clearTimeout(speechTimer);

    speechTimer = setTimeout(() => {
        speech.hidden = true;
    }, 3000);
}

// ===== 恢復目前應有的表情 =====
function restoreImage() {
    if (resting) {
        changeImage(images.rest);
    } else {
        changeImage(images.normal);
    }
}

// ===== 開始休息 =====
function startRest() {
    resting = true;
    walking = false;

    clearTimeout(expressionTimer);

    cookie.classList.remove("walking");
    cookie.classList.add("resting");

    // 休息 3～6 秒
    restUntil = performance.now() + 3000 + Math.random() * 3000;

    changeImage(images.rest);

    if (Math.random() < 0.5) {
        say("呼～休息一下 💤");
    }
}

// ===== 開始散步 =====
function startWalk() {
    resting = false;
    walking = true;

    clearTimeout(expressionTimer);

    cookie.classList.remove("resting");
    cookie.classList.add("walking");

    changeImage(images.normal);
}

// ===== 主要移動程式 =====
function animate(time) {
    if (!lastTime) {
        lastTime = time;
    }

    const delta = Math.min((time - lastTime) / 1000, 0.05);
    lastTime = time;

    const maxX = Math.max(
        0,
        window.innerWidth - cookie.offsetWidth
    );

    if (resting) {
        if (time >= restUntil) {
            startWalk();
        }
    } else if (walking) {
        x += direction * speed * delta;

        // 碰到右邊就往左走
        if (x >= maxX) {
            x = maxX;
            direction = -1;
        }

        // 碰到左邊就往右走
        if (x <= 0) {
            x = 0;
            direction = 1;
        }

        // 隨機休息
        if (Math.random() < 0.0015) {
            startRest();
        }
    }

    // 更新位置
    cookie.style.left = x + "px";

    // 更新面向
    cookie.classList.toggle(
        "facing-left",
        direction === -1
    );

    requestAnimationFrame(animate);
}

// ===== 點擊 Cookie =====
cookie.addEventListener("click", () => {
    const random = Math.floor(Math.random() * messages.length);

    say(messages[random]);

    clearTimeout(expressionTimer);

    // 點擊時吐舌頭
    changeImage(images.tongue);

    expressionTimer = setTimeout(() => {
        restoreImage();
    }, 1600);
});

// ===== 隨機眨眼 =====
// cookie_blink.gif 本身應包含眨眼影格
// 因此不必反覆重新載入同一張 GIF

// ===== Cookie 偶爾自己說話 =====
setInterval(() => {
    if (!resting && Math.random() < 0.45) {
        const random = Math.floor(Math.random() * messages.length);
        say(messages[random]);
    }
}, 12000);

// ===== 視窗大小改變 =====
window.addEventListener("resize", () => {
    x = Math.min(
        x,
        Math.max(0, window.innerWidth - cookie.offsetWidth)
    );
});

// ===== 啟動 Cookie =====
changeImage(images.normal);
startWalk();
requestAnimationFrame(animate);
