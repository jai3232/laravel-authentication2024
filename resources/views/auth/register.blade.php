<form method="POST" action="{{ route('register') }}">
    @csrf
    <div>
        <label for="name">Name</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <div>
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" required>
    </div>
    <div>
        <button type="submit">Register</button>
    </div>
</form>
@foreach($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
@if (Session('error'))
<p class="text-danger">{{ session('error') }}</p>
@endif