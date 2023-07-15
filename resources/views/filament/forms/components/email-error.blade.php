@error('email')
    @if($errors->has('email.unique'))
    <a href=" ... ">Forgot Password?</a>
    @endif
@enderror