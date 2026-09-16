<?php
include_once('./_common.php');

// 운세 데이터 배열 (카테고리별로 정리)
$fortunes = [
    'love' => [
        '오늘은 특별한 인연을 만날 수 있는 날입니다. 💕',
        '마음을 열면 사랑이 찾아옵니다. 용기를 내보세요!',
        '소중한 사람에게 먼저 연락해보는 건 어떨까요?',
        '오늘의 당신은 매력이 넘칩니다. 자신감을 가지세요!',
        '진심은 언젠가 통하게 되어있습니다. 조급해하지 마세요.',
    ],
    'money' => [
        '작은 행운이 당신을 찾아올 예정입니다. 💰',
        '오늘은 저축보다 자기계발에 투자하는 날입니다.',
        '예상치 못한 곳에서 기회가 찾아올 수 있습니다.',
        '현명한 소비가 미래의 부를 만듭니다.',
        '감사하는 마음이 더 많은 풍요를 불러옵니다.',
    ],
    'health' => [
        '오늘은 몸이 가벼워지는 날입니다. 가볍게 산책해보세요! 🏃',
        '충분한 휴식이 내일의 에너지가 됩니다.',
        '물을 많이 마시고 스트레칭을 해보세요.',
        '건강한 식단이 하루를 바꿉니다.',
        '규칙적인 생활이 최고의 보약입니다.',
    ],
    'study' => [
        '오늘 배운 것이 미래의 자산이 됩니다. 📚',
        '작은 노력이 모여 큰 성과를 만듭니다.',
        '새로운 것을 배우기에 완벽한 날입니다.',
        '꾸준함이 재능을 이깁니다. 포기하지 마세요!',
        '오늘의 실수는 내일의 성장입니다.',
    ],
];

// 모든 운세를 하나의 배열로 합치기 (행운의 숫자/색상 추가)
$all_fortunes = [];
$lucky_colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E2'];

foreach ($fortunes as $category => $messages) {
    foreach ($messages as $message) {
        $all_fortunes[] = [
            'category' => $category,
            'message' => $message,
            'luckyNumber' => rand(1, 99),
            'luckyColor' => $lucky_colors[array_rand($lucky_colors)],
        ];
    }
}

require_once(G5_THEME_PATH.'/modern/_head.inc.php');
include_once('./head.sub.php');
?>
<div class="m-shell">
    <?php require G5_THEME_PATH.'/modern/_nav.inc.php'; ?>
    <main class="m-container" style="padding: 32px 20px 48px;">
        <div class="fortune-wrapper">
            <h1 class="fortune-title">🎲 오늘의 운세</h1>
            <p class="fortune-subtitle">포춘 쿠키를 깨서 오늘의 운세를 확인해보세요!</p>

            <div class="fortune-cookie-container">
                <div class="fortune-cookie" id="fortuneCookie">
                    <span class="cookie-icon">🥠</span>
                </div>
            </div>

            <button class="fortune-button" id="drawFortuneBtn">
                운세 뽑기
            </button>

            <div class="fortune-result" id="fortuneResult" style="display: none;">
                <div class="fortune-card">
                    <div class="fortune-category" id="fortuneCategory"></div>
                    <div class="fortune-message" id="fortuneMessage"></div>

                    <div class="lucky-info">
                        <div class="lucky-item">
                            <span class="lucky-label">🍀 행운의 숫자</span>
                            <span class="lucky-value" id="luckyNumber"></span>
                        </div>
                        <div class="lucky-item">
                            <span class="lucky-label">🎨 행운의 색상</span>
                            <span class="lucky-color-box" id="luckyColorBox"></span>
                        </div>
                    </div>

                    <button class="copy-button" id="copyBtn">📋 운세 복사하기</button>
                </div>
            </div>

            <div class="fortune-history" id="fortuneHistory" style="display: none;">
                <h2 class="history-title">📜 최근 운세 기록</h2>
                <div id="historyList" class="history-list"></div>
            </div>
        </div>
    </main>
    <?php require G5_THEME_PATH.'/modern/_footer.inc.php'; ?>
</div>

<style>
/* 배경 별 애니메이션 */
.fortune-wrapper {
    max-width: 600px;
    margin: 0 auto;
    text-align: center;
    position: relative;
}

.fortune-wrapper::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background:
        radial-gradient(2px 2px at 20% 30%, white, transparent),
        radial-gradient(2px 2px at 60% 70%, white, transparent),
        radial-gradient(1px 1px at 50% 50%, white, transparent),
        radial-gradient(1px 1px at 80% 10%, white, transparent),
        radial-gradient(2px 2px at 90% 60%, white, transparent),
        radial-gradient(1px 1px at 33% 90%, white, transparent);
    background-size: 200% 200%;
    animation: stars 20s ease-in-out infinite;
    opacity: 0.3;
    pointer-events: none;
    z-index: -1;
}

