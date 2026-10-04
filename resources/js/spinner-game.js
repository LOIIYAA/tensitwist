const gameApp = document.getElementById('game-app');

if (gameApp) {
    const nextQuestionUrl = gameApp.dataset.nextQuestionUrl;
    const submitAnswerUrl = gameApp.dataset.submitAnswerUrl;
    const dashboardUrl = gameApp.dataset.dashboardUrl;
    const resultUrl = gameApp.dataset.resultUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    let spinnerQuestions = [];

    try {
        spinnerQuestions = JSON.parse(gameApp.dataset.spinnerQuestions || '[]');
    } catch (error) {
        spinnerQuestions = [];
    }

    const wheel = document.getElementById('spinner-wheel');
    const spinButton = document.getElementById('spin-button');
    const endGameButton = document.getElementById('end-game-button');
    const questionModal = document.getElementById('question-modal');
    const resultModal = document.getElementById('result-modal');

    const questionText = document.getElementById('question-text');
    const resultIcon = document.getElementById('result-icon');
    const resultTitle = document.getElementById('result-title');
    const resultAnswer = document.getElementById('result-answer');
    const resultExplanation = document.getElementById('result-explanation');

    const answerButtons = document.querySelectorAll('[data-answer]');
    const nextRoundButton = document.getElementById('next-round-button');
    const wheelLabels = document.querySelectorAll('[data-wheel-label]');

    const correctCount = document.getElementById('correct-count');
    const wrongCount = document.getElementById('wrong-count');
    const progressCount = document.getElementById('progress-count');
    const gameStatus = document.getElementById('game-status');

    const sounds = {
        spinner: new Audio(gameApp.dataset.spinnerSound),
        success: new Audio(gameApp.dataset.successSound),
        failure: new Audio(gameApp.dataset.failureSound),
    };

    sounds.spinner.volume = 0.45;
    sounds.success.volume = 0.55;
    sounds.failure.volume = 0.55;

    let currentQuestion = null;
    let isSpinning = false;
    let rotation = 0;
    let spinnerStopTimer = null;
    let feedbackStopTimer = null;

    const stopAudio = (audio) => {
        audio.pause();

        try {
            audio.currentTime = 0;
        } catch (error) {
            // Browser may not have loaded the audio metadata yet.
        }
    };

    const stopAllSounds = () => {
        window.clearTimeout(spinnerStopTimer);
        window.clearTimeout(feedbackStopTimer);

        Object.values(sounds).forEach((audio) => {
            stopAudio(audio);
        });
    };

    const playAudio = (name, maxDuration = 3000) => {
        const audio = sounds[name];

        if (!audio) {
            return;
        }

        stopAudio(audio);

        audio.play().catch(() => {
            // Gameplay continues if the browser blocks audio.
        });

        if (name === 'spinner') {
            window.clearTimeout(spinnerStopTimer);

            spinnerStopTimer = window.setTimeout(() => {
                stopAudio(audio);
            }, maxDuration);

            return;
        }

        window.clearTimeout(feedbackStopTimer);

        feedbackStopTimer = window.setTimeout(() => {
            stopAudio(audio);
        }, maxDuration);
    };

    const showModal = (modal) => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    };

    const hideModal = (modal) => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    };

    const updateProgress = (progress) => {
        correctCount.textContent = progress.correct;
        wrongCount.textContent = progress.wrong;
        progressCount.textContent = `${progress.answered} / ${progress.total}`;
    };

    const setSpinButton = (text, disabled = false) => {
        spinButton.textContent = text;
        spinButton.disabled = disabled;
        spinButton.classList.toggle('opacity-50', disabled);
        spinButton.classList.toggle('cursor-not-allowed', disabled);
    };

    const renderWheelLabels = () => {
        wheelLabels.forEach((element) => {
            const index = Number(element.dataset.wheelLabel);
            const question = spinnerQuestions[index];

            if (!question) {
                element.textContent = '';
                return;
            }

            element.textContent = question.label;

            if (question.answered) {
                element.innerHTML = `${question.label}<br><span class="text-lg">✓</span>`;
                element.classList.add('opacity-45', 'line-through');
            } else {
                element.classList.remove('opacity-45', 'line-through');
            }
        });
    };

    const completeGame = () => {
        wheel.classList.add('opacity-50');
        setSpinButton('Permainan Selesai', true);
        gameStatus.textContent = 'Semua pertanyaan sudah dijawab. Tekan “Selesaikan Permainan” untuk melihat hasil akhir.';
        gameStatus.classList.remove('hidden');
    };

    const fetchQuestion = async (questionId) => {
        const separator = nextQuestionUrl.includes('?') ? '&' : '?';
        const response = await fetch(
            `${nextQuestionUrl}${separator}question_id=${encodeURIComponent(questionId)}`,
            {
                headers: {
                    Accept: 'application/json',
                },
            }
        );

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Pertanyaan tidak dapat diambil.');
        }

        return data;
    };

    const spinWheel = async () => {
        if (isSpinning) {
            return;
        }

        const remainingQuestions = spinnerQuestions.filter((question) => !question.answered);

        if (remainingQuestions.length === 0) {
            completeGame();
            return;
        }

        isSpinning = true;
        setSpinButton('Memutar...', true);
        gameStatus.classList.add('hidden');

        const selectedQuestion = remainingQuestions[
            Math.floor(Math.random() * remainingQuestions.length)
        ];

        const selectedIndex = spinnerQuestions.findIndex(
            (question) => question.id === selectedQuestion.id
        );

        const sectorAngle = 90;
        const targetAngle = selectedIndex * sectorAngle + (sectorAngle / 2);
        const normalizedRotation = ((rotation % 360) + 360) % 360;
        const desiredRotation = (360 - targetAngle) % 360;
        const extraRotation = 1440 + ((desiredRotation - normalizedRotation + 360) % 360);

        rotation += extraRotation;
        wheel.style.transform = `rotate(${rotation}deg)`;
        playAudio('spinner', 3500);

        try {
            const data = await fetchQuestion(selectedQuestion.id);

            if (data.completed) {
                completeGame();
                isSpinning = false;
                return;
            }

            currentQuestion = data.question;

            window.setTimeout(() => {
                stopAudio(sounds.spinner);
                questionText.textContent = currentQuestion.text;
                showModal(questionModal);
                isSpinning = false;
            }, 3600);
        } catch (error) {
            stopAudio(sounds.spinner);
            gameStatus.textContent = error.message || 'Terjadi kesalahan saat mengambil pertanyaan.';
            gameStatus.classList.remove('hidden');
            isSpinning = false;
            setSpinButton('🌀 Putar Spinner');
        }
    };

    const submitAnswer = async (selectedAnswer) => {
        if (!currentQuestion) {
            return;
        }

        answerButtons.forEach((button) => {
            button.disabled = true;
        });

        try {
            const response = await fetch(submitAnswerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    question_id: currentQuestion.id,
                    selected_answer: selectedAnswer,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Gagal menyimpan jawaban.');
            }

            hideModal(questionModal);
            updateProgress(data.progress);

            const answeredQuestion = spinnerQuestions.find(
                (question) => question.id === currentQuestion.id
            );

            if (answeredQuestion) {
                answeredQuestion.answered = true;
            }

            renderWheelLabels();

            if (data.is_correct) {
                resultIcon.textContent = '🎉';
                resultTitle.textContent = 'Benar!';
                resultAnswer.textContent = 'Jawabanmu tepat.';
                playAudio('success', 2800);
            } else {
                resultIcon.textContent = '😕';
                resultTitle.textContent = 'Belum Tepat';
                resultAnswer.textContent = `Jawaban yang benar: ${data.correct_answer.toUpperCase()}.`;
                playAudio('failure', 2800);
            }

            resultExplanation.textContent = data.explanation || 'Lanjutkan ke pertanyaan berikutnya, ya!';
            nextRoundButton.textContent = data.completed
                ? 'Selesaikan Permainan'
                : 'Putar Lagi';
            nextRoundButton.dataset.completed = data.completed ? 'true' : 'false';

            showModal(resultModal);
        } catch (error) {
            gameStatus.textContent = error.message || 'Terjadi kesalahan saat mengirim jawaban.';
            gameStatus.classList.remove('hidden');

            answerButtons.forEach((button) => {
                button.disabled = false;
            });
        }
    };

    spinButton.addEventListener('click', spinWheel);

    answerButtons.forEach((button) => {
        button.addEventListener('click', () => {
            submitAnswer(button.dataset.answer);
        });
    });

    nextRoundButton.addEventListener('click', () => {
        if (nextRoundButton.dataset.completed === 'true') {
            stopAllSounds();
            window.location.href = resultUrl;
            return;
        }

        hideModal(resultModal);
        currentQuestion = null;

        answerButtons.forEach((button) => {
            button.disabled = false;
        });

        setSpinButton('🌀 Putar Spinner');
    });

    endGameButton?.addEventListener('click', () => {
        const shouldEndGame = window.confirm(
            'Akhiri permainan dan kembali ke dashboard? Progres sesi ini tetap tersimpan.'
        );

        if (!shouldEndGame) {
            return;
        }

        stopAllSounds();
        window.location.href = dashboardUrl;
    });

    window.addEventListener('beforeunload', stopAllSounds);

    renderWheelLabels();
}