@if(getAuthLevel()=="super-admin")
    <select class="custom-select custom-select-sm custom-select bg-primary btn-navbar" id="filter_level">
        @foreach(getUserGroup() as $level)
            <option value="{{ $level->id }}">{{ $level->name }}</option>
        @endforeach
    </select>
@else
    <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
            <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </div>
@endif
