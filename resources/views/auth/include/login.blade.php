<div id="login" class="{{ (!Session::has('tab') || (Session::has('tab') && Session::get('tab') == 'login')) ? 'active' : '' }} tab-pane fade in">
    <div class="panel-default login-panel panel">
        <div class="panel-body">
            <form id="formLogin" role="form" action="{{ route('login') }}" method="post" autocomplete="off">
                {{ csrf_field() }}
                <fieldset>
                    <div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
                        <label style="color: #111111">NIM/NIP</label>
                        <input required="" class="form-control nim" placeholder="NIM/NIP" name="id" id="id" type="text" autocomplete="on" value="{{old('id')}}"
                            required autofocus> @if ($errors->has('id'))
                        <span class="text-danger">
                            <strong>{{ $errors->first('id') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label style="color: #111111">Password</label>
                        <input name="form" type="hidden" value="login">
                        <input required class="form-control" placeholder="Password" name="password" type="password" id="password"> @if ($errors->has('password'))
                        <span class="text-danger">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="col-lg-6">
                            <div class="checkbox">
                                <label style="color: #111111">
                                    <input type="checkbox" name="remember" {{ old( 'remember') ? 'checked' : '' }}> Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-lg-offset-3 col-lg-3">
                            <button type="submit" id="submit_button" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>