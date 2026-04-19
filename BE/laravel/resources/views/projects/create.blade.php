<!DOCTYPE html>
<html>
<head>
  <title>NextState - Create Project</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="sidebar">
    <h3><i class="fa-solid fa-folder-open"></i> NextState</h3>
    <br>
    <a href="/" class="nav-item active"><i class="fa-solid fa-grip"></i> Dashboard</a>
    <a href="/team" class="nav-item"><i class="fa-solid fa-users"></i> Team</a>
  </div>

  <div class="content">
    <h2>Projects</h2>
    <div class="header-buttons">
        <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
    </div>

    <div class="project-list">
      @foreach($projects as $project)
        <div class="project-card" style="border-top:5px solid #007bff">
          <h3><a href="{{ route('projects.show', ['id' => $project->id]) }}" style="text-decoration:none; color:inherit;">{{ $project->name }}</a></h3>
          <p>{{ $project->description }}</p>
          <small>Start: {{ $project->start_date }} | End: {{ $project->end_date }}</small>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Modal Create Project (Always Open for this Route) -->
  <div id="addProjectModal" class="modal-overlay" style="display: flex;">
      <div class="modal-content">
          <div class="modal-header">
              <h3>Create New Project</h3>
              <a href="{{ route('home') }}" class="close-btn" style="text-decoration:none; color:inherit;"><i class="fa-solid fa-xmark"></i></a>
          </div>
          <p class="text-muted mb-3" style="font-size: 14px;">Add a new project to your list</p>
          <form method="POST" action="{{ route('projects.store') }}">
              @csrf
              <div class="form-group mt-3">
                  <label for="name">Project Name</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Enter project name" required>
              </div>
              <div class="form-group mt-3">
                  <label for="description">Description</label>
                  <textarea id="description" name="description" class="form-control" placeholder="Enter project description" required></textarea>
              </div>
              <div class="form-group mt-3">
                  <label for="color">Color</label>
                  <select id="color" name="color" class="form-control">
                    <option value="#6f42c1">Purple</option>
                    <option value="#dc3545">Red</option>
                    <option value="#fd7e14">Orange</option>
                    <option value="#28a745">Green</option>
                    <option value="#007bff">Blue</option>
                    <option value="#e83e8c">Pink</option>
                  </select>
              </div>
              <div class="form-group mt-3" style="display:flex; gap:10px;">
                  <div style="flex:1;">
                      <label for="start_date">Start Date</label>
                      <input type="date" id="start_date" name="start_date" class="form-control" required>
                  </div>
                  <div style="flex:1;">
                      <label for="end_date">End Date</label>
                      <input type="date" id="end_date" name="end_date" class="form-control" required>
                  </div>
              </div>
              <div class="modal-footer mt-4 gap-2" style="display:flex; justify-content:flex-end;">
                  <a href="{{ route('home') }}" class="btn border-btn" style="text-decoration:none;">Cancel</a>
                  <button type="submit" class="btn btn-dark">Create Project</button>
              </div>
          </form>
      </div>
  </div>
</body>
</html>
