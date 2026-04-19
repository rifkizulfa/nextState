<!DOCTYPE html>
<html>
<head>
  <title>TaskFlow - Project Detail</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- Menggunakan Font Awesome untuk ikon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  <div class="sidebar">
    <h3><i class="fa-solid fa-folder-open"></i> NextState</h3>
    <br>
    <a href="/" class="nav-item active"><i class="fa-solid fa-grip"></i> Dashboard</a>
    <a href="/team" class="nav-item"><i class="fa-solid fa-users"></i> Team</a>
  </div>

  <div class="content main-content-full">
    <div class="project-header-actions">
        <div>
            <div class="breadcrumb">
                <a href="/"><i class="fa-solid fa-arrow-left"></i></a> 
                <h2>{{ $project->name }}</h2>
            </div>
            <p class="text-muted" style="margin-left: 25px;">{{ $project->description }}</p>
        </div>
        <div class="header-buttons" style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-danger" onclick="openDeleteModal('{{ route('projects.destroy', $project->id) }}', 'menghapus project ini beserta seluruh task')"><i class="fa-solid fa-trash"></i> Delete Project</button>
            <button class="btn btn-dark" onclick="document.getElementById('addTaskModal').style.display='flex'"><i class="fa-solid fa-plus"></i> New Task</button>
        </div>
    </div>

    <div class="task-board-container">
        <div class="filter-tabs">
            <a href="?filter=all" class="tab {{ $filter == 'all' ? 'active' : '' }}">All ({{ $all_count }})</a>
            <a href="?filter=to-do" class="tab {{ $filter == 'to-do' ? 'active' : '' }}">To Do ({{ $todo_count }})</a>
            <a href="?filter=in-progress" class="tab {{ $filter == 'in-progress' ? 'active' : '' }}">In Progress ({{ $inprogress_count }})</a>
            <a href="?filter=done" class="tab {{ $filter == 'done' ? 'active' : '' }}">Done ({{ $done_count }})</a>
        </div>

        <div class="task-list">
            @forelse($tasks as $task)
            <div class="task-card">
                <div class="task-card-header">
                    <div class="task-title-area">
                        <h4>{{ $task->title }}</h4>
                        <span class="badge priority-{{ strtolower($task->priority) }}">{{ strtolower($task->priority) }}</span>
                        <span class="badge status-{{ strtolower(str_replace(' ', '-', $task->status)) }}">{{ $task->status }}</span>
                    </div>
                    <div class="task-actions">
                        <button class="action-btn" title="Edit"
                        onclick='openEditModal(
                            {{ $task->id }},
                            @json($task->title),
                            @json($task->description),
                            @json($task->status),
                            @json($task->priority),
                            @json($task->due_date)
                        )'>
                            <i class="fa-solid fa-pencil"></i>
                        </button>

                        <!-- tombol DELETE -->
                        <!-- tombol DELETE -->
                        <button type="button" class="action-btn" title="Delete" style="background:none; border:none; cursor:pointer;" onclick="openDeleteModal('{{ route('tasks.destroy', $task->id) }}', 'menghapus task ini')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
                <p class="task-desc">{{ $task->description }}</p>
                <div class="task-meta">
                    <span class="due-date"><i class="fa-regular fa-calendar"></i> Due {{ $task->due_date }}</span>
                    <span class="assignees"><i class="fa-regular fa-user"></i> {{ implode(', ', $task->assignees) }}</span>
                    @if($task->attachment)
                    <a href="{{ Storage::url($task->attachment) }}" target="_blank" style="margin-left:auto; font-size:12px; color:#007bff;"><i class="fa-solid fa-paperclip"></i> Attachment</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <p>No tasks found for this filter.</p>
            </div>
            @endforelse
        </div>
    </div>
  </div>

  <!-- Modal Add/Edit Task -->
  <div id="addTaskModal" class="modal-overlay" style="display: none;">
      <div class="modal-content modal-lg">
          <div class="modal-header">
              <h3>Task Details</h3>
              <button class="close-btn" onclick="document.getElementById('addTaskModal').style.display='none'"><i class="fa-solid fa-xmark"></i></button>
          </div>
          <p class="text-muted mb-3" style="font-size: 14px;">Add or edit task for this project</p>
          <form method="POST" action="{{ route('tasks.store', $project->id) }}" enctype="multipart/form-data">
              @csrf
              <div class="form-group border-box">
                  <label>Task Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Enter task title" required>
                  
                  <label class="mt-3">Description</label>
                  <textarea name="description" class="form-control" placeholder="Enter task description"></textarea>
                  
                  <!-- Tempat upload dokumen/foto -->
                  <label class="mt-3">Attachment (Documents/Photos)</label>
                  <div class="file-upload-area">
                      <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2"></i>
                      <p class="text-muted m-0">Click or drag file to this area to upload</p>
                      <input type="file" name="attachment" class="file-input">
                  </div>

                  <div class="row mt-3">
                      <div class="col">
                          <label>Status</label>
                          <select name="status" class="form-control">
                              <option value="To Do">To Do</option>
                              <option value="In Progress">In Progress</option>
                              <option value="Done">Done</option>
                          </select>
                      </div>
                      <div class="col">
                          <label>Priority</label>
                          <select name="priority" class="form-control">
                              <option value="Easy">Easy</option>
                              <option value="Medium">Medium</option>
                              <option value="High">High</option>
                          </select>
                      </div>
                  </div>
              </div>

              <div class="form-group mt-3">
                  <label>Due Date</label>
                  <input type="date" name="due_date" class="form-control">
              </div>

              <div class="form-group border-box mt-3">
                  <label>Assign To (Team Members)</label>
                  <div class="checkbox-list mt-2">
                      @foreach($users as $user)
                      <label class="checkbox-item"><input type="checkbox" name="assignees[]" value="{{ $user->id }}"> {{ $user->name }} - {{ $user->role ?? 'Member' }}</label>
                      @endforeach
                  </div>
              </div>

              <div class="modal-footer mt-4 gap-2" style="display:flex; justify-content:flex-end;">
                  <button type="button" class="btn border-btn" onclick="document.getElementById('addTaskModal').style.display='none'">Cancel</button>
                  <button type="submit" class="btn btn-dark">Save Task</button>
              </div>
          </form>
      </div>
  </div>
  <div id="editTaskModal" class="modal-overlay" style="display: none;">
  <div class="modal-content modal-lg">
    <div class="modal-header">
      <h3>Edit Task</h3>
      <button class="close-btn" onclick="closeEditModal()">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form id="editTaskForm" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label>Task Title</label>
        <input type="text" name="title" id="edit_title" class="form-control">
      </div>

      <div class="form-group mt-2">
        <label>Description</label>
        <textarea name="description" id="edit_description" class="form-control"></textarea>
      </div>

      <div class="row mt-2">
        <div class="col">
          <label>Status</label>
          <select name="status" id="edit_status" class="form-control">
            <option value="To Do">To Do</option>
            <option value="In Progress">In Progress</option>
            <option value="Done">Done</option>
          </select>
        </div>

        <div class="col">
          <label>Priority</label>
          <select name="priority" id="edit_priority" class="form-control">
            <option value="Easy">Easy</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select>
        </div>
      </div>

      <div class="form-group mt-2">
        <label>Due Date</label>
        <input type="date" name="due_date" id="edit_due_date" class="form-control">
      </div>

      <div class="form-group mt-2 border-box">
        <label>Update Attachment (optional)</label>
        <input type="file" name="attachment" class="form-control">
      </div>

      <div class="modal-footer mt-3">
        <button type="button" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-dark">Update Task</button>
      </div>
    </form>
  </div>
