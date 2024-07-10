@if (\Session::has('success'))
<div class="alert alert-info">{{ \Session::get('success') }}</div>
@endif
@if (\Session::has('error'))
<div class="alert alert-info">{{ \Session::get('error') }}</div>
@endif
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <button type="submit">Login</button>
    </div>
</form>