function switchTab(tab) {
    // Reset todos os botões
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.setAttribute('aria-selected', 'false');
        b.classList.remove('bg-[#1a1e28]', 'text-[#e8c97e]', 'shadow-[0_2px_8px_rgba(0,0,0,.35)]');
        b.classList.add('bg-transparent', 'text-[#4a5068]');
    });

    // Reset todos os painéis
    document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));

    // Ativa o botão
    const btn = document.getElementById('tab-' + tab);
    btn.setAttribute('aria-selected', 'true');
    btn.classList.add('bg-[#1a1e28]', 'text-[#e8c97e]', 'shadow-[0_2px_8px_rgba(0,0,0,.35)]');
    btn.classList.remove('bg-transparent', 'text-[#4a5068]');

    // Ativa o painel
    document.getElementById('panel-' + tab).classList.add('active');
    clearAlert();
}

function togglePass(id, btn) {
    const inp = document.getElementById(id);
    if (inp.type === 'password') {
        inp.type = 'text';
        btn.textContent = '🙈';
    } else {
        inp.type = 'password';
        btn.textContent = '👁';
    }
}

/* ════════════════════════════════════════════════
   Strength meter
════════════════════════════════════════════════ */
const rules = {
    len: v => v.length >= 8,
    upper: v => /[A-Z]/.test(v),
    num: v => /[0-9]/.test(v),
    special: v => /[^A-Za-z0-9]/.test(v),
};

function updateStrength(val) {
    const keys = Object.keys(rules);
    const score = keys.filter(k => rules[k](val)).length;

    // Atualiza critérios visuais
    keys.forEach(k => {
        const el = document.getElementById('p-' + k);
        if (el) el.classList.toggle('ok', rules[k](val));
    });

    // Atualiza segmentos da barra
    for (let i = 1; i <= 4; i++) {
        const seg = document.getElementById('seg' + i);
        seg.classList.remove('seg-active-1', 'seg-active-2', 'seg-active-3', 'seg-active-4');
        if (i <= score) seg.classList.add('seg-active-' + score);
    }
}

/* ════════════════════════════════════════════════
   Alert helpers
════════════════════════════════════════════════ */
function showAlert(msg, type = 'error') {
    const el = document.getElementById('global-alert');
    el.textContent = msg;

    // Reseta e aplica classes Tailwind dinamicamente
    el.className = 'rounded-[10px] px-4 py-3 text-sm mb-5 block ';
    if (type === 'error') {
        el.className += 'bg-[rgba(244,127,127,.1)] border border-[rgba(244,127,127,.25)] text-[#f47f7f]';
    } else {
        el.className += 'bg-[rgba(111,208,164,.1)] border border-[rgba(111,208,164,.25)] text-[#6fd0a4]';
    }
}

function clearAlert() {
    const el = document.getElementById('global-alert');
    el.className = 'hidden';
    el.textContent = '';
}

/* ════════════════════════════════════════════════
   Field errors
════════════════════════════════════════════════ */
function showFieldErrors(errors) {
    // Limpa erros anteriores
    document.querySelectorAll('.field-error').forEach(e => {
        e.textContent = '';
        e.classList.add('hidden');
    });
    document.querySelectorAll('.input-field').forEach(i => i.classList.remove('is-error'));

    // Mapa campo (backend) → id do elemento de erro (frontend)
    const idMap = {
        'email': 'err-login-email',
        'password': 'err-login-password',
        'name': 'err-reg-name',
        'password_confirmation': 'err-reg-confirm',
    };

    Object.entries(errors).forEach(([field, msgs]) => {
        const errId = idMap[field] ?? ('err-reg-' + field.replace('_confirmation', 'confirm'));
        const errEl = document.getElementById(errId);
        if (errEl) {
            errEl.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
            errEl.classList.remove('hidden');
        }
    });
}

/* ════════════════════════════════════════════════
   Loading state
════════════════════════════════════════════════ */
function setLoading(btnId, loading) {
    const btn = document.getElementById(btnId);
    const label = btnId === 'btn-login' ? 'Entrar' : 'Criar conta';
    btn.disabled = loading;
    btn.innerHTML = loading ? '<span class="spinner"></span> Aguarde…' : label;
}

/* ════════════════════════════════════════════════
   AJAX helper
════════════════════════════════════════════════ */
async function postJson(url, data) {
    const res = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
        },
        body: JSON.stringify(data),
    });
    return {
        status: res.status,
        data: await res.json()
    };
}

/* ════════════════════════════════════════════════
   Login form
════════════════════════════════════════════════ */
document.getElementById('form-login').addEventListener('submit', async e => {
    e.preventDefault();
    clearAlert();

    const email = document.getElementById('login-email').value.trim();
    const password = document.getElementById('login-password').value;

    setLoading('btn-login', true);
    try {
        const {
            status,
            data
        } = await postJson('/auth/login', {
            email,
            password
        });
        if (status === 200 && data.redirect) {
            window.location.href = data.redirect;
        } else if (status === 422) {
            showFieldErrors(data.errors ?? {});
            if (data.message) showAlert(data.message);
        } else {
            showAlert(data.message ?? 'Erro ao fazer login. Tente novamente.');
        }
    } catch {
        showAlert('Erro de conexão. Verifique sua internet.');
    } finally {
        setLoading('btn-login', false);
    }
});

/* ════════════════════════════════════════════════
   Register form
════════════════════════════════════════════════ */
document.getElementById('form-register').addEventListener('submit', async e => {
    e.preventDefault();
    clearAlert();

    const name = document.getElementById('reg-name').value.trim();
    const email = document.getElementById('reg-email').value.trim();
    const password = document.getElementById('reg-password').value;
    const password_confirmation = document.getElementById('reg-confirm').value;

    setLoading('btn-register', true);
    try {
        const {
            status,
            data
        } = await postJson('/auth/register', {
            name,
            email,
            password,
            password_confirmation
        });
        if (status === 201 && data.redirect) {
            showAlert('Conta criada! Redirecionando…', 'success');
            setTimeout(() => window.location.href = data.redirect, 900);
        } else if (status === 422) {
            showFieldErrors(data.errors ?? {});
            if (data.message) showAlert(data.message);
        } else {
            showAlert(data.message ?? 'Erro ao criar conta. Tente novamente.');
        }
    } catch {
        showAlert('Erro de conexão. Verifique sua internet.');
    } finally {
        setLoading('btn-register', false);
    }
});

/* ════════════════════════════════════════════════
   Init — suporte a ?tab=register na URL
════════════════════════════════════════════════ */
const urlTab = new URLSearchParams(location.search).get('tab');
if (urlTab === 'register') switchTab('register');


window.switchTab = switchTab;
window.togglePass = togglePass;
window.updateStrength = updateStrength;