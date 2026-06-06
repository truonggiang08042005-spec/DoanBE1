<div class="flex justify-center py-10">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h1 class="text-2xl font-extrabold text-slate-900">Đăng nhập</h1>
        <p class="text-sm text-slate-600 mt-1">Đăng nhập để quản lý đặt sân nhanh hơn.</p>

        <?php if (!empty($error)): ?>
            <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form class="mt-6 space-y-4" method="POST" action="<?= BASE_URL ?>index.php?controller=auth&action=login">
            <div>
                <label class="block text-sm font-semibold text-slate-700" for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-sportgreen-500"
                    autocomplete="email"
                    required
                >
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700" for="password">Mật khẩu</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-sportgreen-500"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="w-full rounded-xl bg-sportgreen-700 px-4 py-2.5 text-white font-bold hover:bg-sportgreen-800 transition">
                Đăng nhập
            </button>
        </form>

        <div class="mt-5 text-sm text-slate-600">
            Chưa có tài khoản?
            <a class="font-semibold text-sportgreen-800 hover:underline" href="<?= BASE_URL ?>index.php?controller=auth&action=register">
                Đăng ký ngay
            </a>
        </div>
    </div>
</div>

