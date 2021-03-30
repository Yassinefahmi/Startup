<h1>Register</h1>
<form method="post" action="">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Register</button>
</form>