</div>

  <!-- Modal Delete Confirmation -->
  <div id="deleteConfirmModal" class="modal-overlay" style="display: none; z-index: 2000;">
      <div class="modal-content" style="max-width: 400px; text-align: center;">
          <h3 style="color: #e53e3e; margin-bottom: 15px;"><i class="fa-solid fa-triangle-exclamation"></i> Confirm Deletion</h3>
          <p class="text-muted mb-3" style="font-size: 14px;">Apakah Anda yakin ingin <span id="deleteItemName" style="font-weight:bold;"></span>?</p>
          <form id="deleteModalForm" method="POST">
              @csrf
              @method('DELETE')
              <div class="modal-footer mt-4 gap-2" style="display:flex; justify-content:center;">
                  <button type="button" class="btn border-btn" onclick="document.getElementById('deleteConfirmModal').style.display='none'">Cancel</button>
                  <button type="submit" class="btn btn-danger">Ya, Hapus</button>
              </div>
          </form>
      </div>
  </div>

<script>
function openEditModal(id, title, description, status, priority, due_date) {
    document.getElementById('editTaskModal').style.display = 'flex';

    document.getElementById('edit_title').value = title;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_priority').value = priority;
    document.getElementById('edit_due_date').value = due_date;

    // set action form
    document.getElementById('editTaskForm').action = '/tasks/' + id;
}

function closeEditModal() {
    document.getElementById('editTaskModal').style.display = 'none';
}

function openDeleteModal(url, itemName) {
    document.getElementById('deleteConfirmModal').style.display = 'flex';
    document.getElementById('deleteItemName').innerText = itemName;
    document.getElementById('deleteModalForm').action = url;
}
</script>

</body>
</html>
