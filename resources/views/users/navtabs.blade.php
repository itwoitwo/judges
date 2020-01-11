<ul class="nav nav-tabs nav-justified mb-3">
    <li class="nav-item"><a href="{{ route('show', ['screen_name' => $userInfo['screen_name']]) }}" class="nav-link {{ Request::is('users/' . $userInfo['screen_name']) ? 'active' : '' }}">投票所 </a></li>
    <li class="nav-item"><a href="{{ route('messagebox', []) }}" class="nav-link {{ Request::is('messagebox') ? 'active' : '' }}">受信箱</a></li>
    <li class="nav-item"><a href="{{ route('followlist', []) }}" class="nav-link {{ Request::is('followlist') ? 'active' : '' }}">フォローリスト</a></li>
</ul>