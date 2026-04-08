@extends('layout.base')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')

@section('content')

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- ══════════════════════════════════════
         Card: Avatar
    ══════════════════════════════════════ --}}
        <div class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6">
            <h2 class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068] mb-5">Foto de Perfil</h2>

            <div class="flex flex-col sm:flex-row items-center gap-6">

                {{-- Preview do avatar --}}
                <div class="relative group shrink-0" id="avatar-wrapper">
                    <div id="avatar-preview" class="w-24 h-24 rounded-full overflow-hidden ring-4 ring-[#272b38]">
                        @if (Auth::user()->avatar)
                            <img id="avatar-img" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"
                                class="w-full h-full object-cover">
                        @else
                            <div id="avatar-placeholder"
                                class="w-full h-full flex items-center justify-center text-3xl font-bold text-[#0d0f14]"
                                style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Overlay de hover --}}
                    <label for="avatar-input"
                        class="absolute inset-0 flex items-center justify-center rounded-full
                              bg-black/0 group-hover:bg-black/50
                              cursor-pointer transition-all duration-200">
                        <span
                            class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity text-center leading-tight px-1">
                            Alterar<br>foto
                        </span>
                    </label>

                    <input type="file" id="avatar-input" name="avatar"
                        accept="image/jpeg,image/png,image/webp,image/gif" class="hidden">
                </div>

                {{-- Info + botões --}}
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-sm text-[#cdd3e2] font-medium mb-1">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-[#4a5068] mb-4">JPG, PNG ou WebP · Máximo 2 MB</p>

                    <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                        <label for="avatar-input"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                                  text-[#0d0f14] cursor-pointer border-0
                                  hover:opacity-90 active:scale-95 transition-all duration-200"
                            style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">
                            ↑ Enviar foto
                        </label>

                        @if (Auth::user()->avatar)
                            <button type="button" id="btn-remove-avatar"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                                       bg-transparent border border-[#272b38] text-[#f47f7f]
                                       hover:border-[#f47f7f]/40 hover:bg-[rgba(244,127,127,.06)]
                                       transition-all duration-200 cursor-pointer">
                                ✕ Remover
                            </button>
                        @endif
                    </div>

                    {{-- Barra de progresso upload --}}
                    <div id="upload-progress-wrap" class="hidden mt-3">
                        <div class="h-1.5 bg-[#272b38] rounded-full overflow-hidden">
                            <div id="upload-progress-bar" class="h-full rounded-full transition-all duration-300"
                                style="width:0%; background: linear-gradient(90deg,#e8c97e,#c9a84c)"></div>
                        </div>
                        <p id="upload-progress-text" class="text-[.72rem] text-[#4a5068] mt-1">Enviando…</p>
                    </div>

                    <p id="avatar-msg" class="hidden text-[.75rem] mt-2"></p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
         Card: Dados pessoais
    ══════════════════════════════════════ --}}
        <div class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6">
            <h2 class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068] mb-5">Dados Pessoais</h2>

            <form id="form-profile" novalidate>
                @csrf

                <div class="space-y-5">

                    {{-- Nome --}}
                    <div>
                        <label class="block text-[.74rem] font-medium text-[#4a5068] uppercase tracking-[.06em] mb-2"
                            for="prof-name">Nome completo</label>
                        <input id="prof-name" name="name" type="text" value="{{ Auth::user()->name }}" required
                            class="profile-input w-full px-4 py-3
                                  bg-[#13161d] border border-[#272b38] rounded-xl
                                  text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                  transition-all duration-200">
                        <p id="err-prof-name" class="hidden text-[#f47f7f] text-[.72rem] mt-1.5"></p>
                    </div>

                    {{-- E-mail --}}
                    <div>
                        <label class="block text-[.74rem] font-medium text-[#4a5068] uppercase tracking-[.06em] mb-2"
                            for="prof-email">E-mail</label>
                        <input id="prof-email" name="email" type="email" value="{{ Auth::user()->email }}" required
                            class="profile-input w-full px-4 py-3
                                  bg-[#13161d] border border-[#272b38] rounded-xl
                                  text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                  transition-all duration-200">
                        <p id="err-prof-email" class="hidden text-[#f47f7f] text-[.72rem] mt-1.5"></p>
                    </div>

                </div>

                <div class="flex items-center justify-between pt-5 mt-5 border-t border-[#272b38]">
                    <p id="profile-msg" class="hidden text-[.8rem]"></p>
                    <button type="submit" id="btn-save-profile"
                        class="ml-auto inline-flex items-center gap-2 px-6 py-2.5 rounded-xl
                               text-sm font-semibold text-[#0d0f14]
                               border-0 cursor-pointer
                               hover:opacity-90 active:scale-95
                               disabled:opacity-55 disabled:cursor-not-allowed
                               transition-all duration-200"
                        style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">
                        Salvar alterações
                    </button>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════
         Card: Alterar senha
    ══════════════════════════════════════ --}}
        <div class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6">
            <h2 class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#4a5068] mb-5">Alterar Senha</h2>

            <form id="form-password" novalidate>
                @csrf

                <div class="space-y-5">

                    {{-- Senha atual --}}
                    <div>
                        <label class="block text-[.74rem] font-medium text-[#4a5068] uppercase tracking-[.06em] mb-2"
                            for="cur-password">Senha atual</label>
                        <div class="relative">
                            <input id="cur-password" name="current_password" type="password" placeholder="••••••••"
                                class="profile-input w-full pl-4 pr-11 py-3
                                      bg-[#13161d] border border-[#272b38] rounded-xl
                                      text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                      transition-all duration-200">
                            <button type="button" onclick="togglePass('cur-password',this)"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#4a5068]
                                       hover:text-[#cdd3e2] bg-transparent border-0 cursor-pointer text-sm
                                       transition-colors duration-200">👁</button>
                        </div>
                        <p id="err-cur-password" class="hidden text-[#f47f7f] text-[.72rem] mt-1.5"></p>
                    </div>

                    {{-- Nova senha --}}
                    <div>
                        <label class="block text-[.74rem] font-medium text-[#4a5068] uppercase tracking-[.06em] mb-2"
                            for="new-password">Nova senha</label>
                        <div class="relative">
                            <input id="new-password" name="password" type="password" placeholder="••••••••"
                                oninput="updatePassStrength(this.value)"
                                class="profile-input w-full pl-4 pr-11 py-3
                                      bg-[#13161d] border border-[#272b38] rounded-xl
                                      text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                      transition-all duration-200">
                            <button type="button" onclick="togglePass('new-password',this)"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#4a5068]
                                       hover:text-[#cdd3e2] bg-transparent border-0 cursor-pointer text-sm
                                       transition-colors duration-200">👁</button>
                        </div>

                        {{-- Barra de força --}}
                        <div class="flex gap-1 mt-2 h-[3px]">
                            <div id="ps1" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                            <div id="ps2" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                            <div id="ps3" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                            <div id="ps4" class="flex-1 rounded-sm bg-[#272b38] transition-colors duration-300">
                            </div>
                        </div>

                        <p id="err-new-password" class="hidden text-[#f47f7f] text-[.72rem] mt-1.5"></p>
                    </div>

                    {{-- Confirmar nova senha --}}
                    <div>
                        <label class="block text-[.74rem] font-medium text-[#4a5068] uppercase tracking-[.06em] mb-2"
                            for="conf-password">Confirmar nova senha</label>
                        <div class="relative">
                            <input id="conf-password" name="password_confirmation" type="password"
                                placeholder="••••••••"
                                class="profile-input w-full pl-4 pr-11 py-3
                                      bg-[#13161d] border border-[#272b38] rounded-xl
                                      text-[#cdd3e2] text-sm placeholder-[#4a5068]
                                      transition-all duration-200">
                            <button type="button" onclick="togglePass('conf-password',this)"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-[#4a5068]
                                       hover:text-[#cdd3e2] bg-transparent border-0 cursor-pointer text-sm
                                       transition-colors duration-200">👁</button>
                        </div>
                        <p id="err-conf-password" class="hidden text-[#f47f7f] text-[.72rem] mt-1.5"></p>
                    </div>

                </div>

                <div class="flex items-center justify-between pt-5 mt-5 border-t border-[#272b38]">
                    <p id="password-msg" class="hidden text-[.8rem]"></p>
                    <button type="submit" id="btn-save-password"
                        class="ml-auto inline-flex items-center gap-2 px-6 py-2.5 rounded-xl
                               text-sm font-semibold text-[#0d0f14]
                               border-0 cursor-pointer
                               hover:opacity-90 active:scale-95
                               disabled:opacity-55 disabled:cursor-not-allowed
                               transition-all duration-200"
                        style="background: linear-gradient(135deg,#e8c97e,#c9a84c)">
                        Alterar senha
                    </button>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════
         Card: Zona de perigo
    ══════════════════════════════════════ --}}
        <div class="bg-[#1a1e28] border border-[rgba(244,127,127,.2)] rounded-2xl p-6">
            <h2 class="text-[.72rem] uppercase tracking-[.08em] font-semibold text-[#f47f7f] mb-2">Zona de Perigo</h2>
            <p class="text-xs text-[#4a5068] mb-4">
                Uma vez excluída, a conta não poderá ser recuperada. Todos os dados serão permanentemente removidos.
            </p>
            <button type="button" id="btn-delete-account"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium
                       bg-transparent border border-[rgba(244,127,127,.3)] text-[#f47f7f]
                       hover:bg-[rgba(244,127,127,.08)] hover:border-[#f47f7f]/60
                       transition-all duration-200 cursor-pointer">
                Excluir minha conta
            </button>
        </div>

    </div>

    {{-- ══ Modal de confirmação de exclusão ══ --}}
    <div id="modal-delete" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
        onclick="if(event.target===this) closeDeleteModal()">
        <div
            class="bg-[#1a1e28] border border-[#272b38] rounded-2xl p-6 w-full max-w-sm
                shadow-[0_32px_64px_rgba(0,0,0,.5)]">
            <h3 class="font-display text-lg text-white mb-2">Excluir conta?</h3>
            <p class="text-sm text-[#4a5068] mb-6">
                Esta ação é irreversível. Digite sua senha atual para confirmar.
            </p>
            <div class="mb-4">
                <input id="delete-confirm-password" type="password" placeholder="Senha atual"
                    class="profile-input w-full px-4 py-3
                          bg-[#13161d] border border-[#272b38] rounded-xl
                          text-[#cdd3e2] text-sm placeholder-[#4a5068]
                          transition-all duration-200">
                <p id="err-delete-password" class="hidden text-[#f47f7f] text-[.72rem] mt-1.5"></p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()"
                    class="flex-1 py-2.5 rounded-xl text-sm font-medium text-[#4a5068]
                           bg-transparent border border-[#272b38]
                           hover:border-[#3a3f52] hover:text-[#cdd3e2]
                           transition-all duration-200 cursor-pointer">
                    Cancelar
                </button>
                <button id="btn-confirm-delete"
                    class="flex-1 py-2.5 rounded-xl text-sm font-semibold text-white
                           bg-[#f47f7f] border-0 cursor-pointer
                           hover:bg-[#e06060] active:scale-95
                           transition-all duration-200">
                    Confirmar exclusão
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <style>
        .profile-input:focus {
            outline: none;
            border-color: #e8c97e;
            box-shadow: 0 0 0 3px rgba(232, 201, 126, .15);
        }

        .profile-input.is-error {
            border-color: #f47f7f !important;
        }

        .spinner-sm {
            display: inline-block;
            width: 13px;
            height: 13px;
            border: 2px solid rgba(13, 15, 20, .3);
            border-top-color: #0d0f14;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            vertical-align: middle;
            margin-right: 5px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* Strength bar */
        .ps-active-1 {
            background-color: #f47f7f !important;
        }

        .ps-active-2 {
            background-color: #f4a77f !important;
        }

        .ps-active-3 {
            background-color: #e8c97e !important;
        }

        .ps-active-4 {
            background-color: #6fd0a4 !important;
        }
    </style>

    <script>
        /* ─── Helpers ─── */
        const csrf = () => document.querySelector('meta[name=csrf-token]').content;

        async function postJson(url, data) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf()
                },
                body: JSON.stringify(data),
            });
            return {
                status: res.status,
                data: await res.json()
            };
        }

        function clearFieldErrors(prefix) {
            document.querySelectorAll(`[id^="err-${prefix}"]`).forEach(e => {
                e.textContent = '';
                e.classList.add('hidden');
            });
            document.querySelectorAll('.profile-input').forEach(i => i.classList.remove('is-error'));
        }

        function showFieldErrors(errors, prefix) {
            Object.entries(errors).forEach(([field, msgs]) => {
                const el = document.getElementById(
                    `err-${prefix}-${field.replace('_confirmation','conf').replace('current_','cur-')}`);
                if (el) {
                    el.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
                    el.classList.remove('hidden');
                }
            });
        }

        function setMsg(id, msg, type = 'success') {
            const el = document.getElementById(id);
            el.textContent = msg;
            el.className = type === 'success' ? 'text-[.8rem] text-[#6fd0a4]' : 'text-[.8rem] text-[#f47f7f]';
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 4000);
        }

        function setLoading(btnId, loading, label) {
            const btn = document.getElementById(btnId);
            btn.disabled = loading;
            btn.innerHTML = loading ? `<span class="spinner-sm"></span> Aguarde…` : label;
        }

        function togglePass(id, btn) {
            const inp = document.getElementById(id);
            inp.type = inp.type === 'password' ? 'text' : 'password';
            btn.textContent = inp.type === 'password' ? '👁' : '🙈';
        }

        /* ─── Strength bar ─── */
        const passRules = {
            len: v => v.length >= 8,
            upper: v => /[A-Z]/.test(v),
            num: v => /[0-9]/.test(v),
            special: v => /[^A-Za-z0-9]/.test(v),
        };

        function updatePassStrength(val) {
            const score = Object.values(passRules).filter(r => r(val)).length;
            for (let i = 1; i <= 4; i++) {
                const seg = document.getElementById('ps' + i);
                seg.classList.remove('ps-active-1', 'ps-active-2', 'ps-active-3', 'ps-active-4');
                if (i <= score) seg.classList.add('ps-active-' + score);
            }
        }

        /* ════════════════════════════════════════
           AVATAR UPLOAD
        ════════════════════════════════════════ */
        const avatarInput = document.getElementById('avatar-input');

        avatarInput.addEventListener('change', async () => {
            const file = avatarInput.files[0];
            if (!file) return;

            // Validação client-side
            if (file.size > 2 * 1024 * 1024) {
                showAvatarMsg('Arquivo muito grande. Máximo 2 MB.', 'error');
                return;
            }
            if (!['image/jpeg', 'image/png', 'image/webp', 'image/gif'].includes(file.type)) {
                showAvatarMsg('Formato inválido. Use JPG, PNG ou WebP.', 'error');
                return;
            }

            // Preview imediato
            const reader = new FileReader();
            reader.onload = e => renderAvatarPreview(e.target.result);
            reader.readAsDataURL(file);

            // Upload com progresso
            const wrap = document.getElementById('upload-progress-wrap');
            const bar = document.getElementById('upload-progress-bar');
            const txt = document.getElementById('upload-progress-text');
            wrap.classList.remove('hidden');
            bar.style.width = '0%';

            const formData = new FormData();
            formData.append('avatar', file);
            formData.append('_token', csrf());

            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('profile.avatar') }}');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.onprogress = e => {
                if (e.lengthComputable) {
                    const pct = Math.round(e.loaded / e.total * 100);
                    bar.style.width = pct + '%';
                    txt.textContent = `Enviando… ${pct}%`;
                }
            };

            xhr.onload = () => {
                wrap.classList.add('hidden');
                try {
                    const res = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        showAvatarMsg('Foto atualizada com sucesso!', 'success');
                        // Atualiza avatares no layout (topbar + sidebar)
                        document.querySelectorAll('img[alt="Avatar"], img[alt="Perfil"]').forEach(img => {
                            img.src = res.url + '?t=' + Date.now();
                        });
                    } else {
                        const msgs = res.errors?.avatar ?? [res.message ?? 'Erro ao enviar.'];
                        showAvatarMsg(msgs[0], 'error');
                    }
                } catch {
                    showAvatarMsg('Erro inesperado.', 'error');
                }
            };

            xhr.onerror = () => {
                wrap.classList.add('hidden');
                showAvatarMsg('Erro de conexão.', 'error');
            };
            xhr.send(formData);
        });

        function renderAvatarPreview(src) {
            const wrapper = document.getElementById('avatar-preview');
            wrapper.innerHTML = `<img id="avatar-img" src="${src}" alt="Avatar" class="w-full h-full object-cover">`;
        }

        function showAvatarMsg(msg, type) {
            const el = document.getElementById('avatar-msg');
            el.textContent = msg;
            el.className = type === 'success' ? 'text-[.75rem] text-[#6fd0a4] mt-2' : 'text-[.75rem] text-[#f47f7f] mt-2';
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 4000);
        }

        /* Remover avatar */
        const btnRemove = document.getElementById('btn-remove-avatar');
        if (btnRemove) {
            btnRemove.addEventListener('click', async () => {
                if (!confirm('Remover foto de perfil?')) return;
                btnRemove.disabled = true;
                const {
                    status,
                    data
                } = await postJson('{{ route('profile.avatar.remove') }}', {});
                btnRemove.disabled = false;
                if (status === 200) {
                    // Substitui preview pelo placeholder de letra
                    const wrapper = document.getElementById('avatar-preview');
                    const initial = '{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}';
                    wrapper.innerHTML =
                        `<div class="w-full h-full flex items-center justify-center text-3xl font-bold text-[#0d0f14]" style="background:linear-gradient(135deg,#e8c97e,#c9a84c)">${initial}</div>`;
                    btnRemove.remove();
                    showAvatarMsg('Foto removida.', 'success');
                } else {
                    showAvatarMsg(data.message ?? 'Erro ao remover.', 'error');
                }
            });
        }

        /* ════════════════════════════════════════
           PERFIL — dados pessoais
        ════════════════════════════════════════ */
        document.getElementById('form-profile').addEventListener('submit', async e => {
            e.preventDefault();
            clearFieldErrors('prof');

            const name = document.getElementById('prof-name').value.trim();
            const email = document.getElementById('prof-email').value.trim();

            setLoading('btn-save-profile', true, 'Salvar alterações');
            try {
                const {
                    status,
                    data
                } = await postJson('{{ route('profile.update') }}', {
                    name,
                    email
                });
                if (status === 200) {
                    setMsg('profile-msg', data.message ?? 'Perfil atualizado!', 'success');
                    // Atualiza nome no layout
                    document.querySelectorAll('.sidebar-user-name, [data-user-name]').forEach(el => el
                        .textContent = name);
                } else if (status === 422) {
                    showFieldErrors(data.errors ?? {}, 'prof');
                    if (data.message) setMsg('profile-msg', data.message, 'error');
                } else {
                    setMsg('profile-msg', data.message ?? 'Erro ao salvar.', 'error');
                }
            } catch {
                setMsg('profile-msg', 'Erro de conexão.', 'error');
            } finally {
                setLoading('btn-save-profile', false, 'Salvar alterações');
            }
        });

        /* ════════════════════════════════════════
           ALTERAR SENHA
        ════════════════════════════════════════ */
        document.getElementById('form-password').addEventListener('submit', async e => {
            e.preventDefault();
            clearFieldErrors('');

            const current_password = document.getElementById('cur-password').value;
            const password = document.getElementById('new-password').value;
            const password_confirmation = document.getElementById('conf-password').value;

            setLoading('btn-save-password', true, 'Alterar senha');
            try {
                const {
                    status,
                    data
                } = await postJson('{{ route('profile.password') }}', {
                    current_password,
                    password,
                    password_confirmation
                });
                if (status === 200) {
                    setMsg('password-msg', data.message ?? 'Senha alterada!', 'success');
                    document.getElementById('form-password').reset();
                    updatePassStrength('');
                } else if (status === 422) {
                    showFieldErrors(data.errors ?? {}, '');
                    if (data.message) setMsg('password-msg', data.message, 'error');
                } else {
                    setMsg('password-msg', data.message ?? 'Erro ao alterar senha.', 'error');
                }
            } catch {
                setMsg('password-msg', 'Erro de conexão.', 'error');
            } finally {
                setLoading('btn-save-password', false, 'Alterar senha');
            }
        });

        /* ════════════════════════════════════════
           EXCLUIR CONTA
        ════════════════════════════════════════ */
        document.getElementById('btn-delete-account').addEventListener('click', () => {
            const modal = document.getElementById('modal-delete');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        function closeDeleteModal() {
            const modal = document.getElementById('modal-delete');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('delete-confirm-password').value = '';
            document.getElementById('err-delete-password').classList.add('hidden');
        }

        document.getElementById('btn-confirm-delete').addEventListener('click', async () => {
            const password = document.getElementById('delete-confirm-password').value;
            const errEl = document.getElementById('err-delete-password');
            errEl.classList.add('hidden');

            if (!password) {
                errEl.textContent = 'Informe sua senha.';
                errEl.classList.remove('hidden');
                return;
            }

            const btn = document.getElementById('btn-confirm-delete');
            btn.disabled = true;
            btn.textContent = 'Aguarde…';

            try {
                const {
                    status,
                    data
                } = await postJson('{{ route('profile.destroy') }}', {
                    password
                });
                if (status === 200 && data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    errEl.textContent = data.message ?? 'Senha incorreta.';
                    errEl.classList.remove('hidden');
                    btn.disabled = false;
                    btn.textContent = 'Confirmar exclusão';
                }
            } catch {
                errEl.textContent = 'Erro de conexão.';
                errEl.classList.remove('hidden');
                btn.disabled = false;
                btn.textContent = 'Confirmar exclusão';
            }
        });
    </script>
@endpush