@keyframes stars {
    0%, 100% { background-position: 0% 0%; }
    50% { background-position: 100% 100%; }
}

.fortune-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--m-text);
    margin-bottom: 0.5rem;
}

.fortune-subtitle {
    font-size: 1.1rem;
    color: var(--m-text-muted);
    margin-bottom: 3rem;
}

.fortune-cookie-container {
    margin: 3rem 0;
    perspective: 1000px;
}

.fortune-cookie {
    display: inline-block;
    transition: transform 0.3s ease;
}

.fortune-cookie:hover {
    transform: scale(1.1) rotate(5deg);
}

.fortune-cookie.crack {
    animation: crack 0.5s ease-out;
}

@keyframes crack {
    0% { transform: scale(1) rotate(0deg); }
    25% { transform: scale(1.2) rotate(-10deg); }
    50% { transform: scale(0.9) rotate(10deg); }
    75% { transform: scale(1.1) rotate(-5deg); }
    100% { transform: scale(1) rotate(0deg); }
}

.cookie-icon {
    font-size: 8rem;
    display: block;
    cursor: pointer;
}

.fortune-button {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem 3rem;
    font-size: 1.2rem;
    font-weight: 600;
    border-radius: var(--m-radius-lg);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.fortune-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.fortune-button:active {
    transform: translateY(0);
}

.fortune-result {
    margin-top: 3rem;
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fortune-card {
    background: var(--m-surface);
    border-radius: var(--m-radius-lg);
    padding: 2rem;
    box-shadow: var(--m-shadow);
}

.fortune-category {
    font-size: 1rem;
    color: var(--m-text-muted);
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.fortune-message {
    font-size: 1.5rem;
    color: var(--m-text);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

/* 행운의 정보 스타일 */
.lucky-info {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin: 1.5rem 0;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.1);
    border-radius: var(--m-radius-lg);
}

.lucky-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}

.lucky-label {
    font-size: 0.875rem;
    color: var(--m-text-muted);
}

.lucky-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--m-text);
}

.lucky-color-box {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    border: 2px solid var(--m-surface);
    display: inline-block;
}

