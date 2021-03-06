<h1>Contact Page</h1>
<form method="post" action="">
    <div class="mb-3">
        <label class="form-label">Subject</label>
        <input type="text" name="subject" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Message</label>
        <textarea name="message" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<hr>
<p>Foo-<?= $foo ?></p>