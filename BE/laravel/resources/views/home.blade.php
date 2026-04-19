<!DOCTYPE html>
<html>
<head>
  <title>NextState - Home</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="sidebar">
    <h3><i class="fa-solid fa-folder-open"></i> NextState</h3>
    <br>
    <div class="user-profile">
      <div class="avatar-sm">{{ substr(Auth::user()->name, 0, 1) }}</div>
      <div class="user-info">
        <span class="user-name">{{ Auth::user()->name }}</span>
        <span class="user-role">Member</span>
      </div>
    </div>
    <br>
    <a href="/" class="nav-item active"><i class="fa-solid fa-grip"></i> Dashboard</a>
    <a href="/team" class="nav-item"><i class="fa-solid fa-users"></i> Team</a>
    
    <div class="sidebar-footer">
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
      </form>
    </div>
  </div>

  <div class="content">
    <h2>Projects</h2>
    <div class="header-buttons">
        <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
    </div>

    <div class="project-list">
      @foreach($projects as $project)
        <div class="project-card" style="border-top:5px solid #007bff">
          <h3><a href="{{ route('projects.show', ['id' => $project->id]) }}" class="stretched-link" style="text-decoration:none; color:inherit;">{{ $project->name }}</a></h3>
          <p>{{ $project->description }}</p>
          <small>Start: {{ $project->start_date }} | End: {{ $project->end_date }}</small>
        </div>
      @endforeach
    </div>
  </div>
</body>
</html>
