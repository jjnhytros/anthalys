<a href="{{ route('messages.compose') }}" class="btn btn-danger w-100 mb-3">Compose Email</a> <!-- Add this button -->

<ul class="nav flex-column nav-pills shadow mb-3">
    <li class="nav-item">
        <a href="{{ route('messages.inbox') }}"
            class="nav-link {{ request()->routeIs('messages.inbox') ? 'active' : '' }}">
            <i class="bi bi-inbox"></i> Inbox
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('messages.sent') }}" class="nav-link {{ request()->routeIs('messages.sent') ? 'active' : '' }}">
            <i class="bi bi-send"></i> Sent Mail
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('messages.trashed') }}"
            class="nav-link {{ request()->routeIs('messages.trashed') ? 'active' : '' }}">
            <i class="bi bi-trash"></i> Trash
        </a>
    </li>
</ul>

<h5 class="mb-3">More</h5>
<ul class="nav flex-column nav-pills shadow rounded">
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-folder"></i> Promotions
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-folder"></i> Job List
        </a>
    </li>
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="bi bi-folder"></i> Backup
        </a>
    </li>
</ul>
