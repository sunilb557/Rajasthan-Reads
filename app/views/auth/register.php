<div class="card" style="max-width: 400px; margin: 0 auto;">
    <div class="card-header">
        <h2 class="card-title text-center">Create Account</h2>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/register">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars((new CSRF())->generateToken()) ?>">

        <div class="form-group">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirm" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Create account</button>

        <p class="mt-2 text-center">
            Already have an account? <a href="<?= BASE_URL ?>/login" class="link">Login</a>
        </p>
    </form>
</div>