.copy-button {
    background: var(--m-surface);
    color: var(--m-text);
    border: 2px solid var(--m-text-muted);
    padding: 0.75rem 1.5rem;
    border-radius: var(--m-radius-lg);
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.copy-button:hover {
    background: var(--m-text-muted);
    color: var(--m-surface);
    transform: translateY(-2px);
}

.fortune-history {
    margin-top: 4rem;
    padding-top: 3rem;
    border-top: 1px solid var(--m-text-muted);
}

.history-title {
    font-size: 1.5rem;
    color: var(--m-text);
    margin-bottom: 1.5rem;
}

.history-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.history-item {
    background: var(--m-surface);
    border-radius: var(--m-radius-lg);
    padding: 1rem 1.5rem;
    box-shadow: var(--m-shadow);
    text-align: left;
    transition: transform 0.2s ease;
}

.history-item:hover {
    transform: translateX(5px);
}

.history-date {
    font-size: 0.875rem;
    color: var(--m-text-muted);
    margin-bottom: 0.5rem;
}

.history-category {
    font-size: 0.875rem;
    color: var(--m-text-muted);
    margin-bottom: 0.5rem;
}

.history-message {
    font-size: 1rem;
    color: var(--m-text);
    line-height: 1.5;
}

@media (max-width: 768px) {
    .fortune-title {
        font-size: 2rem;
    }

    .cookie-icon {
        font-size: 6rem;
    }

    .fortune-button {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }

    .fortune-message {
        font-size: 1.25rem;
    }

    .lucky-info {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<script>
// 운세 데이터를 JavaScript로 전달
const fortuneData = <?php echo json_encode($all_fortunes, JSON_UNESCAPED_UNICODE); ?>;

const cookieEl = document.getElementById('fortuneCookie');
const drawBtn = document.getElementById('drawFortuneBtn');
const resultEl = document.getElementById('fortuneResult');
const categoryEl = document.getElementById('fortuneCategory');
const messageEl = document.getElementById('fortuneMessage');
const copyBtn = document.getElementById('copyBtn');

// 카테고리 한글 이름 매핑
const categoryNames = {
    'love': '💕 연애운',
    'money': '💰 재물운',
    'health': '🏃 건강운',
    'study': '📚 학업운'
};

// 오늘 날짜 구하기 (YYYY-MM-DD 형식)
function getTodayDate() {
    const today = new Date();
    return today.toISOString().split('T')[0];
}

// 오늘의 운세 불러오기
function getTodayFortune() {
    const saved = localStorage.getItem('todayFortune');
    if (saved) {
        const data = JSON.parse(saved);
        if (data.date === getTodayDate()) {
            return data.fortune;
        }
    }
    return null;
}

// 오늘의 운세 저장하기
function saveTodayFortune(fortune) {
    const data = {
        date: getTodayDate(),
        fortune: fortune,
        timestamp: new Date().getTime()
    };
    localStorage.setItem('todayFortune', JSON.stringify(data));

    // 히스토리에도 추가
    addToHistory(fortune);
}

// 히스토리에 추가
function addToHistory(fortune) {
    let history = JSON.parse(localStorage.getItem('fortuneHistory') || '[]');

    // 중복 방지 (같은 날짜의 운세는 하나만)
    history = history.filter(item => item.date !== getTodayDate());

    // 새 운세를 맨 앞에 추가
    history.unshift({
        date: getTodayDate(),
        ...fortune
    });

    // 최대 5개까지만 유지
    if (history.length > 5) {
        history = history.slice(0, 5);
    }

    localStorage.setItem('fortuneHistory', JSON.stringify(history));
    displayHistory();
}

// 히스토리 표시
function displayHistory() {
    const history = JSON.parse(localStorage.getItem('fortuneHistory') || '[]');
    const historyEl = document.getElementById('fortuneHistory');

    if (history.length === 0) {
        historyEl.style.display = 'none';
        return;
    }

    historyEl.style.display = 'block';
    const listEl = document.getElementById('historyList');
    listEl.innerHTML = '';

    history.forEach(item => {
        const li = document.createElement('div');
        li.className = 'history-item';
        li.innerHTML = `
            <div class="history-date">${item.date}</div>
            <div class="history-category">${categoryNames[item.category]}</div>
            <div class="history-message">${item.message}</div>
        `;
        listEl.appendChild(li);
    });
}

// 운세 표시 함수
function displayFortune(fortune) {
    categoryEl.textContent = categoryNames[fortune.category];
    messageEl.textContent = fortune.message;

    // 행운의 숫자와 색상 표시
    document.getElementById('luckyNumber').textContent = fortune.luckyNumber;
    const colorBox = document.getElementById('luckyColorBox');
    colorBox.style.backgroundColor = fortune.luckyColor;

    resultEl.style.display = 'block';

    setTimeout(() => {
        resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
}

// 페이지 로드 시 오늘의 운세가 있으면 바로 표시
window.addEventListener('DOMContentLoaded', function() {
    const todayFortune = getTodayFortune();
    if (todayFortune) {
        displayFortune(todayFortune);
        drawBtn.textContent = '다시 보기';
    }

    // 히스토리 표시
    displayHistory();
});

// 운세 뽑기 버튼 클릭
drawBtn.addEventListener('click', function() {
    // 이미 오늘의 운세가 있으면 다시 표시만
    const existingFortune = getTodayFortune();
    if (existingFortune) {
        displayFortune(existingFortune);
        return;
    }

    // 쿠키 깨지는 애니메이션
    cookieEl.classList.add('crack');

    setTimeout(() => {
        cookieEl.classList.remove('crack');

        // 랜덤 운세 선택
        const randomIndex = Math.floor(Math.random() * fortuneData.length);
        const fortune = fortuneData[randomIndex];

        // 오늘의 운세로 저장
        saveTodayFortune(fortune);

        // 결과 표시
        displayFortune(fortune);

        // 버튼 텍스트 변경
        drawBtn.textContent = '다시 보기';
    }, 500);
});

// 운세 복사 버튼 클릭
copyBtn.addEventListener('click', function() {
    const luckyNumber = document.getElementById('luckyNumber').textContent;
    const text = `${categoryEl.textContent}\n${messageEl.textContent}\n\n🍀 행운의 숫자: ${luckyNumber}`;

    navigator.clipboard.writeText(text).then(() => {
        const originalText = copyBtn.textContent;
        copyBtn.textContent = '✅ 복사 완료!';

        setTimeout(() => {
            copyBtn.textContent = originalText;
        }, 2000);
    }).catch(() => {
        alert('복사에 실패했습니다. 다시 시도해주세요.');
    });
});
</script>

<?php include_once('./tail.sub.php'); ?>
