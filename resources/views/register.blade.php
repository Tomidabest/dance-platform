<x-layout>

    <h1> Register <h1>

    <div class="search-regiter__form">
        <form action="{{route('register.store')}}" method = "POST">
            @csrf

            <input name="first_name" placeholder="First Name" type="text" class="search-register__input" required minlength="2" maxlength="255" pattern="[A-Za-z\s]+" value="{{old('first_name')}}">
            @error('first_name')
                <p> {{$message}}</p>
            @enderror

            <input name="last_name" placeholder="Last Name" type="text" class="search-register__input" required minlength="2" maxlength="255" pattern="[A-Za-z\s]+" value="{{old('last_name')}}">
            @error('last_name')
                <p> {{$message}}</p>
            @enderror

            <input name="username" placeholder="Username" type="text" class="search-register__input" required minlength="3" maxlength="255" value="{{old('username')}}">
            @error('username')
                <p> {{$message}}</p>
            @enderror

            <input name="email" placeholder="Email" type="email" class="search-register__input" required value="{{old('email')}}">
            @error('email')
                <p> {{$message}}</p>
            @enderror

            <input name="password" placeholder="Password" type="password" class="search-register__input" required minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).{8,}">
            @error('password')
                <p> {{$message}}</p>
            @enderror

            <input name="password_confirmation" placeholder="Repeat Password" type="password" class="search-register__input" required minlength="8">
            @error('password_confirmation')
                <p> {{$message}}</p>
            @enderror

            <button class="search-register__button" type='submit'>Sign up</button>
        </form>
    </div>

</x-layout>